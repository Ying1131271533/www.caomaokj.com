<?php

namespace app\index\middleware;

use app\common\model\Member as U;
use think\Controller;

class Login extends Controller
{
    public function handle($request, \Closure $next)
    {
        // $request->hello = 'ThinkPHP';
        $this->autologin();

        return $next($request);
    }

    /**
     * 自动登录
     *
     * @param
     * @return redirect 重定向
     */
    public function autologin()
    {
        if (session('?user')) {
            return $this->redirect(lastUrl());
        } else if (cookie('?user')) {
            $cookie = cookie('user');
            $user = U::where([
                'username' => encrypt_akali($cookie['username'], 'D', config('app.encrypt_key')),
                'password' => encrypt_akali($cookie['password'], 'D', config('app.encrypt_key')),
                // 'username' => encrypt_akali(cookie('user')['username'], 'D', config('app.encrypt_key')),
                // 'password' => encrypt_akali(cookie('user')['password'], 'D', config('app.encrypt_key')),
            ])->find();

            if (empty($user)) {
                cookie('user', null);
            } else {
                // 重新登录状态
                session('user', ['id' => $user['id'], 'username' => $user['username']]);

                // 成功跳转地址
                return $this->redirect(lastUrl());
            }

        }
    }
}
