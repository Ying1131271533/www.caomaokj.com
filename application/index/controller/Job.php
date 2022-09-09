<?php
namespace app\index\controller;

use app\common\model\ChinaPlaces;
use app\common\model\Job as ModelJob;
use app\index\logic\Job as LogicJob;
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
        // 招聘数据
        $data = LogicJob::getJobPageList($request);
        // 精选职位推荐
        $featuredJob = ModelJob::getListData(['status' => 1], 5, ['views' => 'desc', 'id' => 'desc']);
        $data['featuredJob'] = $featuredJob;
        // 返回数据
        return view('', $data);
    }

    /*
     * 岗位详情
     *
     * detail
     */
    public function detail(int $id)
    {
        if (!$id) {
            return akali('id不能为空');
        }

        $job = ModelJob::get($id, 'detail');
        // 查看次数加1
        $job->setInc('views');
        if (!$job) {
            return akali('职位数据不存在');
        }

        return view('', ['job' => $job]);
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
    public function save_release(Request $request)
    {
        // 接收数据
        $params = $request->param();

        // 验证数据
        $validate = validate('Job');
        if (!$validate->check($params)) {
            return fail($validate->getError());
        }

        // 获取地区值
        $release_places = $params['city_value'];
        $city_value     = $this->get_city_value($release_places);

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
        return json($china_places[0]['children']);
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
