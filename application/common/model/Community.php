<?php
namespace app\common\model;

class Community extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function detail()
    {
        return $this->hasOne('CommunityDetail');
    }

    public function imgs()
    {
        return $this->hasMany('CommunityImg');
    }

    public function categorys()
    {
        return $this->belongsTo('category', 'category_id');
    }
}
