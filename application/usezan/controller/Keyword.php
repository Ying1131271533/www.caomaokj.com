<?php
namespace app\usezan\controller;

use app\common\model\Activity;
use app\common\model\ActivityKeyword;
use app\common\model\Article as A;
use app\common\model\ArticleKeyword;
use app\common\model\Keyword as ModelKeyword;
use app\common\model\Logistics as L;
use app\common\model\LogisticsKeyword as LK;
use library\Character;
use think\facade\View;

class Keyword extends Base
{
    /**
     * 初始化
     *
     * @param
     * @return View
     */
    public function _uzauto()
    {
    }

    /**
     * 关键词列表 ⎛⎝≥⏝⏝≤⎛⎝
     *
     * @param
     * @return View
     */
    public function index()
    {
        $where                        = [];
        $keyword                      = input('keyword/s');
        !empty($keyword) and $where[] = ['name', 'like', '%' . $keyword . '%'];

        $list = ModelKeyword::where($where)->order(['hot_spot' => 'desc', 'sort' => 'desc', 'id' => 'asc'])->paginate(30);
        $this->assign('list', $list);
        return view();
    }

    /**
     * 关键词添加
     *
     * @param
     * @return view
     */
    public function add()
    {
        if (request()->isPost()) {
            $names    = input("name/a");
            $hot_spot = input("hot_spot/d", 0);
            empty($names) and jinx('参数不能为空');

            // 开启事务
            // ModelKeyword::startTrans();

            $data = [];
            foreach ($names as $key => $value) {
                if (!empty($value)) {
                    $data = [
                        'name'     => $value,
                        'hot_spot' => $hot_spot,
                    ];

                    $result = ModelKeyword::where('name', $data['name'])->find();
                    if (empty($result)) {
                        $id = ModelKeyword::create($data)->getData('id');
                    }
                }
            }

            // 提交事务
            // ModelKeyword::commit();
            jinx("添加成功");
        }

        return view();
    }

    /**
     * 关键词修改
     *
     * @param
     * @return View
     */
    public function edit()
    {
        $id = input('id/d');
        empty($id) and jinx('参数不能为空');
        if (request()->isPost()) {
            $data             = input();
            $data['hot_spot'] = input('hot_spot/d');

            $result = ModelKeyword::update($data);
            empty($result) and jinx('修改失败');
            jinx('修改成功');
        }

        $keyword = ModelKeyword::get($id);
        View::assign('keyword', $keyword);

        return view();
    }

    /**
     * 关键词删除
     *
     * @param
     * @return
     */
    public function del()
    {
        /**********************   获取关键词id   **********************/
        $id = input('id/d');
        empty($id) and jinx('参数不能为空');

        /**********************   找出关键词   **********************/
        $keyword = ModelKeyword::get($id);
        empty($keyword) and jinx('找不到该关键词');

        /**********************   开启事务   **********************/
        $keyword->startTrans();
        try {
            // 删除关联数据
            $keyword->articles()->detach($id);
            // 删除关键词
            $keyword->delete();
            // 提交事务
            $keyword->commit();
            jinx('删除成功');
        } catch (\Exception $e) {
            // 回滚事务
            $keyword->rollback();
            jinx('删除失败');
        }

    }

    /**
     * 文章关键词分配
     *
     * @param
     * @return view
     */
    public function articleKeyword()
    {
        if (request()->isPost()) {
            $id       = input('id/d');
            $keywords = input('keywords/a');

            // 找出文章
            $article = A::find($id);
            // 开启事务
            $article->startTrans();
            // 查看是否拥有关键词 进行删除
            $articleKeywords = $article->keywords->column('id');
            if (count($articleKeywords) >= 1) {
                $delResult = $article->keywords()->detach($articleKeywords);
                if ($delResult === false) {
                    // 回滚事务
                    $keyword->rollback();
                    jinx('分配失败');
                }
            }

            if (!empty($keywords)) {
                $result = $article->keywords()->saveAll($keywords);
                if ($result === false) {
                    // 回滚事务
                    $keyword->rollback();
                    jinx($article->getError());
                }
            }

            // 提交事务
            $article->commit();
            jinx('保存成功');
        }

        $id = input("id/d");
        empty($id) and jinx("参数不能为空");

        // 获取所有关键词
        $keyword = ModelKeyword::field('id, name')->order('sort desc, id asc')->select();
        // 关键词分组
        $list = (new Character)->groupByInitials($keyword->toArray(), 'name');
        
        // 获取已分配的关键词
        $data = ArticleKeyword::where('article_id', $id)->column('keyword_id');
        // dump($data);return;
        View::assign('id', $id);
        View::assign('keyword', $keyword);
        View::assign('list', $list);
        View::assign('data', $data);
        return view();
    }

