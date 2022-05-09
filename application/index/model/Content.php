<?php
namespace app\index\model;
use think\Model;
class Content extends Model{

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
        $data = $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(16,false,['page'=>request()->param('page')]);
        foreach ($data as $key=>$vo){
            $data[$key] = $vo;
            if ($vo['labels']){
                $data[$key]['labels'] = array_filter(explode("#",json_decode($vo['labels'])));
            }
        }
        return $data;
    }

    //UI热门推荐
    public function get_select($where,$field,$order='view desc',$limit=12){
        $data = $this->where($where)
            ->order($order)
            ->field($field)
            ->limit($limit)
            ->select()
            ->toArray();
        foreach ($data as $key=>$vo){
            $data[$key] = $vo;
            if (isset($vo['labels']) && $vo['labels']){
                $data[$key]['labels'] = array_filter(explode("#",json_decode($vo['labels'])));
            }
        }
        return $data;
    }

    //UI随机推荐导航
    public function get_hotselect($where,$field=true){
        $data = $this->where($where)
            ->orderRand()
            ->field($field)
            ->limit(8)
            ->select()
            ->toArray();
        foreach ($data as $key=>$vo){
            $data[$key] = $vo;
            if ($vo['labels']){
                $data[$key]['labels'] = array_filter(explode("#",json_decode($vo['labels'])));
            }
        }
        return $data;
    }

    //获取列
    public function get_column($id,$name){
        return $this->where('parentid','=',$id)->column($name);
    }

    //新增、更新
    public function save_type($data,$isup=true){
        $this->allowField(true)->isUpdate($isup)->save($data);
        return $this->id;
    }

    //更新字段
    public function set_field($data){
        return $this->setField($data);
    }

    //+1
    public function inc_view($id){
        return $this->where('id','=',$id)->setInc('view',1,30);
    }



}