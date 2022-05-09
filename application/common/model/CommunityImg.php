<?php
namespace app\common\model;

class CommunityImg extends BaseModel
{
    public function community()
    {
        return $this->belongsTo('Community');
    }
}
