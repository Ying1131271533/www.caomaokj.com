<?php
declare (strict_types = 1);
namespace app\index\controller;

use app\common\model\Member as Mb;
use app\index\controller\CodeApi;
use library\Crypt;
use library\Jwttoken;
use think\captcha\Captcha;
use think\facade\Cache;
use think\Request;

class LoginApi extends BaseApi
{
    /**
     * 用户登录
     *
     * @param Request $request
     * @return \think\Response
     */
    public function login(Request $request)
    {
        /**********************   接收参数   **********************/
        $data = $request->param();
        $data['phone'] = $data['usercode'];

        /**********************   登录类型   **********************/
        if (empty($data['type'])) {
            return $this->create(400, '登录类型不能为空');
        }

        /**********************   检查图片验证码   **********************/
        $captcha     = new Captcha();
        $valiCaptcha = $captcha->check($data['verifyCode'], (int) $data['type']);
        if (!$valiCaptcha) {
            return $this->create(400, '图片验证码错误');
        }
        
        /**********************   1 手机验证码登录 2 帐号、密码登录   **********************/
        switch ($data['type']) {
            case 1:

                /**********************   验证数据   **********************/
                $validate = validate('Login');
                if (!$validate->scene('phone')->check($data)) {
                    return $this->create(400, $validate->getError());
                }

                /**********************   检查登录短信验证码   **********************/
                $smsResult = (new CodeApi)->checkCode($data['phone'], $data['code'], 2);
                if ($smsResult['code'] !== 0) {
                    return $this->create(400, $smsResult['msg']);
                }

                /**********************   查找用户   **********************/
                $user = Mb::where('phone', $data['phone'])->find();
                break;

            case 2:

                /**********************   验证数据   **********************/
                $validate = validate('Login');
                if (!$validate->scene('user')->check($data)) {
                    return $this->create(400, $validate->getError());
                }

                /**********************   查找用户   **********************/
                $user = Mb::whereOr('phone', $data['usercode'])
                    ->whereOr('username', $data['usercode'])
                    ->where('password', sysmd5($data['password']))
                    ->find();
                break;

            default:
                return $this->create(400, '登录类型参数有误');
                break;
        }

        /**********************   用户是否存在 类型判断   **********************/
        if (empty($user) && $data['type'] == 1 || empty($user) && $data['type'] == 3) {
            // 手机登录
            return $this->create(400, '手机号码有误');

        } else if (empty($user) && $data['type'] == 2) {
            // 帐号登录
            return $this->create(400, '帐号或密码有误');

        }

        /**********************   是否被禁止登录   **********************/
        if ($user['status'] == 0) {
            session('user', null);
            cookie('user', null);
            return $this->create(400, '用户已被禁止登录');
        }

        /**********************    保存登录状态  **********************/
        session('user', [
            'id'       => $user['id'],
            'username' => $user['username'],
        ]);

        /**********************    是否自动登录  **********************/
        if ($data['rember'] == 'true') {
            cookie('user', [
                'username' => encrypt_akali($user['username'], 'E', config('app.encrypt_key')),
                'password' => encrypt_akali($user['password'], 'E', config('app.encrypt_key')),
            ], 3600 * 24 * 7);
        } else {
            cookie('user', null);
        }

        /**********************   保存登录信息   **********************/
        $user->ip        = getIp();
        $user->last_time = time();
        $user->save();

        /**********************   返回数据   **********************/
        $resultData = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'url'      => $data['lasturl'],
        ];

        // 获取加密数据返回用户端
        // $resultData = Crypt::encrypt($resultData, $data['web_privatekey']);
        // empty($resultData) and $this->create(400, '数据加密失败！');

        /**********************   生成登录信息token   **********************/
        $token               = (new Jwttoken)->createJwt($user['id'], $user['username'], 14400);
        $resultData['token'] = $token;

