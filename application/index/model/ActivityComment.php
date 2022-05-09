<?php
/**
 * ActivityComment模型
 * @author      [K?Germany:De] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2016 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\index\model;
use think\Model;
class ActivityComment extends Model{

    //list
    public function get_paginate($where,$order='id desc',$field=true){
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(9,false,['page'=>request()->param('page')])
            ->each(function ($item,$key){
                $item['lower'] = Activity::where('id',$item['aid'])->field('title,thumb,url')->find();
            });
    }

    //save-add
    public function save_data($data){
        return $this->allowField(true)->save($data);
    }

    //count
    public function is_count($where){
        return $this->where($where)->count();
    }

}