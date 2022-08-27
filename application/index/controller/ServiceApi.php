<?php
declare (strict_types = 1);
namespace app\index\controller;

use app\common\model\Category;
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
    public function enter(Request $request)
    {
        // 接收参数
        $param = $request->param();
        // halt($param);
        // 验证数据
        $validate = validate('ServiceEnter');
        if (!$validate->check($param)) {
            return $this->create(400, $validate->getError());
        }

        // 获取分类
        $category = Category::find($param['category_id']);
        if (!$category) {
            return $this->create(204, '分类不存在');
        }

        $serviceEnter = ServiceEnter::whereOr('name', $param['name'])
            // ->whereOr('phone', $param['phone'])
            ->whereIn('status', '0,1')
            ->find();
        if($serviceEnter){
            return $this->create(204, '您已申请过了');
        }

        // 助推服务图片
        $service_enter_featured = [];
        if(isset($param['service_enter_featured'])){
            foreach ($param['service_enter_featured'] as $key => $value) {
                if ($key > 3) break;
                $service_enter_featured[]['path'] = $value;
            }
        }

        ServiceEnter::startTrans();

        $serviceEnter = ServiceEnter::create($param);
        $introduce = $serviceEnter->introduce()->save(['content' => $param['service_introduce']]);
        if (!$serviceEnter || !$introduce) {
            ServiceEnter::rollback();
            return $this->create(400, '提交失败');
        }
        if(isset($param['service_enter_featured'])){
            $featureds    = $serviceEnter->featureds()->saveAll($service_enter_featured);
            if (!$featureds) {
                ServiceEnter::rollback();
                return $this->create(400, '提交失败');
            }
        }
        

        ServiceEnter::commit();
        return $this->create(200, '提交成功，请等待工作人员审核');
    }
}
