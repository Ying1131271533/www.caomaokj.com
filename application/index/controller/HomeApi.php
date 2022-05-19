<?php
declare (strict_types = 1);
namespace app\index\controller;

use app\common\model\Article;
use app\common\model\ArticleCollect as AC;
use app\common\model\ArticleComment;
use app\common\model\ArticleLike as AL;
use app\common\model\Category as C;
use app\common\model\Keyword as K;
use app\common\model\Member as M;
use think\cache\driver\Redis;
use think\facade\Cache;
use think\Request;

class HomeApi extends BaseApi
{
    /**
     * 左下角文章列表
     *
     * @param Request $request
     * @return \think\Response
     */
    public function index(Request $request)
    {
        /**********************   接收参数   **********************/
        $catid = $request->param('category/d', 0);

        /**********************   获取文章分类   **********************/
        $categoryData = C::where(['parentid' => 38, 'status' => 1])
            ->field('id, catname')
            ->order('listorder', 'desc')
            ->select();
        $articleCategory = [];
        foreach ($categoryData as $key => $value) {
            $articleCategory[$value['id']] = $value['catname'];
        }

        /**********************   获取文章数据   **********************/
        $where              = [];
        $where[]            = ['status', '=', 1];
        $where[]            = ['type', '=', 0];
        $catid and $where[] = ['catid', '=', $catid];
        $article            = Article::where($where)->limit($this->pageSize)->page($this->page)->field('*')->with(['keywords' => function ($query) {
            $query->field('id, name');
        }])->order('createtime', 'desc')->select();

        /**********************   获取文章总条数   **********************/
        $count = $leftArticle = Article::where($where)->count();

        /**********************   重组文章数据 适应前端   **********************/
        $articleData = [];
        foreach ($article as $key => $value) {

            $keywords = [];
            foreach ($value['keywords'] as $k => $val) {
                $keywords[] = [
                    'keyword_id'   => $val['id'],
                    'keyword_name' => $val['name'],
                ];
            }

            $articleData[] = [
                'ac_id'             => $value['catid'], // 文章分类id
                'activity_id'       => $value['activity_id'], // 活动id
                'add_name'          => '草帽跨境', // 发布作者
                'al_click_num'      => $value['view'], // 点击次数
                'al_collect_num'    => $value['collect_num'], // 收藏次数
                'al_comment_num'    => $value['comment_num'], // 评论次数
                'al_desc'           => $value['description'], // 描述
                'al_id'             => $value['id'], // 文章id
                'al_is_hot_article' => $value['ispos'], // 是否热点新闻
                'al_like_num'       => $value['like_num'], // 点赞次数
                'al_post_time'      => postTime($value['createtime']), // 发布时间
                'al_post_time_m'    => postTime($value['createtime']), // 发布时间
                'al_thumb'          => $value['thumb'], // 封面
                'al_title'          => $value['title'], // 标题
                'al_type'           => $value['type'], // 文章类型
                'keywords'          => $keywords, // 关键词
                'user_thumb'        => '/static/icon/icon-caomao-small.png', // 草帽头像
            ];
        }

        /**********************   返回API数据   **********************/
        $resultData = [
            'articleCategory' => $articleCategory,
            'count'           => $count,
            'articleData'     => $articleData,
        ];
        return $this->create(200, '获取成功', $resultData);
    }

    /**
     * 评论文章 评论/回复
     *
     * @param
     * @return \think\Response
     */
    public function articleComment(Request $request)
    {
        // 是否有登录
        if (empty($this->userid)) {
            return $this->create(300, '请先登录');
        }

        // 接收参数
        $params = $request->param();

        // 找出文章
        $article = Article::find($params['id']);
        // halt($article);
        if (!$article) {
            return $this->create(400, '文章不存在');
        }

        // 组装数据
        $data = [
            'content'     => $params['comment'],
            'parentid'    => $params['pid'],
            'status'      => 1,
            'create_time' => time(),
            'article_id'  => $params['id'],
        ];

        // 保存评论
        $result = $article->comments()->attach($this->userid, $data);
        if (!$result) {
            return $this->create(400, '评论发表失败~');
        }

        // 返回数据
        return $this->create(200, '评论发表成功');

    }

