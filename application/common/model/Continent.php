<?php
namespace app\common\model;

use think\Model;

class Continent extends Model
{
    public function Platforms()
    {
        return $this->belongsToMany('Platform', 'PlatformContinent');
    }
}
