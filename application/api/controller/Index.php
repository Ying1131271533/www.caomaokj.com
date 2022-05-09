<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\common\model\User as U;
use think\Request;
use think\Response;

class IndexController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // return U::select();
        return Response::create([1, 2, 3], 'json');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read(int $id)
    {
        //
        echo '阿卡丽';
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
        echo '锐雯';
    }
}
