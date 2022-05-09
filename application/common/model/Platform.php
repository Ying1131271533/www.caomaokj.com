<?php
namespace app\common\model;

use think\Model;

class Platform extends Model
{

    public function continents()
    {
        return $this->belongsToMany('Continent', 'PlatformContinent');
    }

    public function details()
    {
        return $this->hasMany('PlatformDetail');
    }

    public function joins()
    {
        return $this->hasMany('PlatformJoin');
    }

}
