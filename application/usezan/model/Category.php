<?php
namespace app\usezan\model;

use think\db\Where;
use think\Model;

class Category extends Model
{

    //Find查询

    public function get_find($id){

        return $this->find($id);

    }



    //查询

    public function get_select($where,$field=true,$order='listorder asc,id asc'){

        return $this->where($where)->order($order)->field($field)->select();

    }



    //缓存

    public function get_cacheselect($where=[],$field=true,$order='listorder asc,id asc'){

        $data = $this->where($where)->order($order)->field($field)->select()->toArray();

        if($data){

            $dataarr = [];

            foreach ($data as $key=>$vo) {

                $dataarr[$vo['id']] = $vo;

            }

            unset($data);

            return $dataarr;

        }

        return [];

    }



    //查询

    public function get_paginate($where=[],$order='listorder asc,createtime desc'){

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



    public function set_field($where,$data){

        return $this->where($where)->setField($data);

    }



    //获取字段

    public function get_value($where,$value){

        return $this->where($where)->value($value);

    }



    //列

    public function get_clounm($where,$value){

        return $this->where($where)->column($value);

    }



    //统计

    public function is_count($where){

        return $this->where($where)->count();

    }



    //删除

    public function del($id){

        return $this->destroy($id);

    }

}
