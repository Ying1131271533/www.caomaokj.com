<?php
namespace app\usezan\controller;

use app\common\model\Attributes as A;
use app\common\model\Category as C;
use app\common\model\Logistics as L;
use app\common\model\Service as S;
use app\common\model\ServiceDetail as SD;
use libs\Tree;
use think\facade\View;

class Service extends Base
{
    /**
     * 服务列表 ⎛⎝≥⏝⏝≤⎛⎝
     *
     * @param
     * @return View
     */
    public function index()
    {
        $where = [];
        if (request()->isPost()) {
            $data = input();
            if (!empty($data['keyword'])) {
                $where[] = ['name', 'like', '%' . $data['keyword'] . '%'];
            }

            if (!empty($data['status'])) {
                $data['status'] == "1" ? $where[] = ['status', '=', 0] : $where[] = ['status', '=', 1];
            }
        }

        $list = S::field('id, name, image, sort, status, recommend, create_time')
            ->where($where)->order(['sort' => 'desc', 'id' => 'desc'])
            ->paginate(30);

        $this->assign("list", $list);
        $this->assign('status', input('status/d', ''));
        return $this->fetch();
    }

    //添加
    public function add()
    {
        if (request()->isPost()) {

            // 接收参数
            $data = input();

            // 主推服务
            if (isset($data['imgs']) && $data['imgs']) {
                $data['imgs'] = $this->checkPics($data);
                unset($data['imgs_order'], $data['imgs_title'], $data['imgs_remark']);
            }

            // 服务详情数据组装
            $detailData = [
                'featured' => input('imgs', ''), // 主推服务
                // 'company'  => input('company', ''), // 企业介绍
                'content'  => input('content', ''), // 服务介绍
            ];

            // dump($data);return;
            // 开启事务
            S::startTrans();
            $service = S::create($data);
            $service->detail()->save($detailData);

            // 提交事务
            S::commit();
            jinx('添加成功');
            try {
                $service = S::create($data);
                $service->detail()->save($detailData);
                // 提交事务
                S::commit();
                jinx('添加成功');
            } catch (\Exception $e) {
                // 回滚事务
                S::rollback();
                jinx('添加失败');
            }

        }

        // 服务分类
        // $category = C::field('id, catname')
        //     ->where(['parentid' => 66, 'status' => 1])
        //     ->order(['listorder' => 'asc', 'id' => 'asc'])
        //     ->select();  
        $category = C::getCategoryList([['id', 'in', [161, 162, 159, 160]], ['status', '=', 1]], 'id, catname');
        
        View::assign("category", $category);
        return view();
    }

    //修改
    public function edit()
    {
        $id = input('id/d');
        empty($id) and jinx('参数不能为空');

        // 找出服务
        $service = S::get($id, 'detail');
        empty($id) and jinx('该服务不存在');

        // 保存数据
        if (request()->isPost()) {

            // 接收参数
            $data = input();
            
            // 主推服务
            if (isset($data['imgs']) && $data['imgs']) {
                $data['imgs'] = $this->checkPics($data);
                unset($data['imgs_order'], $data['imgs_title'], $data['imgs_remark']);
            }

            // 服务详情数据组装
            $detailData = [
                'id'       => $service['detail']['id'], // id
                'featured' => input('imgs', ''), // 主推服务
                // 'company'  => $data['company'], // 企业介绍
                'content'  => $data['content'], // 服务介绍
            ];

            // 开启事务
            S::startTrans();
            try {
                $service = S::update($data);
                $service->detail()->update($detailData);
                // 提交事务
                S::commit();
                jinx('修改成功');
            } catch (\Exception $e) {
                // 回滚事务
                S::rollback();
                jinx('修改失败');
            }

        }

        // featured
        if ($service['detail']['featured']) {
            $imgs      = explode(':::', $service['detail']['featured']);
            $imgs_data = [];
            foreach ($imgs as $key => $vo) {
                $imgs_data[$key] = explode("|", $vo);
            }

            //重新排序组合
            $timeKey = array_column($imgs_data, 1);
            if (!empty($timeKey)) {
                array_multisort($timeKey, SORT_ASC, $imgs_data);
            }
            $service['detail']['featured'] = $imgs_data;
            unset($imgs, $imgs_data);
        }

        // dump($service);return;
        // 服务分类
        // $category = C::field('id, catname')
        //     ->where(['parentid' => 66, 'status' => 1])
        //     ->order(['listorder' => 'asc', 'id' => 'asc'])
        //     ->select();
        
        $category = C::getCategoryList([['id', 'in', [161, 162, 163, 164]], ['status', '=', 1]], 'id, catname');

        View::assign("service", $service);
        View::assign("category", $category);
        return view();
    }

    //多图处理
    protected function checkPics($data, $field = 'imgs')
    {
        if (is_array($data[$field])) {
            $pic = [];
            foreach ($data[$field] as $key => $v) {
                $pic[] = $v . "|" . $data['imgs_order'][$key] . "|" . $data['imgs_title'][$key] . "|" . $data['imgs_remark'][$key];
            }
            return implode(":::", $pic);
        }
    }

    /**
     * 排序
     *
     * @param
     * @return jinx
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
        $service = new S;
        $result  = $service->saveAll($data);
        empty($result) and jinx('排序失败');
        jinx('排序成功');
    }

    // 删除
    public function del()
    {
        $id = input('id/d');
        empty($id) and jinx('参数不能为空');

        // 找出服务
        $service = S::get($id, ['detail' => function ($query) {
            $query->field('id, content, service_id');
        }]);
        empty($service) and jinx('该数据不存在');

        // 开启事务
        S::startTrans();
        try {
            $service->together('detail')->delete();
            // 提交事务
            S::commit();
            jinx('删除成功');
        } catch (\Exception $e) {
            // 回滚事务
            S::rollback();
            jinx('删除失败');
        }

    }

}
