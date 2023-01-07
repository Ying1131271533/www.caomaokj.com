<?php
namespace app\index\controller;

use app\common\model\Activity;
use app\common\model\Article;
use app\common\model\Category;
use app\common\model\College;
use app\common\model\Links;
use app\common\model\Logistics;
use app\common\model\Platform;
use app\common\model\Service;
use app\common\model\Slide;
use app\index\logic\Index as LogicIndex;
use library\Crypt;
use think\facade\View;

class Index extends Base
{
    /**
     * 首页
     *
     * @param
     * @return view
     */
    public function index()
    {
        /**********************   轮播图   **********************/
        $banner = Slide::where(['cid' => 126, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->select();

        /**********************   轮播图右边 两个推荐文章   **********************/
        $hotArticle = Article::where(['status' => 1])
        // $hotArticle = Article::where(['ispos' => 1, 'status' => 1])
            ->field('id, title, thumb')
            ->order(['id' => 'desc'])
            ->limit(2)
            ->select();

        /**********************   推荐活动   **********************/
        $activity = Activity::where(['status' => 1])
            // ->where('endtime', '>', time())
            ->order('listorder', 'desc')
            ->limit(4)
            ->select();

        /**********************   推荐服务   **********************/
        $serviceList = LogicIndex::getServiceList();
        // halt($serviceList);
        
        /**********************   跨境干货 - 左下角文章分类   **********************/
        $category = Category::where(['parentid' => 38, 'status' => 1])
            ->field('id, catname')
            ->order('listorder', 'desc')
            ->select();

        /**********************   跨境干货 - 左下角文章列表   **********************/
        $leftArticle = Article::with('keywords')
            ->where(['status' => 1])
            ->limit(20)
            ->page(1)
            ->order('createtime', 'desc')
            ->select();

        /**********************   推荐培训   **********************/
        $college = College::where(['status' => 1])
            ->where('end_time', '>', time())
            ->order('listorder', 'desc')
            ->limit(3)
            ->select();

        /**********************   推荐物流   **********************/
        $logistics = Logistics::where(['status' => 1])
            ->order('listorder', 'desc')
            ->limit(3)
            ->select();

        /**********************   底部公告   **********************/
        $announcement = Slide::where(['cid' => 154, 'status' => 1])
            ->field('id, thumb, title, url, status')
            ->order('listorder', 'desc')
            ->find();

        /**********************   友情链接   **********************/
        $links = Links::where('status', 1)->order('listorder', 'desc')->select();

        /**********************   变量赋值   **********************/
        View::assign([
            'serviceData'   => $serviceList,
            'banner'        => $banner,
            'hotArticle'    => $hotArticle,
            'category'      => $category,
            'leftArticle'   => $leftArticle,
            'activity'      => $activity,
            'college'       => $college,
            'logistics'     => $logistics,
            'announcement'  => $announcement,
            'links'         => $links,
            'toolbar'       => true, // 手机端显示底部工具栏
        ]);

        return $this->fetch();
    }

    /**
     * 关于我们
     *
     * @param
     * @return view
     */
    public function about()
    {
        /**********************   轮播图   **********************/
        $banner = Slide::where(['cid' => 152, 'status' => 1])->order('listorder', 'desc')->find();
        View::assign('banner', $banner);
        return View::fetch();
    }

    public function akali()
    {
        $key = Crypt::key();
        dump($key);exit;
    }

}
