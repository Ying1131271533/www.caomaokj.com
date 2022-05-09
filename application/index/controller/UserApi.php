<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\model\Activity;
use app\common\model\ActivityCollect;
use app\common\model\ActivityJoin;
use app\common\model\ActivityOrder as AO;
use app\common\model\Article as A;
use app\common\model\ArticleCollect as AC;
use app\common\model\College;
use app\common\model\CollegeCollect;
use app\common\model\CollegeJoin;
use app\common\model\CollegeOrder as CO;
use app\common\model\Member as U;
use think\Db;
use think\Request;

class UserApi extends BaseApi
{
    /**
     * 中间件
     *
     * array
     */
    protected $middleware = ['User'];

    /**
     * 修改头像
     *
     * @return \think\Response
     */
    public function updateAvatar()
    {

        /**********************   获取文件对象   **********************/
        $file = request()->file('file');

        /**********************   验证并上传   **********************/
        $info = $file->validate(['size' => '5242880', 'ext' => 'jpg,gif,png'])->move('storage');

        /**********************   判断是否成功   **********************/
        if ($info) {
            /**********************   修改数据库的头像路径   **********************/
            $src    = '/storage/' . $info->getSaveName();
            $result = U::update(['id' => $this->userid, 'avatar' => $src]);
            if (empty($result)) {
                return $this->create(400, '修改失败');
            }

            /**********************   返回接口   ***** *****************/
            $resultData = [
                'user_id' => $this->userid,
                'src'     => $src,
            ];
            return $this->create(200, '修改成功', $resultData);
        } else {
            return $this->create(400, $file->getError());
        }
    }

    /**
     * 修改密码
     *
     * @return \think\Response
     */
    public function changePwd(Request $request)
    {
        /**********************   接收参数   **********************/
        $oldPwd   = $request->param('oldPwd/s');
        $newPwd   = $request->param('newPwd/s');
        $reNewPwd = $request->param('reNewPwd/s');

        /**********************   对比原密码   **********************/
        $password = U::where('id', $this->userid)->value('password');
        if (sysmd5($oldPwd) !== $password) {
            return $this->create(400, '原密码不正确');
        }

        /**********************   验证数据   **********************/
        $validate = validate('ResetPass');
        if (!$validate->scene('changePwd')->check(['password' => $newPwd, 'rePassword' => $reNewPwd])) {
            return $this->create(400, $validate->getError());
        }

        /**********************   修改密码   **********************/
        $result = U::update(['id' => $this->userid, 'password' => $newPwd]);
        if (empty($result)) {
            return $this->create(400, '修改失败');
        }

        return $this->create(200, '修改成功');
    }

    /**
     * 手机更改绑定
     *
     * @return \think\Response
     */
    public function changePhone(Request $request)
    {
        /**********************   接收数据   **********************/
        $data = $request->param();

        /**********************   验证手机和密码   **********************/
        $validate = validate('ResetPass');
        if (!$validate->scene('changePhone')->check($data)) {
            return $this->create(400, $validate->getError());
        }

        /**********************   短信验证码   **********************/
        $smsResult = (new CodeApi)->checkCode($data['phone'], $data['code'], 1);
        if ($smsResult['code'] !== 0) {
            return $this->create(400, $smsResult['msg']);
        }

        /**********************   手机号码   **********************/
        $result = U::update(['id' => $this->userid, 'phone' => $data['phone']]);
        if (empty($result)) {
            return $this->create(400, '修改失败');
        }

        /**********************   返回api接口   **********************/
        return $this->create(200, '修改成功');
    }

    /**
     * 我的资料
     *
     * @return \think\Response
     */
    public function changeInfo(Request $request)
    {
        /**********************   接收数据   **********************/
        $data = [
            'id'           => $this->userid,
            'nickname'     => $request->param('user_name/s', ''),
            'introduction' => $request->param('user_desc/s', ''),
            // 'avatar'       => $request->param('user_thumb/s', ''),
        ];

        /**********************   验证数据   **********************/
        $validate = validate()->rule([
            'nickname'     => 'require|max:20',
            'introduction' => 'max:255',
        ])->message([
            'nickname.require' => '昵称不能为空',
            'nickname.max'     => '昵称不能超过20个字符',
            'introduction.max' => '简介不能超过255个字符',
        ]);
        if (!$validate->check($data)) {
            return $this->create(400, $validate->getError());
        }

        /**********************   更新数据   **********************/
        // return $this->create(400, '阿卡丽', $data);
        $result = U::update($data);
        if (empty($result)) {
            return $this->create(400, '修改失败');
        }

        /**********************   返回api接口   **********************/
        return $this->create(200, '修改成功');
    }

