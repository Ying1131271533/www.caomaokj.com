<?php
declare (strict_types = 1);
namespace app\index\controller;

use think\Request;

class ServiceApi extends BaseApi
{
    /**
     * 服务商入驻
     *
     * @param Request $request
     * @return \think\Response
     */
    public function index(Request $request)
    {
        // 接收参数
        $param  = $request->param('request');
        halt($param);

        return $this->create(200, '获取成功');
    }
}
