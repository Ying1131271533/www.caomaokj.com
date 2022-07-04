<?php
declare (strict_types = 1);
namespace app\index\controller;

use app\common\model\ServiceEnter;
use think\Request;

class ServiceApi extends BaseApi
{
    /**
     * 服务商入驻
     *
     * @param Request $request
     * @return \think\Response
     */
    public function index(Request $request)
    {
        // 接收参数
        $param  = $request->param();
        // 验证数据
        $validate = validate('ServiceEnter');
        if (!$validate->check($param)) {
            return $this->create(400, $validate->getError());
        }

        ServiceEnter::startTrans();
        try {
            $serviceEnter = ServiceEnter::create($param);
            $introduce = $serviceEnter -> introduce()->save($param);
            $featureds = $serviceEnter -> featureds()->save($param);
            ServiceEnter::commit();
            return $this->create(200, '提交成功');
        } catch (\Exception $e) {
            ServiceEnter::rollback();
            return $this->create(400, $e->getMessage());
        }
    }
}
