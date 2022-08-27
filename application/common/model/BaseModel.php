<?php

namespace app\common\model;

use think\model;

abstract class BaseModel extends model
{   
    // 获取有分页参数的数据 - 接口用
    public static function getPageData(int $page = 1, int $size = 30, string $order = 'id')
    {
        return self::order($order, 'desc')->paginate($size, false, ['page' => $page]);
    }
    // 获取渲染分页
    public static function getPageList(array $where = [], int $limit = 30, string $order = 'id')
    {
        return self::order($order, 'desc')->paginate($limit);
    }
    // 获取数据分页
    public static function getListData(array $where = [], array $order = ['id' => 'desc'], $limit = false)
    {
        return self::where($where)->limit($limit)->order($order)->select();
    }
}