    /**
     * 我的收藏
     *
     * @return \think\Response
     */
    public function collectList(Request $request)
    {
        /**********************   接收参数   **********************/
        $type = $request->param('type/d', 1);
        $page = $request->param('page/d', 1);

        /**********************   数据   **********************/
        $pageSize  = 10;
        $collectId = [];
        $count     = 0;
        $list      = [];

        /**********************   收藏文章的id   **********************/
        if ($type == 1) {
            $collectId = AC::where('member_id', $this->userid)
                ->limit($pageSize)
                ->page($page)
                ->order('create_time', 'desc')
                ->column('create_time, article_id');
            // krsort($collectId); 键名排序
            $count = AC::where('member_id', $this->userid)->count();

            /**********************   收藏的文章   **********************/
            $list = A::field('id, title')->whereIn('id', $collectId)->where('status', 1)->select();

        } elseif ($type == 2) {

            /**********************   收藏活动的id   **********************/
            $collectId = ActivityCollect::where('member_id', $this->userid)
                ->limit($pageSize)
                ->page($page)
                ->order('create_time', 'asc')
                ->column('create_time, activity_id');
            $count = ActivityCollect::where('member_id', $this->userid)->count();

            /**********************   收藏的活动   **********************/
            $list = Activity::field('id, title')->whereIn('id', $collectId)->where('status', 1)->select();
        } elseif ($type == 3) {

            /**********************   收藏活动的id   **********************/
            $collectId = CollegeCollect::where('member_id', $this->userid)
                ->limit($pageSize)
                ->page($page)
                ->order('create_time', 'asc')
                ->column('create_time, college_id');
            $count = CollegeCollect::where('member_id', $this->userid)->count();

            /**********************   收藏的活动   **********************/
            $list = College::field('id, title')->whereIn('id', $collectId)->where('status', 1)->select();
        }

        /**********************   收藏时间   **********************/
        $time = [];
        $num  = 0;
        foreach ($collectId as $key => $value) {
            $time[$num] = $key;
            $num++;
        }

        /**********************   收藏时间日期   **********************/
        foreach ($list as $key => $value) {
            $list[$key]['time'] = date('Y-m-d', $time[$key]);
        }

        /**********************   返回api接口   **********************/
        $resultData = [
            'list'      => $list,
            'paginator' => [
                'count'    => $count,
                'page'     => $page,
                'pageSize' => $pageSize,
            ],
            'type'      => $type,
        ];
        return $this->create(200, '获取成功', $resultData);
    }

    /**
     * 删除收藏
     *
     * @return \think\Response
     */
    public function delCollect(Request $request)
    {
        /**********************   接收参数   **********************/
        $type = $request->param('type/d', 1);
        $id   = $request->param('id/d', 1);

        $user = U::get($this->userid);
        if (empty($user)) {
            return $this->create(400, '用户不存在');
        }

        /**********************   删除收藏   **********************/
        if ($type == 1) {
            $result = $user->collects()->detach($id);
        } elseif ($type == 2) {
            $result = $user->activityCollects()->detach($id);
        } elseif ($type == 3) {
            $result = $user->collegeCollects()->detach($id);
        }

        if (empty($result)) {
            return $this->create(400, '删除失败');
        }

        return $this->create(200, '取消成功');
    }

    /**
     * 报名的活动
     *
     * @return \think\Response
     */
    public function activityJoin(Request $request)
    {
        /**********************   接收参数   **********************/
        $page = $request->param('page/d', 1);

        /**********************   数据   **********************/
        $pageSize = 10;
        $joinId   = [];
        $count    = 0;

        /**********************   报名数据   **********************/
        $join = ActivityJoin::withJoin(['activity', 'order'])
            ->where('member_id', $this->userid)
            ->where('order_status', '<>', 2)
            ->limit($pageSize)
            ->page($page)
            ->order('create_time', 'desc')
            ->select();

        /**********************   条数   **********************/
        $count = ActivityJoin::withJoin(['activity', 'order'])
            ->where('member_id', $this->userid)
            ->where('order_status', '<>', 3)
            ->count();

        /**********************   组装接口数据   **********************/
        $list = [];
        foreach ($join as $key => $value) {
            $list[$key]['activity_name']       = $value['activity']['description'];
            $list[$key]['activity_thumb']      = $value['activity']['thumb'];
            $list[$key]['activity_id']         = $value['activity']['id'];
            $list[$key]['activity_name_short'] = $value['activity']['title'];
            $list[$key]['activity_start_time'] = date('Y-m-d H:i', $value['activity']['starttime']);
            $list[$key]['activity_end_time']   = date('Y-m-d H:i', $value['activity']['endtime']);
            $list[$key]['status']              = activity_status($value['activity']['status'], $value['activity']['endtime']);
            $list[$key]['order_status_name']   = order_status($value['order']['order_status']);
            $list[$key]['pay_amount']          = sprintf("%.2f", $value['order']['total']);
            $list[$key]['need_pay']            = $value['order']['need_pay'];
            $list[$key]['pay_status']          = $value['order']['pay_status'];
            $list[$key]['pay_platform']        = !empty($value['order']['alipay_sn']) ? '支付宝' : '微信';
            $list[$key]['order_status']        = $value['order']['order_status'];
            $list[$key]['pay_add_date']        = $value['order']['create_time'];
            $list[$key]['ap_id']               = $value['order']['id'];
            $list[$key]['add_user']            = '草帽跨境';
        }

        /**********************   返回api接口   **********************/
        $resultData = [
            'list'      => $list,
            'paginator' => [
                'count'    => $count,
                'page'     => $page,
                'pageSize' => $pageSize,
            ],
        ];
        return $this->create(200, '获取成功', $resultData);
    }

