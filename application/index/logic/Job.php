<?php

namespace app\index\logic;

use app\common\model\ChinaPlaces;
use app\common\model\Job as ModelJob;

class Job
{
    /**
     * 岗位分页数据
     *
     * @return array    $data   返回分页数据
     */
    public static function getJobPageList($request)
    {
        // halt($request->param());
        // 条件
        $salary_range = $request->param('salary_range/d', 0);
        $platform     = $request->param('platform', 0);
        $position     = $request->param('position', 0);
        // 更多
        $education_background = $request->param('education_background', '');
        $work_experience      = $request->param('work_experience', '');
        $duty_time            = $request->param('duty_time', '');
        $update_time          = $request->param('update_time', '');
        $city_value           = $request->param('city_value', '');

        $where   = [];
        $where[] = ['status', '=', 1];
        // 月薪范围
        !empty($salary_range) and where_salary_range($where, $salary_range);
        // halt($salary_range_where);
        // 平台要求
        !empty($platform) and $where[] = ['platform', '=', $platform];
        // 工作职位
        !empty($position) and $where[] = ['position', '=', $position];

        // 学历要求
        !empty($education_background) and $where[] = ['education_background', '>=', education_background_val($education_background)];
        // 工作经验
        !empty($work_experience) and $where[] = ['work_experience', '>=', work_experience_val($work_experience)];
        // 到岗时间
        !empty($duty_time) and $where[] = ['duty_time', '>=', duty_time_val($duty_time)];
        // 更新时间
        !empty($update_time) and $where[] = ['update_time', '>=', update_time_val($update_time)];
        // 获取地区值
        if (!empty($city_value)) {
            $city_value_id = ChinaPlaces::where('label', $city_value)->value('value');
            if ($city_value_id) {
                $where[] = ['city_value', '=', $city_value_id];
            }
        }
        // halt($where);

        // 排序
        $order                     = $request->param('order/d', 1);
        $order == 1 ? $order_where = ['views' => 'asc', 'update_time' => 'desc'] : $order_where = ['update_time' => 'desc'];
        $job                       = ModelJob::getPageList($where, 10, $order_where);
        
        // 组装数据
        $data = [
            'job'                  => $job,
            'page'                 => $job->currentPage(),
            'count'                => $job->total(),
            // 条件
            'salary_range'         => $salary_range,
            'platform'             => $platform,
            'position'             => $position,
            // 更多
            'education_background' => $education_background,
            'work_experience'      => $work_experience,
            'duty_time'            => $duty_time,
            'update_time'          => $update_time,
            'city_value'           => $city_value,
            // 排序
            'order'                => $order,
        ];
        return $data;
    }
}
