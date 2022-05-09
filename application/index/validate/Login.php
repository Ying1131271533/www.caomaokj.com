<?php
namespace app\index\validate;

use think\Validate;

class Login extends Validate
{

    // 正则
    protected $regex = [
        // 不限制
        'pwds'  => '/^[a-zA-Z0-9]{8,20}$/',
        // 'pwds'  => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d$@!%*?&]{8,20}/',
        'enNum' => '/^[a-zA-Z][a-zA-Z0-9]{5,19}$/',
    ];

    protected $rule = [
        'phone'    => 'require|mobile|number|length:11',
        'usercode' => 'require|min:4',
        'password' => 'require|length:8,20',
        'code'     => 'require|length:6|number',
        // 有多个验证码时不能使用
        // 'verifyCode' => 'require|captcha',
        'type'     => 'require|number|in:1,2',
    ];

    protected $message = [
        // 手机号码
        'phone.require'      => '请输手机号码',
        'phone.mobile'       => '手机号格式错误',
        'phone.number'       => '手机号格式错误',
        'phone.length'       => '手机号格必须为11位数字',

        // 帐号
        'usercode.require'   => '请输入帐号',
        'usercode.min'       => '账号不能少于4位',

        // 手机验证码
        'code.require'       => '请输入验证码',
        'code.length'        => '请输入6位验证码',
        'code.number'        => '验证码只能为数字',

        // 图片验证码
        'verifyCode.require' => '请输入图片验证码',
        'verifyCode.captcha' => '图片验证码不正确',

        // 密码
        'password.require'   => '请输密码',
        'password.length'    => '密码长度必须为8-20位',
        // 'password.regex'     => '密码格式必须包含大小写字母、数字8-20位',

        // 登录类型
        'type.require'       => '登录类型不能为空',
        'type.number'        => '登录类型必须为数字',
        'type.in'            => '登录类型必须为1和2',
    ];

    protected $scene = [
        'phone' => ['phone', 'code', 'type'],
        'user'  => ['usercode', 'password', 'type'],
    ];
}
