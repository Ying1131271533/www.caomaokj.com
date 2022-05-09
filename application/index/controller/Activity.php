<?php
namespace app\index\controller;

use app\common\model\Activity as A;
use app\common\model\ActivityOrder as AO;
use app\common\model\ActivityRef as AR;
use app\common\model\Category as C;
use app\common\model\Member as U;
use app\common\model\Slide;
use app\index\logic\WechatApplet;
use think\facade\Cache;
use think\facade\View;

class Activity extends Base
{
    /*
     * 活动列表
     *
     * index
     */
    public function index()
    {
        /**********************   轮播图   **********************/
        $banner = Slide::where(['cid' => 144, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->select();
        // halt($banner);
        /**********************   手机轮播图   **********************/
        $phoneBanner = Slide::where(['cid' => 145, 'status' => 1])
            ->field('id, thumb, title, url')
            ->order('listorder', 'desc')
            ->find();

        /**********************   跨境活动分类   **********************/
        $category = C::where(['parentid' => 56, 'status' => 1])
            ->field('id, catname')
            ->order(['listorder' => 'desc', 'id' => 'asc'])
            ->select();

        /**********************   接收参数   **********************/
        $keyword = request()->param('keyword/s', ''); // 活动类型
        $type    = request()->param('type/d', 0); // 活动分类
        $time    = request()->param('time/d', 0); // 时间范围
        $by      = request()->param('by/d', 0); // 最新、最热
        $free    = request()->param('free/d', 0); // 免费
        $ing     = request()->param('ing/d', 0); // 进行中、未结束
        $page    = request()->param('page/d', 1); // 分页

        /**********************   组装条件   **********************/
        $where = [];
        $order = ['listorder' => 'desc', 'createtime' => 'desc'];

        // 搜索
        $keyword and $where[] = ['title', 'like', '%' . $keyword . '%'];

        // 分类
        $type and $where[] = ['catid', '=', $type];
        // 分类名称
        $typeName              = C::where('id', $type)->value('catname');
        $typeName or $typeName = '类型';

        // 时间范围
        switch ($time) {
            case 1:
                $where[]  = ['starttime', 'between time', [strtotime("today"), time() + 604800]];
                $timeName = '近一周';
                break;
            case 2:
                $where[]  = ['starttime', 'between time', [strtotime("today"), time() + 2592000]];
                $timeName = '近一个月';
                break;
            case 3:
                $where[]  = ['starttime', 'between time', [strtotime("today"), time() + 7776000]];
                $timeName = '近三个月';
                break;
            default:
                $timeName = '时间';
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
        $activity = A::where($where)
            ->field('id, endtime, starttime, address, thumb, title, discount, endtime')
            ->order($order)
            ->paginate(12);

        /**********************   获取文章总条数   **********************/
        $count = A::where($where)->count();

        /**********************   变量赋值   **********************/
        View::assign([
            'title'       => '首页',
            'banner'      => $banner,
            'phoneBanner' => $phoneBanner,
            'category'    => $category,
            'keyword'     => $keyword,
            'typeName'    => $typeName,
            'timeName'    => $timeName,
            'activity'    => $activity,
            'count'       => $activity->total(),
            'toolbar'     => true, // 手机端显示底部工具栏
        ]);

        return View::fetch();
    }

    /*
     * 活动详情
     *
     * detail
     */
    public function detail($id)
    {
        /**********************   参数判断   **********************/
        if (!validate()->isInteger($id)) {
            return akali('参数错误');
        }

        /**********************   跨境活动数据   **********************/
        $activity = A::get($id);
        empty($activity) and akali('活动不存在');
        if ($activity['tickets_num'] - $activity['apply_num'] <= 0) {
            $activity['join_num'] = '名额已满';
        } else {
            $activity['join_num'] = $activity['tickets_num'] - $activity['apply_num'];
        }

        /**********************   是否用户推荐   **********************/
        $refUserId = input('ref_user/d');
        $refUserIp = U::where('id', $refUserId)->value('ip'); // 获取推荐用户ip地址
        $ip        = getIp(); // 被推荐用户id
        /*dump($refUserId);
        dump($refUserIp);
        dump($ip);
        dump(ip());
        echo '阿卡丽';
        return;*/
        if (!empty($refUserId) && !empty($refUserIp) && $ip != $refUserIp) {
            $activityRef = AR::where(['ip' => $ip, 'member_id' => $refUserId])->find();
            if (empty($activityRef)) {
                /**********************   增加访问记录   **********************/
                $result = AR::create([
                    'activity_id' => $activity['id'],
                    'ip'          => $ip,
                    'member_id'   => $refUserId,
                ]);
            }
        }

        /**********************   用户是否收藏了   **********************/
        $collect = '';
        if (!empty($this->userid)) {
            $collect = $activity->collects()->where('id', $this->userid)->value('id');
        }

        /**********************   举办活动次数、参与人数   **********************/
        $activityNum    = A::count();
        $activityPeople = A::sum('apply_num');

        /**********************   您可能还会关注   **********************/
        $activityKeyword = $activity->keywords()->find();
        if ($activityKeyword) {
            $likeactivity = $activityKeyword->activitys()
                ->field('id, title, thumb')
                ->order('id', 'asc')
                ->limit(3)
                ->select();
        } else {
            $likeactivity = [];
        }

        /**********************   变量赋值   **********************/
        View::assign([
            'activity'       => $activity,
            'collect'        => $collect,
            'activityNum'    => $activityNum,
            'activityPeople' => $activityPeople,
            'likeactivity'   => $likeactivity,
            // 微信自定义
            'imgUrl'         => 'https://www.caomaokj.com' . $activity['thumb'],
            'desc'           => $activity['description'],
        ]);
        return view();
    }

    /*
     * 活动报名
     *
     * detail
     */
    public function join($id)
    {
        /**********************   参数判断   **********************/
        if (!validate()->isInteger($id)) {
            return akali('参数错误');
        }

        /**********************   找出活动   **********************/
        $activity = A::get($id);
        empty($activity) and akali('活动不存在');

        /**********************   变量赋值   **********************/
        View::assign('activity', $activity);
        return view();
    }

    /*
     * 草帽跨境协议
     *
     * detail
     */
    public function agreement()
    {
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
        // 接收参数
        $order_id = input('order_id/d');

        // 验证数据
        $validate = validate('ActivityOrder');
        if (!$validate->check(['order_id' => $order_id])) {
            return akali($validate->getError());
        }

        // 找出订单
        $order = AO::with('join.activity')->get($order_id);

        // 变量赋值
        View::assign([
            'order'    => $order,
            'join'     => $order['join'],
            'activity' => $order['join']['activity'],
        ]);

        return View::fetch();
    }

    /*
     * 报名成功
     *
     * success
     */
    public function orderSuccess($order_id)
    {
        // 找出订单
        $order = AO::with('join')->where(['order_status' => 1])->find($order_id);

        // 订单是否存在
        empty($order) and akali('订单不存在');

        // 变量赋值
        View::assign([
            'order_id'    => $order['id'],
            'activity_id' => $order['join']['activity_id'],
        ]);

        return View::fetch();
    }

}
