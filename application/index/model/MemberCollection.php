<?php
/**
 * MemberCollection模型
 * @author      [K?Germany:De] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2016 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\index\model;
use think\Model;
class MemberCollection extends Model{

    //list
    public function get_paginate($where,$order='createtime desc',$field=true){
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(12,false,['page'=>request()->param('page')])
            ->each(function ($item,$key){
                $item['createtime'] = date('Y-m-d H:i',$item['createtime']);
                if ($item['type'] == 1){
                    $item['lower'] = Activity::where('id',$item['aid'])->field('title,thumb,url')->find();
                } else {
                    $item['lower'] = Article::where('id',$item['aid'])->field('title,thumb,url')->find();
                }
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

    //del
    public function del($id){
        return $this->destroy($id);
    }

}