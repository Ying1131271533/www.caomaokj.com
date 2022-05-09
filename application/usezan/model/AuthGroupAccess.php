<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class AuthGroupAccess extends Model{

    //添加、修改
    public function save_type($data,$is_update=true){
        return $this->allowField(true)->isUpdate($is_update)->save($data);
    }

    //更新字段
    public function set_field($where,$name,$value){
        return $this->where(new Where($where))->setField([$name=>$value]);
    }

    //删除
    public function del($where){
        return $this->where(new Where($where))->delete();
    }








}