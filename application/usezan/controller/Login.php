<?php
namespace app\usezan\controller;

use app\usezan\model\AuthGroup;
use app\usezan\model\User;
use libs\Geetest;
use think\captcha\Captcha;
use think\Controller;

class Login extends Controller
{
    protected $user;
    public function initialize()
    {
        $this->user = new User();
    }

    public function index()
    {
        return $this->fetch();
    }

    //登录
    public function getlogin()
    {
        if (request()->isAjax()) {
            $info = input("param.");
            //账号密码为空操作
            if (empty($info['username']) || empty($info['password'])) {
                return ['info' => '用户或者密码为空╮(╯_╰)╭', 'status' => 0];
            }
            //获取用户信息
            $user = User::get_where_find(['username' => $info['username']]);
            //密码匹配
            if (!$user || $user['password'] != sysmd5($info['password'])) {
                return ['info' => '用户不存在或者密码错误╮(╯_╰)╭', 'status' => 0];
            }
            //管理员状态
            if (!$user['status']) {
                return ['info' => $user['user'] . '管理员已被禁止╮(╯_╰)╭', 'status' => 0];
            }
            if (config('usezan_verif')) {
                //极验
                $GtSdk   = new Geetest(config('captcha_id'), config('private_key'));
                $user_id = session("user_id");
                if (session("gtserver") == 1) {
                    $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $user_id);
                    if (!$result) {
                        return ['info' => '验证码错误╮(╯_╰)╭', 'status' => 0];
                    }
                } else {
                    if (!$GtSdk->fail_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'])) {
                        return ['info' => '验证码错误╮(╯_╰)╭', 'status' => 0];
                    }
                }
            } else {
                //普通模式
                if (!captcha_check($info['verfiy'])) {
                    return ['info' => '验证码错误╮(╯_╰)╭', 'status' => 0];
                }
            }

            //更新最后登录状态
            $data = [
                'id'             => $user['id'],
                'last_ip'        => request()->ip(),
                'last_logintime' => time(),
            ];
            $this->user->save_type($data);
            //记录session
            session("usezan_admin", $user);
            //缓存当前权限
            if (!$user['adminis'] && $user['group_id'] > 1) {
                $group = (new AuthGroup())->get_value(['id' => $user['group_id']], 'rules');
                cache('auth_group_rules_' . $user['id'], $group);
            }
            return ['info' => '登录成功，正在跳转中...', 'url' => url('/usezan/index'), 'status' => 1];
        } else {
            $this->error("非法操作");
        }
    }

    //退出登录
    public function logout()
    {
        //卸载session
        session("usezan_admin", null);
        session("usezan_template", null);
        $this->redirect("login/index");
    }

    // 极验一次验证
    public function Captcha()
    {
        $GtSdk   = new Geetest(config('captcha_id'), config('private_key'));
        $user_id = md5(md5("usezancms"));
        $status  = $GtSdk->pre_process($user_id);
        session("gtserver", $status);
        session("user_id", $user_id);
        echo $GtSdk->get_response_str();
    }

    /**
     * 验证码
     *
     * @return $captcha->entry()
     */
    public function verify()
    {
        $captcha = new Captcha(config('captcha.'));
        return $captcha->entry();
    }

}
