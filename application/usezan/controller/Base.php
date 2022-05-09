<?php
namespace app\usezan\controller;

use app\common\model\AuthRule;
use app\common\model\Category;
use think\Controller;
use think\facade\Cache;

class Base extends Controller
{
    //自动加载
    protected $tree_id, $lang, $category;
    public function initialize()
    {
        //树菜单
        $this->tree_id = input('param.tree');
        $this->lang    = config('app.default_lang');
        //Auth-Tree
        if (isset($this->tree_id) && $this->tree_id) {
            $this->authTree($this->tree_id);
        }

        //栏目
        $this->category = (new Category)->getCacheselect();
        $this->assign('tree_id', $this->tree_id);
        $this->assign('category', $this->category);
        $this->assign('current_url', strtolower(request()->controller() . '/' . request()->action()));
        $this->assign('extend_config', Cache::store('extend')->get('extend_config'));
        //自动加载函数
        if (method_exists($this, "_uzauto")) {
            $this->_uzauto();
        }
    }

    //节点菜单
    protected function authTree($tree_id)
    {
        // $auth_tree  = Cache::store('rule')->get('auth_cache_rule');
        $auth_tree = AuthRule::field('id, name, title, uzico, parentid, issub')
            ->order('listorder desc, id asc')
            ->select()
            ->toArray();

        $user          = session("usezan_admin");
        $auth_tree_new = [];
        if (!$user['adminis'] && $user['group_id'] > 1) {
            $group = explode(',', cache('auth_group_rules_' . $user['id']));
            foreach ($auth_tree as $key => $vo) {
                if (!in_array($vo['id'], $group)) {
                    continue;
                }

                if ($vo['issub'] && $vo['parentid'] == $tree_id) {
                    $auth_tree_new[$key] = $vo;
                }
            }

        } else {
            foreach ($auth_tree as $key => $vo) {
                if ($vo['issub'] && $vo['parentid'] == $tree_id) {
                    $auth_tree_new[$key] = $vo;
                }
            }
        }

        unset($auth_tree, $user);
        $this->assign('tree_id', $tree_id);
        $this->assign('auth_tree', $auth_tree_new);
    }

}