        return $this->create(200, '登录成功', $resultData);
    }

    /**
     * 用户注册
     *
     * @param Request $request
     * @return \think\Response
     */
    public function register(Request $request)
    {
        /**********************   用户数据组装   **********************/
        $data = [
            'phone'     => $request->param('phone/d'),
            'code'      => $request->param('code/s'),
            'username'  => $request->param('username/s'),
            'password'  => $request->param('password/s'),
            'user_type' => $request->param('user_type/d', 1),
            'nickname'  => 'cmkj_' . user_number(),
        ];

        /**********************   验证用户和密码   **********************/
        $validate = validate('Register');
        if (!$validate->check($data)) {
            return $this->create(400, $validate->getError());
        }

        /**********************   检查注册短信验证码   **********************/
        // $result = (new CodeApi)->checkCode($data['phone'], $data['code'], 1);
        // if ($result['code'] !== 0) {
        //     return $this->create(400, $result['msg']);
        // }

        /**********************   用户数据完善   **********************/
        $data['ip']          = getIp();
        $data['last_time']   = time();
        $data['create_time'] = time();

        /**********************   保存用户   **********************/
        $id = Mb::create($data)->getData('id');
        if (empty($id)) {
            return $this->create(400, '注册失败');
        }

        /**********************   保存登录状态   **********************/
        session('user', [
            'id'       => $id,
            'username' => $data['username'],
        ]);

        /**********************   生成jwtToken   **********************/
        $token = (new Jwttoken)->joinCreateUser($id, $data['username'], 14400);

        /**********************   返回api接口   **********************/
        $resultData = [
            'id'       => $id,
            'username' => $data['username'],
            'token'    => $token,
        ];

        return $this->create(200, '注册成功', $resultData);
    }

    /**
     * 活动和课程报名时创建用户
     *
     * @param $phone char
     * @return array
     */
    public function joinCreateUser($phone)
    {
        /**********************   找出用户   **********************/
        $user = Mb::where('phone', $phone)->find();

        /**********************   是否已注册   **********************/
        if (empty($user)) {

            /**********************   用户数据组装   **********************/
            $data = [
                'phone'       => $phone,
                'username'    => 'cmkj_' . user_number(),
                'password'    => encrypt_akali('jinx', 'D', 'akali'),
                'user_type'   => 1,
                'nickname'    => 'cmkj_' . user_number(),
                'ip'          => getIp(),
                'last_time'   => time(),
                'create_time' => time(),
            ];

            /**********************   保存用户   **********************/
            $user = Mb::create($data);
            if (empty($user)) {
                return ['code' => 400, 'msg' => '创建失败'];
            }
        }

        /**********************   保存登录状态   **********************/
        session('user', [
            'id'       => $user->id,
            'username' => $user->username,
        ]);

        /**********************   返回数据   **********************/
        return [
            'code'     => 200,
            'msg'      => '创建成功',
            'id'       => $user->id,
            'username' => $user->username,
            'token'    => (new Jwttoken)->createJwt($user->id, $user->username, 14400),
        ];
    }

    /**
     * 重置密码
     *
     * @param  Request $request
     * @return \think\Response
     */
    public function resetPass(Request $request)
    {
        /**********************   接收数据   **********************/
        $data = $request->param();

        /**********************   验证手机和密码   **********************/
        $validate = validate('ResetPass');
        if (!$validate->check($data)) {
            return $this->create(400, $validate->getError());
        }

        /**********************   检查找回密码 短信验证码   **********************/
        $smsResult = (new CodeApi)->checkCode($data['phone'], $data['code'], 3);
        if ($smsResult['code'] !== 0) {
            return $this->create(400, $smsResult['msg']);
        }

        /**********************   找到用户信息   **********************/
        $user = Mb::where('phone', $data['phone'])->find();

        /**********************   用户数据更新   **********************/
        $user->password  = $data['password'];
        $user->ip        = getIp();
        $user->last_time = time();
        $result          = $user->save();
        if (empty($result)) {
            return $this->create(400, '更改失败，请刷新页面');
        }

        /**********************   保存登录状态   **********************/
        session('user', [
            'id'       => $user['id'],
            'username' => $user['username'],
        ]);

        /**********************   生成jwtToken   **********************/
        $token = (new Jwttoken)->createJwt($user['id'], $user['username'], 14400);

        /**********************   返回api接口   **********************/
        $resultData = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'token'    => $token,
        ];

        return $this->create(200, '修改成功', $resultData);
    }

    /**
     * 是否已登录
     *
     * @param
     * @return \think\Response
     */
    public function isLogin()
    {
        if (session('?user')) {
            return $this->create(200, '已登录', 0);
        }
        return $this->create(200, '未登录', 1);
    }

    /**
     * token登录状态
     *
     * @param
     * @return \think\Response
     */
    public function isToken()
    {
        /**********************   获取头部信息 - token   **********************/
        $token = request()->header('Authorization');
        if (empty($token)) {
            return $this->create(400, 'token为空');
        }

        /**********************   验证token   **********************/
        $jwt    = new Jwttoken;
        $result = $jwt->verifyJwt($token);
        return $this->create(200, '获取成功', $result);
    }

    /**
     * 手机登录图片验证码
     *
     * @return $captcha->entry()
     */
    public function verifyPhone()
    {
        $captcha = new Captcha(config('captcha.'));
        return $captcha->entry(1);
    }

    /**
     * 帐号登录图片验证码
     * @param
     * @return $captcha->entry()
     */
    public function verifyUser()
    {
        $captcha = new Captcha(config('captcha.'));
        return $captcha->entry(2);
    }
}
