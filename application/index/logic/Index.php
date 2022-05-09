<?php

namespace app\index\logic;

use app\common\model\Platform;
use app\common\model\Service;

class Index
{
    public static function getServiceList()
    {
        // 五条
        $platfromData = Platform::cache(true, cache_time('one_week'))
            ->where('recommend', 1)
            ->order('sort', 'desc')
            ->limit(5)
            ->select()
            ->toArray();
        if (!$platfromData) {
            return [];
        }

        // 四条
        $serviceData = Service::cache(true, cache_time('one_week'))
            ->where(['recommend' => 1, 'status' => 1])
            ->order('sort', 'desc')
            // ->limit(4)
            ->select()
            ->toArray();
        if (!$serviceData) {
            return [];
        }
        
        // 取出四条服务的id
        $serviceIds = array_column($serviceData, 'id');
        // halt($serviceIds);
        // 四条
        $service = Service::cache(true, cache_time('one_week'))
            ->where('status', 1)
            ->where('category_id', 'in', '161, 162, 163, 164')
            ->where('id', 'not in', $serviceIds)
            ->order('sort', 'desc')
            // ->limit(4)
            ->select()
            ->toArray();
        if (!$service) {
            return [];
        }
        // halt($service);

        return array_merge($platfromData, $serviceData, $service);
    }
}
