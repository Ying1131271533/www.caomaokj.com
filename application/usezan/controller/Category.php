<?php
namespace app\usezan\controller;

use app\common\model\Category as C;
use app\common\model\Category as ModelCategory;
use libs\Tree;
use think\Db;
use think\facade\Cache;
use think\Request;

class Category extends Base
{
    protected $mcategory, $categorys, $module;
    public function _uzauto()
    {
        $this->mcategory = new C();
        $this->module    = config('module.m_name');
        $this->assign('module', $this->module);
    }

    //列表
    public function index()
    {
        // dump($this->category);return;
        if ($this->category) {
            foreach ($this->category as $v) {
                $v['str_manage'] = '<a href="' . url('category/add') . '?parentid=' . $v['id'] . '&tree=' . $this->tree_id . '">添加子栏目</a> | <a href="' . url('category/edit') . '?id=' . $v['id'] . '&tree=' . $this->tree_id . '">修改</a> | <a href="javascript:confirm_delete(\'' . url('category/del') . '?id=' . $v['id'] . '&tree=' . $this->tree_id . '\',\'cate\')">删除</a> ';

                $v['status'] = $v['status'] ? '<a class="bth-a ajax-status" onclick=StatusAjax(this) data-href="' . url('ajax/statusajax') . '?id=' . $v['id'] . '&status=0"></a>' : '<a class="bth-a error-c ajax-status" onclick=StatusAjax(this) data-href="' . url('ajax/statusajax') . '?id=' . $v['id'] . '&status=1"></a>';

                // 是否显示为导航
                $v['is_menu'] = $v['is_menu'] ?
                '<a class="bth-a ajax-status" onclick=StatusAjax(this) data-href="' . url('Category/isMenu') . '?id=' . $v['id'] . '&is_menu=0"></a>' :
                '<a class="bth-a error-c ajax-status" onclick=StatusAjax(this) data-href="' . url('Category/isMenu') . '?id=' . $v['id'] . '&is_menu=1"></a>';

                $v['module'] = $this->module[$v['module']]['name'];
                $array[]     = $v;
            }
            $str = "<tr>
						<td width='40' align='center'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input-text-c'></td>
						<td align='center'>\$id</td>
						<td align='center'>\$is_menu</td>
                        <td width='350'>\$spacer \$catname</td>
						<td align='left'>\$url</td>
						<td align='left'>\$module</td>
						<td align='center'>\$status</td>
						<td align='left'>\$str_manage</td>
					</tr>";
            $tree = new Tree($array);
            unset($array);
            $tree->icon = array('&nbsp;&nbsp;&nbsp;' . lang('tree_1'), '&nbsp;&nbsp;&nbsp;' . lang('tree_2'), '&nbsp;&nbsp;&nbsp;' . lang('tree_3'));
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $cate       = $tree->get_tree(0, $str);
            $this->assign('cate', $cate);
        }
        return $this->fetch();
    }

    // 分类树形图
    public function tree()
    {
        $cateData = ModelCategory::order(['listorder' => 'asc', 'id' => 'asc'])->select()->toArray();
        $tree = get_child_tree_data($cateData);
        $ids = array_column($cateData, 'id');
        
        return view('', [
            'ids' => $ids,
            'tree' => $tree,
        ]);
    }

    /**
     * 是否显示为导航
     *
     * @param Request $request
     * @return array
     */
    public function isMenu(Request $request)
    {
        $data = $request->param();
        if (!$data['id']) {return ['status' => 0, 'msg' => lang("do_empty")];}
        // dump($data);return;
        $result = C::update(['id' => $data['id'], 'is_menu' => $data['is_menu']]);
        if ($result) {
            if ($data['is_menu']) {
                $url   = str_replace(substr($data['url'], -9), "is_menu=0", $data['url']);
                $class = 'error-cc';
            } else {
                $url   = str_replace(substr($data['url'], -9), "is_menu=1", $data['url']);
                $class = 'error-c';
            }
            return ['status' => 1, 'msg' => lang("do_success"), 'class' => $class, 'url' => $url];
        } else {
            return ['status' => 0, 'msg' => lang("do_error"), 'class' => 'error'];
        }
    }

