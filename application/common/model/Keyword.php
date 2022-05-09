<?php
namespace app\common\model;

use think\Model;

class Keyword extends Model
{
    public function articles()
    {
        // return $this->belongsToMany('Article');
        // 多对多模型连接时，默认表开头是自己，就会变成：keyword_article，所以要下面一样改成正确的表名
        return $this->belongsToMany('Article', 'ArticleKeyword');
    }

    public function activitys()
    {
        return $this->belongsToMany('Activity', 'ActivityKeyword');
    }
}
