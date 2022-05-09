<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class User extends Model{

    //Find查询
    public static function get_find($id){
        return self::find($id);
    }

    public static function get_where_find($where){
        return self::where(new Where($where))->find();
    }

    //分页查询
    public function get_paginate($where,$page,$field=true,$order='createtime asc'){
        return $this->where(new Where($where))->field($field)->order($order)->paginate($page,false,['query'=>request()->param()]);
    }

    //添加、修改
    public function save_type($data,$is_update=true){
        if ($is_update){
            return $this->allowField(true)->isUpdate($is_update)->save($data);
        } else {
            $this->allowField(true)->isUpdate($is_update)->save($data);
            return $this->id;
        }
    }

    //统计
    public function user_count($where){
        return $this->where(new Where($where))->count();
    }

    //删除
    public function del($id){
        return $this->destroy($id);
    }






}