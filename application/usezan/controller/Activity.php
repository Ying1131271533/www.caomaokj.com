<?php
namespace app\usezan\controller;

use app\common\model\ActivityJoin as AJ;
use app\common\model\Category as C;
use app\common\model\Keyword;
use app\usezan\model\Activity as A;
use library\Character;
use library\ExcelAkali;
use library\Sms;
use libs\Tree;
use think\Db;
use think\facade\View;

class Activity extends Base
{
    protected $activity, $module;
    public function _uzauto()
    {
        $this->module   = 3;
        $this->activity = new A();
    }

    //列表
    public function index()
    {
        $keys = input('get.');
        $map  = [];
        if (!empty($keys['keyword'])) {
            $map['title'] = ['like', '%' . $keys['keyword'] . '%'];
        }
        if (isset($keys['ain']) && $keys['ain'] >= 0) {
            $map['ain'] = $keys['ain'];
        } else {
            $keys['ain'] = 0;
        }
        $list = $this->activity->get_paginate($map);
        $this->assign("list", $list);
        $this->assign('keys', $keys);
        return $this->fetch();
    }

    /**
     * 报名列表 ⎛⎝≥⏝⏝≤⎛⎝
     *
     * @param
     * @return View
     */
    public function join()
    {
        $where = [];

        $data  = input();
        $limit = !empty($data['limit']) ? $data['limit'] : 20;

        // 提交
        if (request()->isPost()) {
            // 显示条数

            !empty($data['activity_id']) and $where[]        = ['aj.activity_id', '=', $data['activity_id']];
            !empty($data['title']) and $where[]              = ['a.title', 'like', '%' . $data['title'] . '%'];
            !empty($data['order_sn']) and $where[]           = ['ao.order_sn', '=', $data['order_sn']];
            !empty($data['phone']) and $where[]              = ['aj.phone', '=', $data['phone']];
            !empty($data['member_id']) and $where[]          = ['aj.member_id', '=', $data['member_id']];
            is_numeric($data['pay_status']) and $where[]     = ['ao.pay_status', '=', $data['pay_status']];
            is_numeric($data['sms_status']) and $where[]     = ['aj.sms_status', '=', $data['sms_status']];
            is_numeric($data['connect_status']) and $where[] = ['aj.connect_status', '=', $data['connect_status']];
        }

        // 报名数据
        $list = AJ::alias('aj')
            ->join('activity a', 'a.id=aj.activity_id')
            ->join('member m', 'aj.member_id=m.id')
            ->join('activity_order ao', 'ao.activity_join_id=aj.id')
            ->field('aj.*, a.title, a.thumb, ao.order_sn, ao.number, ao.need_pay, ao.pay_status')
            ->where($where)
            ->order('aj.id', 'desc')
            ->paginate($limit);

        $this->assign("list", $list);
        $this->assign('activity_id', input('activity_id/d', ''));
        $this->assign('limit', $limit);
        $this->assign('pay_status', input('pay_status/d', ''));
        $this->assign('sms_status', input('sms_status/d', ''));
        $this->assign('connect_status', input('connect_status/d', ''));
        $this->assign('order_sn', input('order_sn/s', ''));
        return $this->fetch();
    }

    /**
     * 群发短信
     *
     * @param  Request $request
     * @return \think\Response
     */
    public function sendGroupMessage()
    {
        /**********************   参数接收   **********************/
        $ids = input('ids/a');
        empty($ids) and akali('请勾选报名');

        /**********************   类型   **********************/
        $joinData = AJ::with(['activity'])->whereIn('id', $ids)->select();

        /**********************   发送短信   **********************/
        foreach ($joinData as $key => $value) {
            // 标题是否超过23字符
            if (mb_strlen($value['activity']['title'], 'utf8') > 23) {
                $title = mb_substr($value['activity']['title'], 0, 19) . '...';
            } else {
                $title = $value['activity']['title'];
            }

            // 整理数组
            $smsData = [
                'phone'      => $value['phone'],
                'title'      => $title,
                'start_time' => get_date($value['activity']['endtime']), // 开课时间
                'type'       => 4, // 短信类型 活动报名通知
            ];

            // 发送短信
            $result = (new Sms)->smsSend($smsData);
            // 发送成功修改数据库状态
            $smsJson = json_decode($result, true);

            if (isset($smsJson['SendStatusSet'][0]['Code']) && $smsJson['SendStatusSet'][0]['Code'] === 'Ok') {
                $value->sms_status = 1;
                $value->save();
            } else {
                return akali('发送失败，请重新发送');
            }
        }

        return akali('发送成功');
    }