    /**
     * 报名的课程
     *
     * @return \think\Response
     */
    public function collegeJoin(Request $request)
    {
        /**********************   接收参数   **********************/
        $page = $request->param('page/d', 1);

        /**********************   数据   **********************/
        $pageSize = 10;
        $joinId   = [];
        $count    = 0;

        /**********************   报名数据   **********************/
        $join = CollegeJoin::withJoin(['college', 'order'])
            ->where('member_id', $this->userid)
            ->where('order_status', '<>', 2)
            ->limit($pageSize)
            ->page($page)
            ->order('create_time', 'desc')
            ->select();

        /**********************   条数   **********************/
        $count = CollegeJoin::withJoin(['college', 'order'])
            ->where('member_id', $this->userid)
            ->where('order_status', '<>', 3)
            ->count();

        /**********************   组装接口数据   **********************/
        $list = [];
        foreach ($join as $key => $value) {
            $list[$key]['activity_name']       = $value['college']['description'];
            $list[$key]['activity_thumb']      = $value['college']['thumb'];
            $list[$key]['activity_id']         = $value['college']['id'];
            $list[$key]['activity_name_short'] = $value['college']['title'];
            $list[$key]['activity_start_time'] = date('Y-m-d H:i', $value['college']['start_time']);
            $list[$key]['activity_end_time']   = date('Y-m-d H:i', $value['college']['end_time']);
            $list[$key]['status']              = activity_status($value['college']['status'], $value['college']['end_time']);
            $list[$key]['order_status_name']   = order_status($value['order']['order_status']);
            $list[$key]['pay_amount']          = sprintf("%.2f", $value['order']['total']);
            $list[$key]['need_pay']            = $value['order']['need_pay'];
            $list[$key]['pay_status']          = $value['order']['pay_status'];
            $list[$key]['pay_platform']        = !empty($value['order']['alipay_sn']) ? '支付宝' : '微信';
            $list[$key]['order_status']        = $value['order']['order_status'];
            $list[$key]['pay_add_date']        = $value['order']['create_time'];
            $list[$key]['ap_id']               = $value['order']['id'];
            $list[$key]['add_user']            = '草帽跨境';
        }

        /**********************   返回api接口   **********************/
        $resultData = [
            'list'      => $list,
            'paginator' => [
                'count'    => $count,
                'page'     => $page,
                'pageSize' => $pageSize,
            ],
        ];
        return $this->create(200, '获取成功', $resultData);
    }

