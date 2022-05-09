<?php
namespace app\usezan\validate;

use think\Validate;

class Platform extends Validate
{
    protected $rule = [
        'name|电商平台名称'   => 'require|unique:platform',
        'logo|电商平台logo' => 'require',
        'title|标题'      => 'require',
        'descrip|描述'    => 'require|max:255',
        'url|链接'        => 'require',
        '区域|链接'         => 'continent[]',
    ];

    protected $message = [

        // 手机
        'phone.require'     => '请输手机号码',
        'phone.number'      => '手机号格式必须为数字',
        'phone.length'      => '手机号格长度为11位',
        'phone.mobile'      => '手机号格式错误',
        'phone.unique'      => '手机号码已被注册',

        // 手机验证码
        'code.require'      => '请输入验证码',
        'code.length'       => '请输入6位验证码',
        'code.number'       => '验证码只能为数字',

        // 用户名
        'username.length'   => '用户名不能少于6个字符',
        'username.regex'    => '用户名必须是英文开头的英文和数字的组合',
        'username.unique'   => '用户名已存在',

        // 密码
        'password.require'  => '请输密码',
        'password.length'   => '密码长度必须为8-20位',
        // 'password.regex'    => '密码格式必须包含大小写字母、数字8-20位',

        // 用户类型
        'user_type.require' => '用户类型不能为空',

    ];

}
