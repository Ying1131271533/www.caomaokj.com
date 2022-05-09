<?php
namespace app\common\model;

use think\Model;

class Service extends Model
{
    protected $autoWriteTimestamp = true;

    public function detail()
    {
        return $this->hasOne('ServiceDetail');
    }

    public function categorys()
    {
        return $this->belongsTo('category', 'category_id');
    }

}
