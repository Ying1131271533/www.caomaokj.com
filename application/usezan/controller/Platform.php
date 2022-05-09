<?php
namespace app\usezan\controller;

use app\common\model\Continent as C;
use app\common\model\Platform as P;
use app\common\model\PlatformContinent as PC;
use app\common\model\PlatformDetail as PD;
use app\common\model\PlatformDetailType as PDT;
use app\common\model\PlatformJoin as PJ;
use think\facade\View;

class Platform extends Base
{
    /**
     * 初始化
     *
     * @param
     * @return View
     */
    public function _uzauto()
    {}

    /**
     * 平台列表 ⎛⎝≥⏝⏝≤⎛⎝
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
                $where[] = ['name|title', 'like', '%' . $data['keyword'] . '%'];
            }

            if (!empty($data['status'])) {
                $data['status'] == "1" ? $where[] = ['status', '=', 0] : $where[] = ['status', '=', 1];
            }
        }

        $list = P::where($where)->order(['sort' => 'desc', 'id' => 'desc'])->paginate(30);
        // halt($list);
        $this->assign("list", $list);
        $this->assign('status', input('status/d', ''));
        return $this->fetch();
    }

    /**
     * 电商平台添加
     *
     * @param
     * @return view
     */
    public function add()
    {
        if (request()->isPost()) {

            /**********************   组装数据   **********************/
            // halt(input());
            // 平台
            $platformData = [
                'name'        => input("name/s"),
                'logo'        => input("logo/s"),
                'title'       => input("title/s"),
                'descrip'     => input("descrip/s"),
                'wechat'     => input("wechat/s"),
                'url'         => input("url/s"),
                'status'      => input("status/d"),
                'create_time' => strtotime(input("createtime/s")),
            ];

            // 六大洲
            $continentData = input("continent/a");

            // 详情图片
            $detailData = [];
            foreach (input("type/a") as $key => $value) {
                if (!empty($value)) {
                    $detailData[] = [
                        'type'  => $key,
                        'image' => $value,
                    ];
                }
            }

            /**********************   保存数据   **********************/

            // 开启事务
            P::startTrans();
            $result = true;

            $platform = P::create($platformData);
            if (empty($platform)) {
                $result = false;
            }

            // 保存六大洲位置
            $continent = $platform->continents()->saveAll($continentData);
            if (empty($continent)) {
                $result = false;
            }

            // 保存详情图片
            $detail = $platform->details()->saveAll($detailData);
            if (empty($continent)) {
                $result = false;
            }

            if ($result === false) {
                // 回滚事务
                P::rollback();
                jinx("添加失败");
            }

            // 提交事务
            P::commit();
            jinx("添加成功");
        }

        // 详情图片类型
        $detailType = PDT::select();

        // 六大洲
        $continent = C::select();
        $this->assign("detailType", $detailType);
        $this->assign("continent", $continent);
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

            /**********************   组装数据   **********************/
            // dump(input());return;
            // 平台
            $platformData = [
                'id'          => input("id/d"),
                'name'        => input("name/s"),
                'logo'        => input("logo/s"),
                'title'       => input("title/s"),
                'descrip'     => input("descrip/s"),
                'wechat'     => input("wechat/s"),
                'url'         => input("url/s"),
                'status'      => input("status/d"),
                'create_time' => strtotime(input("createtime/s")),
            ];

            // 六大洲
            $continentData = input("continent/a");

            // 详情图片
            $detailData = [];
            foreach (input("type/a") as $key => $value) {
                if (!empty($value)) {
                    $detailData[] = [
                        'type'  => $key,
                        'image' => $value,
                    ];
                }
            }

            /**********************   更新数据   **********************/

            // 开启事务
            P::startTrans();
            $result = false;

            $platform = P::update($platformData);
            if (!empty($platform)) {
                $result = true;
            }

            // 删除原来的六大洲位置
            $continentsDel = PC::where('platform_id', $id)->delete();
            if (empty($continentsDel)) {
                $result = true;
            }

            // 保存六大洲位置
            $continent = $platform->continents()->saveAll($continentData);
            if (!empty($continent)) {
                $result = true;
            }

            // 删除原来的详情图片
            if (!empty($platform->details)) {
                $detailsDel = PD::where('platform_id', $id)->delete();
                if (!empty($detailsDel)) {
                    $result = true;
                }
            }

            // 保存详情图片
            $detail = $platform->details()->saveAll($detailData);
            if (!empty($continent)) {
                $result = true;
            }

            if ($result === false) {
                // 回滚事务
                P::rollback();
                jinx("修改失败");
            }

            // 提交事务
            P::commit();
            jinx("修改成功");
        }

        // 平台
        $platform = P::field('*')->with(['details' => function ($query) {
            $query->field('type, image, platform_id'); // 需要手动添加关联字段，像join一样
        }, 'continents' => function ($query) {
            $query->field('id');
        }])->find($id);

        // dump($platform);
        // return;

        // 六大洲选中位置
        $continentsId = [];
        foreach ($platform['continents'] as $value) {
            $continentsId[] = $value['id'];
        }

        // 详情图片
        $details = [];
        foreach ($platform['details'] as $value) {
            $details[$value['type']] = $value['image'];
        }

        // 详情图片类型
        $detailType = PDT::select();

        // 六大洲
        $continent = C::select();

        // dump($continentsId);
        // return;

        $this->assign("platform", $platform);
        $this->assign("continentsId", $continentsId);
        $this->assign("details", $details);
        $this->assign("detailType", $detailType);
        $this->assign("continent", $continent);
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

        /**********************   找出电商平台   **********************/
        $platform = P::get($id);
        empty($platform) and jinx('找不到该电商平台');

        /**********************   开启事务   **********************/
        $platform->startTrans();
        try {
            // 删除六大洲关联数据
            PC::where('platform_id', $id)->delete();
            // 详情图片
            PD::where('platform_id', $id)->delete();
            // 删除电商平台
            $platform->delete();
            // 提交事务
            $platform->commit();
            jinx('删除成功');
        } catch (\Exception $e) {
            // 回滚事务
            $platform->rollback();
            jinx('删除失败');
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

        $platform = new P;
        $result   = $platform->saveAll($data);
        empty($result) and jinx('排序失败');
        jinx('排序成功');
    }

    /**
     * 电商平台入驻报名 ⎛⎝≥⏝⏝≤⎛⎝
     *
     * @param
     * @return View
     */
    public function join()
    {
        $where = [];
        $data  = input();
        if (request()->isPost()) {

            if (!empty($data['platform_id'])) {
                $where[] = ['platform_id', '=', $data['platform_id']];
            }

            if (!empty($data['status'])) {
                $data['status'] == "1" ? $where[] = ['status', '=', 0] : $where[] = ['status', '=', 1];
            }
        }

        // 所有电商平台
        $platform = P::order(['id' => 'asc'])->field('id, name')->select();
        // dump($platform);return;

        // 电商平台入驻申请
        $list = PJ::where($where)->field('*')->with(['platform' => function ($query) {
            $query->field('id, name, logo');
        }])->order('create_time', 'desc')->paginate(30);
        // dump($list[0]['platform']['name']);return;

        $this->assign("platform", $platform);
        $this->assign("list", $list);
        $this->assign('status', input('status/d', ''));
        $this->assign('platform_id', input('platform_id/d', ''));
        return $this->fetch();
    }
}