    /*
     * 订单取消
     *
     * @return \think\Response
     */
    public function cancelOrder()
    {
        try {
            /**********************   引用文件   **********************/
            // header('Content-type:text/html;charset=utf-8');
            require '../extend/pay/alipay/pagepay/service/AlipayTradeService.php';
            require '../extend/pay/alipay/pagepay/buildermodel/AlipayTradeCloseContentBuilder.php';

            /**********************   接收参数   **********************/
            $order_id = input('id/d'); // 订单id
            $type     = input('type/d'); // 订单类型 1 活动 2 课程

            /**********************   找出订单   **********************/
            if ($type == 1) {
                $order   = AO::with('join.activity')->get($order_id);
                $config  = config('pay.alipay.activity');
                $db_name = 'activity';
                $id      = $order['join']['activity']['id'];
            } elseif ($type == 2) {
                $order   = CO::with('join.college')->get($order_id);
                $config  = config('pay.alipay.college');
                $db_name = 'college';
                $id      = $order['join']['college']['id'];
            }

            /**********************   订单是否存在   **********************/
            if (empty($order)) {
                return $this->create(204, '订单不存在');
            }

            /**********************   订单状态   **********************/
            if ($order['order_status'] == 1) {
                return $this->create(204, '取消失败，您的订单已支付');
            } else if ($order['order_status'] == 2) {
                return $this->create(204, '取消失败，您已取消订单');
            }

            /**********************   构造参数   **********************/

            // 商户订单号，商户网站订单系统中唯一订单号
            $out_trade_no = $order['order_sn'];

            // 支付宝交易号
            $trade_no = '';
            // 请二选一设置

            // 构造参数
            $RequestBuilder = new \AlipayTradeCloseContentBuilder();
            $RequestBuilder->setOutTradeNo($out_trade_no);
            $RequestBuilder->setTradeNo($trade_no);

            $aop = new \AlipayTradeService($config);

            // 支付宝返回信息
            $response = $aop->Close($RequestBuilder);
            // var_dump($response);

            /**********************   不管有没有预支付，都修改订单   **********************/
            // 删除订单
            $result = $order->together('join')->delete();
            // 修改订单
            // $order->order_status = 2;
            // $result              = $order->save();

            /**********************   减少报名人数   **********************/
            Db::table($db_name)->where('id', $id)->setDec('apply_num', $order['number']);

            return $this->create(200, '取消成功');

        } catch (\Exception $e) {
            //错误返回
            return $this->create(400, $e->getMessage());
        }
    }

    /**
     * 订单退款
     *
     * @return \think\Response
     */
    public function refundOrder()
    {
        return $this->create(204, '暂时不支持退款操作');
        try {
            /**********************   引用文件   **********************/
            // header('Content-type:text/html;charset=utf-8');
            require '../extend/pay/alipay/pagepay/service/AlipayTradeService.php';
            require '../extend/pay/alipay/pagepay/buildermodel/AlipayTradeRefundContentBuilder.php';

            /**********************   接收参数   **********************/
            $order_id      = input('id/d');
            $type          = input('type/d'); // 订单类型 1 活动 2 课程
            $refund_reason = input('refund_reason/s', ''); // 退款原因

            /**********************   找出订单   **********************/
            if ($type == 1) {
                $order   = AO::with('join.activity')->get($order_id);
                $config  = config('pay.alipay.activity');
                $db_name = 'activity';
                $id      = $order['join']['activity']['id'];
            } elseif ($type == 2) {
                $order   = CO::with('join.college')->get($order_id);
                $config  = config('pay.alipay.college');
                $db_name = 'college';
                $id      = $order['join']['college']['id'];
            }

            /**********************   订单是否存在   **********************/
            if (empty($order)) {
                return $this->create(204, '订单不存在');
            }

            /**********************   订单状态   **********************/
            if ($order['order_status'] == 0) {
                return $this->create(204, '退款失败，您的订单未支付');
            } else if ($order['order_status'] == 2) {
                return $this->create(204, '退款失败，您已取消订单');
            }

            /**********************   构造参数   **********************/

            // 商户订单号，商户网站订单系统中唯一订单号
            $out_trade_no = $order['order_sn'];

            // 支付宝交易号
            $trade_no = $order['alipay_sn'];
            // 请二选一设置

            // 需要退款的金额，该金额不能大于订单金额，必填
            $refund_amount = $order['total'];

            // 退款的原因说明
            $refund_reason = $refund_reason;

            // 标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传
            $out_request_no = md5($out_trade_no);

            //构造参数
            $RequestBuilder = new \AlipayTradeRefundContentBuilder();
            $RequestBuilder->setOutTradeNo($out_trade_no);
            $RequestBuilder->setTradeNo($trade_no);
            $RequestBuilder->setRefundAmount($refund_amount);
            $RequestBuilder->setOutRequestNo($out_request_no);
            $RequestBuilder->setRefundReason($refund_reason);

            $aop = new \AlipayTradeService($config);

            // 支付宝返回信息
            $response = $aop->Refund($RequestBuilder);
            if (!empty($response->code) && $response->code == 10000) {
                // 修改订单状态
                $order->order_status = 2;
                $order->pay_status   = 2;
                $order->save();

                /**********************   减少报名人数   **********************/
                Db::table($db_name)->where('id', $id)->setDec('apply_num', $order['number']);

                return $this->create(200, '退款成功');
            } else {
                return $this->create(200, '退款失败');
            }

        } catch (\Exception $e) {
            //错误返回
            return $this->create(400, $e->getMessage());
        }
    }

}
