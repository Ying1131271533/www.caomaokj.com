<?php

namespace app\index\logic;

use app\common\model\Community as ModelCommunity;

class Community
{
    /**
     * 获取数据
     *
     * @return array    $list       行业社群数据
     */
    public static function getCommunityList()
    {
        $where = ['status' => 1];
        $order = ['sort' => 'desc', 'id' => 'desc'];
        $list = ModelCommunity::getListData($where, 10, $order);
        return $list;
    }
}
