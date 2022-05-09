<?php
namespace app\usezan\controller;

use app\common\model\Category;
use app\common\model\College as ModelCollege;
use app\common\model\CollegeJoin as CJ;
use app\common\model\Tickets;
use app\usezan\logic\College as CollegeLogic;
use library\ExcelAkali;
use library\Sms;
use think\facade\View;

class College extends Base
{
    protected $college;
    public function _uzauto()
    {
        $this->college = new ModelCollege();
    }

    //列表
    public function index()
    {
        $keys = input('get.');
        $map  = [];
        if (!empty($keys['keyword'])) {
            $map['title'] = ['like', '%' . $keys['keyword'] . '%'];
        }
        if (isset($keys['status'])) {
            $map['status'] = $keys['status'];
        } else {
            $keys['status'] = -1;
        }
        $list = $this->college->get_paginate($map);
        // halt($list);
        $this->assign("list", $list);
        $this->assign('keys', $keys);
        return $this->fetch();
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

        $data = CJ::with(['college', 'order'])->whereIn('id', $ids)->select();

        $excelData = [];
        foreach ($data as $key => $value) {
            $excelData[$key]['sequence']       = $key + 1;
            $excelData[$key]['order_sn']       = $value['order']['order_sn'];
            $excelData[$key]['title']          = $value['college']['title'];
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
            "C1" => "课程名称",
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
            ['alphabet' => 'C', 'width' => 24],
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
        $fileName = "课程报名-" . date('Y-m-d-His');
        // 调用方法导出excel
        ExcelAkali::export($header, $type, $excelData, $fileName, $width);
    }

    //添加
    public function add()
    {
        if (request()->isPost()) {
            $params = input('post.');
            CollegeLogic::saveColege($params);
        }

        // 分类
        $category = Category::field('id, catname')
            ->where(['parentid' => 72, 'status' => 1])
            ->order(['listorder' => 'asc', 'id' => 'asc'])
            ->select();

        View::assign("category", $category);

        // 获取所有门票
        $tickets = Tickets::select();
        View::assign("tickets", $tickets);

        return $this->fetch();
    }

    //修改
    public function edit()
    {
        if (request()->isPost()) {
            $params = input('post.');
            CollegeLogic::saveColege($params);
        }

        $id = input("get.id");
        if (!$id) {
            $this->error("缺少必要参数");
        }

        $college = ModelCollege::get($id);

        // 分类
        $category = Category::field('id, catname')
            ->where(['parentid' => 72, 'status' => 1])
            ->order(['listorder' => 'asc', 'id' => 'asc'])
            ->select();

            // 获取所有门票
        $tickets = Tickets::select();
        View::assign("tickets", $tickets);
        
        // 已选的门票
        $collegeTickets = $college -> tickets()->column('id');
        // dump($collegeTickets);
        // halt($college['tickets']);

        View::assign("collegeTickets", $collegeTickets);
        View::assign("category", $category);
        $this->assign("college", $college);
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

        // 接受数据
        $data = input();
        // 显示条数
        $limit = !empty($data['limit']) ? $data['limit'] : 20;

        // 提交
        if (request()->isPost()) {
            !empty($data['college_id']) and $where[]         = ['aj.college_id', '=', $data['college_id']];
            !empty($data['title']) and $where[]              = ['a.title', 'like', '%' . $data['title'] . '%'];
            !empty($data['order_sn']) and $where[]           = ['ao.order_sn', '=', $data['order_sn']];
            !empty($data['phone']) and $where[]              = ['aj.phone', '=', $data['phone']];
            !empty($data['member_id']) and $where[]          = ['aj.member_id', '=', $data['member_id']];
            is_numeric($data['pay_status']) and $where[]     = ['ao.pay_status', '=', $data['pay_status']];
            is_numeric($data['sms_status']) and $where[]     = ['aj.sms_status', '=', $data['sms_status']];
            is_numeric($data['connect_status']) and $where[] = ['aj.connect_status', '=', $data['connect_status']];
        }

        // 报名数据
        $list = CJ::alias('aj')
            ->join('college a', 'a.id=aj.college_id')
            ->join('member m', 'aj.member_id=m.id')
            ->join('college_order ao', 'ao.college_join_id=aj.id')
            ->field('aj.*, a.title, a.thumb, ao.order_sn, ao.number, ao.need_pay, ao.pay_status')
            ->where($where)
            ->order('aj.id', 'desc')
            ->paginate($limit);

        $this->assign("list", $list);
        $this->assign('college_id', input('college_id/d', ''));
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
        $joinData = CJ::with(['college'])->whereIn('id', $ids)->select();

        /**********************   发送短信   **********************/
        foreach ($joinData as $key => $value) {
            // 标题是否超过23字符
            if (mb_strlen($value['college']['title'], 'utf8') > 23) {
                $title = mb_substr($value['college']['title'], 0, 19) . '...';
            } else {
                $title = $value['college']['title'];
            }

            // 整理数组
            $smsData = [
                'phone'      => $value['phone'],
                'title'      => $title,
                'start_time' => get_date($value['college']['end_time']), // 开课时间
                'type'       => 5, // 短信类型 课程报名通知
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

    // 报名详情 ⎛⎝≥⏝⏝≤⎛⎝
    public function joinDetail()
    {
        $id   = input('id/d');
        $join = CJ::with(['college', 'order'])->find($id);
        View::assign([
            "college" => $join['college'],
            "join"    => $join,
            "order"   => $join['order'],
        ]);
        return view();
    }

    //url
    protected function setUrl($catid, $aid)
    {
        $parturl = $this->category[$catid]['url'];
        $data    = [
            'id'  => $aid,
            'url' => '/' . $parturl . '/show/' . $aid . '.html',
        ];
        $this->college->set_field($data);
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
        $this->college->save_all($data);
        $this->success("更新排序成功(ˇˍˇ)");
    }

    //删除
    public function delete($id)
    {
        CollegeLogic::deleteById($id);
    }

}
