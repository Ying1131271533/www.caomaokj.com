<?php
namespace app\index\controller;

use app\common\model\Article;
use app\common\model\Attributes as A;
use app\common\model\Category as C;
use app\common\model\Logistics as ModelLogistics;
use app\common\model\LogisticsServiceType as LS;
use app\index\logic\Logistics as LogicLogistics;
use think\facade\View;

class Logistics extends Base
{
    /*
     * 物流资源列表
     *
     * index
     */
    public function index()
    {
        // 获取参数
        $params = request()->param();
        // 获取物流数据
        $data = LogicLogistics::getLogisticsData($params);
        // 视图赋值
        return view('', $data);
    }

    /*
     * 物流资源详情
     *
     * detail
     */
    public function detail()
    {
        /**********************   接收参数   **********************/
        $id = input('id/d');
        empty($id) and akali('参数错误');

        /**********************   跨境物流资源数据   **********************/
        $logistics = ModelLogistics::where('status', 1)
            ->field('id, title, thumb, address, qq, phone, email, wechat, content')
            ->get($id);
        empty($logistics) and akali('物流资源不存在');

        /**********************   新闻中心   **********************/
        $article = Article::where(['status' => 1, 'catid' => 68])
            ->field('id, title, thumb, description, createtime')
            ->limit(3)
            ->order(['listorder' => 'desc', 'id' => 'desc'])
            ->select();

        /**********************   变量赋值   **********************/
        View::assign('logistics', $logistics);
        View::assign('article', $article);
        return view();
    }

}
