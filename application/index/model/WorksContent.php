<?php
namespace app\index\model;
use think\db\Where;
use think\Model;
class WorksContent extends Model{

    public function get_find($id){
        return $this->where('wid','=',$id)->value('content');
    }

}