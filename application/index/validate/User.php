<?php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{

    //正则
    protected $regex = [
        'pwds'  => '/^[a-zA-Z0-9]{8,20}$/',
        'enNum' => '/^[a-zA-Z][a-zA-Z0-9]{5,19}$/',
    ];

    protected $rule = [
        'phone'    => 'require|unique:member|mobile|number|length:11',
        'code'     => 'require|length:6',
        'username' => 'require|chsAlphaNum|unique:member|regex:enNum',
        'password' => 'require|regex:pwds',
    ];

    protected $message = [

        // 手机
        'phone.require'    => '请输手机号码',
        'phone.number'     => '手机号格式必须为数字',
        'phone.length'     => '手机号格长度为11位',
        'phone.mobile'     => '手机号格式错误',
        'phone.unique'     => '手机号码已被注册',

        // 验证码
        'code.require'     => '请输入验证码',
        'code.length'      => '请输入6位验证码',

        // 用户名
        'username.length'  => '用户名不能少于6个字符',
        'username.regex'   => '用户名必须是英文开头的英文和数字的组合',
        'username.unique'  => '用户名已存在',

        // 密码
        'password.require' => '请输密码',
        'password.regex'   => '密码不符合规范，长度必须为8-20',
    ];

}
