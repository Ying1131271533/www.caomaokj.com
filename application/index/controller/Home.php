<?php
namespace app\index\controller;

use app\common\model\Activity as Ac;
use app\common\model\Article as A;
use app\common\model\Category;
use app\common\model\College as CollegeModel;
use app\common\model\Keyword as K;
use app\common\model\Logistics as L;
use app\common\model\Slide;
use think\facade\Cache;
use think\facade\View;

class Home extends Base
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
        $banner = Slide::where(['cid' => 134, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->select();

        /**********************   右边的四个推荐文章   **********************/
        $article = A::where(['ispos' => 1, 'status' => 1])
            ->field('id, title, thumb')
            ->order(['listorder' => 'desc', 'createtime' => 'desc'])
            ->limit(4)
            ->select();

        /**********************   跨境干货 - 左下角文章分类   **********************/
        $category = Category::where(['parentid' => 38, 'status' => 1])
            ->field('id, catname')
            ->order('listorder', 'desc')
            ->select();

        /**********************   跨境干货 - 左下角文章列表   **********************/
        $leftArticle = A::with('keywords')
            ->where(['status' => 1])
            ->limit(20)
            ->page(1)
            ->order('createtime', 'desc')
            ->select();

        // 课程轮播图
        $college_list = CollegeModel::getArticleCollegeList();

        /**********************   热门文章   **********************/
        $hotArticle = A::where(['hot_spot' => 1, 'status' => 1])
            ->field('id, title')
            ->limit(10)
            ->order('listorder', 'desc')
            ->select();
        $hotNum = 1;
        foreach ($hotArticle as $key => $value) {
            $hotArticle[$key]['num'] = $hotNum;
            $hotNum++;
        }

        /**********************   最新文章   **********************/
        $newArticle = A::where(['status' => 1])
            ->field('id, title')
            ->limit(10)
            ->order('createtime', 'desc')
            ->select();
        $newNum = 1;
        foreach ($newArticle as $key => $value) {
            $newArticle[$key]['num'] = $newNum;
            $newNum++;
        }

        /**********************   关注热点 - 关键词   **********************/
        $hotKeywords = K::where(['hot_spot' => 1])
            ->field('id, name')
            ->order('sort', 'desc')
            ->limit(10)
            ->select();

        /**********************   变量赋值   **********************/
        View::assign([
            'banner'       => $banner,
            'article'      => $article,
            'category'     => $category,
            'leftArticle'  => $leftArticle,
            'college_list' => $college_list,
            'hotArticle'   => $hotArticle,
            'newArticle'   => $newArticle,
            'hotKeywords'  => $hotKeywords,
        ]);

        return View::fetch();
    }

    /**
     * 文章详情
     *
     * @param
     * @return view
     */
    public function article()
    {
        /**********************   接收参数   **********************/
        $id = input('id/d');
        empty($id) and akali('参数有误');
        
        /**********************   找出文章   **********************/
        $article = A::cache(cache_time())->get($id);
        empty($article) and akali('文章不存在');

        /**********************   文章浏览数加一   **********************/
        // $article->setInc('view');
        $article_views = Cache::get('article_views');
        // halt($article_views);
        if (!$article_views) {
            $article_views = [];
        }

        if (!isset($article_views[$id])) {
            $article_views[$id] = 0;
        }

        $article_views[$id] += 1;
        Cache::set('article_views', $article_views);

        /**********************   上一篇 和 下一篇   **********************/
        $topArticle    = A::where('id', '<', $id)->field('id, title')->order('id', 'desc')->find();
        $bottomArticle = A::where('id', '>', $id)->field('id, title, thumb,createtime')->order('id', 'asc')->find();

        /**********************   右上角三条文章   **********************/
        $articleKeyword = $article->keywords()->find();
        empty($articleKeyword) and akali('文章缺少关键词');
        $rightArticle = $articleKeyword->articles()
            ->field('id, title, createtime, thumb, catid')
            ->order('id', 'asc')
            ->limit(3)
            ->select();

        /**********************   热门文章   **********************/
        $hotArticle = A::where(['hot_spot' => 1, 'status' => 1])
            ->field('id, title, thumb, createtime')
            ->limit(10)
            ->order('listorder', 'desc')
            ->select();
        $hotNum = 1;
        foreach ($hotArticle as $key => $value) {
            $hotArticle[$key]['num'] = $hotNum;
            $hotNum++;
        }

        /**********************   最新文章   **********************/
        $newArticle = A::where(['status' => 1])
            ->field('id, title')
            ->limit(10)
            ->order('createtime', 'desc')
            ->select();
        $newNum = 1;
        foreach ($newArticle as $key => $value) {
            $newArticle[$key]['num'] = $newNum;
            $newNum++;
        }

        /**********************   猜你喜欢的文章   **********************/
        $likeArticle = $articleKeyword->articles()
            ->field('id, title, createtime')
            ->order('id', 'asc')
            ->limit(5)
            ->select();

        /**********************   关注热点 - 关键词   **********************/
        $hotKeywords = K::where('name', 'like', '%' . $article['keyword'] . '%')
            ->field('id, name')
            ->order('sort', 'desc')
            ->limit(10)
            ->select();

        /**********************   底部业务展示图   **********************/
        $banner = Slide::where(['cid' => 153, 'status' => 1])
            ->field('id, thumb')
            ->order('listorder', 'desc')
            ->find();

        /**********************   相关文章   **********************/
        $relatedArticle = $articleKeyword->articles()
            ->where('catid', $article['catid'])
            ->field('id, title, createtime, thumb, description, catid')
            ->order('listorder', 'asc')
            ->limit(9)
            ->select();

        View::assign([
            'topArticle'     => $topArticle,
            'bottomArticle'  => $bottomArticle,
            'rightArticle'   => $rightArticle,
            'hotArticle'     => $hotArticle,
            'newArticle'     => $newArticle,
            'likeArticle'    => $likeArticle,
            'hotKeywords'    => $hotKeywords,
            'banner'         => $banner,
            'relatedArticle' => $relatedArticle,
            'article'        => $article,
            // 微信自定义
            'imgUrl'         => 'https://www.caomaokj.com' . $article['thumb'],
            'desc'           => $article['description'],
        ]);
        return View::fetch();
    }
    
    /*
     * 搜索
     *
     * @return view
     */
    public function search()
    {
        /**********************   关键词   **********************/
        $keyword = input('keyword/s', '');
        empty($keyword) and akali('搜索内容不能为空');

        /**********************   选择类型   **********************/
        $select_type = input('select_type/d', 0);

        /**********************   页数   **********************/
        $page = input('page/d', 1);

        /**********************   数据   **********************/
        $articleList  = [];
        $collegeList  = [];
        $activityList = [];

        /**********************   条件   **********************/
        $where   = [];
        $where[] = ['status', '=', 1];
        $where[] = ['title', 'like', '%' . $keyword . '%'];

        /**********************   条数   **********************/
        $select_type ? $limit = 10 : $limit = 3;

        /**********************   搜索找到条数   **********************/
        $count = 0;

        /**********************   排序   **********************/
        $order = ['id' => input('sort/s', 'desc')];

        /**********************   判断选择类型   **********************/
        switch ($select_type) {
            case 0:

                /**********************   文章   **********************/
                $articleList = A::with(['keywords' => function ($query) {
                    $query->field('id, name');
                }])
                    ->field('id, title, thumb, description, createtime')
                    ->where($where)
                    ->order($order)
                    ->limit($limit)
                    ->select();

                // 文章条数
                $articleCount = A::where($where)->count();
                // dump($articleCount);return;
                $count += $articleCount;

                /**********************   活动   **********************/
                $activityList = Ac::field('id, title, thumb, address, endtime, discount')
                    ->where($where)
                    ->order($order)
                    ->limit($limit)
                    ->select();

                // 活动条数
                $activityCount = Ac::where($where)->count();
                $count += $activityCount;

                /**********************   学院   **********************/
                $collegeList = CollegeModel::field('id, title, thumb, address, end_time, discount')
                    ->where($where)
                    ->order($order)
                    ->limit($limit)
                    ->select();

                // 学院条数
                $collegeCount = CollegeModel::where($where)->count();
                $count += $collegeCount;

                break;
            case 1:

                /**********************   文章   **********************/
                $articleList = A::field('id, title, thumb, description, createtime')
                    ->where($where)
                    ->order($order)
                    ->limit($limit)
                    ->select();

                /**********************   文章条数   **********************/
                $articleCount = A::where($where)->count();
                $count += $articleCount;

                break;
            case 2:

                /**********************   活动   **********************/
                $activityList = Ac::field('id, title, thumb, address, endtime, discount')
                    ->where($where)
                    ->order($order)
                    ->page($page)
                    ->limit($limit)
                    ->select();

                /**********************   活动条数   **********************/
                $activityCount = Ac::where($where)->count();
                $count += $activityCount;

                break;
            case 3:

                /**********************   学院   **********************/
                $collegeList = CollegeModel::field('id, title, thumb, address, end_time, discount')
                    ->where($where)
                    ->order($order)
                    ->page($page)
                    ->limit($limit)
                    ->select();

                /**********************   学院条数   **********************/
                $collegeCount = CollegeModel::where($where)->count();
                $count += $collegeCount;

                break;
        }

        /**********************   热门活动   **********************/
        $activity = Ac::where(['status' => 1, 'popular' => 1])
            ->where('endtime', '>', time() + 3600 * 24 * 30)
            ->field('id, title, thumb')
            ->order('listorder', 'desc')
            ->find();

        /**********************   推荐培训   **********************/
        $college = CollegeModel::where(['status' => 1, 'recommend' => 1])
            ->field('id, title, thumb')
            ->order('listorder', 'desc')
            ->find();

        /**********************   推荐物流   **********************/
        $logistics = L::where(['status' => 1, 'recommend' => 1])
            ->field('id, title, thumb')
            ->order('listorder', 'desc')
            ->find();

        /**********************   关注热点 - 关键词   **********************/
        $hotKeywords = K::where(['hot_spot' => 1])
            ->field('id, name')
            ->order('sort', 'desc')
            ->limit(10)
            ->select();

        /**********************   文章关键词替换   **********************/
        foreach ($articleList as $key => $value) {
            $articleList[$key]['title']       = str_replace($keyword, '<em>' . $keyword . '</em>', $value['title']);
            $articleList[$key]['description'] = str_replace($keyword, '<em>' . $keyword . '</em>', $value['description']);
        }

        /**********************   活动关键词替换   **********************/
        foreach ($activityList as $key => $value) {
            $activityList[$key]['title'] = str_replace($keyword, '<em>' . $keyword . '</em>', $value['title']);
        }

        /**********************   培训关键词替换   **********************/
        foreach ($collegeList as $key => $value) {
            $collegeList[$key]['title'] = str_replace($keyword, '<em>' . $keyword . '</em>', $value['title']);
        }
        // dump($activityList);return;

        // 变量赋值
        View::assign([
            'keyword'        => $keyword,
            'keyword_search' => $keyword,
            'select_type'    => $select_type,
            'articleList'    => $articleList,
            'activityList'   => $activityList,
            'collegeList'    => $collegeList,
            'count'          => $count,
            'activity'       => $activity,
            'college'        => $college,
            'logistics'      => $logistics,
            'hotKeywords'    => $hotKeywords,
        ]);

        return view();
    }
}
