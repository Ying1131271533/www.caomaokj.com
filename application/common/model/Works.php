<?php
namespace app\common\model;

use think\Model;

class Works extends Model
{

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

    //list
    public function getPaginate($where, $order, $field = true)
    {
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(21, false, ['page' => request()->param('page')]);
    }

    //UI随机推荐导航
    public function getHotselect($where, $field = true)
    {
        return $this->where($where)
            ->orderRand()
            ->field($field)
            ->limit(8)
            ->select()
            ->toArray();
    }

    public function getSelect($where, $limit = 12, $order = 'listorder desc,createtime desc', $field = true)
    {
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->limit($limit)
            ->select()
            ->toArray();
    }

    //add
    public function saveAdd($data)
    {
        return $this->allowField(true)->save($data);
    }

    //count
    public function isCount($where)
    {
        return $this->where($where)->count();
    }

    //+1
    public function incView($id)
    {
        return $this->where('id', '=', $id)->setInc('view', 1, 30);
    }

}
