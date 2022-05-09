<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class Notice extends Model{

    //更新全部
    public function save_all($data){
        return $this->saveAll($data);
    }

}