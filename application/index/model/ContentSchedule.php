<?php
namespace app\index\model;
use think\db\Where;
use think\Model;
class ContentSchedule extends Model{

    public function get_find($id){
        return $this->where('aid','=',$id)->value('content');
    }




}