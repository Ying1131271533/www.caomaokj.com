<?php
namespace app\usezan\controller;

use app\common\model\Category;
use app\common\model\Community as ModelCommunity;
use app\common\model\CommunityDetail;
use app\usezan\logic\Community as LogicCommunity;
use think\Request;

class Community extends Base
{
    /**
     * 行业社群列表
     *
     * @param  Request      $request    请求对象
     * @return objct        $list       行业社群数据分页
     */
    public function index(Request $request)
    {
        $params = $request -> param();
        $list = LogicCommunity::getCommunityList($params);
        
        return view('', ['list' => $list, 'status' => input('status', 1)]);
    }

    /**
     * 行业社群添加
     *
     * @param  Request      $request    请求对象
     * @return objct        view()      视图
     */
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $params = $request->param();
            LogicCommunity::saveCommunity($params);
        }
        return view();
    }

    /**
     * 行业社群修改
     *
     * @param  Request      $request    请求对象
     * @return objct        view()      视图
     */
    public function edit(Request $request)
    {
        $params = $request->param();
        if ($request->isPost()) {
            $params = $request->param();
            LogicCommunity::saveCommunity($params);
        }
        
        $community = ModelCommunity::with(['detail', 'imgs'])->get($params['id']);
        return view('', ['community' => $community]);
    }

    /**
     * 删除
     *
     * @param  int      $id         行业社群id
     * @return string               返回信息
     */
    public function del($id)
    {
        LogicCommunity::deleteById($id);
    }
}
