<?php
namespace app\usezan\validate;

use think\Validate;

class LogisticsService extends Validate
{
    protected $rule = [
        'name' => 'require|unique:logistics_service_type|max:20',
    ];

    protected $message = [
        'name.require' => '名称不能为空',
        'name.unique'  => '名称已存在',
        'name.max'     => '名称不能超过20个字符',

    ];

}
