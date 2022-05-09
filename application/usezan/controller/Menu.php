<?php
namespace app\usezan\controller;
use app\usezan\model\Menu;
use think\facade\Cache;

class Menu extends Base {
    protected $menu;
    public function _uzauto () {
        $this->menu = new Menu();
    }

    //菜单列表
	public function index(){
        $menu = $this->menu->get_select(['type'=>1]);
        $this->assign("menu",$menu);
	    return $this->fetch();
    }

    //热门菜单
    public function hotindex(){
        $menu = $this->menu->get_select(['type'=>2]);
        $this->assign("menu",$menu);
        return $this->fetch();
    }

    //菜单add
    public function add(){
        if (request()->isPost()){
            $info = input("post.");
            //检查导航是否存在
            $ismenu = $this->menu->is_count(['url'=>$info['url']]);
            if ($ismenu) $this->error("导航已经存在");
            $result = $this->menu->save_type($info,false);
            if ($result) {
                $this->set_cache(1);
                $this->success("添加导航成功");
            } else {
                $this->error("添加导航失败");
            }
        }
        return $this->fetch();
    }

    //热门add
    public function hotadd(){
        if (request()->isPost()){
            $info = input("post.");
            $result = $this->menu->save_type($info,false);
            if ($result) {
                $this->set_cache(2);
                $this->success("添加导航成功");
            } else {
                $this->error("添加导航失败");
            }
        }
        return $this->fetch();
    }

    //修改
    public function edit(){
        if (request()->isPost()) {
            $info = input("post.");
            //检查导航是否存在
            $ismenu = $this->menu->is_count([['url','=',$info['url']],['id','<>',$info['id']]]);
            if ($ismenu) $this->error("导航已经存在");
            if ($this->menu->save_type($info)) {
                $this->set_cache(1);
                $this->success("修改导航成功");
            } else {
                $this->error("修改导航失败");
            }
        }
        $id = input("get.id");
        if (!$id) $this->error("缺少必要参数");
        $menu = $this->menu->get_find($id);
        $this->assign("menu",$menu);
        return $this->fetch();
    }

    //修改
    public function hotedit(){
        if (request()->isPost()) {
            $info = input("post.");
            if ($this->menu->save_type($info)) {
                $this->set_cache(2);
                $this->success("修改导航成功");
            } else {
                $this->error("修改导航失败");
            }
        }
        $id = input("get.id");
        if (!$id) $this->error("缺少必要参数");
        $menu = $this->menu->get_find($id);
        $this->assign("menu",$menu);
        return $this->fetch();
    }

    //设置缓存
    public function set_cache($type){
        $field = $type == 1 ? 'id,title,url,target,catid' : 'id,title,url,target,catid,seo_title,seo_keywords,seo_description';
        $data = $this->menu->get_menucache(['type'=>$type,'status'=>1],$field);
        Cache::store('menu')->set('menu_'.$type,$data);
    }

    //排序操作
    public function listorder(){
        $listorders = input('post.listorders/a');
        $type = input('param.type',1);
        //判断
        if (empty($listorders) && !is_array($listorders)) $this->error(lang('do_empty'));
        $data = [];
        foreach($listorders as $key=>$v){
            $data[$key] = [
                'id' => $key,
                'listorder' => $v
            ];
        }
        $result = $this->menu->save_all($data);
        if ($result){
            $this->set_cache($type);
            $this->success("排序成功");
        } else {
            $this->error('没什么变化');
        }
    }

    //删除
    public function del(){
        $id = input("get.id");
        $type = input("get.type");
        if (!$id) $this->error("缺少必要参数");
        $result = $this->menu->del($id);
        if ($result) {
            $this->set_cache($type);
            $this->success("删除导航成功");
        } else {
            $this->error("删除导航失败");
        }
    }

}
