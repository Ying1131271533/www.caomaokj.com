<?php
namespace app\index\model;
use think\Model;
class Links extends Model{

    public function get_select(){
        return $this->where('status','=',1)
            ->order('listorder desc,createtime asc')
            ->field('id,name,url')
            //->cache('links',60)
            ->select();
    }

    //add
    public function save_add($data){
        return $this->allowField(true)->save($data);
    }

    //count
    public function is_count($where){
        return $this->where($where)->count();
    }

}