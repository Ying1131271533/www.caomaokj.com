<?php
namespace app\usezan\controller;
use app\usezan\model\Works;
use app\usezan\model\WorksContent;
use libs\Tree;
class Works extends Base {
    protected $works,$works_content,$module;
    public function _uzauto() {
        $this->module = 1;
        $this->works = new Works();
        $this->works_content = new WorksContent();
    }

    //列表
	public function index(){
        $keys = input('get.');
        $map = [];
        if (!empty($keys['keyword'])) {
            $map['title|description'] = ['like','%'.$keys['keyword'].'%'];
        }
        if (isset($keys['status']) && $keys['status'] >= 0){
            $map['status'] = $keys['status'];
        } else {
            $keys['status'] = -1;
        }
        $list = $this->works->get_paginate($map);
        $this->assign("list",$list);
        $this->assign('keys',$keys);
	    return $this->fetch();
    }

    //添加
    public function add(){
        if (request()->isPost()){
            $info = input('post.');
            //处理data
            $info['createtime'] = time();
            if (!$info['view']) $info['view'] = rand(1,499);
            $aid = $this->works->save_type($info,false);
            if ($aid){
                if ($info['content']){
                    $this->set_content($aid,$info['content'],true);
                }
                $this->set_url($info['catid'],$aid);
                $this->success('添加成功');
            } else {
                $this->error('添加失败');
            }
        }
        //栏目
        $categorys = $this->category ? $this->category : [];
        if ($categorys){
            foreach($categorys as $vo) {
                if ($vo['status'] && intval($vo['module']) === $this->module){
                    $array[] = $vo;
                }
                continue;
            }
            $str  = "<option value='\$id' \$selected>\$spacer \$catname</option>";
            $tree = new Tree($array);
            $select_categorys = $tree->get_tree(0, $str,0);
            $this->assign('select_categorys', $select_categorys);
        }
        return $this->fetch();
    }

    //修改
    public function edit(){
        if (request()->isPost()){
            $info = input('post.');
            //处理data
            $info['createtime'] = strtotime($info['createtime']);
            if (empty($info['thumb'])){
                unset($info['thumb'],$info['oldthumb']);
            } else {
                del_oldthumb($info['oldthumb']);
            }
            $result = $this->works->save_type($info);
            //content
            if ($info['content']){
                $this->set_content($info['id'],$info['content'],false);
            }
            //url
            $this->set_url($info['catid'],$info['id']);
            $this->success('修改成功');
        }
        $id = input("get.id");
        if (!$id) $this->error("缺少必要参数");
        $list = $this->works->get_find($id);
        //content
        $list['content'] = $this->works_content->get_find($id);
        //category
        foreach($this->category as $vo) {
            if ($vo['status'] && intval($vo['module']) === $this->module){
                $vo['selected'] = $vo['id'] == $list['catid'] ? 'selected' : '';
                $array[] = $vo;
            }
            continue;
        }
        $str  = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree = new Tree($array);
        $select_categorys = $tree->get_tree(0,$str,$list['catid']);
        $this->assign('select_categorys', $select_categorys);
        $this->assign("list",$list);
        return $this->fetch();
    }

    //url
    protected function set_url($catid,$aid){
        $parturl = $this->category[$catid]['url'];
        $data = [
            'id' => $aid,
            'url' => '/'.$parturl.'/show/'.$aid.'.html'
        ];
        $this->works->set_field($data);
    }

    //内容
    protected function set_content($aid,$content,$isup){
        return $isup ? $this->works_content->save_add(['wid'=>$aid,'content'=>$content]) : $this->works_content->save_up($aid,$content);
    }

    //排序
    public function listorder(){
        $listorders = input('post.listorders/a');
        if (empty($listorders)) $this->error("缺少必要参数");
        //遍历更新
        $data = [];
        foreach ($listorders as $k => $v) {
            $data[$k] = [
                'id' => $k,
                'listorder' => $v
            ];
        }
        $this->works->save_all($data);
        $this->success("更新排序成功(ˇˍˇ)");
    }

    //删除
    public function del(){
        $id = input('get.id');
        if (!$id) $this->error(lang('do_empty'));
        $thumb = $this->works->get_value(['id'=>$id],'thumb');
        $del = $this->works->del($id);
        if ($del) {
            if ($thumb) del_oldthumb($thumb);
            $this->works_content->del($id);
            $this->success(lang('delete_ok'));
        } else {
            $this->error(lang('delete_error'));
        }
    }





}
