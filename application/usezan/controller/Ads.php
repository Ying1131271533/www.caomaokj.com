<?php
namespace app\usezan\controller;
use app\usezan\model\Uads;
class Ads extends Base {
    protected $uads;
    public function _uzauto() {
        $this->uads = new Uads();
    }

    //列表
    public function index(){
        $keys = input('get.');
        $map = [];
        if (!empty($keys['keyword'])) {
            $map['name'] = ['like','%'.$keys['keyword'].'%'];
        }
        if (isset($keys['type']) && $keys['type'] >= 0){
            $map['type'] = $keys['type'];
        } else {
            $keys['type'] = -1;
        }
        $list = $this->uads->get_paginate($map);
        $this->assign('keys',$keys);
        $this->assign("list",$list);
        return $this->fetch();
    }

    //添加、操作
    public function add(){
        if (request()->isPost()){
            $info = input("post.");
            $info['createtime'] = time();
            $result = $this->uads->save_type($info,false);
            if ($result) {
                $this->success("添加广告成功");
            } else {
                $this->error("添加广告失败");
            }
        }
        return $this->fetch();
    }

    //修改、操作
    public function edit(){
        if (request()->isPost()){
            $info = input("post.");
            $result = $this->uads->save_type($info);
            if ($result) {
                $this->success("修改广告成功",url('ads/index').'?tree='.$this->tree_id);
            } else {
                $this->error("修改广告失败");
            }
        }
        $id = input("get.id");
        if (!$id) $this->error("缺少必要参数");
        //查询数据
        $list = $this->uads->get_find($id);
        $this->assign("list",$list);
        return $this->fetch();
    }

    //排序操作
    public function listorder(){
        $listorders = input('post.listorders/a');
        //判断
        if (empty($listorders) && !is_array($listorders)) {
            $this->error(lang('do_empty'));
        }
        $data = [];
        foreach ($listorders as $key => $v) {
            $data[$key] = [
                'id' => $key,
                'listorder' => $v
            ];
        }
        $result = $this->uads->save_all($data);
        if ($result){
            $this->success(lang('listorder_ok'));
        } else {
            $this->error('没什么变化');
        }
    }

    //删除
    public function del(){
        $id = input('get.id');
        if (!$id) $this->error(lang('do_empty'));
        //获取图片
        $thumb = $this->uads->get_value(['id'=>$id],'cover');
        $del = $this->uads->del($id);
        if ($del) {
            if ($thumb) del_oldthumb($thumb);
            $this->success(lang('delete_ok'));
        } else {
            $this->error(lang('delete_error'));
        }
    }




}

