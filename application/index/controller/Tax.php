<?php
namespace app\index\controller;

use app\common\model\Category as C;
use app\common\model\Service as S;
use app\common\model\Slide;
use think\facade\View;

class Tax extends Base
{
    /*
     * 服务列表
     *
     * index
     */
    public function index()
    {
        $category_id = input('category_id');
        // 找出服务分类
        $list = C::field('id, catname')
            ->with(['services' => function ($query) {
                $query->field('id, name, description, image, category_id')->where('status', 1);
            }])
            ->order(['listorder' => 'asc', 'id' => 'asc'])
            ->where(['status' => 1, 'parentid' => 158])
            ->select();

        /**********************   轮播图   **********************/
        $banner = Slide::where(['cid' => 150, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->select();

        View::assign([
            'list'    => $list,
            'banner'  => $banner,
            'toolbar' => true, // 手机端显示底部工具栏
        ]);

        return view();
    }


}
