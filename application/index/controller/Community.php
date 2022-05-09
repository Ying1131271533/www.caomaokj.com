<?php
namespace app\index\controller;

use app\common\model\Community as ModelCommunity;
use app\common\model\CommunityDetail;
use app\common\model\Slide;
use app\index\logic\Community as LogicCommunity;

class Community extends Base
{
    /*
     * 行业社群列表
     *
     * index
     */
    public function index()
    {
        /**********************   轮播图   **********************/
        $banner = Slide::where(['cid' => 150, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->select();

        $list = LogicCommunity::getCommunityList();
        
        return view('', [
            'banner' => $banner,
            'list'   => $list,
        ]);
    }

    /*
     * 行业社群详情
     *
     * detail
     */
    public function detail($id)
    {
        $community = ModelCommunity::with(['detail', 'imgs'])
        ->cache(true, cache_time('one_week'))
        ->get($id);

        // halt($community);
        return view('', ['community' => $community]);
    }

}
