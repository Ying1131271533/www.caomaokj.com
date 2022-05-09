<?php
namespace library;

use Firebase\JWT;

class Jwttoken
{
    /**
     * 用户登录
     *
     * @param int $id 用户id
     * @param string $username 用户名
     * @param int $expire_time 过期时间 默认4小时
     * @return obj $jwt
     */
    public function createJwt($id, $username, $expire_time = 14400)
    {
        $key    = config('app.token_key'); //jwt的签发密钥，验证token的时候需要用到
        $time   = time();
        $expire = $time + $expire_time; //过期时间
        $token  = array(
            "id"       => $id,
            "username" => $username,
            "iss"      => "樱", //签发组织
            "aud"      => "阿卡丽", //签发作者
            "iat"      => $time, //签发时间
            "nbf"      => $time, //生效时间
            "exp"      => $expire,
        );

        $jwt = JWT::encode($token, $key);
        return $jwt;
    }

    //校验jwt权限API
    public function verifyJwt($jwt)
    {
        $key = config('app.token_key');
        try {
            $jwtAuth  = json_encode(JWT::decode($jwt, $key, array('HS256')));
            $authInfo = json_decode($jwtAuth, true);
            $msg      = [];
            if (!empty($authInfo['username'])) {
                $msg = [
                    'code'     => 0,
                    'msg'      => 'Token验证通过',
                    'id'       => $authInfo['id'],
                    'username' => $authInfo['username'],
                ];
            } else {
                //Token验证不通过,用户不存在
                $msg = [
                    'code' => 10001,
                    'msg'  => '当前用户不存在',
                ];
            }
            return $msg;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return [
                'code' => 10002,
                'msg'  => 'Token无效',
            ];
            exit;
        } catch (\Firebase\JWT\ExpiredException $e) {
            //Token过期
            return [
                'code' => 10003,
                'msg'  => '登录信息已超时，请重新登录',
            ];
            exit;
        } catch (Exception $e) {
            return [
                'code' => 10004,
                'msg'  => '未知错误',
            ];
            exit;
        }
    }
}
