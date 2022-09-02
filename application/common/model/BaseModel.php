<?php

namespace app\common\model;

use think\model;

abstract class BaseModel extends model
{   
    // 获取有分页参数的数据 - 因为条件比较简单，所以后台接口用
    public static function getPageData(int $page = 1, int $size = 30, string $order = 'id')
    {
        return self::order($order, 'desc')->paginate($size, false, ['page' => $page]);
    }
    // 获取渲染分页 - 前端用
    public static function getPageList(array $where = [], int $limit = 30, array $order = ['id' => 'desc'])
    {
        return self::where($where)->order($order)->paginate($limit);
    }
    // 获取数据分页 - 前端接口用
    public static function getListData(array $where = [], $limit = false, array $order = ['id' => 'desc'])
    {
        return self::where($where)->limit($limit)->order($order)->select();
    }
}
