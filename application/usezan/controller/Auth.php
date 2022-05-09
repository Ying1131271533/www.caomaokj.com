<?php
/**
 * 用户权限管理
 * @author      [我就叫小柯 ] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2017 [环企优站科技]  (https://www.h7uz.com)
 * @version     Usezan企业网站管理系统 v1.0
 */
namespace app\usezan\controller;

use app\usezan\model\AuthGroup;
use app\usezan\model\AuthRule;
use libs\Tree;
use think\Db;
use think\facade\Cache;

class Auth extends Base
{
    //自动加载
    protected $auth_group, $auth_rule, $auth_group_access;
    public function _uzauto()
    {
        $this->auth_group        = new AuthGroup(); //用户组
        $this->auth_rule         = new AuthRule(); //权限节点
        $this->auth_group_access = Db::name('auth_group_access'); //中间表
    }
/*+------------------------------------
+   用户组方法  $this->auth_group
+------------------------------------*/
    //用户组列表
    public function auth_index()
    {
        $keyword = input('param.keyword'); //搜索
        $map     = [];
        if (!empty($keyword)) {
            $map['title'] = ['like', '%' . $keyword . '%'];
        }
        $auth = $this->auth_group->get_paginate($map, config("usezan_page"));
        $this->assign("ulist", $auth);
        return $this->fetch();
    }

    //角色添加、操作
    public function auth_add()
    {
        if (request()->isPost()) {
            $info   = input('param.');
            $result = $this->get_auth($info, 0);
            if ($result) {
                $this->success('添加用户 “' . $info['title'] . '” 成功(ˇˍˇ)', url('auth/auth_index') . '?tree=' . $info['tree']);
            } else {
                $this->error('添加用户失败╮(╯_╰)╭');
            }
        }
        return $this->fetch();
    }

    //角色组编辑、操作
    public function auth_edit()
    {
        if (request()->isPost()) {
            $info   = input('param.');
            $result = $this->get_auth($info, 1);
            if ($result) {
                $this->success('修改用户 “' . $info['title'] . '” 成功(ˇˍˇ)', url('auth/auth_index') . '?tree=' . $info['tree']);
            } else {
                $this->error('修改用户失败');
            }
        }
        $id   = input("param.id");
        $auth = $this->auth_group->get_find($id);
        $this->assign("auth", $auth);
        return $this->fetch();
    }

    //删除角色
    public function del()
    {
        $id = input('param.id');
        if (!$id) {
            $this->error(lang('do_empty'));
        }

        $del = $this->auth_group->del($id);
        if ($del) {
            $this->success(lang('delete_ok'), url('auth/auth_index') . '?tree=' . input('param.tree'));
        } else {
            $this->error(lang('delete_error'));
        }
    }

