<?php
/**
 * User(管理员控制器)
 * @author      [我就叫小柯 知更：你叫个屁叫？ 管理员的命名成User也就有你这傻逼了！] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2017 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\usezan\controller;

use app\usezan\model\AuthGroup;
use app\usezan\model\AuthGroupAccess;
use app\usezan\model\User as U;
use think\db\Where;

class User extends Base
{
    //自动加载
    protected $user, $auth_group, $auth_group_access;
    public function _uzauto()
    {
        $this->user              = new U();
        $this->auth_group        = new AuthGroup();
        $this->auth_group_access = new AuthGroupAccess();

    }

    //管理员列表
    public function index()
    {
        $keyword = input('param.keyword'); //搜索
        $map     = [];
        if (!empty($keyword)) {
            $map['username'] = ['like', '%' . $keyword . '%'];
        }
        $user = $this->user->get_paginate($map, config("usezan_page"));
        foreach ($user as $k => $v) {
            $user[$k]          = $v;
            $user[$k]['rname'] = $this->auth_group->get_value(['id' => $v['group_id']], 'title');
        }
        $this->assign("ulist", $user);
        return $this->fetch();
    }

    //管理员添加、操作
    public function add()
    {
        if (request()->isPost()) {
            $info   = input('post.');
            $result = $this->addSave($info, 0);
            if ($result) {
                //添加[权限]
                $this->auth_group_access->save_type(['uid' => $result, 'group_id' => $info['group_id']], false);
                $this->success('添加管理员 “' . $info['username'] . '” 成功(ˇˍˇ)', url('user/index') . '?tree=' . $info['tree']);
            } else {
                $this->error('添加管理员失败╮(╯_╰)╭');
            }
        }
        //用户组
        $rules = $this->auth_group->get_select(['status' => 1, 'id' => ['neq', config('usezan_admin_super')]]);
        $this->assign("rules", $rules);
        return $this->fetch();
    }

    //管理员编辑、操作
    public function edit()
    {
        if (request()->isPost()) {
            $info   = input('post.');
            $result = $this->addSave($info, 1);
            if ($result) {
                $this->success('修改管理员 “' . $info['username'] . '” 成功(ˇˍˇ)', url('user/index') . '?tree=' . $info['tree']);
            } else {
                $this->error('修改管理员失败');
            }
        }
        $id   = input("param.id");
        $user = $this->user->get_find($id);
        if (session('usezan_admin.id') != config('usezan_admin_super')) {
            $rules = $this->auth_group->get_select(['status' => 1, 'id' => ['neq', config('usezan_admin_super')]]);
        } else {
            $rules = $this->auth_group->get_select(['status' => 1]);
        }
        $this->assign("rules", $rules);
        $this->assign("user", $user);
        return $this->fetch();
    }

    //删除管理员
    public function del()
    {
        $id = input('param.id');
        if (!$id) {
            $this->error("缺少必要参数...");
        }

        $del = U::destroy($id);
        if ($del) {
            $this->auth_group_access->del(['uid' => $id]);
            $this->success(lang('delete_ok'));
        } else {
            $this->error(lang('delete_error'));
        }
    }

    //添加、修改操作
    protected function addSave($data, $id)
    {
        $id = $id ? $data['id'] : '';
        if ($id) {
            if (empty($data['username'])) {
                $this->error("账号不能为空╮(╯_╰)╭");
            }

            $username = $this->user->user_count(['username' => $data['username'], 'id' => ['neq', $data['id']]]);
            if ($username) {
                $this->error('管理员' . $data['username'] . '已经存在╮(╯_╰)╭');
            }

            if (empty($data['password'])) {
                $data['password'] = $data['opwd'];
            } else {
                $data['password'] = sysmd5($data['password']);
            }
            unset($data['opwd']);
            //更新[权限]
            $this->auth_group_access->set_field(['uid' => $data['id']], 'group_id', $data['group_id']);
        } else {
            if (empty($data['username']) || empty($data['password'])) {
                $this->error("账号或者密码不能为空╮(╯_╰)╭");
            }
            $username = $this->user->user_count(['username' => $data['username']]);
            if ($username) {
                $this->error('管理员' . $data['username'] . '已经存在╮(╯_╰)╭');
            }
            $data['password']   = sysmd5($data['password']);
            $data['createtime'] = time();
        }
        return $id ? $this->user->save_type($data) : $this->user->save_type($data, false);
    }

}
