<?php
/**
 * ActivityComment模型
 * @author      [K?Germany:De] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2016 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class ActivityComment extends Model{

    //list
    public function get_paginate($where,$order="status asc,id desc",$field=true){
        return $this->where(new Where($where))
            ->order($order)
            ->field($field)
            ->paginate(config('usezan_page'),false,['page'=>request()->param('page')])
            ->each(function ($item,$key){
                $item['lower'] = Activity::where('id',$item['aid'])->field('title,thumb,url')->find();
            });
    }

    //select
    public function get_select($where,$field=true){
        $data = $this->where($where)
            ->field($field)
            ->paginate()
            ->each(function ($item,$key){
                $item['title'] = Activity::where('id',$item['aid'])->value('title');
                $item['createtime'] = date('Y-m-d H:i',$item['createtime']);
                unset($item['aid']);
            })->toArray();
        return $data['data'] ? $data['data'] : [];
    }

    //save-add
    public function save_data($data){
        return $this->allowField(true)->save($data);
    }

    //批量更新
    public function save_all($data,$is_update=true){
        return $this->isUpdate($is_update)->saveAll($data);
    }

    //count
    public function is_count($where){
        return $this->where($where)->count();
    }

}