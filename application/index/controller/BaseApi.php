<?php
declare (strict_types = 1);
namespace app\index\controller;

// use think\Controller;
use think\exception\HttpResponseException;
use think\Response;

abstract class BaseApi// extends Controller

{
    protected $page;
    protected $pageSize;
    protected $userid;
    protected $username;

    public function __construct()
    {
        // 获取页数
        $this->page = input('page/d', config('app.page'));

        // 获取条数
        $this->pageSize = input('page_size/d', config('app.page_size'));

        // 获取用户
        if (session('?user')) {
            $this->userid   = session('user')['id'];
            $this->username = session('user')['username'];
        } else {
            $this->userid   = '';
            $this->username = '';
        }
    }

    /**
     * 返回数据
     *
     * @param $data
     * @param int $code
     * @param string $msg
     * @param string $json
     * @return \think\Response
     */
    protected function create(int $code = 200, string $msg = '', $data = [], string $type = 'json'): Response
    {
        $result = [
            // 状态码
            'code' => $code,
            // 消息
            'msg'  => $msg,
            // 返回数据
            'data' => $data,
        ];
        // 返回api接口
        $response = Response::create($result, $type);
        throw new HttpResponseException($response);
    }
/* 做完后再打开 还有在 api/config/app.php 配置里面也可以设置错误页面样式
public function __call($name, $arguments)
{
// 404, 方法不存在的错误
return $this->create(404, '资源不存在~~');
}
 */
}
