<?php
namespace app\index\controller;

use app\common\model\Category;
use app\common\model\College as CollegeModel;
use app\common\model\CollegeOrder as CO;
use app\common\model\CollegeRef as CR;
use app\common\model\Member as U;
use app\common\model\Slide;
use app\common\model\Tickets as TicketsModel;
use app\index\logic\WechatApplet;
use think\facade\View;

class College extends Base
{
    /*
     * 课程列表
     *
     * index
     */
    public function index()
    {
        /**********************   跨境课程数据   **********************/
        $category = Category::field('id, catname')
            ->where(['status' => 1, 'parentid' => 72])
            ->order(['listorder' => 'asc', 'id' => 'asc'])
            ->select();

        // 课程

        $college = CollegeModel::with(['category', 'tickets'])
            ->field('id, catid, title, thumb, address, start_time, end_time, discount')
            ->where('status', 1)
            ->select();
        // halt($college);
        /**********************   轮播图   **********************/
        $banner = Slide::where(['cid' => 146, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->select();

        /**********************   变量赋值   **********************/
        View::assign([
            'banner'   => $banner,
            'category' => $category,
            'college'  => $college,
            'toolbar'  => true, // 手机端显示底部工具栏
        ]);
        return View::fetch();
    }

    /*
     * 课程详情
     *
     * detail
     */
    public function detail($id)
    {
        /**********************   参数判断   **********************/
        if (!validate()->isInteger($id)) {
            return akali('参数错误');
        }

        /**********************   跨境课程数据   **********************/
        $college = CollegeModel::with('tickets')->get($id);
        empty($college) and akali('课程不存在');
        if ($college['tickets_num'] - $college['apply_num'] <= 0) {
            $college['join_num'] = '名额已满';
        } else {
            $college['join_num'] = $college['tickets_num'] - $college['apply_num'];
        }

        // 获取门票数组最后一个键
        $tickesEndLen = count($college['tickets']) - 1;

        /**********************   是否用户推荐   **********************/
        $refUserId = input('ref_user/d');
        $refUserIp = U::where('id', $refUserId)->value('ip'); // 获取推荐用户ip地址
        $ip        = getIp(); // 被推荐用户id
        if (!empty($refUserId) && !empty($refUserIp) && $ip != $refUserIp) {
            $collegeRef = CR::where(['ip' => $ip, 'member_id' => $refUserId])->find();
            if (empty($collegeRef)) {
                /**********************   增加访问记录   **********************/
                $result = CR::create([
                    'college_id' => $college['id'],
                    'ip'         => $ip,
                    'member_id'  => $refUserId,
                ]);
            }
        }

        /**********************   举办课程次数、参与人数   **********************/
        $collegeNum    = CollegeModel::count();
        $collegePeople = CollegeModel::sum('apply_num');

        /**********************   您可能还会关注   **********************/
        $likeCollege = CollegeModel::field('id, title, thumb')
            ->where([['status', '=', 1], ['id', '<>', $id], ['end_time', '>', time()]])
            ->limit(2)
            ->select();

        /**********************   自定义微信分享   **********************/
        $url = 'https://www.caomaokj.com/index/college/detail/id/' . $id;
        // $url       = 'https://www.caomaokj.com' . url('index/college/detail', ['id' => $id]);
        $rand_char = get_rand_char(32);
        $signature = (new WechatApplet($this->time, $rand_char, $url))->getWxSignature();
        $wechat    = [
            'link'         => $url,
            'imgUrl'       => 'https://www.caomaokj.com' . $college['thumb'],
            'title'        => $college['title'],
            'desc'         => $college['description'],
            'signature'    => $signature,
            'timestamp'    => $this->time,
            'nonceStr'     => $rand_char,
            'appid'        => config('wechat.app_id'),
        ];

        /**********************   门票数据   **********************/
        $ticketsIds = $college->tickets()->column('id');
        $tickets    = TicketsModel::whereIn('id', $ticketsIds)->select();

        /**********************   变量赋值   **********************/
        View::assign('college', $college);
        View::assign('tickesEndLen', $tickesEndLen);
        View::assign('collegeNum', $collegeNum);
        View::assign('collegePeople', $collegePeople);
        View::assign('likeCollege', $likeCollege);
        View::assign('wechat', $wechat);
        return view();
    }

    /*
     * 课程报名
     *
     * join
     */
    public function join()
    {
        /**********************   参数判断   **********************/
        $params   = input();
        $validate = new \app\index\validate\CollegeJoin;
        if (!$validate->check($params)) {
            akali($validate->getError());
        }

        /**********************   找出课程   **********************/
        $college = CollegeModel::get($params['college_id']);
        empty($college) and akali('课程不存在');

        // 找出门票
        $tickets = $college->tickets()->where('id', $params['tickets_id'])->find();
        empty($college) and akali('门票不存在');

        /**********************   变量赋值   **********************/
        View::assign('params', $params);
        View::assign('college', $college);
        View::assign('tickets', $tickets);
        return view();
    }

    /*
     * 活动报名订单 - 确认支付页面
     *
     * confirm
     * return view
     */
    public function confirm()
    {
        /**********************   接收参数   **********************/
        $order_id = input('order_id/d');

        /**********************   验证数据   **********************/
        $validate = validate('CollegeOrder');
        if (!$validate->check(['order_id' => $order_id])) {
            return akali($validate->getError());
        }

        /**********************   找出订单   **********************/
        $order = CO::with('join.college.tickets')->get($order_id);

        // 找出订单门票
        $tickets = TicketsModel::get($order['join']['tickets_id']);
        /**********************   变量赋值   **********************/
        View::assign([
            'order'   => $order,
            'join'    => $order['join'],
            'college' => $order['join']['college'],
            'tickets' => $tickets,
        ]);

        return View::fetch();
    }

    /*
     * 报名成功
     *
     * success
     */
    public function orderSuccess($order_sn)
    {
        /**********************   找出订单   **********************/
        $order = CO::with('join')->where(['order_status' => 1, 'order_sn' => $order_sn])->find();

        /**********************   订单是否存在   **********************/
        empty($order) and akali('订单不存在');

        /**********************   变量赋值   **********************/
        View::assign([
            'order_id'   => $order['id'],
            'college_id' => $order['join']['college_id'],
        ]);

        return View::fetch();
    }

}
