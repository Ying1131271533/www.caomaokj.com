<?php
namespace app\common\model;

use think\db\Where;
use think\Model;

class ContentSchedule extends Model
{

    public function getFind($id)
    {
        return $this->where('aid', '=', $id)->value('content');
    }

}
