<?php
namespace app\index\controller;

use app\common\model\ChinaPlaces;
use app\common\model\Job as ModelJob;
use think\facade\Cache;
use think\Request;

class Job extends Base
{
    /*
     * 岗位列表
     *
     * index
     */
    public function index(Request $request)
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
        if(empty($salary_range)){
            $salary_range_where = where_salary_range($salary_range);
            foreach($salary_range_where as $value){
                $where[] = $value;
            }
        }
        // halt($salary_range_where);
        // 平台要求
        !empty($platform) and $where[] = ['platform', '=', $platform];
        // 工作职位
        !empty($position) and $where[] = ['position', '=', $position];

        // 学历要求
        !empty($education_background) and $where[] = ['education_background', '=', education_background_val($education_background)];
        // 工作经验
        !empty($work_experience) and $where[] = ['work_experience', '=', work_experience_val($work_experience)];
        // 到岗时间
        !empty($duty_time) and $where[] = ['duty_time', '=', duty_time_val($duty_time)];
        // 更新时间
        !empty($update_time) and $where[] = ['update_time', '=', update_time_val($update_time)];
        // 获取地区值
        if (!empty($city_value)) {
            $city_value_id = $this->get_city_value($city_value);
            $where[]       = ['city_value', '=', $city_value_id];
        }
        // halt($where);

        // 排序
        $order = $request->param('order/d', 1);
        $order == 1 ? $order_where = ['views' => 'asc', 'update_time' => 'desc'] : $order_where = ['update_time' => 'desc'];
        
        $job = ModelJob::where($where)
                    ->where('salary_min >= 3000 or salary_min <= 5000')
                    ->where('salary_max >= 3000 or salary_max <= 5000')
                    // ->where($salary_range_where)
                    ->order($order_where)
                    ->paginate(10);

        // halt($order);
        /* if (empty($salary_range_array)) {
            $job = ModelJob::getPageList($where, 10, $order_where);
        } else {
            if (count($salary_range_array) < 2) {
                $job = ModelJob::where($where)
                    ->where($salary_range_array)
                    ->order($order_where)
                    ->paginate(10);
            } else {
                $job = ModelJob::where($where)
                    ->whereOr('salary_min>3000 or salary_max<5000')
                    // ->where('salary_min>3000 or salary_max<5000')
                    ->order($order_where)
                    ->paginate(10);
            }
        } */
        // halt($job);
        // 整理福利
        // if (!empty($job['welfare'])) {
        //     $job['welfare'] = explode(',', $job['welfare']);
        // }
        // halt($job[0]->getAttr('welfare'));
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
            'china_places'                => $this->get_china_places(),
        ];
        return view('', $data);
    }

    /*
     * 岗位详情
     *
     * detail
     */
    public function detail()
    {

    }

    /*
     * 发布岗位
     *
     * release
     */
    public function release()
    {
        return view();
    }

    // 保存岗位
    public function save_release()
    {
        // 接收数据
        $params = input();

        // 验证数据
        $validate = validate('Job');
        if (!$validate->check($params)) {
            return fail($validate->getError());
        }

        // 获取地区值
        $release_places = $params['city_value'];
        $release_places = $this->get_city_value($release_places);

        // 将省市区和地区表id加入数据中
        $params['place']      = str_replace('/', '-', str_replace(' ', '', $release_places));
        $params['city_value'] = $city_value;

        // 如果福利不为空，则加入数据
        if (($params['welfare'])) {
            $params['welfare'] = implode(',', $params['welfare']);
        }

        // 开启事务
        ModelJob::startTrans();

        try {
            $job = ModelJob::create($params);
            if (!$job) {
                return fail('岗位保存出错1');
            }

            $job_detail = $job->detail()->save($params);
            if (!$job_detail) {
                return fail('岗位保存出错2');
            }

            // 提交事务
            ModelJob::commit();
        } catch (\Exception $e) {
            // 回滚事务
            ModelJob::rollback();
            return fail($e->getMessage());
        }

        return success();
    }

    // 获取地区值
    public function get_city_value($release_places)
    {
        // 发布地区
        $city_name = explode('/', str_replace(' ', '', $release_places));
        if (!$city_name) {
            return fail('发布地区有误');
        }

        // 获取地区表id
        $city_value = ChinaPlaces::where('label', $city_name[2])->value('value');
        if (!$city_value) {
            return fail('找不到发布地区');
        }
        return $city_value;
    }

    // 获取中国地区数据
    public function get_china_places()
    {
        $parent_id = input('parent_id', 0);
        // 获取中国地区数据
        $china_places = ChinaPlaces::cache(cache_time('one_month'))->all()->toArray();
        // 地区数据分类
        $china_places = Cache::remember('china_places', $this->get_children($china_places, $parent_id), cache_time('one_month'));
        return success($china_places[0]['children']);
    }

    // 获取中国地区数据子级分类
    protected function get_children($data, $parent_id = 0)
    {
        $tmp = [];
        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $parent_id) {
                $value['children'] = $this->get_children($data, $value['value']);
                if (empty($value['children'])) {
                    unset($value['children']);
                }

                $tmp[] = $value;
            }
        }
        return $tmp;
    }
}
