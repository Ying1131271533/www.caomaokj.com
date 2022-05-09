<?php
namespace app\index\model;
use think\db\Where;
use think\Model;
class Uads extends Model{

    public function get_select(){
        return $this->where([['status','=',1],['type','=',1]])
            ->order('listorder desc,createtime asc')
            ->field('id,name,nofollow,url')
            //->cache('uads',60)
            ->select();
    }

    //find
    public function get_find($id,$field='id,name,cover,nofollow,url'){
        return $this->where('id','=',$id)->field($field)->find();
    }



}