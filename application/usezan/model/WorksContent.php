<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class WorksContent extends Model{

    public function get_find($id){
        return $this->where('wid','=',$id)->value('content');
    }

    //add
    public function save_add($data){
        return $this->save($data);
    }

    //up
    public function save_up($aid,$content){
        return $this->where('wid','=',$aid)->setField('content',$content);
    }

    //del
    public function del($id){
        return $this->where('wid','=',$id)->delete();
    }




}