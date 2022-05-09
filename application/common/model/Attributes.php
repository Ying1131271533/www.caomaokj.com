<?php

namespace app\common\model;

use think\Model;

class Attributes extends Model
{
    public function logistics()
    {
        return $this->belongsToMany('Logistics', 'LogisticsAttributes');
    }
}
