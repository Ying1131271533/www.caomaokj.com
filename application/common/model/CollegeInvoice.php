<?php
namespace app\common\model;

use think\Model;

class CollegeInvoice extends Model
{
    protected $autoWriteTimestamp = true;
    /*protected $type               = [
    'create_time' => 'timestamp',
    ];*/

    public function order()
    {
        return $this->belongsTo('CollegeOrder');
    }
}
