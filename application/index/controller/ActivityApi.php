<?php
declare (strict_types = 1);
namespace app\index\controller;

use app\common\model\Activity;
use app\common\model\ActivityJoin as J;
use app\common\model\ActivityRef as AR;
use app\common\model\Member as U;
use app\index\controller\CodeApi as CodeApi;
use think\Request;

class ActivityApi extends BaseApi
{
    /**
     * 活动数据
     *
     * @param Request $request
     * @return \think\Response
     */
    public function getActivityList(Request $request)
    {
        /**********************   接收参数   **********************/
        $keyword = $request->param('keyword/s', ''); // 活动类型
        $type    = $request->param('type/d', 0); // 活动分类
        $time    = $request->param('time/d', 0); // 时间范围
        $by      = $request->param('by/d', 0); // 最新、最热
        $free    = $request->param('free/d', 0); // 免费
        $ing     = $request->param('ing/d', 0); // 进行中、未结束
        $page    = $request->param('page/d', 1); // 分页

        /**********************   组装条件   **********************/
        $where = [];
        $order = ['listorder' => 'desc', 'createtime' => 'desc'];

        // 搜索
        $keyword and $where[] = ['title', 'like', '%' . $keyword . '%'];

        // 分类
        $type and $where[] = ['catid', '=', $type];

        // 时间范围
        switch ($time) {
            case 1:
                $where[] = ['starttime', 'between time', [strtotime("today"), time() + 604800]];
                break;
            case 2:
                $where[] = ['starttime', 'between time', [strtotime("today"), time() + 2592000]];
                break;
            case 3:
                $where[] = ['starttime', 'between time', [strtotime("today"), time() + 7776000]];
                break;
        }

        // 最新、最热
        switch ($by) {
            case 1:
                $order = ['createtime' => 'desc'];
                break;
            case 2:
                $order = ['apply_num' => 'desc'];
                break;
        }

        // 免费
        $free and $where[] = [' discount', '=', 0.00];

        // 未结束
        $ing and $where[] = ['endtime', '>', time()];

        /**********************   获取活动数据   **********************/
        $activity = Activity::where($where)
            ->limit(12)
            ->page($page)
            ->field('id, endtime,starttime,address,thumb,title,discount,endtime')
            ->order($order)
            ->select();

        /**********************   获取文章总条数   **********************/
        $count = Activity::where($where)->count();

        /**********************   重组文章数据 适应前端   **********************/
        $data = [];
        foreach ($activity as $value) {
            $data[] = [
                'activity_id'         => $value['id'], // 活动id
                'activity_end_time'   => date('Y-m-d H:i:s', $value['endtime']), // 活动结束时间
                'activity_start_time' => date('Y-m-d H:i:s', $value['starttime']), // 活动开始时间
                'activity_local'      => $value['address'], // 点击次数
                'activity_thumb'      => $value['thumb'], // 封面
                'activity_name'       => $value['title'], // 标题
                'course_id'           => 0, // 课程id
                'original_price_name' => '', // 原价名称
                'priceName'           => empty($value['discount']) ? '免费' : "￥" . $value['discount'], // 价格名称
                'time'                => $value['endtime'] > time() ? '进行中' : '已结束', // 时间进度
            ];
        }

        /**********************   返回API数据   **********************/
        $resultData = [
            'count' => $count,
            'data'  => $data,
        ];
        return $this->create(200, '获取成功', $resultData);
    }
    
    /**
     * 评论文章 评论/回复
     *
     * @param
     * @return \think\Response
     */
    public function activityComment(Request $request)
    {
        // 是否有登录
        if (empty($this->userid)) {
            return $this->create(300, '请先登录');
        }

        // 接收参数
        $params = $request->param();

        // 找出文章
        $activity = Activity::find($params['id']);
        // halt($activity);
        if (!$activity) {
            return $this->create(400, '文章不存在');
        }
        
        // 评论长度
        if(mb_strlen($params['comment']) > 255){
            return $this->create(400, '评论长度不能超过255个字符');
        }

        // 组装数据
        $data = [
            'content'     => $params['comment'],
            'parentid'    => $params['pid'],
            'status'      => 1,
            'create_time' => time(),
            // 'activity_id'  => $params['id'],
            'member_id'   => $this->userid,
        ];

        // 保存评论
        $result = $activity->comments()->save($data);
        if (!$result) {
            return $this->create(400, '评论发表失败~');
        }

        // 返回数据
        return $this->create(200, '评论发表成功');

    }

