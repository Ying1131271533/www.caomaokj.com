<?php

namespace app\usezan\model;

use think\Model;

class LogisticsServiceType extends Model
{
    public function logistics()
    {
        return $this->belongsToMany('Logistics', 'LogisticsService');
    }
}