    /**
     * 获取文章评论
     *
     * @param
     * @return json
     */
    public function getArticleComment(Request $request)
    {
        $id = $request->param('id/d', 0);
        if (empty($id)) {
            return $this->create(400, '获取失败');
        }

        /**********************   找出文章   **********************/
        $article = Article::with(['comments' => function ($query) {
            $query->order('id', 'desc');
        }])->get($id);
        // halt($article);
        if (empty($article)) {
            return $this->create(400, '文章不存在');
        }

        $commentsData = [];
        foreach ($article->comments as $key => $comment) {
            $comment -> member;
            $commentsData[$key] = [
                'acom_add_time'  => date('Y-m-d', $comment['create_time']),
                'acom_comment'   => $comment['content'],
                'acom_id'        => $comment['id'],
                'acom_parent_id' => $comment['parentid'],
                'al_id'          => $article['id'],
                'user_id'        => $comment['member']['id'],
                'user_name'      => $comment['member']['username'],
                'time'           => postTime($comment['create_time']),
                'user_head'      => $comment['member']['avatar'],
            ];
            
            $commentsData[$key]['parent'] = [];
            foreach ($article->comments as $val) {
                if ($comment['parentid'] == $val['id']) {
                    $commentsData[$key]['parent'] = [
                        'user_id'      => $val['member']['id'],
                        'user_name'    => $val['member']['username'],
                        'acom_comment' => $val['content'],
                    ];
                    continue;
                }
            }
        }
        
        $resultData = [
            'count'    => count($commentsData),
            'comments' => $commentsData,
        ];
        return $this->create(200, '获取成功', $resultData);
    }

    /**
     * 文章收藏
     *
     * @param
     * @return \think\Response
     */
    public function articleCollect($id)
    {
        if (!validate()->isInteger($id)) {
            return $this->create(400, '参数错误');
        }

        /**********************   是否有登录   **********************/
        if (empty($this->userid)) {
            return $this->create(204, '请先登录');
        }

        /**********************   找出文章   **********************/
        $article = Article::get($id);
        if (empty($article)) {
            return $this->create(400, '文章不存在');
        }

        /**********************   用户是否已收藏   **********************/
        $userLike = $article->collects()->find($this->userid);
        if (!empty($userLike)) {
            return $this->create(400, '您已经收藏了，休息一下吧~~');
        }

        /**********************   开启事务   **********************/
        $article->startTrans();

        /**********************   保存用户收藏   **********************/
        $articleResult = $article->collects()->save($this->userid, ['create_time' => time()]);
        /**********************   文章收藏加一   **********************/
        $incResult = $article->setInc('collect_num');

        // 是否都修改了数据库
        if (!$articleResult || !$incResult) {
            $article->rollback();
            return $this->create(400, '收藏失败~~');
        }

        $article->commit();
        /**********************   返回数据   **********************/
        return $this->create(200, '收藏成功', $article->collect_num);
    }

    /**
     * 文章点赞
     *
     * @param
     * @return \think\Response
     */
    public function articleLike($id)
    {
        if (!validate()->isInteger($id)) {
            return $this->create(400, '参数错误');
        }

        /**********************   是否有登录   **********************/
        if (empty($this->userid)) {
            return $this->create(204, '请先登录');
        }

        /**********************   找出文章   **********************/
        $article = Article::get($id);
        if (empty($article)) {
            return $this->create(400, '文章不存在');
        }

        /**********************   用户是否已点赞   **********************/
        $userLike = $article->likes()->find($this->userid);
        if (!empty($userLike)) {
            return $this->create(400, '您已经点赞了，休息一下吧~~');
        }

        /**********************   开启事务   **********************/
        $article->startTrans();

        /**********************   保存用户点赞   **********************/
        $articleResult = $article->likes()->save($this->userid);
        /**********************   文章点赞加一   **********************/
        $incResult = $article->setInc('like_num');

        // 是否都修改了数据库
        if (!$articleResult || !$incResult) {
            $article->rollback();
            return $this->create(400, '点赞失败~~');
        }

        $article->commit();
        /**********************   返回数据   **********************/
        return $this->create(200, '点赞成功', $article->collect_num);

    }

