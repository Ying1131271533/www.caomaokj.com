<?php
namespace app\index\controller;

use app\common\model\Continent as C;
use app\common\model\Platform as P;
use app\common\model\PlatformJoin as J;
use app\index\controller\CodeApiController as CodeApi;
use think\Request;

class PlatformApi extends BaseApi
{
    /**
     * 用户入驻信息保存
     *
     * @param Request $request
     * @return \think\Response
     */
    public function index(Request $request)
    {
        /**********************   接收参数   **********************/
        $data = $request->param();

        /**********************   电商平台   **********************/
        $id = input('c_id/d', '');
        if (!empty($id)) {
            $continent = C::get($id);
            $platform  = $continent->platforms()->where('status', 1)->order('sort', 'desc')->select();
        } else {
            $platform = P::where('status', 1)->order('sort', 'desc')->select();
        }

        /**********************   没有数据   **********************/
        if (empty($platform)) {
            return $this->create(204, '没有数据');
        }

        /**********************   返回数据   **********************/
        return $this->create(200, '获取成功', $platform);
    }

    /**
     * 用户入驻信息保存
     *
     * @param Request $request
     * @return \think\Response
     */
    public function join(Request $request)
    {
        /**********************   接收参数   **********************/
        $data = $request->param();

        /**********************   验证数据   **********************/
        $validate = validate('Platform');
        if (!$validate->check($data)) {
            return $this->create(400, $validate->getError());
        }

        /**********************   检查登录短信验证码   **********************/
        $smsResult = (new CodeApi)->checkCode($data['phone'], $data['code'], 6);
        if ($smsResult['code'] !== 0) {
            return $this->create(400, $smsResult['msg']);
        }

        /**********************   保存用户入驻信息   **********************/
        $result = J::create($data);
        if (empty($result)) {
            return $this->create(400, '提交失败');
        }

        /**********************   返回数据   **********************/
        $resultData = [
            'url' => url('customer/platform'),
        ];
        return $this->create(200, '提交成功', $resultData);
    }
}
