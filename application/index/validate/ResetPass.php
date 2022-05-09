<?php
namespace app\index\validate;

use think\Validate;

class ResetPass extends Validate
{
    // 正则
    protected $regex = [
        'passReg' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d$@!%*?&]{8,20}/',
    ];

    protected $rule = [
        'phone'    => 'require|mobile|number|length:11',
        'code'     => 'require|length:6|number',
        'password' => 'require|length:8,20|confirm:rePassword',
    ];

    protected $message = [

        // 手机
        'phone.require'    => '请输手机号码',
        'phone.mobile'     => '手机号格式错误',
        'phone.number'     => '手机号格式错误',
        'phone.length'     => '手机号格式错误',

        // 验证码
        'code.require'     => '请输入验证码',
        'code.length'      => '请输入6位验证码',
        'code.number'      => '验证码必须为数字',

        // 密码
        'password.require' => '请输密码',
        'password.length'  => '密码长度必须为8-20位',
        // 'password.regex'   => '密码格式必须包含大小写字母、数字8-20位',
        'password.confirm' => '两次密码不一致',
    ];

    protected $scene = [
        'changePwd'   => ['password'],
        'changePhone' => ['phone, code'],
    ];
}
