<?php
namespace app\usezan\controller;

use app\common\model\Attributes as A;
use think\facade\View;

class Attributes extends Base
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
     * 走货列表 ⎛⎝≥⏝⏝≤⎛⎝
     *
     * @param
     * @return View
     */
    public function index()
    {
        $where                        = [];
        $keyword                      = input('keyword/s');
        !empty($keyword) and $where[] = ['name', 'like', '%' . $keyword . '%'];

        $list = A::where($where)->order(['sort' => 'desc', 'id' => 'asc'])->paginate(30);
        View::assign('list', $list);
        return view();
    }

    /**
     * 走货属性添加
     *
     * @param
     * @return view
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input();

            // 验证数据
            $validate = validate('Attributes');
            if (!$validate->check($data)) {
                return jinx($validate->getError());
            }

            // 保存
            $attributes       = new A;
            $attributes->name = $data['name'];
            $attributes->save();
            if (empty($attributes->id)) {
                jinx("添加失败");
            }
            jinx("添加成功");
        }

        return view();
    }

    /**
     * 走货属性修改
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
            $validate = validate('Attributes');
            if (!$validate->check($data)) {
                return jinx($validate->getError());
            }

            // 保存
            $result = A::update($data);
            empty($result) and jinx('修改失败');
            jinx("修改成功");
        }

        $attributes = A::get($id);
        View::assign('attributes', $attributes);

        return view();
    }

    /**
     * 走货属性删除
     *
     * @param
     * @return
     */
    public function del()
    {
        /**********************   获取走货属性id   **********************/
        $id = input('id/d');
        empty($id) and jinx('参数不能为空');

        /**********************   找出走货属性   **********************/
        $attributes = A::get($id);
        empty($attributes) and jinx('找不到该走货属性');

        /**********************   开启事务   **********************/
        $attributes->startTrans();
        try {
            // 删除关联数据
            $attributes->logistics()->detach();
            // 删除走货属性
            $attributes->delete();
            // 提交事务
            $attributes->commit();
            jinx('删除成功');
        } catch (\Exception $e) {
            // 回滚事务
            $attributes->rollback();
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
        $attributes = new A;
        $result     = $attributes->saveAll($data);
        empty($result) and jinx('排序失败');
        jinx('排序成功');
    }
}
