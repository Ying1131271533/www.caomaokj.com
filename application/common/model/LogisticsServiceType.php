<?php

namespace app\common\model;

use think\Model;

class LogisticsServiceType extends Model
{
    public function logistics()
    {
        return $this->belongsToMany('Logistics', 'LogisticsService');
    }
}
