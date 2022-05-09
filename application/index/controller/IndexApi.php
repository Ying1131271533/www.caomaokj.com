<?php
namespace app\index\controller;

use app\common\model\Member as B;
use app\common\model\Slide as S;
use think\Request;

class IndexApi extends BaseApi
{
    /**
     * 首页轮播图点击跳转 暂时不知道电境眼为什么要做接口判断
     *
     * @param Request $request
     * @return \think\Response
     */
    public function bannerClick(Request $request)
    {
        /**********************   接收参数   **********************/
        $id = $request->param('id/d', '');

        $banner = S::find($id);
        if (empty($banner)) {
            return $this->create(400, '获取失败');
        }

        return $this->create(200, '获取成功');
    }

    /**
     * 左导航
     *
     * @param string $type
     * @return \think\Response
     */
    public function navVisit($type)
    {
        return $this->create(400, '阿卡丽', $type);
    }

}
