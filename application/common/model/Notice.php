<?php
namespace app\common\model;

use think\Model;

class Notice extends Model
{

    //get_column
    public function getColumn($where)
    {
        return $this->where($where)->column('id');
    }

    //paginate
    public function getPaginate($where, $limit = 15)
    {
        return $this->where($where)
            ->paginate($limit, false, ['query' => request()->get()]);
    }

    //更新全部
    public function saveAll($data)
    {
        return $this->saveAll($data);
    }

}
