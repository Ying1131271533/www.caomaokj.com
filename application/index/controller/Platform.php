<?php
namespace app\index\controller;

use app\common\model\Continent as C;
use app\common\model\Platform as P;
use app\common\model\PlatformDetailType as PDT;
use app\common\model\PlatformJoin as J;
use app\common\model\Slide;
use think\facade\View;

class Platform extends Base
{
    /*
     * 入驻平台列表
     *
     * index
     */
    public function index()
    {
        /**********************   电脑展示图   **********************/
        $banner = Slide::where(['cid' => 138, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->find();

        /**********************   手机展示图   **********************/
        $phoneBanner = Slide::where(['cid' => 139, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->find();

        /**********************   电商平台   **********************/
        $id = input('id/d', '');
        if (!empty($id)) {
            $continent = C::get($id);
            $platform  = $continent->platforms()->where('status', 1)->order('sort', 'desc')->select();
        } else {
            $platform = P::where('status', 1)->order('sort', 'desc')->select();
        }

        /**********************   视图变量赋值   **********************/
        View::assign([
            'banner'      => $banner,
            'phoneBanner' => $phoneBanner,
            'id'          => $id,
            'platform'    => $platform,
            'toolbar'     => true, // 手机端显示底部工具栏
        ]);

        return view();
    }

    /*
     * 电商平台详情
     *
     * detail
     */
    public function detail()
    {
        $id = input('id/d');
        empty($id) and akali('参数有误');

        // 平台
        $platform = P::field('*')->with(['details' => function ($query) {
            $query->field('type, image, platform_id');
        }, 'continents' => function ($query) {
            $query->field('id');
        }])->find($id);

        // 详情图片
        $details = [];
        foreach ($platform['details'] as $value) {
            $details[$value['type']] = $value['image'];
        }

        // 详情图片类型
        $detailType = PDT::select();

        // dump($continentsId);
        // return;

        $this->assign("platform", $platform);
        $this->assign("details", $details);
        $this->assign("detailType", $detailType);
        return view();
    }

    /*
     * 入驻申请
     *
     * join
     */
    public function join()
    {

        /**********************   电商平台id   **********************/
        $id = input('id/d');
        empty($id) and akali('参数有误');

        /**********************   电商平台   **********************/
        $platform = P::field('id, name')->find($id);

        View::assign('platform', $platform);
        return view();
    }

}
