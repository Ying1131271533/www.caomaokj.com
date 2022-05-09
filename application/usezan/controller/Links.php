<?php
namespace app\usezan\controller;

use app\usezan\model\Links as L;

class Links extends Base
{
    protected $links;
    public function _uzauto()
    {
        $this->links = new L();
    }

    //列表
    public function index()
    {
        $keyword = input('get.keyword'); //搜索
        $map     = [];
        if (!empty($keyword)) {
            $map['name'] = ['like', '%' . $keyword . '%'];
        }
        $list = $this->links->get_paginate($map);
        $this->assign("list", $list);
        return $this->fetch();
    }

    //添加、操作
    public function add()
    {
        if (request()->isPost()) {
            $info               = input("post.");
            $info['createtime'] = time();
            $result             = $this->links->save_type($info, false);
            if ($result) {
                $this->success("添加友链成功");
            } else {
                $this->error("添加友链失败");
            }
        }
        return $this->fetch();
    }

    //修改、操作
    public function edit()
    {
        if (request()->isPost()) {
            $info   = input("post.");
            $result = $this->links->save_type($info);
            if ($result) {
                $this->success("修改友链成功", url('links/index') . '?tree=' . $this->tree_id);
            } else {
                $this->error("修改友链失败");
            }
        }
        $id = input("get.id");
        if (!$id) {
            $this->error("缺少必要参数");
        }

        //查询数据
        $list = $this->links->get_find($id);
        $this->assign("list", $list);
        return $this->fetch();
    }

    //排序操作
    public function listorder()
    {
        $listorders = input('post.listorders/a');
        //判断
        if (empty($listorders) && !is_array($listorders)) {
            $this->error(lang('do_empty'));
        }
        $data = [];
        foreach ($listorders as $key => $v) {
            $data[$key] = [
                'id'        => $key,
                'listorder' => $v,
            ];
        }
        $result = $this->links->save_all($data);
        if ($result) {
            $this->success(lang('listorder_ok'));
        } else {
            $this->error('没什么变化');
        }
    }

    //删除
    public function del()
    {
        $id = input('get.id');
        if (!$id) {
            $this->error(lang('do_empty'));
        }

        $del = $this->links->del($id);
        if ($del) {
            $this->success(lang('delete_ok'));
        } else {
            $this->error(lang('delete_error'));
        }
    }

}