    /**
     * 活动关键词分配
     *
     * @param
     * @return view
     */
    public function activityKeyword()
    {
        if (request()->isPost()) {
            $id       = input('id/d');
            $keywords = input('keywords/a');

            // 找出文章
            $activity = Activity::find($id);
            // 开启事务
            $activity->startTrans();
            // 查看是否拥有关键词 进行删除
            $activityKeywords = $activity->keywords->column('id');
            if (count($activityKeywords) >= 1) {
                $delResult = $activity->keywords()->detach($activityKeywords);
                if ($delResult === false) {
                    // 回滚事务
                    $keyword->rollback();
                    jinx('分配失败');
                }
            }

            if (!empty($keywords)) {
                $result = $activity->keywords()->saveAll($keywords);
                if ($result === false) {
                    // 回滚事务
                    $keyword->rollback();
                    jinx($this->roleModel->getError());
                }
            }

            // 提交事务
            $activity->commit();
            jinx('保存成功');
        }

        $id = input("id/d");
        empty($id) and jinx("参数不能为空");

        // 获取所有关键词
        $keyword = ModelKeyword::order('sort desc, id asc')->select();
        // 关键词分组
        $list = (new Character)->groupByInitials($keyword->toArray(), 'name');

        // 获取活动已分配的关键词
        $data = ActivityKeyword::where('activity_id', $id)->column('keyword_id');

        View::assign('id', $id);
        View::assign('list', $list);
        View::assign('keyword', $keyword);
        View::assign('data', $data);
        return view('article_keyword');
    }

    /**
     * 物流服务关键词分配
     *
     * @param
     * @return view
     */
    public function logisticsKeyword()
    {
        if (request()->isPost()) {
            $id       = input('id/d');
            $keywords = input('keywords/a');

            // 找出物流资源
            $logistics = L::find($id);

            // 开启事务
            $logistics->startTrans();

            // 查看是否拥有关键词 进行删除
            $logisticsKeywords = $logistics->keywords->column('id');
            if (count($logisticsKeywords) >= 1) {
                $delResult = $logistics->keywords()->detach($logisticsKeywords);
                if ($delResult === false) {
                    // 回滚事务
                    $keyword->rollback();
                    jinx('分配失败');
                }
            }

            if (!empty($keywords)) {
                $result = $logistics->keywords()->saveAll($keywords);
                if ($result === false) {
                    // 回滚事务
                    $keyword->rollback();
                    jinx($this->roleModel->getError());
                }
            }

            // 提交事务
            $logistics->commit();
            jinx('保存成功');
        }

        $id = input("id/d");
        empty($id) and jinx("参数不能为空");

        // 获取所有关键词
        $keyword = ModelKeyword::order('sort desc, id asc')->select();
        // 关键词分组
        $list = (new Character)->groupByInitials($keyword->toArray(), 'name');

        // 获取活动已分配的关键词
        $data = LModelKeyword::where('logistics_id', $id)->column('keyword_id');

        View::assign('id', $id);
        View::assign('keyword', $keyword);
        View::assign('list', $list);
        View::assign('data', $data);
        return view('article_keyword');
    }

    /**
     * 排序
     *
     * @param
     * @return akali
     */
    public function sort()
    {
        $sort = input("sort/a");
        empty($sort) and jinx('参数不能为空');
        $data = [];
        foreach ($sort as $key => $value) {
            $data[] = [
                'id'   => $key,
                'sort' => $value,
            ];
        }
        $keyword = new K;
        $result  = $keyword->saveAll($data);
        empty($result) and jinx('排序失败');
        jinx('排序成功');
    }
}
