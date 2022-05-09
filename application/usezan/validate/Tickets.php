<?php
namespace app\usezan\validate;

use app\common\validate\BaseValidate;

class Tickets extends BaseValidate
{
    protected $rule = [
        'id|门票id'                 => 'require|number|exist:tickets',
        'name|门票名称'             => 'require|unique:tickets|max:30',
        'people_number|门票人数'    => 'require|number|lt:10000',
        'price|价格'                => 'require|float',
        'discount_price|折扣价格'   => 'require|float',
    ];
    protected $message = [
        'id.exist' => '该门票不存在',
    ];

    // 验证场景
    protected $scene = [
        'add'   => [
            'name',
            'people_number',
            'price',
            'discount_price',
        ],
        'edit'   => [
            'id',
            'name',
            'people_number',
            'price',
            'discount_price',
        ],
        'del' => ['id'],
    ];
}
