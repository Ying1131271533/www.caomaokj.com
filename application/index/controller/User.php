<?php
namespace app\index\controller;

use app\common\model\ActivityJoin as AJ;
use app\common\model\ActivityOrder as AO;
use app\common\model\ActivityRef as AR;
use app\common\model\CollegeJoin as CJ;
use app\common\model\CollegeOrder as CO;
use app\common\model\CollegeRef as CR;
use app\common\model\Member as U;
use think\facade\View;

class User extends Base
{
    /**
     * 初始化
     *
     * @return view
     */
    public function __construct(\think\App $app)
    {
        parent::initialize();
        empty($this->userid) and $this->redirect('login/index');
        View::assign('action', request()->action());
    }

    /**
     * 消息中心
     *
     * @return view
     */
    public function index()
    {
        /**********************   找到用户   **********************/
        $user = U::field('phone, email, wechat, qq')->find($this->userid);
        empty($user) and akali('找不到用户');

        View::assign([
            'user'    => $user,
            'toolbar' => true, // 手机端显示底部工具栏
        ]);
        return view();
    }

    /**
     * 修改头像
     *
     * @return view
     */
    public function avatar()
    {
        /**********************   找到用户   **********************/
        $user = U::where('id', $this->userid)->value('id');
        empty($user) and akali('找不到用户');

        return view();
    }

    /**
     * 账号安全
     *
     * @return view
     */
    public function security()
    {
        /**********************   找到用户   **********************/
        $user = U::field('phone')->find($this->userid);
        empty($user) and akali('找不到用户');

        View::assign('user', $user);
        return view();
    }

    /**
     * 我的资料
     *
     * @return view
     */
    public function info()
    {
        /**********************   找到用户   **********************/
        $user = U::field('nickname, phone, introduction')->get($this->userid);
        empty($user) and akali('找不到用户');

        View::assign('user', $user);
        return view();
    }

    /**
     * 我的收藏
     *
     * @return view
     */
    public function collect()
    {
        return view();
    }

    /**
     * 报名的活动
     *
     * @return view
     */
    public function activity()
    {
        return view();
    }

    /**
     * 活动订单详情
     *
     * @return view
     */
    public function activityOrder($order_id)
    {
        /**********************   接收参数   **********************/
        if (!validate()->isInteger($order_id)) {
            return akali('参数有误！');
        }

        /**********************   找出订单，是否存在   **********************/
        $order = AO::with('join.activity')->where('order_status', '<>', 2)->find($order_id);
        if (empty($order)) {
            return akali('找不到该订单');
        }

        /**********************   用户转发带来的点击量   **********************/
        $clickNum = AR::where('member_id', $this->userid)->count();

        /**********************   我转发带来的报名   **********************/
        $joinNum = AJ::where('ref_user', $this->userid)->count();

        /**********************   视图赋值   **********************/
        View::assign([
            'order'    => $order,
            'join'     => $order['join'],
            'activity' => $order['join']['activity'],
            'clickNum' => $clickNum,
            'joinNum'  => $joinNum,
        ]);
        return view();
    }

    /**
     * 活动开发票
     *
     * @return view
     */
    public function activityInvoice($order_id)
    {
        /**********************   接收参数   **********************/
        if (!validate()->isInteger($order_id)) {
            return akali('参数有误！');
        }

        /**********************   找出订单，是否存在   **********************/
        $order = AO::with(['join.activity'])->find($order_id);
        if (empty($order)) {
            return akali('找不到该订单');
        }

        /**********************   提交发票   **********************/
        if (request()->isPost()) {
            dump(input());
            return;
        }

        /**********************   视图赋值   **********************/
        View::assign([
            'order'    => $order,
            'join'     => $order['join'],
            'activity' => $order['join']['activity'],
        ]);

        return view();
    }

    /**
     * 报名的课程
     *
     * @return view
     */
    public function college()
    {
        return view();
    }

    /**
     * 课程订单详情
     *
     * @return view
     */
    public function collegeOrder($order_id)
    {
        /**********************   接收参数   **********************/
        if (!validate()->isInteger($order_id)) {
            return akali('参数有误！');
        }

        /**********************   找出订单，是否存在   **********************/
        $order = CO::with('join.college')->where('order_status', '<>', 2)->find($order_id);
        if (empty($order)) {
            return akali('找不到该订单');
        }

        /**********************   用户转发带来的点击量   **********************/
        $clickNum = CR::where('member_id', $this->userid)->count();

        /**********************   我转发带来的报名   **********************/
        $joinNum = CJ::where('ref_user', $this->userid)->count();

        /**********************   视图赋值   **********************/
        View::assign([
            'order'    => $order,
            'join'     => $order['join'],
            'college'  => $order['join']['college'],
            'tickets'  => $order['join']['tickets'],
            'clickNum' => $clickNum,
            'joinNum'  => $joinNum,
        ]);
        return view();
    }

    /**
     * 修改密码
     *
     * @return view
     */
    public function changePwd()
    {
        return view();
    }

    /**
     * 修改绑定手机
     *
     * @return view
     */
    public function changePhone()
    {
        return view();
    }
}
