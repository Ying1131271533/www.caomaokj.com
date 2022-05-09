<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class AuthGroup extends Model{

    //Find查询
    public function get_find($id){
        return $this->get($id);
    }

    //分页查询
    public function get_paginate($where,$limit){
        return $this->where(new Where($where))->paginate($limit);
    }

    //查询
    public function get_select($where){
     return $this->where(new Where($where))->select();
    }

    //添加、修改
    public function save_type($data,$is_update=true){
        return $this->allowField(true)->isUpdate($is_update)->save($data);
    }

    //获取字段
    public function get_value($where,$value){
        return $this->where(new Where($where))->value($value);
    }

    //统计
    public function is_count($where){
        return $this->where(new Where($where))->count();
    }

    //删除
    public function del($id){
        return $this->destroy($id);
    }






}