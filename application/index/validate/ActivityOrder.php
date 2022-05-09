<?php

namespace app\index\validate;

use app\common\model\ActivityOrder as AO;
use think\Validate;

class ActivityOrder extends Validate
{
    protected $rule = [
        'order_id' => 'require|number|checkOrder',
    ];

    // 验证消息
    protected $message = [

        // 订单id
        'order_id.require' => '订单id不能为空',
        'order_id.number'  => '订单id只能为数字',

    ];

    // 订单是否存在，和支付订单状态，活动是否已结束
    protected function checkOrder($value, $rule, $data)
    {
        $order = AO::with('join.activity')->get($value);

        if ($order['join']['activity']['endtime'] < time() || $order['join']['activity']['status'] == 0) {
            return '活动已结束';
        }

        if (empty($order)) {return '订单不存在';}
        if ($order['order_status'] == 1 || $order['pay_status'] == 1) {return '订单已支付，请查看我的报名';}
        if ($order['order_status'] == 2) {return '订单已取消';}
        return true;
    }
}
