<?php
/**
 * 网站配置控制器
 * @author      [我就叫小柯] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2017 [环企优站科技]  (http://www.usezan.com)
 * @version     Usezan企业网站管理系统 v1.0
 */
namespace app\usezan\controller;
use app\usezan\model\Extend;
use app\usezan\model\System;
use think\facade\Env;

class System extends Base {
	//自动加载
    protected $system,$lang;
	public function _uzauto() {
		$this->system = new System();
	}

    //配置列表
	public function index() {
		$groupid = input("get.groupid",1); //分组
		$template = input("get.tem",'index'); //模板
		$system = $this->get_sys_data($groupid);
		$this->assign("system",$system);
		return $this->fetch($template);
	}

    //附件配置
    public function attach(){
        $system = $this->get_sys_data(4);
        $this->assign("system",$system);
        return $this->fetch();
    }

    //高级设置
    public function extended () {
        if (request()->isPost()) {
            $data = input('post.');
            foreach ($data as $key=>$vo){
                (new Extend())->get_setfield($key,$vo);
            }
            (new Extend())->get_select();
            $this->success('更新设置成功');
        }
        return $this->fetch();
    }


    //配置数据
    protected function get_sys_data($groupid){
        $where = $groupid == 1 ? ['groupid'=>$groupid,'lang'=>$this->lang] : ['groupid'=>$groupid];
        return $this->system->get_select($where);
    }


	//配置更新
	public function update () {
		if (request()->isPost()) {
			$groupid = input("post.groupid");
            $info = input('post.');
			//更新数据
            foreach ($info as $key => $v) {
			    $where = $groupid == 1 ? ['varname'=>$key] : ['varname'=>$key];
                $this->system->set_field($where,['value'=>$v]);
            }
            $this->cachecon();
            $this->success("更新网站配置成功");
		}
	}

	//配置添加
	public function add () {
		$info = input("post.");
		if (request()->isPost()) {
			$info = input("post.");
			$sys = $this->system->save_type($info,false);
			if ($sys) {
				$this->success("添加配置成功");
			} else {
				$this->error("添加配置失败");
			}
		}
		return $this->fetch();
	}

	//生成配置文件
	public function cachecon(){
        $data = $this->system->get_cache_select(['lang'=>$this->lang]);
        $ndata = [];
        foreach($data as $key=>$v){
            $ndata[$v['varname']] = $v['value'];
        }
        $ndata = "<?php\r\nreturn " . var_export($ndata, true) . ";\r\n";
        $path = Env::get('config_path')."system_".$this->lang.".php";
        if (!file_put_contents($path,$ndata)) {
            return false;
        }
        unset($data);
        return true;
    }









}