    /**
     * 获取文章评论
     *
     * @param
     * @return json
     */
    public function getactivityComment(Request $request)
    {
        $id = $request->param('id/d', 0);
        if (empty($id)) {
            return $this->create(400, '获取失败');
        }

        // 找出活动
        $activity = Activity::with(['comments' => function ($query) {
            $query->order('id', 'desc');
        }])->get($id);
        // halt($activity);
        if (empty($activity)) {
            return $this->create(400, '活动不存在');
        }

        $commentsData = [];
        foreach ($activity->comments as $key => $comment) {
            $comment->member;
            $commentsData[$key] = [
                'acom_add_time'  => date('Y-m-d', $comment['create_time']),
                'acom_comment'   => $comment['content'],
                'acom_id'        => $comment['id'],
                'acom_parent_id' => $comment['parentid'],
                'al_id'          => $activity['id'],
                'user_id'        => $comment['member']['id'],
                'user_name'      => $comment['member']['username'],
                'time'           => postTime($comment['create_time']),
                'user_head'      => $comment['member']['avatar'],
            ];

            $commentsData[$key]['parent'] = [];
            foreach ($activity->comments as $val) {
                if ($comment['parentid'] == $val['id']) {
                    $commentsData[$key]['parent'] = [
                        'user_id'      => $val['member']['id'],
                        'user_name'    => $val['member']['username'],
                        'acom_comment' => $val['content'],
                    ];
                    continue;
                }
            }
        }

        $resultData = [
            'count'    => count($commentsData),
            'comments' => $commentsData,
        ];
        return $this->create(200, '获取成功', $resultData);
    }

    /**
     * 活动收藏
     *
     * @param int $id
     * @return \think\Response
     */
    public function activityCollect($id)
    {
        /**********************   是否有登录   **********************/
        if (empty($this->userid)) {
            return $this->create(204, '请先登录');
        }

        /**********************   参数检查   **********************/
        if (!validate()->isInteger($id)) {
            return $this->create(400, '参数错误');
        }

        /**********************   找出活动   **********************/
        $activity = Activity::get($id);
        if (empty($activity)) {
            return $this->create(400, '活动不存在');
        }

        /**********************   用户是否已收藏   **********************/
        $userCollect = $activity->collects()->find($this->userid);
        if (!empty($userCollect)) {
            return $this->create(400, '您已经收藏了，休息一下吧~~');
        }

        /**********************   开启事务   **********************/
        $activity->startTrans();
        
        // 保存用户收藏
        $collectsResult = $activity->collects()->save($this->userid, ['create_time' => time()]);

        // 收藏加一
        $incResult = $activity->setInc('collect_num');

        // 是否都修改了数据库
        if (!$collectsResult || !$incResult) {
            $activity->rollback();
            return $this->create(400, '收藏失败~~');
        }

        // 提交事务
        $activity->commit();
        // 返回数据
        return $this->create(200, '收藏成功', $activity->collect_num);
    }

    /**
     * 活动点赞
     *
     * @param int $id
     * @return \think\Response
     */
    public function activityLike($id)
    {
        /**********************   是否有登录   **********************/
        if (empty($this->userid)) {
            return $this->create(204, '请先登录');
        }

        /**********************   参数判断   **********************/
        if (!validate()->isInteger($id)) {
            return $this->create(400, '参数错误');
        }

        /**********************   找出文章   **********************/
        $activity = Activity::get($id);
        if (empty($activity)) {
            return $this->create(400, '文章不存在');
        }

        /**********************   用户是否已点赞   **********************/
        $userLike = $activity->likes()->find($this->userid);
        if (!empty($userLike)) {
            return $this->create(400, '您已经点赞了，休息一下吧~~');
        }

        // 开启事务
        $activity->startTrans();

        // 保存用户点赞
        $likesResult = $activity->likes()->save($this->userid);

        // 文章点赞加一
        $incResult = $activity->setInc('like_num');

        // 是否都修改了数据库
        if (!$likesResult || !$incResult) {
            $activity->rollback();
            return $this->create(400, '点赞失败~~');
        }

        // 提交事务
        $activity->commit();
        // 返回数据
        return $this->create(200, '点赞成功', $activity->like_num);
    }

