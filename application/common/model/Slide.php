<?php
namespace app\common\model;

use think\Model;

class Slide extends Model
{
    public function category()
    {
        return $this->hasOne('Category', 'cid');
    }

    /**
     * 获取轮播图
     *
     * @param int $cid 栏目分类id
     * @param int $type 类型 1:轮播图 2:展示图
     * @param int $status 状态 1:显示 2:不显示
     * @return array $data
     */
    public function get($cid = '', $type = '', $status = '')
    {
        $where               = [];
        $cid and $where[]    = ['cid', $cid];
        $type and $where[]   = ['type', $type];
        $status and $where[] = ['status', $status];

        $data = $this->where('type', 1)
            ->order('listorder', 'asc')
            ->field('id, thumb, title, url')
            ->select();
        if ($data->isEmpty()) {
            return [];
        }
        return $data;
    }

}
