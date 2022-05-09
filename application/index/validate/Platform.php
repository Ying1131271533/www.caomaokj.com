<?php
namespace app\index\validate;

use think\Validate;

class Platform extends Validate
{
    protected $rule = [
        'name'    => 'require|chsAlpha|length:2,20',
        'phone'   => 'require|mobile|unique:platform_join,phone^platform_id',
        'code'    => 'require|length:6|number',
        'company' => 'require|chs|length:2,30',
        'email'   => 'require|email',
    ];

    protected $message = [
        // 姓名
        'name.require'    => '请输入姓名',
        'name.chsAlpha'   => '姓名只能是汉字和英文',
        'name.length'     => '姓名长度为2-20个字符',

        // 手机号码
        'phone.require'   => '请输手机号码',
        'phone.mobile'    => '手机号格式错误',
        'phone.unique'    => '该平台您已申请',

        // 手机验证码
        'code.require'    => '请输入验证码',
        'code.length'     => '请输入6位验证码',
        'code.number'     => '验证码只能为数字',

        // 公司名称
        'company.require' => '请输入公司名称',
        'company.length'  => '公司名称长度为2-30个字符',
        'company.chs'     => '公司名称只能为汉字',

        // 密码
        'email.require'   => '请输入邮箱',
        'email.email'     => '邮箱格式错误',

        // 手机验证码类型
        'type.require'    => '提交类型不能为空',
        'type.number'     => '提交类型必须为数字',
    ];

}
