<?php
namespace app\usezan\controller;

use app\common\model\Attributes as A;
use app\common\model\Logistics as L;
use app\common\model\LogisticsAttributes as LA;
use app\common\model\LogisticsKeyword as LK;
use app\common\model\LogisticsService as LS;
use app\common\model\LogisticsServiceType as LST;
use libs\Tree;
use think\facade\View;

class Logistics extends Base
{
    protected $logistics, $module;
    public function _uzauto()
    {
        $this->module    = 6;
        $this->logistics = new L();
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

        $list = $this->logistics->get_paginate($map);
        $this->assign("list", $list);
        $this->assign('keys', $keys);
        return $this->fetch();
    }

    //添加
    public function add()
    {
        if (request()->isPost()) {
            $info = input();

            // 物流属性
            $attributesData = input("attributes/a");
            empty($attributesData) and jinx('物流属性不能为空');

            // 服务类型
            $serviceData = input("service/a");
            empty($serviceData) and jinx('服务类型不能为空');

            // imgs
            if (isset($info['imgs']) && $info['imgs']) {
                $info['imgs'] = $this->checkPics($info);
                unset($info['imgs_order'], $info['imgs_title'], $info['imgs_remark']);
            }

            // 开启事务
            L::startTrans();
            try {
                $logistics = L::create($info);
                $logistics->attributes()->saveAll($attributesData);
                // dump($serviceData);return;
                $logistics->services()->saveAll($serviceData);
                // 提交事务
                L::commit();
                jinx('添加成功');
            } catch (\Exception $e) {
                // 回滚事务
                L::rollback();
                jinx('添加失败');
            }
        }

        // 物流属性
        $attributes = A::where(['status' => 1])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        /**********************   服务类型   **********************/
        $seviceType = LST::where(['status' => 1])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        //栏目
        $categorys = $this->category ? $this->category : [];
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
        }

        View::assign('attributes', $attributes);
        View::assign('seviceType', $seviceType);
        return $this->fetch();
    }

    //修改
    public function edit()
    {
        if (request()->isPost()) {
            $id = input('id/d');
            empty($id) and jinx('参数不能为空');

            $info = input();
            // dump(input());return;

            //处理data
            if (empty($info['thumb'])) {
                unset($info['thumb'], $info['oldthumb']);
            } else {
                del_oldthumb($info['oldthumb']);
            }

            //imgs
            if (isset($info['imgs']) && $info['imgs']) {
                $info['imgs'] = $this->checkPics($info);
                unset($info['imgs_order'], $info['imgs_title'], $info['imgs_remark']);
            }

            // 物流属性
            $attributesData = input("attributes/a");
            empty($attributesData) and jinx('物流属性不能为空');

            // 服务类型
            $serviceData = input("service/a");
            empty($serviceData) and jinx('服务类型不能为空');

            // 开启事务
            L::startTrans();
            try {
                $logistics = L::update($info);
                LA::where('logistics_id', $id)->delete();
                LS::where('logistics_id', $id)->delete();
                $logistics->attributes()->saveAll($attributesData);
                $logistics->services()->saveAll($serviceData);
                // 提交事务
                L::commit();
                jinx('修改成功');
            } catch (\Exception $e) {
                // 回滚事务
                L::rollback();
                jinx('修改失败');
            }

        }

        $id = input("id");
        if (!$id) {
            $this->error("缺少必要参数");
        }

        $list = L::field('*')->with(['attributes' => function ($query) {
            $query->field('id, name'); // 需要手动添加关联字段，像join一样
        }, 'services' => function ($query) {
            $query->field('id, name');
        }])->find($id);

        // imgs
        if ($list['imgs']) {
            $imgs      = explode(':::', $list['imgs']);
            $imgs_data = [];
            foreach ($imgs as $key => $vo) {
                $imgs_data[$key] = explode("|", $vo);
            }
            //重新排序组合
            $timeKey = array_column($imgs_data, 1);
            if (!empty($timeKey)) {
                array_multisort($timeKey, SORT_ASC, $imgs_data);
            }
            $list['imgs'] = $imgs_data;
            unset($imgs, $imgs_data);
        }
        // 物流属性
        $attributes = A::where(['status' => 1])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        // 物流属性选中位置
        $attributesId = [];
        foreach ($list['attributes'] as $value) {
            $attributesId[] = $value['id'];
        }

        /**********************   服务类型   **********************/
        $seviceType = LST::where(['status' => 1])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        // 物流属性选中位置
        $serviceId = [];
        foreach ($list['services'] as $value) {
            $serviceId[] = $value['id'];
        }

        // category
        foreach ($this->category as $vo) {
            if ($vo['status'] && intval($vo['module']) === $this->module) {
                $vo['selected'] = $vo['id'] == $list['catid'] ? 'selected' : '';
                $array[]        = $vo;
            }
            continue;
        }

        $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree             = new Tree($array);
        $select_categorys = $tree->get_tree(0, $str, $list['catid']);
        $this->assign('attributes', $attributes);
        $this->assign('attributesId', $attributesId);
        $this->assign('seviceType', $seviceType);
        $this->assign('serviceId', $serviceId);
        $this->assign('select_categorys', $select_categorys);
        $this->assign("list", $list);
        return $this->fetch();
    }

    //多图处理
    protected function checkPics($data, $field = 'imgs')
    {
        if (is_array($data[$field])) {
            $pic = [];
            foreach ($data[$field] as $key => $v) {
                $pic[] = $v . "|" . $data['imgs_order'][$key] . "|" . $data['imgs_title'][$key] . "|" . $data['imgs_remark'][$key];
            }
            return implode(":::", $pic);
        }
    }

    //url
    protected function setUrl($catid, $aid)
    {
        $parturl = $this->category[$catid]['url'];
        $data    = [
            'id'  => $aid,
            'url' => '/' . $parturl . '/show/' . $aid . '.html',
        ];
        $this->logistics->set_field($data);
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
        $this->logistics->save_all($data);
        $this->success("更新排序成功(ˇˍˇ)");
    }

    // 删除
    public function del()
    {
        $id = input('id');
        empty($id) and jinx('参数不能为空');
        $logistics = L::get($id);

        // 开启事务
        L::startTrans();
        try {
            $logistics->keywords()->detach();
            $logistics->attributes()->detach();
            $logistics->services()->detach();
            $logistics->delete();

            // 提交事务
            L::commit();
            jinx('删除成功');
        } catch (\Exception $e) {
            // 回滚事务
            L::rollback();
            jinx('删除失败');
        }

    }
}
