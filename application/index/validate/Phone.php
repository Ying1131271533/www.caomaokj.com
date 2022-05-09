<?php
namespace app\index\validate;

use think\Validate;

class Phone extends Validate
{

    protected $rule = [
        'phone' => 'require|number|length:11|mobile',
        'type'  => 'require|number|in:1,2,3,4,5,6',
    ];

    protected $message = [
        // 手机号码
        'phone.require' => '请输手机号码',
        'phone.length'  => '手机号只能为11位',
        'phone.number'  => '手机号只能为数字',
        'phone.mobile'  => '手机号格式错误',

        // 短信类型
        'type.require'  => '短信类型不能为空',
        'type.number'   => '短信类型只能为数字',
        'type.in'       => '短信类型参数有误',
    ];

}
