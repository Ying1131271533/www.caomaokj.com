<?php
namespace app\usezan\controller;

use app\common\model\Article as ModelArticle;
use app\common\model\ArticleKeyword as AK;
use app\common\model\Category as C;
use app\common\model\Keyword as K;
use app\usezan\model\Article as A;
use library\Character;
use libs\Tree;
use think\facade\View;

class Article extends Base
{
    protected $article, $module;
    public function _uzauto()
    {
        $this->module  = 5;
        $this->article = new A();
    }

    // 列表
    public function index()
    {
        $where = [];
        if (request()->isPost()) {
            $data                                 = input();
            !empty($data['keyword']) and $where[] = ['title', 'like', '%' . $data['keyword'] . '%'];

            if (!empty($data['ispos'])) {
                $data['ispos'] == "1" ? $where[] = ['ispos', '=', 0] : $where[] = ['ispos', '=', 1];
            }

            if (!empty($data['hot_spot'])) {
                $data['hot_spot'] == "1" ? $where[] = ['hot_spot', '=', 0] : $where[] = ['hot_spot', '=', 1];
            }

        }

        $list = A::where($where)->order(['id' => 'desc'])->paginate(30);

        $this->assign("list", $list);
        $this->assign('ispos', input('ispos/d', ''));
        $this->assign('hot_spot', input('hot_spot/d', ''));
        return $this->fetch();
    }

    //添加
    public function add()
    {
        if (request()->isPost()) {
            $data     = input();
            $keywords = input('keywords/a');
            $validate = validate('Article');
            if (!$validate->check($data)) {
                return jinx($validate->getError());
            }
            // halt($keywords);
            // 开启事务
            $this->article->startTrans();

            // 保存文章
            $id = $this->article->save_type($data, false);
            /*if ($aid) {
            $this->setUrl($data['catid'], $aid);
            jinx('添加成功');
            } else {
            jinx('添加失败');
            }*/
            if (empty($id)) {
                // 回滚事务
                $this->article->rollback();
                jinx('文章添加失败');
            }

            // 找出文章
            $article = A::find($id);
            // 保存关键词
            $result = $article->keywords()->saveAll($keywords);
            if ($result === false) {
                // 回滚事务
                $this->article->rollback();
                jinx($article->getError());
            }

            // 提交事务
            $this->article->commit();
            jinx('保存成功');
        }

        //栏目
        /*$categorys = $this->category ? $this->category : [];
        if ($categorys) {
        foreach ($categorys as $vo) {
        if ($vo['status'] && intval($vo['module']) === $this->module) {
        $array[] = $vo;
        }
        continue;
        }
        $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree             = new Tree($array);
        $select_categorys = $tree->get_tree(0, $str, 0);
        $this->assign('select_categorys', $select_categorys);
        }*/

        // 分类
        $category = C::field('id, catname')
            ->where(['parentid' => 38, 'status' => 1])
            ->order(['listorder' => 'desc', 'id' => 'asc'])
            ->select();

        View::assign("category", $category);

        // 获取所有关键词
        $keyword = K::field('id, name')->order('sort desc, id asc')->select();
        // 关键词分组
        $list = (new Character)->groupByInitials($keyword->toArray(), 'name');

        View::assign('list', $list);

        return $this->fetch();
    }

    //添加
    public function akali()
    {
        if (request()->isPost()) {
            $data     = input();
            $validate = validate('Article');
            if (!$validate->check($data)) {
                return jinx($validate->getError());
            }

            $aid = $this->article->save_type($data, false);
            if ($aid) {
                $this->setUrl($data['catid'], $aid);
                jinx('添加成功');
            } else {
                jinx('添加失败');
            }
        }

        //栏目
        /*$categorys = $this->category ? $this->category : [];
        if ($categorys) {
        foreach ($categorys as $vo) {
        if ($vo['status'] && intval($vo['module']) === $this->module) {
        $array[] = $vo;
        }
        continue;
        }
        $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree             = new Tree($array);
        $select_categorys = $tree->get_tree(0, $str, 0);
        $this->assign('select_categorys', $select_categorys);
        }*/

        // 分类
        $category = C::field('id, catname')
            ->where(['parentid' => 38, 'status' => 1])
            ->order(['listorder' => 'desc', 'id' => 'asc'])
            ->select();

        View::assign("category", $category);
        return $this->fetch();
    }

    //修改
    public function edit()
    {
        $id = input("get.id");
        if (request()->isPost()) {
            $data = input();
            // halt($data);
            // 验证数据
            $validate = validate('Article');
            if (!$validate->check($data)) {
                return jinx($validate->getError());
            }

            // 如果旧图片和新图片不一样则删除图片文件
            $data = del_old_img($data);

            $article = $this->article->find($id);
            $result = $article->save($data);
            if(!$article){
                jinx('修改失败');
            }
            // $this->article->where('id', $data['id'])->cache(true)->save($data);
            // $this->setUrl($data['catid'], $data['id']);
            jinx('修改成功');
        }
        if (!$id) {
            jinx("缺少必要参数");
        }

        $article = $this->article->get_find($id);

        //category
        /*foreach ($this->category as $vo) {
        if ($vo['status'] && intval($vo['module']) === $this->module) {
        $vo['selected'] = $vo['id'] == $article['catid'] ? 'selected' : '';
        $array[]        = $vo;
        }
        continue;
        }
        $str              = "<option value='\$id' \$selected>\$spacer \$catname</option>";
        $tree             = new Tree($array);
        $select_categorys = $tree->get_tree(0, $str, $article['catid']);
        $this->assign('select_categorys', $select_categorys);*/

        // 分类
        $category = C::field('id, catname')
            ->where(['parentid' => 38, 'status' => 1])
            ->order(['listorder' => 'desc', 'id' => 'asc'])
            ->select();

        View::assign("category", $category);

        $this->assign("article", $article);
        return $this->fetch();
    }

    //url
    protected function setUrl($catid, $aid)
    {
        $parturl = $this->category[$catid]['url'];
        $data    = [
            'id'  => $aid,
            'url' => '/' . $parturl . '/show/' . $aid . '.html',
        ];
        $this->article->set_field($data);
    }

    //排序
    public function listorder()
    {
        $listorders = input('post.listorders/a');
        if (empty($listorders)) {
            jinx("缺少必要参数");
        }

        //遍历更新
        $data = [];
        foreach ($listorders as $k => $v) {
            $data[$k] = [
                'id'        => $k,
                'listorder' => $v,
            ];
        }
        $this->article->save_all($data);
        jinx("更新排序成功(ˇˍˇ)");
    }

    //删除
    public function del()
    {
        $id = input('get.id');
        if (!$id) {
            jinx(lang('do_empty'));
        }

        $thumb = $this->article->get_value(['id' => $id], 'thumb');
        $del   = $this->article->del($id);
        if ($del) {
            if ($thumb) {
                del_oldthumb($thumb);
            }

            jinx(lang('delete_ok'));
        } else {
            jinx(lang('delete_error'));
        }
    }

}
