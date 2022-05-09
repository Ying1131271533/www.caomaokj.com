<?php
/**
 * 后台Ajax处理控制器
 * @author      [我就叫小柯] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2017 [环企优站科技]  (http://www.usezan.com)
 * @version     Usezan企业网站管理系统 v1.0
 */
namespace app\usezan\controller;

use app\usezan\model\Category;
use libs\Tree;
use think\Db;

class Ajax extends Base
{
    protected $db_table;

    /**
     * 改变状态
     *
     * @param input()
     * @return Array
     */
    public function status()
    {
        if (request()->isAjax()) {
            $data = [
                'id'    => input('id/d'),
                'value' => input('value/d') ? 0 : 1,
                'field' => input('field/s'),
                'db'    => input('db/s'),
                'url'   => input('url/s'),
            ];
            // return ['code' => 1, 'msg' => '修改失败', 'data' => $data['value']];
            $result = Db::name($data['db'])->where('id', $data['id'])->setField($data['field'], $data['value']);
            if (empty($result)) {
                return ['code' => 1, 'msg' => '修改失败'];
            }
            $url = str_replace('value/' . input('value/d'), 'value/' . $data['value'], $data['url']);
            return ['code' => 0, 'msg' => '修改成功', 'value' => $data['value'], 'url' => $url];
        }
    }

    //Ajax状态更新
    public function statusajax()
    {
        $info           = input('param.');
        $info['status'] = input('status/d');
        if (!$info['id']) {return ['status' => 0, 'msg' => lang("do_empty")];}
        //数据表操作
        switch (strtolower($info['con'])) {
            case 'auth':
                $request = Db::name("auth_rule")->where('id', $info['id'])->setField(['status' => $info['status']]);
                break;
            case 'category':
                $request = (new Category())->set_field(['id' => $info['id']], ['status' => $info['status']]);
                cache('category', (new Category())->get_cacheselect([]));
                break;
            case 'menu':
                $request = Db::name(strtolower($info['con']))->where('id', $info['id'])->setField(['status' => $info['status']]);
                $type    = $info['do'] == 'sta' ? 1 : 2;
                (new Menu())->set_cache($type);
                break;
            case 'ads':
                $request = Db::name('uads')->where('id', $info['id'])->setField(['status' => $info['status']]);
                break;
            case 'form':
                $request = Db::name('activity_comment')->where('id', $info['id'])->setField(['status' => $info['status']]);
                break;
            case 'keyword':
                $request = Db::name('keyword')->where('id', $info['id'])->setField(['hotspot' => $info['status']]);
                break;
            default:
                $request = Db::name(strtolower($info['con']))->where('id', $info['id'])->setField(['status' => $info['status']]);
                break;
        }
        if ($request) {
            if ($info['status']) {
                $url   = str_replace(substr($info['url'], -8), "status=0", $info['url']);
                $class = 'error-cc';
            } else {
                $url   = str_replace(substr($info['url'], -8), "status=1", $info['url']);
                $class = 'error-c';
            }
            return ['status' => 1, 'msg' => lang("do_success"), 'class' => $class, 'url' => $url];
        } else {
            return ['status' => 0, 'msg' => lang("do_error"), 'class' => 'error'];
        }
    }

    //获取菜单
    public function getcid()
    {
        //异步
        if (request()->isAjax()) {
            $aid = input('post.');
            if (!$aid['aid']) {
                return ['status' => 0, 'data' => '请选择栏目ID'];
            }
            $cate = $this->category[$aid['aid']];
            if (empty($cate)) {
                return ['status' => 0, 'data' => '栏目不存在，请更新栏目缓存!'];
            }
            return ['status' => 1, 'data' => $cate, 'type' => 1];
        }
        $cate = Db::name('category')
            ->where('status', '=', 1)
            ->field("id,parentid,catname,url")
            ->order("listorder asc,id asc")
            ->select();
        $str = "<tr class='on-radio'>
      			<td width='30' align='center'><input class='inputcheckbox' name='ids' value='\$id' type='radio'/></td>
				<td align='left'>\$id</td>
				<td>\$spacer\$catname</td>
      		</tr>";
        $tree = new Tree($cate);
        unset($array);
        $tree->icon = array('&nbsp;&nbsp;&nbsp;' . lang('tree_1'), '&nbsp;&nbsp;&nbsp;' . lang('tree_2'), '&nbsp;&nbsp;&nbsp;' . lang('tree_3'));
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $categorys  = $tree->get_tree(0, $str);
        $this->assign('categorys', $categorys);
        $this->assign('menu', input('get.menu', 0));
        return $this->fetch();
    }

    //移除图片
    public function delpic()
    {
        $info = input("get.");
        if (!$info['id']) {
            return ['status' => 0, 'msg' => lang("do_empty")];
        }
        // dump($info);return;
        //数据表
        $db_table = Db::name(strtolower($info['con']));
        switch (strtolower($info['con'])) {
            case 'system':
                $request = $db_table->where('id', $info['id'])->setField(['value' => '']);
                break;
            case 'category':
                $request = $db_table->where('id', $info['id'])->setField(['iconimg' => '']);
                break;
            default:
                $request = $db_table->where('id', $info['id'])->setField(['thumb' => '']);
                break;
        }
        if ($request) {
            del_oldthumb($info['thumb']);
            return ['status' => 1, 'msg' => lang("do_success"), 'class' => 'success'];
        } else {
            return ['status' => 0, 'msg' => lang("do_error"), 'class' => 'error'];
        }
    }

}
