<?php

namespace app\index\validate;

use app\common\model\CollegeJoin as CollegeJoinModel;
use think\Validate;

class CollegeJoin extends Validate
{
    protected $rule = [
        'college_id|课程' => 'require|number',
        'tickets_id|门票' => 'require|number',
    ];

    // 验证消息
    protected $message = [

        // 订单id
        'college_id.require' => '课程id不能为空',
        'tickets_id.require' => '门票id不能为空',

    ];
}
