<?php
namespace app\usezan\model;
use think\db\Where;
use think\Model;
class AuthRule extends Model{

    //Find查询
    public function get_find($id){
        return $this->get($id);
    }

    public function get_where_find($where,$order='listorder asc,id asc'){
        return $this->where(new Where($where))->order($order)->find();
    }

    //查询
    public function get_where_select($where,$order='listorder asc,id asc',$field=true){
        return $this->where(new Where($where))->order($order)->field($field)->select();
    }

    //查询
    public function get_select($order='listorder asc,id asc'){
        $data = $this->order($order)->select();
        return $data->toArray();
    }

    //缓存
    public function get_cache_select($order='listorder asc,id asc',$field=true){
        $data = $this->order($order)->field($field)->select();
        return $data->toArray();
    }

    //添加、修改
    public function save_type($data,$is_update=true){
        $this->allowField(true)->isUpdate($is_update)->save($data);
        if ($is_update){
            return true;
        } else {
          return $this->id;
        }
    }

    //批量更新
    public function save_all($data){
        return $this->isUpdate(true)->saveAll($data);
    }

    //更新字段
    public function set_field($data){
        return $this->setField($data);
    }

    //获取字段
    public function get_value($where,$value){
        return $this->where(new Where($where))->value($value);
    }

    //统计
    public function is_count($where){
        return $this->where(new Where($where))->count();
    }

    //删除
    public function del($id){
        return $this->destroy($id);
    }

    public function del_where($where){
        return $this->where(new Where($where))->delete();
    }


}