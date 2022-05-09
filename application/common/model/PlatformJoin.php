<?php
namespace app\common\model;

use think\Model;

class PlatformJoin extends Model
{
    protected $autoWriteTimestamp = true;

    public function platform()
    {
        return $this->belongsTo('platform');
    }

}
