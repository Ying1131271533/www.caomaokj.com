<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class Searchkey extends Model{

    //Find查询
    public function get_find($id){
        return $this->find($id);
    }

    //查询
    public function get_paginate($where=[],$order='id asc'){
        return $this->where(new Where($where))->order($order)->field(true)->paginate(config('usezan_page'),false,['query'=>request()->param()]);
    }

    //更新全部
    public function save_all($data){
        return $this->saveAll($data);
    }

    //新增、更新
    public function save_type($data,$isup=true){
        return $this->allowField(true)->isUpdate($isup)->save($data);
    }

    //获取字段
    public function get_value($where,$value){
        return $this->where($where)->value($value);
    }

    //删除
    public function del($id){
        return $this->destroy($id);
    }




}