    //角色操作
    protected function get_auth($data, $id)
    {
        $id = $id ? $data['id'] : '';
        if (empty($data['title'])) {
            $this->error("用户名不能为空");
        }

        if ($id) {
            $username = $this->auth_group->is_count(['title' => $data['title'], 'id' => ['neq', $id]]);
        } else {
            $username = $this->auth_group->is_count(['title' => $data['title']]);
        }
        if ($username) {
            $this->error('用户' . $data['title'] . '已经存在');
        }

        return $id ? $this->auth_group->save_type($data) : $this->auth_group->save_type($data, false);
    }

/*+------------------------------------
+   权限节点方法  $this->auth_rule
+------------------------------------*/
    //节点列表
    public function auth_rule()
    {
        $rule = $this->auth_rule->get_select();
        if ($rule) {
            foreach ($rule as $k => $v) {
                $v['str_manage'] = '<a href="' . url('auth/auth_rule_add') . '?id=' . $v['id'] . '&tree=' . $this->tree_id . '">添加子栏目</a> | <a href="' . url('auth/auth_rule_edit') . '?id=' . $v['id'] . '&tree=' . $this->tree_id . '">修改</a> | <a href="javascript:confirm_delete(\'' . url('auth/auth_rule_del') . '?id=' . $v["id"] . '&tree=' . $this->tree_id . '\')">删除</a> ';
                $v['back']       = $v['level'] == 1 ? "class=back-se" : '';
                $v['status']     = $v['status'] ? '<a class="bth-a ajax-status" onclick=StatusAjax(this) data-href="' . url('ajax/statusajax') . '?id=' . $v['id'] . '&status=0"></a>' : '<a class="bth-a error-c ajax-status" onclick=StatusAjax(this) data-href="' . url('ajax/statusajax') . '?id=' . $v['id'] . '&status=1"></a>';
                if ($v['ismenu']) {
                    $v['ismenu'] = '左侧菜单';
                } elseif ($v['issub']) {
                    $v['ismenu'] = 'Tree';
                } else {
                    $v['ismenu'] = '------------';
                }
                $array[] = $v;
            }
            //字符串
            $str = "<tr \$back>
                    <td align='center'><input name='listorders[\$id]' type='text' size='2' value='\$listorder' class='input-text-c'></td>
                    <td>\$spacer\$title &nbsp;</td>
                    <td>\$name</td>
                    <td>\$ismenu</td>
                    <td align='center'>\$status</td>
                    <td align='center'>\$str_manage</td>
                </tr>";
        }
        $tree = new Tree($array);
        unset($array);
        $tree->icon = array('&nbsp;&nbsp;&nbsp;' . lang('tree_1'), '&nbsp;&nbsp;&nbsp;' . lang('tree_2'), '&nbsp;&nbsp;&nbsp;' . lang('tree_3'));
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $rule       = $tree->get_tree(0, $str);
        $this->assign('rule', $rule);
        return $this->fetch();
    }

    // 节点树形图
    public function auth_tree(){
        
    }

    //节点添加、操作
    public function auth_rule_add()
    {
        if (request()->isPost()) {
            $info = input("post.");
            //检查节点名称
            $rulename = $this->auth_rule->is_count(['name' => $info['name']]);
            if ($rulename) {
                $this->error("权限节点已经存在");
            }

            //等级+1
            $info['level'] = 1;
            if ($info['parentid']) {
                $leve          = $this->auth_rule->get_value(['id' => $info['parentid']], 'level');
                $info['level'] = $leve + 1;
            }
            //导航控制[等级<3]
            if ($info['level'] < 3) {
                $info['ismenu'] = 1;
            } else {
                $info['ismenu'] = 0;
            }
            //插入数据
            $rule_id = $this->auth_rule->save_type($info, false);
            if ($rule_id) {
                $this->auth_rule->set_field(['id' => $rule_id, 'cdname' => $rule_id]);
                $this->success("权限节点添加成功");
            } else {
                $this->error("权限节点添加失败");
            }
        }
        //获取分组
        $id   = input("param.id", 0);
        $rule = $this->auth_rule->get_select();
        //有ID处理
        if ($id) {
            foreach ($rule as $k => $v) {
                $v['selected'] = $v['id'] == $id ? "selected" : "";
                $array[]       = $v;
            }
        } else {
            $array = $rule;
        }
        $str  = "<option value='\$id' \$selected>\$spacer\$title &nbsp;</option>";
        $tree = new Tree($array);
        unset($rule);
        $tree->icon = array('&nbsp;&nbsp;&nbsp;' . lang('tree_1'), '&nbsp;&nbsp;&nbsp;' . lang('tree_2'), '&nbsp;&nbsp;&nbsp;' . lang('tree_3'));
        $tree->nbsp = '&nbsp;';
        $rule       = $tree->get_tree(0, $str);
        $this->assign("rule", $rule);
        return $this->fetch();
    }

