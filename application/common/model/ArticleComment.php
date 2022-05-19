<?php
namespace app\common\model;

class ArticleComment extends BaseModel
{
    protected $autoWriteTimestamp = false;

    public function article()
    {
        return $this->belongsTo('article');
    }

    public function member()
    {
        return $this->belongsTo('member');
    }
}
