<?php
namespace app\index\model;
use think\Model;
class Notice extends Model{

    //get_column
    public function get_column($where){
        return $this->where($where)->column('id');
    }

    //paginate
    public function get_paginate($where,$limit=15){
        return $this->where($where)
            ->paginate($limit,false,['query'=>request()->get()]);
    }

    //更新全部
    public function save_all($data){
        return $this->saveAll($data);
    }


}