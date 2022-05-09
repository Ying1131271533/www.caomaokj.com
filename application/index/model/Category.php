<?php
namespace app\index\model;
use think\Model;
class Category extends Model{


    //获取列
    public function get_column($id){
        return $this->where('parentid','=',$id)->column('id');
    }
}