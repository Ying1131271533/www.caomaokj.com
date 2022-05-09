<?php
declare (strict_types = 1);
namespace app\index\controller;

use library\Crypt as C;

class CryptApi extends BaseApi
{
    /**
     * 获取密钥和签名
     *
     * @return Response    api返回的json数据
     */
    public function get_key()
    {
        // 获取密钥
        $key = C::web_key();
        if (empty($key)) {
            $this->create(400, '密钥获取失败');
        }

        // 获取签名
        $sign = C::sign(config('app.sign'), $key['publickey']);
        if (empty($sign)) {
            $this->create(400, '签名获取失败');
        }
        $key['sign'] = $sign;
        $this->create(200, '密钥获取成功', $key);
    }
}
