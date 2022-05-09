<?php
namespace app\common\model;

use think\Model;

class Article extends Model
{
    public function keywords()
    {
        return $this->belongsToMany('Keyword');
    }

    public function comments()
    {
        return $this->belongsToMany('Member', 'ArticleComment');
    }

    public function collects()
    {
        return $this->belongsToMany('Member', 'ArticleCollect');
    }

    public function likes()
    {
        return $this->belongsToMany('Member', 'ArticleLike');
    }

    //paginate
    public function getPaginate($where, $order, $field = true)
    {
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(8, false, ['page' => request()->param('page')])
            ->each(function ($item, $key) {
                $item['collection'] = 0;
                if (request()->user) {
                    $item['collection'] = MemberCollection::where([['uid', '=', request()->user['id']], ['aid', '=', $item['id']], ['type', '=', 2]])->count();
                }
            });
    }

    //推荐
    public function recommData($where)
    {
        $data = $this->where($where)
            ->order('ispos desc,view desc,createtime desc')
            ->field('id,title,thumb,createtime,url')
            ->limit(8)
            ->select();
        if ($data->isEmpty()) {
            $data = $this->where([['status', '=', 1]])
                ->order('view desc,createtime desc')
                ->field('id,title,thumb,createtime,url')
                ->limit(8)
                ->select();
        }
        return $data;
    }

    //save-add
    public function saveData($data)
    {
        return $this->allowField(true)->save($data);
    }

    //+1
    public function incView($id)
    {
        return $this->where('id', $id)->setInc('view', 1, 30);
    }

    //count
    public function isCount($where)
    {
        return $this->where($where)->count();
    }

    //find
    public function getFind($id, $field = true)
    {
        $data = $this->field($field)->findOrEmpty($id);
        if ($data->isEmpty()) {
            return [];
        }
        return $data->toArray();
    }

    //where-find
    public function getWfind($where, $order, $field = 'id,title,url')
    {
        return $this->where($where)->order($order)->field($field)->find();
    }

}
