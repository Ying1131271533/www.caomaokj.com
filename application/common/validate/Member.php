<?php
declare (strict_types = 1);

namespace app\common\validate;

use think\Validate;

class Member extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'name|用户名'    => 'require|chsDash|unique:member',
        'password|密码' => 'require|min:6',
        'email|邮箱'    => 'require|email|unique:member',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];
}
