<?php
namespace app\common\model;

use think\db\Where;
use think\Model;

class Activity extends Model
{
    // protected $autoWriteTimestamp = true;
    // protected $type               = [
    //     'createtime' => 'timestamp',
    //     'starttime'  => 'timestamp',
    //     'endtime'    => 'timestamp',
    // ];

    public function keywords()
    {
        return $this->belongsToMany('Keyword');
    }

    public function comments()
    {
        return $this->belongsToMany('Member', 'ActivityComment');
    }

    public function collects()
    {
        return $this->belongsToMany('Member', 'ActivityCollect');
    }

    public function likes()
    {
        return $this->belongsToMany('Member', 'ActivityLike');
    }

    public function joins()
    {
        return $this->hasMany('ActivityJoin');
    }

    //paginate
    public function getPaginate($where, $order, $field = true)
    {
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(12, false, ['page' => request()->param('page')])
            ->each(function ($item, $key) {
                $time = time();
                if ($item['endtime'] < $time) {
                    $item['ain']    = 2;
                    $item['aintxt'] = '已结束';
                } else {
                    $item['ain']    = 1;
                    $item['aintxt'] = '进行中';
//                    if ($item['starttime'] > $time){
                    //                        $item['aintxt'] = '未开始';
                    //                    } else {
                    //                        $item['aintxt'] = '进行中';
                    //                    }
                }
            });
    }

    //推荐
    public function recommData($id = null)
    {
        $where = $id ? [['status', '=', 1], ['id', '<>', $id]] : [['status', '=', 1]];
        return $this->where($where)
            ->order('view desc,createtime desc')
            ->field('id,title,url')
            ->limit(12)
            ->select();
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
