<?php
namespace app\common\model;

use think\db\Where;
use think\Model;

class WorksContent extends Model
{

    public function getFind($id)
    {
        return $this->where('wid', '=', $id)->value('content');
    }

}
