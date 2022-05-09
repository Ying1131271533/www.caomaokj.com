<?php
namespace app\index\controller;

use app\common\model\Category as C;
use app\common\model\Service as S;
use app\common\model\Slide;
use think\facade\View;

class Service extends Base
{
    /*
     * 服务列表
     *
     * index
     */
    public function index()
    {
        // 获取分类id
        $category_id = input('category_id');

        // 找出服务分类
        $list = C::field('id, catname')
            ->with(['services' => function ($query) {
                $query->field('id, name, description, image, category_id')->where('status', 1);
            }])
            ->order(['listorder' => 'asc', 'id' => 'asc'])
            ->where(['status' => 1, 'parentid' => $category_id])
            ->select();
        
        /**********************   轮播图   **********************/
        $banner = Slide::where(['cid' => 150, 'status' => 1])
            ->field('id, thumb, title, url')
            ->cache(true, cache_time())
            ->order('listorder', 'desc')
            ->select();

        View::assign([
            'list'    => $list,
            'banner'  => $banner,
            'toolbar' => true, // 手机端显示底部工具栏
        ]);
        return view();
    }

    /*
     * 服务详情
     *
     * detail
     */
    public function detail()
    {
        // 接收参数
        $id = input('id/d');
        empty($id) and akali('参数不能为空');

        // 找出服务
        $service = S::get($id, 'detail');
        empty($id) and akali('数据不存在');

        // 主推服务
        if ($service['detail']['featured']) {
            $imgs      = explode(':::', $service['detail']['featured']);
            $imgs_data = [];
            foreach ($imgs as $key => $vo) {
                $imgs_data[$key] = explode("|", $vo);
            }

            //重新排序组合
            $timeKey = array_column($imgs_data, 1);
            if (!empty($timeKey)) {
                array_multisort($timeKey, SORT_ASC, $imgs_data);
            }
            $service['detail']['featured'] = $imgs_data;
            unset($imgs, $imgs_data);
        }

        View::assign('service', $service);
        return view();
    }

}
