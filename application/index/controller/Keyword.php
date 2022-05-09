<?php
namespace app\index\controller;

use app\common\model\Article as A;
use app\common\model\Keyword as K;
use library\Character;
use think\Db;
use think\facade\View;

class Keyword extends Base
{
    /*
     * 关键词首页
     *
     * view
     */
    public function index()
    {
        // PHP获取汉字首字母并分组排序
        // https://blog.csdn.net/hxl1995/article/details/78280219

        // 所有关键词
        $keyword = K::withCount(['articles' => function ($query) {
            $query->where('status', 1);
        }])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        $list = (new Character)->groupByInitials($keyword->toArray(), 'name', true);

        View::assign('list', $list);
        return view();
    }

    /*
     * 关键词关联文章
     *
     * view
     */
    public function article()
    {
        // 接收参数
        $id = input('id/d');
        empty($id) and akali('参数不能为空');

        // 找到关键词
        $keyword = K::get($id);
        empty($keyword) and akali('关键词不存在');

        // 关联文章
        $articleList = $keyword->articles()
            ->field('id, title, thumb, description, createtime')
            ->where('status', 1)
            ->limit(10)
            ->select();

        // 文章条数
        $countList = $keyword->articles()->where('status', 1)->count();

        // 猜你喜欢
        $articleLike = A::field('id, title, createtime')
            ->where([['title', 'like', '%' . $keyword['name'] . '%'], ['status', '=', 1]])
            ->limit(8)
            ->select();

        // 赋值
        View::assign([
            'keyword'     => $keyword,
            'articleList' => $articleList,
            'articleLike' => $articleLike,
            'countList'   => $countList,
        ]);

        return View::fetch();
    }

}
