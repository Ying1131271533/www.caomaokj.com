<?php
namespace app\common\model;

use think\db\Where;
use think\Model;

class Searchkey extends Model
{

    public function getSelect()
    {
        return $this->where('status', '=', 1)
            ->order('id asc')
            ->field('id,title,url')
            ->select();
    }

}
