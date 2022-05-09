<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class System extends Model{

    //Find查询
    public function get_find($id){
        return $this->get($id);
    }

    //查询
    public function get_select($where){
        return $this->where(new Where($where))->field(true)->order('id asc')->select();
    }

    public function get_cache_select($where){
        $data = $this->where(new Where($where))->field('varname,info,value')->select();
        return $data->toArray();
    }

    //添加、修改
    public function save_type($data,$is_update=true){
        return $this->allowField(true)->isUpdate($is_update)->save($data);
    }

    //批量
    public function save_all($data,$is_update=true){
        return $this->isUpdate($is_update)->saveAll($data);
    }

    //更新字段
    public function set_field($where,$data){
        return $this->where(new Where($where))->setField($data);
    }

    //删除
    public function del($where){
        return $this->where(new Where($where))->delete();
    }




}