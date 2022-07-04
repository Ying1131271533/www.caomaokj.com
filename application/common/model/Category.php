<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{

    public function slides()
    {
        return $this->hasMany('Slide', 'cid');
    }

    public function colleges()
    {
        return $this->hasMany('College', 'catid');
    }

    public function logistics()
    {
        return $this->hasMany('Logistics', 'catid');
    }

    public function services()
    {
        return $this->hasMany('Service');
    }

    public function serviceEnter()
    {
        return $this->hasMany('ServiceEnter');
    }

    // 获取分类列表缓存
    public static function getCategoryList($where = [], $field = true, $cache = true, $order = ['listorder' => 'desc', 'id' => 'asc']){
        $data = self::where($where)
        ->order($order)
        ->field($field)
        // ->cache($cache, cache_time('one_week'))
        ->select()
        ->toArray();
        return $data;
    }

    //Find查询
    public function getFind($id)
    {

        return $this->find($id);

    }

    // 查询

    public function getSelect($where, $field = true, $order = 'listorder asc,id asc')
    {
        return $this->where($where)->order($order)->field($field)->select();
    }

    // 缓存
    public function getCacheselect($where = [], $field = true, $order = 'listorder asc,id asc')
    {
        $data = $this->where($where)->order($order)->field($field)->select()->toArray();
        if ($data) {
            $dataarr = [];
            foreach ($data as $key => $vo) {
                $dataarr[$vo['id']] = $vo;
            }
            unset($data);
            return $dataarr;
        }

        return [];
    }

    // 查询
    public function getPaginate($where = [], $order = 'listorder asc,createtime desc')
    {

        return $this->where(new Where($where))->order($order)->field(true)->paginate(config('usezan_page'), false, ['query' => request()->param()]);

    }

    //新增、更新

    public function saveType($data, $isup = true)
    {

        return $this->allowField(true)->isUpdate($isup)->save($data);

    }

    public function setField($where, $data)
    {

        return $this->where($where)->setField($data);

    }

    //获取字段

    public function getValue($where, $value)
    {

        return $this->where($where)->value($value);

    }

    //列

    public function getClounm($where, $value)
    {

        return $this->where($where)->column($value);

    }

    //统计

    public function isCount($where)
    {

        return $this->where($where)->count();

    }

    //删除

    public function del($id)
    {

        return $this->destroy($id);

    }
}
