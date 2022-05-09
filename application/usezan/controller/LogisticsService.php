<?php
namespace app\usezan\controller;

use app\common\model\LogisticsServiceType as LST;
use think\facade\View;

class LogisticsService extends Base
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
     * 服务列表 ⎛⎝≥⏝⏝≤⎛⎝
     *
     * @param
     * @return View
     */
    public function index()
    {
        $where                        = [];
        $keyword                      = input('keyword/s');
        !empty($keyword) and $where[] = ['name', 'like', '%' . $keyword . '%'];

        $list = LST::where($where)->order(['sort' => 'desc', 'id' => 'asc'])->paginate(30);
        View::assign('list', $list);
        return view();
    }

    /**
     * 服务类型添加
     *
     * @param
     * @return view
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input();
            // 验证数据
            $validate = validate('LogisticsService');
            if (!$validate->check($data)) {
                return jinx($validate->getError());
            }

            // 保存
            $logistics       = new LST;
            $logistics->name = $data['name'];
            $logistics->save();
            if (empty($logistics->id)) {
                jinx("添加失败");
            }
            jinx("添加成功");
        }

        return view();
    }

    /**
     * 服务类型修改
     *
     * @param
     * @return View
     */
    public function edit()
    {
        $id = input('id/d');
        empty($id) and jinx('参数不能为空');

        if (request()->isPost()) {
            $data = input();

            // 验证数据
            $validate = validate('LogisticsService');
            if (!$validate->check($data)) {
                return jinx($validate->getError());
            }

            // 保存
            $result = LST::update($data);
            jinx("修改成功");
        }

        $service = LST::get($id);
        View::assign('service', $service);

        return view();
    }

    /**
     * 服务类型删除
     *
     * @param
     * @return
     */
    public function del()
    {
        /**********************   获取服务类型id   **********************/
        $id = input('id/d');
        empty($id) and jinx('参数不能为空');

        /**********************   找出服务类型   **********************/
        $service = LST::get($id);
        empty($service) and jinx('找不到该服务类型');

        /**********************   开启事务   **********************/
        $service->startTrans();
        try {
            // 删除关联数据
            $service->logistics()->detach();
            // 删除服务类型
            $service->delete();
            // 提交事务
            $service->commit();
            jinx('删除成功');
        } catch (\Exception $e) {
            // 回滚事务
            $service->rollback();
            jinx($e->getError());
        }
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
        $service = new LST;
        $result  = $service->saveAll($data);
        empty($result) and jinx('排序失败');
        jinx('排序成功');
    }
}
