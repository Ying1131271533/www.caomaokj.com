<?php
namespace app\common\model;

class CommunityDetail extends BaseModel
{
    public function community()
    {
        return $this->hasOne('Community');
    }
}
