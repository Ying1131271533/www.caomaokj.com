<?php
namespace app\common\model;

use think\Model;

class CollegeJoin extends Model
{
    protected $autoWriteTimestamp = true;

    public function order()
    {
        return $this->hasOne('CollegeOrder');
    }

    public function college()
    {
        return $this->belongsTo('college');
    }

    public function tickets()
    {
        return $this->belongsTo('Tickets');
    }
}
