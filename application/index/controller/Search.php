<?php
namespace app\index\controller;

use app\common\model\Activity as Ac;
use app\common\model\Article as A;
use app\common\model\College as C;
use app\common\model\Keyword as K;
use app\common\model\Logistics as L;
use think\facade\View;

class Search extends Base
{
    /*
     * 搜索
     *
     * index
     */
    public function index()
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
                $collegeList = C::field('id, title, thumb, address, end_time, discount')
                    ->where($where)
                    ->order($order)
                    ->limit($limit)
                    ->select();

                // 学院条数
                $collegeCount = C::where($where)->count();
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
                $collegeList = C::field('id, title, thumb, address, end_time, discount')
                    ->where($where)
                    ->order($order)
                    ->page($page)
                    ->limit($limit)
                    ->select();

                /**********************   学院条数   **********************/
                $collegeCount = C::where($where)->count();
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
        $college = C::where(['status' => 1, 'recommend' => 1])
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
            'keyword'      => $keyword,
            'select_type'  => $select_type,
            'articleList'  => $articleList,
            'activityList' => $activityList,
            'collegeList'  => $collegeList,
            'count'        => $count,
            'activity'     => $activity,
            'college'      => $college,
            'logistics'    => $logistics,
            'hotKeywords'  => $hotKeywords,
        ]);

        return view();
    }
}
