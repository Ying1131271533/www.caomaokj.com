<?php
namespace app\common\model;

use think\db\Where;
use think\Model;

class User extends Model
{

    public function joins()
    {
        return $this->hasMany('ActivityJoin');
    }

    //Find查询
    public static function getFind($id)
    {
        return self::find($id);
    }

    public static function getWhereFind($where)
    {
        return self::where(new Where($where))->find();
    }

    //分页查询
    public function getPaginate($where, $page, $field = true, $order = 'createtime asc')
    {
        return $this->where(new Where($where))->field($field)->order($order)->paginate($page, false, ['query' => request()->param()]);
    }

    //添加、修改
    public function saveType($data, $is_update = true)
    {
        if ($is_update) {
            return $this->allowField(true)->isUpdate($is_update)->save($data);
        } else {
            $this->allowField(true)->isUpdate($is_update)->save($data);
            return $this->id;
        }
    }

    //统计
    public function userCount($where)
    {
        return $this->where(new Where($where))->count();
    }

    //删除
    public function del($id)
    {
        return $this->destroy($id);
    }

}
