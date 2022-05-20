<?php
namespace app\common\model;

class CollegeComment extends BaseModel
{
    protected $autoWriteTimestamp = false;

    // 文章
    public function college()
    {
        return $this->belongsTo('college');
    }

    public function member()
    {
        return $this->belongsTo('member');
    }
}
