<?php
/**
 * Activity模型
 * @author      [K?Germany:De] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2016 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\index\model;
use think\db\Where;
use think\Model;
class Activity extends Model{

    //paginate
    public function get_paginate($where,$order,$field=true){
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(12,false,['page'=>request()->param('page')])
            ->each(function($item,$key){
                $time = time();
                if ($item['endtime'] < $time){
                    $item['ain'] = 2;
                    $item['aintxt'] = '已结束';
                } else {
                    $item['ain'] = 1;
                    $item['aintxt'] = '进行中';
//                    if ($item['starttime'] > $time){
//                        $item['aintxt'] = '未开始';
//                    } else {
//                        $item['aintxt'] = '进行中';
//                    }
                }
            });
    }

    //推荐
    public function recomm_data($id=null){
        $where = $id ? [['status','=',1],['id','<>',$id]] : [['status','=',1]];
        return $this->where($where)
            ->order('view desc,createtime desc')
            ->field('id,title,url')
            ->limit(12)
            ->select();
    }

    //save-add
    public function save_data($data){
        return $this->allowField(true)->save($data);
    }

    //+1
    public function inc_view($id){
        return $this->where('id',$id)->setInc('view',1,30);
    }

    //count
    public function is_count($where){
        return $this->where($where)->count();
    }

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




}