    /**
     * 导出报名列表excel
     *
     * @param  Request $request
     * @return \think\Response
     */
    public function export()
    {
        /**********************   参数接收   **********************/
        $ids = input('ids/a');
        empty($ids) and akali('请勾选报名');
        $ids = array_unique($ids);

        $data = AJ::with(['activity', 'order'])->whereIn('id', $ids)->select();

        $excelData = [];
        foreach ($data as $key => $value) {
            $excelData[$key]['sequence']       = $key+1;
            $excelData[$key]['order_sn']       = $value['order']['order_sn'];
            $excelData[$key]['title']          = $value['activity']['title'];
            $excelData[$key]['name']           = $value['name'];
            $excelData[$key]['phone']          = $value['phone'];
            $excelData[$key]['company']        = $value['company'];
            $excelData[$key]['demand']         = $value['demand'];
            $excelData[$key]['number']         = $value['order']['number'];
            $excelData[$key]['pay_status']     = $value['order']['pay_status'] == 1 ? '已支付' : '未支付';
            $excelData[$key]['sms_status']     = $value['sms_status'] == 1 ? '已通知' : '未通知';
            $excelData[$key]['connect_status'] = $value['connect_status'] == 1 ? '已联系' : '未联系';
            $excelData[$key]['create_time']    = $value['create_time'];
        }
        
        // 设置表格的表头数据
        $header = [
            "A1" => "序号",
            "B1" => "订单号",
            "C1" => "活动名称",
            "D1" => "姓名",
            "E1" => "联系电话",
            "F1" => "公司名称",
            "G1" => "需求/问题",
            "H1" => "数量",
            "I1" => "支付状态",
            "J1" => "短信通知",
            "K1" => "联系状态",
            "L1" => "报名时间",
        ];

        // 设置表格的行列宽
        $width = [
            ['alphabet' => 'A', 'width' => 8],
            ['alphabet' => 'B', 'width' => 24],
            ['alphabet' => 'C', 'width' => 18],
            ['alphabet' => 'D', 'width' => 12],
            ['alphabet' => 'E', 'width' => 14],
            ['alphabet' => 'F', 'width' => 30],
            ['alphabet' => 'G', 'width' => 30],
            ['alphabet' => 'H', 'width' => 8],
            ['alphabet' => 'I', 'width' => 12],
            ['alphabet' => 'J', 'width' => 12],
            ['alphabet' => 'K', 'width' => 12],
            ['alphabet' => 'L', 'width' => 20],
        ];

        // halt($header);
        // 保存文件的类型
        $type = false;
        // 设置下载文件保存的名称
        $fileName = "活动报名-" . date('Y-m-d-His');
        // 调用方法导出excel
        ExcelAkali::export($header, $type, $excelData, $fileName, $width);
    }

