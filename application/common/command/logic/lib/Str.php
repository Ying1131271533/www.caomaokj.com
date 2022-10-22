<?php

namespace app\common\logic\lib;

class Str
{
    // 生产token
    public function createToken($str)
    {
        $tokenSalt = md5(uniqid(md5(microtime(true)), true));
        return sha1($tokenSalt . $str);
    }

    // 生产盐
    public function salt(int $bit)
    {
        // 盐字符集
        $chars = 'abcdefqhijklmnoparstuvwxvzABCDEFGHIJKLMNOPORSTUVWXYZ0123456789';
        $str   = '';
        for ($i = 0; $i < $bit; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
