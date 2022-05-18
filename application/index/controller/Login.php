<?php
namespace app\index\controller;

use app\common\model\Category;
use app\common\model\Continent;
use app\common\model\Member as U;
use app\index\logic\WechatApplet;
use think\Controller;
use think\facade\View;

class Login extends Controller
{
    // 六大洲
    protected $continents;
    protected $time;

    /**
     * 初始化
     *
     * @param
     * @return
     */
    protected function initialize()
    {
        parent::initialize();
        // 导航菜单
        $menu = Category::field('id, catname, parentid, url, iconimg, target, class')
            ->where(["status" => 1, 'is_menu' => 1])
            ->order("listorder", "asc")
            ->select()
            ->toArray();
        /**********************   自定义微信分享   **********************/
        $url       = 'https://www.caomaokj.com' . request()->url();
        $rand_char = get_rand_char(32);
        $signature = (new WechatApplet($this->time, $rand_char, $url))->getWxSignature();
        $wechat    = [
            'link'      => $url,
            'imgUrl'    => 'https://www.caomaokj.com//static/icon/icon-title.png',
            'title'     => '草帽跨境 - 跨境卖家综合服务平台',
            'desc'      => '草帽跨境 - 跨境卖家综合服务平台',
            'signature' => $signature,
            'timestamp' => $this->time,
            'nonceStr'  => $rand_char,
            'appid'     => config('wechat.app_id'),
        ];

        View::assign('wechat', $wechat);
        View::assign('time', time());
        View::assign('menu', getChild($menu));
        View::assign('continents', Continent::select());
        View::assign('controller', strtolower(request()->controller()));
    }

    /**
     * 用户登录
     *
     * @param
     * @return view
     */
    public function index()
    {
        // echo '阿卡丽';
        return view();
    }

    /**
     * 注册
     *
     * @param
     * @return view
     */
    public function register()
    {

        return view();
    }

    /**
     * 用户使用协议
     *
     * @param
     * @return view
     */
    public function agreement()
    {
        return view();
    }

    /**
     * 忘记密码
     *
     * @param
     * @return view
     */
    public function resetpass()
    {
        return view();
    }

    /**
     * 退出登录
     *
     * @param
     * @return redirect 重定向
     */
    public function logout()
    {
        // 清除登录状态
        session("user", null);
        cookie("user", null);
        return $this->redirect('/');
    }

}
