<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class Works extends Model{

    //Find查询
    public function get_find($id){
        return $this->find($id);
    }

    //查询
    public function get_paginate($where=[],$order='status asc,listorder desc,createtime desc'){
        return $this->where(new Where($where))->order($order)->field(true)->paginate(config('usezan_page'),false,['query'=>request()->param()]);
    }

    //更新全部
    public function save_all($data){
        return $this->saveAll($data);
    }

    //新增、更新
    public function save_type($data,$isup=true){
        $this->allowField(true)->isUpdate($isup)->save($data);
        return $this->id;
    }

    //获取字段
    public function get_value($where,$value){
        return $this->where($where)->value($value);
    }

    //更新字段
    public function set_field($data){
        return $this->setField($data);
    }

    //删除
    public function del($id){
        return $this->destroy($id);
    }




}