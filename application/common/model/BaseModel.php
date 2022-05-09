<?php

namespace app\common\model;

use think\model;

abstract class BaseModel extends model
{
    public static function getPageData(int $page = 1, int $size = 30, string $order = 'id')
    {
        return self::order($order, 'desc')->paginate($size, false, ['page' => $page]);
    }

    public static function getPageList(array $where = [], int $limit = 30, string $order = 'id')
    {
        return self::order($order, 'desc')->paginate($limit);
    }

    public static function getListData(array $where = [], array $order = ['id' => 'desc'], $limit = false)
    {
        return self::where($where)->limit($limit)->order($order)->select();
    }
}