    //添加
    public function add()
    {
        if (request()->isPost()) {
            $info = input("post.");
            if (empty($info['iconimg'])) {
                unset($info['iconimg']);
            }

            //检查路径
            if (empty($info['url'])) {
                $this->error("栏目路径不能为空");
            } else {
                $urltrue = $this->mcategory->isCount(['url' => $info['url']]);
                if ($urltrue) {
                    $this->error("栏目路径已经存在");
                }

            }
            if ($this->checkUrl($info['url'])) {
                $this->error("路径格式为数字、英文并且不能为show");
            }
            //添加数据
            $id = $this->mcategory->saveType($info, false);
            if ($id) {
                $this->repairCache(false);
                $this->success("添加栏目成功", url("category/add") . '?parentid=' . $info['parentid'] . '&tree=' . $this->tree_id);
            } else {
                $this->error("添加栏目失败");
            }
        }
        
        //栏目数据
        // $cid       = input('cid');
        $parentid  = input("get.parentid", 0); //父级ID
        $categorys = $this->category ? $this->category : [];
        if ($categorys) {
            foreach ($categorys as $r) {
                $array[] = $r;
            }
            $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
            $tree             = new Tree($array);
            $select_categorys = $tree->get_tree(0, $str, $parentid);
            $this->assign('select_categorys', $select_categorys);
        }
        $inmd = $parentid ? $this->module[$this->category[$parentid]['module']]['module'] : 'works';
        $this->assign('inmd', $inmd);

        return $this->fetch();
    }

    //修改
    public function edit()
    {
        if (request()->isPost()) {
            $info = input("post.");
            if (empty($info['id'])) {
                $this->error("缺少必要参数");
            }

            //检查路径
            if (empty($info['url'])) {
                $this->error("栏目路径不能为空");
            } else {
                $urltrue = $this->mcategory->isCount([['url', '=', $info['url']], ['id', 'neq', $info['id']]]);
                if ($urltrue) {
                    $this->error("栏目路径已经存在");
                }
            }

            // if ($this->checkUrl($info['url'])) {
            //     $this->error("路径格式为数字、英文并且不能为show");
            // }

            $result = $this->mcategory->saveType($info);
            // 更新
            if ($result) {
                // 更新url
                $this->getUrl($info['id'], $info['url'], $info['module']);
                // cache
                $this->repairCache(false);
                $this->success("修改栏目成功", url("category/index") . '?tree=' . $this->tree_id);
            } else {
                $this->error('修改栏目失败');
            }
        }

        $id = input("get.id", 0);
        if (empty($id)) {
            $this->error("缺少必要参数╮(╯_╰)╭");
        }

        // 栏目数据
        $cate = $this->category[$id];
        foreach ($this->category as $r) {
            $r['selected'] = $r['id'] == $cate['parentid'] ? 'selected' : '';
            $array[]       = $r;
        }
        $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree             = new Tree($array);
        $select_categorys = $tree->get_tree(0, $str, $cate['parentid']);
        $this->assign('select_categorys', $select_categorys);
        $this->assign("cate", $cate);

        return $this->fetch();
    }

    //更新内容路径
    protected function getUrl($catid, $url, $module)
    {
        if ($this->category[$catid]['url'] != $url) {
            $db_module = $this->module[$module]['module'];
            $data      = Db::name($db_module)->where('catid', $catid)->field("id,url")->select();
            if (!empty($data)) {
                $newurl = [];
                foreach ($data as $key => $val) {
                    $newurl[$key] = str_replace($this->category[$catid]['url'], $url, $val);
                    Db::name($db_module)->where('id', $newurl[$key]['id'])->setField(['url' => $newurl[$key]['url']]);
                }
            }
            return true;
        }
    }

    //排序
    public function listorder()
    {
        $listorders = input("post.listorders/a");
        if (empty($listorders)) {
            $this->error("缺少必要参数");
        }

        $data = [];
        foreach ($listorders as $key => $v) {
            $data[$key] = [
                'id'        => $key,
                'listorder' => $v,
            ];
        }
        $result = $this->mcategory->saveAll($data);
        if ($result) {
            $this->repairCache(false);
            $this->success("排序成功");
        } else {
            $this->error('排序失败，请重试...');
        }
    }

    //删除
    public function del()
    {
        $catid = input("get.id", 0);
        //子类ID
        $arrchildid = $this->mcategory->isCount(['parentid' => $catid]);
        if ($arrchildid) {
            return fail("请先删除子栏目");
        }
        $this->mcategory->del($catid);
        $this->repairCache(false);
        return success("删除栏目成功");
    }

    //更新缓存状态
    public function repairCache($isurl = true)
    {
        Cache::store('category')->set('category', $this->mcategory->getCacheselect());
        if ($isurl) {
            $this->success('更新缓存成功');
        } else {
            return true;
        }
    }

    //检查show
    protected function checkUrl($url)
    {
        $nourl = ['show', 'labels'];
        if (in_array(strtolower($url), $nourl)) {
            return true;
        }
        if (!preg_match("/^[A-Za-z0-9\/]+$/u", $url)) {
            return true;
        }
        return false;
    }

}
