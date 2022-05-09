<?php
namespace app\usezan\controller;

use app\common\model\Category;
use app\usezan\model\Slide as S;
use libs\Tree;

class Slide extends Base
{
    protected $slide;
    public function _uzauto()
    {
        $this->slide = new S();
    }

    //列表
    public function index()
    {
        $keyword              = input('keyword');
        $where                = [];
        $keyword and $where[] = ['a.title', 'like', "%" . $keyword . "%"];

        $list = S::alias('a')
            ->join('category b', 'a.cid = b.id')
            ->where($where)
            ->field('a.*, b.catname')
            ->order('b.id desc, a.listorder desc')
            ->paginate(20);
        // dump($list);return;
        $this->assign("list", $list);
        return $this->fetch();
    }

    // 添加、操作
    public function add()
    {
        if (request()->isPost()) {
            $info               = input("post.");
            $info['createtime'] = time();
            $result             = $this->slide->save_type($info, false);
            if ($result) {
                $this->success("添加幻灯片成功");
            } else {
                $this->error("添加幻灯片失败");
            }
        }

        // 栏目分类
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

    //修改、操作
    public function edit()
    {
        if (request()->isPost()) {
            $info = input("post.");
            //处理图片
            if (empty($info['thumb'])) {
                $info['thumb'] = $info['othumb'];
            } else {
                del_oldthumb($info['othumb']);
            }
            unset($info['othumb']);
            $result = $this->slide->save_type($info);
            if ($result) {
                $this->success("修改幻灯片成功", url('slide/index') . '?tree=' . $this->tree_id);
            } else {
                $this->error("修改幻灯片失败");
            }
        }

        // 栏目数据
        $cid  = input('cid');
        $cate = $this->category[$cid];
        foreach ($this->category as $r) {
            $r['selected'] = $r['id'] == $cate['id'] ? 'selected' : '';
            $array[]       = $r;
        }
        $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree             = new Tree($array);
        $select_categorys = $tree->get_tree(0, $str, $cate['id']);
        $this->assign('select_categorys', $select_categorys);
        $this->assign("cate", $cate);

        //查询数据
        $id = input("get.id");
        if (!$id) {
            $this->error("缺少必要参数");
        }
        $list = $this->slide->get_find($id);
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
        $result = $this->slide->save_all($data);
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

        //获取图片
        $thumb = $this->slide->get_value(['id' => $id], 'thumb');
        $del   = $this->slide->del($id);
        if ($del) {
            del_oldthumb($thumb);
            $this->success(lang('delete_ok'));
        } else {
            $this->error(lang('delete_error'));
        }
    }

}
