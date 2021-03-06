<?php
namespace app\common\model;

class ActivityComment extends BaseModel
{
    protected $autoWriteTimestamp = false;

    // 文章
    public function activity()
    {
        return $this->belongsTo('activity');
    }

    public function member()
    {
        return $this->belongsTo('member');
    }
}
