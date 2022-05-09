<?php
declare (strict_types = 1);
namespace app\index\controller;

use app\common\model\Logistics as L;
use think\Request;

class LogisticsApi extends BaseApi
{
    /**
     * 推荐物流
     *
     * @param Request $request
     * @return \think\Response
     */
    public function recommendLogistics(Request $request)
    {
        /**********************   接收参数   **********************/
        $data  = $request->param('request', '');
        $limit = $request->param('limit/d', 0);

        /**********************   获取文章数据   **********************/
        $where     = [];
        $where[]   = ['status', '=', 1];
        $logistics = L::where($where)->field('id, title, thumb')->limit($limit)->order('listorder', 'desc')->select();

        return $this->create(200, '获取成功', $logistics);
    }
}