    //节点修改、操作
    public function auth_rule_edit()
    {
        if (request()->isPost()) {
            $info = input("post.");
            //检查节点名称
            $rulename = $this->auth_rule->is_count(['name' => $info['name'], 'id' => ['neq', $info['id']]]);
            if ($rulename) {
                $this->error("权限节点已经存在");
            }

            //等级+1
            $info['level'] = 1;
            if ($info['parentid']) {
                $leve          = $this->auth_rule->get_value(['id' => $info['parentid']], 'level');
                $info['level'] = $leve + 1;
            }
            //更新数据
            $ruletrue = $this->auth_rule->save_type($info);
            if ($ruletrue) {
                $this->success("权限节点修改成功", url('auth/auth_rule') . '?tree=' . $info['tree']);
            } else {
                $this->error("权限节点修改失败");
            }
        }
        //获取编辑数据
        $id = input('param.id');
        if (!$id) {
            $this->error("缺少必要参数");
        }

        $rule_list = $this->auth_rule->get_find($id);
        $rule      = $this->auth_rule->get_select();
        foreach ($rule as $k => $v) {
            $v['selected'] = $v['id'] == $rule_list['parentid'] ? "selected" : "";
            $array[]       = $v;
        }
        $str  = "<option value='\$id' \$selected>\$spacer\$title &nbsp;</option>";
        $tree = new Tree($array);
        unset($array);
        $tree->icon = array('&nbsp;&nbsp;&nbsp;' . lang('tree_1'), '&nbsp;&nbsp;&nbsp;' . lang('tree_2'), '&nbsp;&nbsp;&nbsp;' . lang('tree_3'));
        $tree->nbsp = '&nbsp;';
        $rule       = $tree->get_tree(0, $str);
        $this->assign("rule", $rule);
        $this->assign("rule_list", $rule_list);
        return $this->fetch();
    }

    //节点删除
    public function auth_rule_del()
    {
        $id = input('param.id');
        if (!$id) {
            $this->error(lang('do_empty'));
        }

        //检查是否存在子节点
        $pname = $this->auth_rule->is_count(['parentid' => $id]);
        if ($pname) {
            $this->error("请先删除子节点");
        }

        $del = $this->auth_rule->del($id);
        if ($del) {
            $this->success(lang('delete_ok'), url('auth/auth_rule') . '?tree=' . input('param.tree'));
        } else {
            $this->error(lang('delete_error'));
        }
    }

    //排序操作-
    public function listorder()
    {
        $listorders = input('param.listorders/a');
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
        $this->auth_rule->save_all($data);
        $this->success(lang('listorder_ok'));
    }

    //用户授权
    public function auth_node()
    {
        if (request()->isPost()) {
            $node = input("param.");
            if (!$node['id']) {
                $this->error("缺少必要参数");
            }

            $node['rules'] = implode(",", $node['rules']);
            if ($this->auth_group->save_type($node)) {
                $this->success('授权成功', url('auth/auth_index'));
            } else {
                $this->error("缺少必要参数");
            }
        }
        //读取权限节点操作
        $nid = input("param.id");
        //权限节点
        $node = $this->auth_rule->get_select();
        //现有的权限
        $role_node = explode(",", $this->auth_group->where('id', $nid)->value("rules"));
        //检查当前权限
        $nodedata = [];
        foreach ($node as $key => $v) {
            $nodedata[$key]          = $v;
            $nodedata[$key]['check'] = "";
            if ($role_node) {
                if (in_array($v['id'], $role_node)) {
                    $nodedata[$key]['check'] = "checked";
                }
            } else {
                $nodedata[$key]['check'] = "";
            }
        }
        $node = GetLayer($nodedata);
        $this->assign("node", $node);
        $this->assign("nid", $nid);
        return $this->fetch();
    }

    //更新权限缓存
    public function auth_rule_cache()
    {
        $data = $this->auth_rule->get_cache_select('listorder asc,id asc', 'id,name,title,uzico,parentid,issub');
        Cache::store('rule')->set('auth_cache_rule', $data);
        $this->success('更新权限缓存成功');
    }

}
