<?php
namespace app\common\model;

use think\Model;

class ServiceEnter extends Model
{
    protected $autoWriteTimestamp = true;

    public function introduce()
    {
        return $this->hasOne('ServiceEnterIntroduce');
    }

    public function featureds()
    {
        return $this->hasMany('ServiceEnterIntroduce');
    }
}
