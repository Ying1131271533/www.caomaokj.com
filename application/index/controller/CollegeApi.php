<?php
declare (strict_types = 1);
namespace app\index\controller;

use app\common\model\College as CollegeModel;
use app\common\model\CollegeJoin as J;
use app\common\model\CollegeRef as CR;
use app\common\model\Member as U;
use app\common\model\Tickets;
use app\index\controller\CodeApi as CodeApi;
use think\Request;

class CollegeApi extends BaseApi
{
    /**
     * 课程列表
     *
     * @param Request $request
     * @return Response
     */
    public function courseList(Request $request)
    {
        // 接收参数
        $cateId = $request->param('cate_id/d', 0);
        $page   = $request->param('page/d', 1);

        // 组装条件
        $where             = ['status' => 1];
        $cateId and $where = ['catid' => $cateId, 'status' => 1];

        // 找出数据
        $college = CollegeModel::with('tickets')
            ->where($where)
            ->order(['listorder' => 'asc', 'id' => 'asc'])
            ->limit(10)
            ->page($page)
            ->select();
        // return $this->create(200, '获取成功', $college);
        $count = CollegeModel::where($where)->count();

        // 组装数据
        $resultData = [
            'count'       => $count,
            'collegeList' => $college,
            'time'        => time(),
        ];

        // 返回数据
        return $this->create(200, '获取成功', $resultData);
    }
    /**
     * 课程收藏
     *
     * @param int $id
     * @return \think\Response
     */
    public function collegeCollect($id)
    {
        if (!validate()->isInteger($id)) {
            return $this->create(400, '参数错误');
        }

        /**********************   是否有登录   **********************/
        if (empty($this->userid)) {
            return $this->create(204, '请先登录');
        }

        /**********************   找出课程   **********************/
        $college = CollegeModel::get($id);
        if (empty($college)) {
            return $this->create(400, '文章不存在');
        }

        /**********************   用户是否已收藏   **********************/
        $result = $college->collects()->find($this->userid);
        if (!empty($result)) {
            return $this->create(400, '您已经收藏了，休息一下吧~~');
        }

        /**********************   开启事务   **********************/
        $college->startTrans();

        /**********************   保存用户收藏   **********************/
        $collectsResult = $college->collects()->save($this->userid, ['create_time' => time()]);
        /**********************   收藏加一   **********************/
        $incResult = $college->setInc('collect_num');

        // 是否都修改了数据库
        if (!$collectsResult || !$incResult) {
            $college->rollback();
            return $this->create(400, '收藏失败~~');
        }

        // 提交事务
        $college->commit();
        // 返回数据
        return $this->create(200, '收藏成功', $college->collect_num);
    }

    /**
     * 课程点赞
     *
     * @param int $id
     * @return \think\Response
     */
    public function collegeLike($id)
    {
        if (!validate()->isInteger($id)) {
            return $this->create(400, '参数错误');
        }

        /**********************   是否有登录   **********************/
        if (empty($this->userid)) {
            return $this->create(204, '请先登录');
        }

        /**********************   找出文章   **********************/
        $college = CollegeModel::get($id);
        if (empty($college)) {
            return $this->create(400, '文章不存在');
        }

        /**********************   用户是否已点赞   **********************/
        $userLike = $college->likes()->find($this->userid);
        if (!empty($userLike)) {
            return $this->create(400, '您已经点赞了，休息一下吧~~');
        }

        // 开启事务
        $college->startTrans();

        // 保存用户点赞
        $likesResult = $college->likes()->save($this->userid);
        //  文章点赞加一
        $incResult = $college->setInc('like_num');
        //  提交事务
        $college->commit();
        //  返回数据

        // 是否都修改了数据库
        if (!$likesResult || !$incResult) {
            $college->rollback();
            return $this->create(400, '点赞失败~~');
        }

        /**********************   提交事务   **********************/
        $college->commit();
        return $this->create(200, '点赞成功', $college->like_num);
    }

