<?php
namespace app\index\model;
use think\db\Where;
use think\Model;
class Searchkey extends Model{

    public function get_select(){
        return $this->where('status','=',1)
            ->order('id asc')
            ->field('id,title,url')
            ->select();
    }



}