    // 添加
    public function add()
    {
        if (request()->isPost()) {
            $info = input('post.');
            //处理data
            $info['createtime'] = time();
            $istime             = $this->checkTime($info['starttime'], $info['endtime']);
            if ($istime) {
                $this->error('结束时间不能小于开始时间');
            }
            if (!$info['view']) {
                $info['view'] = rand(1, 499);
            }

            // 接收关键词
            $keywords = input('keywords/a');

            $id = $this->activity->save_type($info, false);
            /* if ($aid) {
            $this->setUrl($info['catid'], $aid);
            $this->success('添加成功');
            } else {
            $this->error('添加失败');
            } */

            if (empty($id)) {
                // 回滚事务
                $this->activity->rollback();
                jinx('文章添加失败');
            }

            // 找出文章
            $activity = A::find($id);
            // 保存关键词
            $result = $activity->keywords()->saveAll($keywords);
            if ($result === false) {
                // 回滚事务
                $this->activity->rollback();
                jinx($activity->getError());
            }

            // 提交事务
            $this->activity->commit();
            jinx('保存成功');
        }
        //栏目
        /*$categorys = $this->category ? $this->category : [];
        if ($categorys) {
        foreach ($categorys as $vo) {
        if ($vo['status'] && intval($vo['module']) === $this->module) {
        $array[] = $vo;
        }
        continue;
        }
        $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree             = new Tree($array);
        $select_categorys = $tree->get_tree(0, $str, 0);
        $this->assign('select_categorys', $select_categorys);
        }*/

        // 分类
        $category = C::field('id, catname')
            ->where(['parentid' => 56, 'status' => 1])
            ->order(['listorder' => 'asc', 'id' => 'asc'])
            ->select();
        View::assign("category", $category);

        // 获取所有关键词
        $keyword = Keyword::field('id, name')->order('sort desc, id asc')->select();
        // 关键词分组
        $list = (new Character)->groupByInitials($keyword->toArray(), 'name');
        View::assign("list", $list);

        return $this->fetch();
    }

    //修改
    public function edit()
    {
        if (request()->isPost()) {
            $info = input('post.');
            //处理data
            if (empty($info['thumb'])) {
                unset($info['thumb'], $info['oldthumb']);
            } else {
                del_oldthumb($info['oldthumb']);
            }
            $istime = $this->checkTime($info['starttime'], $info['endtime']);
            if ($istime) {
                $this->error('结束时间不能小于开始时间');
            }
            $this->activity->save_type($info);
            $this->setUrl($info['catid'], $info['id']);
            $this->success('修改成功');
        }
        $id = input("get.id");
        if (!$id) {
            $this->error("缺少必要参数");
        }

        $activity = A::get($id);
        //category
        /*foreach ($this->category as $vo) {
        if ($vo['status'] && intval($vo['module']) === $this->module) {
        $vo['selected'] = $vo['id'] == $list['catid'] ? 'selected' : '';
        $array[]        = $vo;
        }
        continue;
        }
        $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree             = new Tree($array);
        $select_categorys = $tree->get_tree(0, $str, $list['catid']);
        $this->assign('select_categorys', $select_categorys);*/

        // 分类
        $category = C::field('id, catname')
            ->where(['parentid' => 56, 'status' => 1])
            ->order(['listorder' => 'asc', 'id' => 'asc'])
            ->select();

        View::assign("category", $category);

        $this->assign("activity", $activity);
        return $this->fetch();
    }

    // 添加
    public function joinDetail()
    {
        $id   = input('id/d');
        $join = AJ::with(['activity', 'order'])->find($id);
        View::assign([
            "activity" => $join['activity'],
            "join"     => $join,
            "order"    => $join['order'],
        ]);
        return view();
    }

    // time
    protected function checkTime($starttime, $endtime)
    {
        if (strtotime($endtime) < strtotime($starttime)) {
            return true;
        }
        return false;
    }

    //url
    protected function setUrl($catid, $aid)
    {
        $parturl = $this->category[$catid]['url'];
        $data    = [
            'id'  => $aid,
            'url' => '/' . $parturl . '/show/' . $aid . '.html',
        ];
        $this->activity->set_field($data);
    }

    //排序
    public function listorder()
    {
        $listorders = input('post.listorders/a');
        if (empty($listorders)) {
            $this->error("缺少必要参数");
        }

        //遍历更新
        $data = [];
        foreach ($listorders as $k => $v) {
            $data[$k] = [
                'id'        => $k,
                'listorder' => $v,
            ];
        }
        $this->activity->save_all($data);
        $this->success("更新排序成功(ˇˍˇ)");
    }

    //删除
    public function del()
    {
        $id = input('get.id');
        if (!$id) {
            $this->error(lang('do_empty'));
        }

        $thumb = $this->activity->get_value(['id' => $id], 'thumb');
        $del   = $this->activity->del($id);
        if ($del) {
            if ($thumb) {
                del_oldthumb($thumb);
            }

            $this->success(lang('delete_ok'));
        } else {
            $this->error(lang('delete_error'));
        }
    }

}