    /**
     * 用户报名信息保存
     *
     * @param Request $request
     * @return \think\Response
     */
    public function collegeJoin(Request $request)
    {
        /**********************   接收参数   **********************/
        $data = $request->param();

        /**********************   找出课程   **********************/
        $college = CollegeModel::where(['status' => 1, 'id' => $data['college_id']])->find();
        if (empty($college)) return $this->create(204, '课程不存在');

        // 找出门票
        $tickets = Tickets::get($data['tickets_id']);
        if (empty($tickets)) return $this->create(204, '门票不存在');
        // return $this->create(400, '阿卡丽', $tickets);

        /**********************   加入验证数组   **********************/
        $data['end_time']    = $college['end_time'];
        $data['tickets_num'] = $college['tickets_num'];
        $data['apply_num']   = $college['apply_num'];
        $data['shop_num']    = $college['shop_num'];
        $data['number']      = $request->param('number/d', 1);
        $data['tickets_id']  = $tickets['id'];

        /**********************   验证数据   **********************/
        $validate = new \app\index\validate\College;
        if (!$validate->check($data)) {
            return $this->create(400, $validate->getError());
        }
        
        /**********************   检查登录短信验证码   **********************/
        $smsResult = (new CodeApi)->checkCode($data['phone'], $data['code'], 6);
        if ($smsResult['code'] !== 0) {
            return $this->create(400, $smsResult['msg']);
        }

        /**********************   查找用户或创建用户，并保存登录状态   **********************/
        $createUser = (new LoginApi)->joinCreateUser($request->param('phone/s'));
        if ($createUser['code'] !== 200) {
            return $this->create(400, '报名失败，请刷新页面');
        }

        /**********************   组装报名数据   **********************/
        $collegeJoinData = [
            'name'       => $request->param('name/s'),
            'phone'      => $request->param('phone/s'),
            'company'    => $request->param('company/s'),
            'demand'     => $request->param('demand/s'),
            'tickets_id' => $request->param('tickets_id/d'),
            'member_id'  => $createUser['id'],
        ];

        /**********************   组装订单数据   **********************/
        $collegeOrderData = [
            'order_sn'       => 'XY' . date('YmdHis', time()) . mt_rand(0, 9999),
            'original_price' => $tickets['price'],
            'shop_price'     => $tickets['discount_price'],
            'number'         => $data['number'],
            'total'          => $tickets['discount_price'] * $data['number'],
            'need_pay'       => $tickets['discount_price'] == 0 ? 0 : 1,
            'order_status'   => $tickets['discount_price'] == 0 ? 1 : 0,
            'pay_status'     => $tickets['discount_price'] == 0 ? 1 : 0,
        ];

        /**********************   是否用户推荐   **********************/
        $refUserId = CR::where(['ip' => getIp(), 'college_id' => $college['id']])->value('member_id');

        /**********************   如果是推荐则数据加入推荐用户id   **********************/
        !empty($refUserId) and $collegeOrderData['ref_user'] = $refUserId;

        /**********************   开启事务   **********************/
        $college->startTrans();

        /**********************   保存活动报名   **********************/
        $join = $college->joins()->save($collegeJoinData);

        /**********************   保存活动报名订单   **********************/
        $order = $join->order()->save($collegeOrderData);

        /**********************   增加报名人数   **********************/
        $joinNumber = $tickets['people_number'] * $data['number'];
        $incResult = $college->setInc('apply_num', $joinNumber);

        // 是否都修改了数据库
        if (!$join || !$order || !$incResult) {
            $college->rollback();
            return $this->create(400, '报名失败~~');
        }

        /**********************   提交事务   **********************/
        $college->commit();

        /**********************   返回数据接口   **********************/
        $resultData = [
            'url'   => $tickets['discount_price'] == 0 ? url('college/orderSuccess', ['order_sn' => $order['order_sn']]) : url('college/confirm', ['order_id' => $order['id']]),
            'token' => $createUser['token'],
        ];
        return $this->create(200, '提交成功', $resultData);

        try {

        } catch (\Exception $e) {
            $college->rollback();
            return $this->create(400, $e->getMessage());
        }
    }
}
