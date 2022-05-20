<?php
namespace app\common\model;

class ArticleComment extends BaseModel
{
    protected $autoWriteTimestamp = false;

    // 文章
    public function article()
    {
        return $this->belongsTo('article');
    }

    public function member()
    {
        return $this->belongsTo('member');
    }
}
