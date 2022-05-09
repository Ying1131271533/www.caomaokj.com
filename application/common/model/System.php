<?php
namespace app\common\model;

use think\db\Where;
use think\Model;

class System extends Model
{

    //Find查询
    public function getFind($id)
    {
        return $this->get($id);
    }

    //查询
    public function getSelect($where)
    {
        return $this->where(new Where($where))->field(true)->order('id asc')->select();
    }

    public function getCacheSelect($where)
    {
        $data = $this->where(new Where($where))->field('varname,info,value')->select();
        return $data->toArray();
    }

    //添加、修改
    public function saveType($data, $is_update = true)
    {
        return $this->allowField(true)->isUpdate($is_update)->save($data);
    }

    //批量
    public function saveAll($data, $is_update = true)
    {
        return $this->isUpdate($is_update)->saveAll($data);
    }

    //更新字段
    public function setField($where, $data)
    {
        return $this->where(new Where($where))->setField($data);
    }

    //删除
    public function del($where)
    {
        return $this->where(new Where($where))->delete();
    }

}