    /**
     * 物流推荐文章
     *
     * @param Request $request
     * @return \think\Response
     */
    public function recommendArticle(Request $request)
    {
        /**********************   接收参数   **********************/
        $title = $request->param('title/s', '');

        /**********************   获取文章数据   **********************/
        $where   = [];
        $where[] = ['status', '=', 1];
        $where[] = ['catid', '=', 68];
        $article = Article::where($where)
            ->field('id, title')
            ->limit(10)
            ->order(['listorder' => 'desc', 'id' => 'desc'])
            ->select();

        return $this->create(200, '获取成功', $article);
    }

    /**
     * 关键词关联文章
     *
     * @param Request $request
     * @return \think\Response
     */
    public function keywordArticle(Request $request)
    {
        /**********************   接收参数   **********************/
        $page      = $request->param('page/d', '');
        $keywordId = $request->param('keywordId/d', '');

        /**********************   参数是否有误   **********************/
        if (empty($page) || empty($keywordId)) {
            return $this->create(400, '参数有误');
        }

        /**********************   找到关键词   **********************/
        $keyword = K::get($keywordId);
        if (empty($keyword)) {
            return $this->create(400, '关键词不存在');
        }

        /**********************   关联文章   **********************/
        $articleData = $keyword->articles()
            ->field('id, title, thumb, description, createtime')
            ->where('status', 1)
            ->page($page)
            ->limit(10)
            ->select();

        /**********************   是否有数据   **********************/
        if (empty($articleData)) {
            return $this->create(204, '没有数据');
        }

        /**********************   重组文章数据 适应前端   **********************/
        $articleList = [];
        foreach ($articleData as $key => $value) {
            $articleList[] = [
                'al_id'        => $value['id'], // 文章id
                'al_desc'      => $value['description'], // 描述
                'al_post_time' => postTime($value['createtime']), // 发布时间
                'al_thumb'     => $value['thumb'], // 封面
                'al_title'     => $value['title'], // 标题
            ];
        }

        return $this->create(200, '获取成功', $articleList);
    }

    /**
     * 搜索 - 文章
     *
     * @param Request $request
     * @return \think\Response
     */
    public function searchArticle(Request $request)
    {
        /**********************   接收参数   **********************/
        $page        = $request->param('page/d');
        $keyword     = $request->param('keyword/s', '');
        $select_type = $request->param('select_type/d', 1);

        /**********************   排序   **********************/
        $order = ['id' => input('sort/s', 'desc')];

        /**********************   条件   **********************/
        $where   = [];
        $where[] = ['status', '=', 1];
        $where[] = ['title', 'like', '%' . $keyword . '%'];

        /**********************   参数是否有误   **********************/
        if (empty($page) || empty($keyword) || empty($select_type)) {
            return $this->create(400, '参数有误');
        }

        /**********************   文章   **********************/
        $articleData = Article::with(['keywords' => function ($query) {
            $query->field('id, name');
        }])
            ->field('id, title, thumb, description, createtime')
            ->where($where)
            ->order($order)
            ->page($page)
            ->limit(10)
            ->select();
        if ($articleData->isEmpty()) {
            return $this->create(204, '没有资源');
        }

        /**********************   条数   **********************/
        $count = Article::where($where)->order($order)->count();

        /**********************   重组文章数据 适应前端   **********************/
        $articleList = [];
        foreach ($articleData as $key => $value) {
            $articleList[] = [
                'url'           => url('home/article', ['id' => $value['id']]), // 文章url
                'desc'          => str_replace($keyword, '<em>' . $keyword . '</em>', $value['description']), // 描述
                'add_time'      => postTime($value['createtime']), // 发布时间
                'logo'          => $value['thumb'], // 封面
                'title'         => str_replace($keyword, '<em>' . $keyword . '</em>', $value['title']), // 标题
                'add_user_name' => '草帽跨境', // 作者
                'extra_info'    => $value['keywords'], // 关键词
            ];
        }

        /**********************   返回api接口数据   **********************/
        $resultData = [
            'article' => $articleList,
            'count'   => $count,
        ];

        return $this->create(200, '获取成功', $resultData);
    }

    // 脚本定时更新文章浏览量
    public function update_view()
    {
        $article_views = Cache::get('article_views');
        foreach ($article_views as $key => $value) {
            Article::update(['id' => $key, 'view' => $value]);
        }
        Cache::rm('article_views');
    }

}
