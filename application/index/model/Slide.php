<?php
namespace app\index\model;
use think\db\Where;
use think\Model;
class Slide extends Model{

    public function get_select(){
        $data = $this->where('status','=',1)
            ->order('listorder asc,createtime asc')
            ->field('id,title,thumb,url')
            ->select();
        if ($data->isEmpty()){
            return [];
        }
        return $data;
    }



}