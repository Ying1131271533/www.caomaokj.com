<?php
namespace app\usezan\controller;
use app\usezan\model\Searchkey;
class Searchkey extends Base {
    protected $searchkey;
    public function _uzauto() {
        $this->searchkey = new Searchkey();
    }

    //列表
    public function index(){
        $map = [];
        $list = $this->searchkey->get_paginate($map);
        $this->assign("list",$list);
        return $this->fetch();
    }

    //添加、操作
    public function add(){
        if (request()->isPost()){
            $info = input("post.");
            $result = $this->searchkey->save_type($info,false);
            if ($result) {
                $this->success("添加成功");
            } else {
                $this->error("添加失败");
            }
        }
        return $this->fetch();
    }

    //修改、操作
    public function edit(){
        if (request()->isPost()){
            $info = input("post.");
            $result = $this->searchkey->save_type($info);
            if ($result) {
                $this->success("修改成功",url('searchkey/index').'?tree='.$this->tree_id);
            } else {
                $this->error("修改失败");
            }
        }
        $id = input("get.id");
        if (!$id) $this->error("缺少必要参数");
        //查询数据
        $list = $this->searchkey->get_find($id);
        $this->assign("list",$list);
        return $this->fetch();
    }

    //删除
    public function del(){
        $id = input('get.id');
        if (!$id) $this->error(lang('do_empty'));
        $del = $this->searchkey->del($id);
        if ($del) {
            $this->success(lang('delete_ok'));
        } else {
            $this->error(lang('delete_error'));
        }
    }




}

