<?php
namespace app\usezan\controller;

use app\usezan\logic\Member as LogicMember;
use think\Request;

class Member extends Base
{
    /**
     * @description:  オラ!オラ!オラ!オラ!⎛⎝≥⏝⏝≤⎛⎝
     * @author: 神织知更
     * @time: 2022/03/29 15:40
     *
     * 用户列表
     *
     * @param  Request    $request	请求对象
     * @return template
     */
    public function index(Request $request)
    {
        // 获取参数
        $params = $request->param();
        // 获取用户列表数据
        $list = LogicMember::getMemberList($params);
        
        return view('', [
            'status' => input('status'),
            'list' => $list,
        ]);
    }
}
