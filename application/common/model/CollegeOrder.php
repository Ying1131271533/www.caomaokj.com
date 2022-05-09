<?php
namespace app\common\model;

use think\Model;

class CollegeOrder extends Model
{
    protected $autoWriteTimestamp = true;
    /*protected $type               = [
    'create_time' => 'timestamp',
    ];*/

    public function join()
    {
        return $this->belongsTo('CollegeJoin');
    }

    public function invoice()
    {
        return $this->hasOne('CollegeInvoice');
    }

    // 购买价
    public function setShopPrcieAttr($value)
    {
        return sprintf("%.2f", $value);
    }
}
