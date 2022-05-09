<?php
namespace app\common\model;

use think\Model;

class ActivityOrder extends Model
{
    protected $autoWriteTimestamp = true;
    /*protected $type               = [
    'create_time' => 'timestamp',
    ];*/

    public function join()
    {
        return $this->belongsTo('ActivityJoin');
    }

    public function invoice()
    {
        return $this->hasOne('ActivityInvoice');
    }

    // 购买价
    public function setShopPrcieAttr($value)
    {
        return sprintf("%.2f", $value);
    }
}
