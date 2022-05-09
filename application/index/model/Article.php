<?php
/**
 * Article模型
 * @author      [K?Germany:De] <[972581428@qq.com]>
 * @copyright   Copyright (c) 2016 [环企优站科技]  (https://www.h7uz.com)
 */
namespace app\index\model;
use think\Model;
class Article extends Model{

    //paginate
    public function get_paginate($where,$order,$field=true){
        return $this->where($where)
            ->order($order)
            ->field($field)
            ->paginate(8,false,['page'=>request()->param('page')])
            ->each(function($item,$key){
                $item['collection'] = 0;
                if (request()->user){
                    $item['collection'] = MemberCollection::where([['uid','=',request()->user['id']],['aid','=',$item['id']],['type','=',2]])->count();
                }
            });
    }

    //推荐
    public function recomm_data($where){
        $data =  $this->where($where)
            ->order('ispos desc,view desc,createtime desc')
            ->field('id,title,thumb,createtime,url')
            ->limit(8)
            ->select();
        if ($data->isEmpty()){
            $data = $this->where([['status','=',1]])
                ->order('view desc,createtime desc')
                ->field('id,title,thumb,createtime,url')
                ->limit(8)
                ->select();
        }
        return $data;
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