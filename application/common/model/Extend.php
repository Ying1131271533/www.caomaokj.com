<?php
namespace app\common\model;

use think\db\Where;
use think\Model;

class Extend extends Model
{
    //查询全部
    public function getSelect($open = 1)
    {
        $data  = $this->where(new Where(['open' => $open, 'status' => 1]))->field('name,value')->select();
        $datas = [];
        foreach ($data as $key => $vo) {
            $datas[$vo['name']] = $vo['value'];
        }
        switch ($open) {
            case 2:
                cache('extend_config_wap', $datas);
                break;
            default:
                cache('extend_config', $datas);
                break;
        }
        unset($data);
        return true;
    }

    public function getData($where)
    {
        return $this->where(new Where($where))->column("value", "name");
    }

    //更新字段
    public function getSetfield($name, $value)
    {
        return $this->where('name', $name)->setField('value', $value);
    }

}
