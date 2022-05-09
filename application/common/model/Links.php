<?php
namespace app\common\model;

use think\Model;

class Links extends Model
{

    public function getSelect()
    {
        return $this->where('status', '=', 1)
            ->order('listorder desc,createtime asc')
            ->field('id,name,url')
        //->cache('links',60)
            ->select();
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

}