    /**
     * 用户报名信息保存
     *
     * @param Request $request
     * @return \think\Response
     */
    public function activityJoin(Request $request)
    {

        /**********************   接收参数   **********************/
        $data = $request->param();

        /**********************   找出活动   **********************/
        $activity = Activity::where(['status' => 1, 'id' => $data['activity_id']])->find();
        if (empty($activity)) {
            return $this->create(204, '该活动不存在');
        }

        /**********************   加入验证数组   **********************/
        $data['endtime']     = $activity['endtime'];
        $data['tickets_num'] = $activity['tickets_num'];
        $data['apply_num']   = $activity['apply_num'];
        $data['shop_num']    = $activity['shop_num'];
        $data['number']      = $request->param('number/d', 1);

        /**********************   验证数据   **********************/
        $validate = validate('Activity');
        if (!$validate->check($data)) {
            return $this->create(400, $validate->getError());
        }

        /**********************   检查登录短信验证码   **********************/
        $smsResult = (new CodeApi)->checkCode($data['phone'], $data['code'], 6);
        if ($smsResult['code'] !== 0) {
            return $this->create(400, $smsResult['msg']);
        }

        /**********************   查找用户或创建用户，并保存登录状态   **********************/
        $createResult = (new LoginApi)->joinCreateUser($request->param('phone/s'));
        if ($createResult['code'] !== 200) {
            return $this->create(400, '报名失败，请刷新页面');
        }

        /**********************   组装报名数据   **********************/
        $activityJoinData = [
            'name'      => $request->param('name/s'),
            'phone'     => $request->param('phone/s'),
            'company'   => $request->param('company/s'),
            'demand'    => $request->param('demand/s'),
            'member_id' => $createResult['id'],
        ];

        /**********************   组装订单数据   **********************/
        $activityOrderData = [
            'order_sn'       => 'HD' . date('YmdHis', time()) . mt_rand(0, 9999),
            'original_price' => $activity['price'],
            'shop_price'     => $activity['discount'],
            'number'         => $data['number'],
            'total'          => $activity['discount'] * $data['number'],
            'need_pay'       => $activity['discount'] == 0 ? 0 : 1,
            'order_status'   => $activity['discount'] == 0 ? 1 : 0,
            'pay_status'     => $activity['discount'] == 0 ? 1 : 0,
        ];

        /**********************   是否用户推荐   **********************/
        $refUserId = AR::where(['ip' => getIp(), 'activity_id' => $activity['id']])->value('member_id');

        /**********************   如果是推荐则数据加入推荐用户id   **********************/
        !empty($refUserId) and $activityOrderData['ref_user'] = $refUserId;

        /**********************   开启事务   **********************/
        $activity->startTrans();

        /**********************   保存活动报名   **********************/
        $join = $activity->joins()->save($activityJoinData);

        /**********************   保存活动报名订单   **********************/
        $order = $join->order()->save($activityOrderData);

        /**********************   增加报名人数   **********************/
        $incResult = $activity->setInc('apply_num', $activityOrderData['number']);

        // 是否都修改了数据库
        if (!$join || !$order || !$incResult) {
            $activity->rollback();
            return $this->create(400, '报名失败~~');
        }

        /**********************   提交事务   **********************/
        $activity->commit();

        /**********************   返回数据接口   **********************/
        $resultData = [
            'url'   => $activity['discount'] == 0 ? url('activity/orderSuccess', ['order_id' => $order['id']]) : url('activity/confirm', ['order_id' => $order['id']]),
            'token' => $createResult['token'],
        ];
        return $this->create(200, '提交成功', $resultData);
        
    }

    /**
     * 异步检测订单的微信支付状态
     *
     * @param Request $request
     * @return \think\Response
     */
    public function checkWxOrder(Request $request)
    {
        /**********************   引用微信支付文件   **********************/
        require_once "../extend/pay/wechat/lib/WxPay.Api.php";
        require_once "../extend/pay/wechat/example/WxPay.Config.php";
        require_once '../extend/pay/wechat/example/log.php';
        return $this->create(400, '阿卡丽');
        /**********************   初始化日志   **********************/
        $logHandler = new CLogFileHandler("../logs/" . date('Y-m-d') . '.log');
        $log        = Log::Init($logHandler, 15);

        /**********************   接收参数   **********************/
        $order_id = $request->param('order_id/s');
        if (empty($order_id)) {
            return $this->create(400, '参数错误，请刷新页面');
        }

        // 商户订单号
        $transaction_id = $order_id;
        $input          = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $config = new WxPayConfig();
        $resutl = WxPayApi::orderQuery($config, $input);

        /**********************   返回查询结果   **********************/
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            // echo $result['trade_state'];
            return $this->create(200, '支付成功');
        } else {
            // echo "FAIL";
            return $this->create(204, '未支付');
        }

    }
}
