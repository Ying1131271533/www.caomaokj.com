<?php
/**
 * 后台行为
 */
namespace app\usezan\behavior;
use libs\Auth;
class Check{
    use \traits\controller\Jump;
    public function run($params){
        $this->Check_Login();
        $this->Check_Auth();
    }

    //检查是否登录
    protected function Check_Login(){
        if (!session('?usezan_admin') && strtolower(request()->controller()) != 'login') {
            $this->redirect("login/index");
        }
    }

    //检查权限
    protected function Check_Auth(){
        if(!in_array(request()->controller(),config("auth_controller"))){
            //检查用户权限状态
            if (session("?usezan_admin") && !session("usezan_admin.adminis")) {
                //当前节点
                $controller = strtolower(request()->controller()); //控制器
                $actionname = strtolower(request()->action()); //方法
                $curent_url = array($controller.'/'.$actionname);
                $uid = session("usezan_admin.id"); //用户ID
                //实例化Auth
                $auth = new Auth();
                if (!$auth->check($curent_url,$uid)) {
                    $this->error('没有操作权限,请联系管理员╮(╯_╰)╭');
                }
            }
        }
    }


}