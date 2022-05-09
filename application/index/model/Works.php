<?php
namespace app\index\model;
use think\Model;
class Works extends Model{

    //find
    public function get_find($id,$field=true){
        $data = $this->field($field)->findOrEmpty($id);
        if ($data->isEmpty()){
            return [];
        }
        return $data->toArray();
    }

    //where-find
    public function get_wfind($where,$order,$field='id,title,url'){
        return $this->where($where)->order($order)->field($field)->find();
    }

    //list
    public function get_paginate($where,$order,$field=true){
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(21,false,['page'=>request()->param('page')]);
    }

    //UI随机推荐导航
    public function get_hotselect($where,$field=true){
        return $this->where($where)
            ->orderRand()
            ->field($field)
            ->limit(8)
            ->select()
            ->toArray();
    }

    public function get_select($where,$limit=12,$order='listorder desc,createtime desc',$field=true){
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->limit($limit)
            ->select()
            ->toArray();
    }

    //add
    public function save_add($data){
        return $this->allowField(true)->save($data);
    }

    //count
    public function is_count($where){
        return $this->where($where)->count();
    }

    //+1
    public function inc_view($id){
        return $this->where('id','=',$id)->setInc('view',1,30);
    }

}