<?php
namespace app\usezan\controller;

use app\common\model\Service as ModelService;
use app\common\model\ServiceEnter as ModelServiceEnter;
use app\usezan\controller\Service;

class ServiceEnter extends Base
{
    /**
     * 服务商入驻列表 ⎛⎝≥⏝⏝≤⎛⎝
     *
     * @param
     * @return View
     */
    public function index()
    {
        $where = [];
        $data  = input();
        // 显示条数
        $limit = !empty($data['limit']) ? $data['limit'] : 20;

        // 提交
        if (request()->isPost()) {
            !empty($data['title']) and $where[]     = ['name', 'like', '%' . $data['name'] . '%'];
            !empty($data['phone']) and $where[]     = ['phone', '=', $data['phone']];
            (int) $data['status'] > -1 and $where[] = ['status', '=', $data['status']];
        }

        $list = ModelServiceEnter::with('featureds')
            ->where($where)
            ->order(['id' => 'desc'])
            ->paginate($limit);
        // halt($list);
        return view('', [
            'list'   => $list,
            'limit'  => $limit,
            'status' => input('status/d', -1),
            'name'   => input('name/s', ''),
        ]);
    }

    // 审核状态
    public function status()
    {
        $id     = input('id/d');
        $status = input('status/d');

        // 找到服务商入驻数据
        $serviceEnter = ModelServiceEnter::find($id);
        if (!$serviceEnter) {
            return success('数据不存在');
        }

        // 服务数组
        $serviceData = [
            'name'        => $serviceEnter['name'],
            'description' => $serviceEnter['introduction'],
            'image'       => $serviceEnter['logo'],
            'wechat'      => $serviceEnter['customer_qr_code'],
            'remarks'     => '二维码扫码联系',
            'phone'       => $serviceEnter['phone'],
            'url'         => $serviceEnter['url'],
            'status'      => 1,
            'category_id' => $serviceEnter['category_id'],
        ];

        $serviceDetailData = ['content'  => $serviceEnter['introduce']['content']];
        // 服务详情数组
        $imgs = $serviceEnter->featureds()->column('path');
        if ($imgs) {
            $serviceDetailData['featured'] = $this->checkPics($imgs);
        }
        // dump($imgs);
        // halt($serviceDetailData);

        ModelService::startTrans();

        try {
            $service = ModelService::create($serviceData);
            $detail = $service->detail()->save($serviceDetailData);

            // 修改审核状态
            $serviceEnter->status     = $status;
            $serviceEnter->audit_time = time();
            $result                   = $serviceEnter->save();

            // 提交事务
            ModelService::commit();
            return success('添加成功');
        } catch (\Exception $e) {
            // 回滚事务
            ModelService::rollback();
            return success($e->getMessage());
        }

    }

    // 多图处理
    protected function checkPics($data)
    {
        if (is_array($data)) {
            $pic = [];
            foreach ($data as $key => $v) {
                $pic[] = $v . "|" . $key . "||";
            }
            return implode(":::", $pic);
        }
    }

    // 修改
    public function detail()
    {
        $id           = input('id/d');
        $serviceEnter = ModelServiceEnter::with(['featureds'])->find($id);
        // halt($serviceEnter);
        return view('', ["serviceEnter" => $serviceEnter]);
    }

    // 删除
    public function del()
    {
        $id = input('id/d');
        empty($id) and jinx('参数不能为空');

        // 找出服务
        $service = S::get($id, ['detail' => function ($query) {
            $query->field('id, content, service_id');
        }]);
        empty($service) and jinx('该数据不存在');

        // 开启事务
        S::startTrans();
        try {
            $service->together('detail')->delete();
            // 提交事务
            S::commit();
            jinx('删除成功');
        } catch (\Exception $e) {
            // 回滚事务
            S::rollback();
            jinx('删除失败');
        }

    }

}
