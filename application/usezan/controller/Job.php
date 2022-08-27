<?php
namespace app\usezan\controller;

use app\common\model\Job as ModelJob;

class Job extends Base
{
    public function index()
    {
        $where = [];
        $data  = input();
        // 显示条数
        $limit = !empty($data['limit']) ? $data['limit'] : 20;

        // 提交
        if (request()->isPost()) {
            !empty($data['company']) and $where[]         = ['company', 'like', '%' . $data['company'] . '%'];
            !empty($data['phone']) and $where[]           = ['phone', '=', $data['phone']];
            (int) $data['check_status'] > -1 and $where[] = ['check_status', '=', $data['check_status']];
            $data['status'] > -1 and $where[]             = ['status', '=', $data['status']];
            !empty($data['position']) and $where[]        = ['position', '=', $data['position']];
        }

        // 岗位列表
        $list = ModelJob::where($where)->order(['id' => 'desc'])->paginate($limit);
        // halt($list);

        $view_data = [
            'list'         => $list,
            'limit'        => $limit,
            'check_status' => input('check_status/d', -1),
            'status'       => input('status/d', -1),
            'position'     => input('position/d', 0),
            'name'         => input('name/s', ''),
        ];
        return view('', $view_data);
    }

    public function detail()
    {
        $job = ModelJob::get(input('id/d'), 'detail');
        // 分割福利
        if(!empty($job['welfare'])) $job['welfare'] = explode(',', $job['welfare']);
        return view('', ['job' => $job]);
    }

    public function check()
    {
        // 接收参数
        $id = input('id/d');
        $check_status = input('check_status/d');
        $data = ['id' => $id, 'check_status' => $check_status, 'check_time' => time()];
        $check_status == 1 and $data['status'] = 1;
        // 修改数据状态
        $job = ModelJob::update($data);
        if(!$job) return fail('审核失败');
        return success();
    }

    public function delete()
    {
        $id = input('id/d', '');
        if(!$id) return fail('id不能为空');
        // 找到数据
        $job = ModelJob::get($id, 'detail');
        if(!$job) return fail('找不到数据');
        // 删除当前及关联模型
        $result = $job->together('detail')->delete();
        // 返回结果
        if(!$result) return fail('删除失败');
        return success();
    }
}
