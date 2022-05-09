<?php
namespace app\common\model;

use think\Model;

class ActivityJoin extends Model
{
    protected $autoWriteTimestamp = true;

    public function order()
    {
        return $this->hasOne('ActivityOrder');
    }

    public function activity()
    {
        return $this->belongsTo('activity');
    }
}
