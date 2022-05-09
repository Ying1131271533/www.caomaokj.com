<?php

namespace app\index\middleware;

use app\index\controller\BaseApi;

class IsLogin extends BaseApi
{
    public function handle($request, \Closure $next)
    {
        // 是否登录
        if (!$this->index()) {
            return $this->create(400, '请重新登录');
        }
        return $next($request);
    }

    // 是否登录
    public function index()
    {
        // 获取用户
        if (!session('?user')) {
            return false;
        }
        return true;
    }
}
