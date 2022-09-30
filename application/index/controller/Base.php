<?php
namespace app\index\controller;

use app\common\model\Category as C;
use app\common\model\Continent;
use app\common\model\Member as U;
use app\common\model\Menu;
use app\common\model\Service as S;
use app\index\logic\WechatApplet;
use think\Controller;
use think\facade\View;

class Base extends Controller
{
    // 导航菜单
    protected $menu;
    // 用户id
    protected $userid;
    // 用户名
    protected $username;
    // 昵称
    protected $nickname;
    // 头像
    protected $avatar;
    // 六大洲
    protected $continents;
    // 当前时间戳
    protected $time;

    /**
     * 初始化
     *
     * @param
     * @return
     */
    public function initialize()
    {
        parent::initialize();
        
        /**********************   导航菜单   **********************/
        $this->menu = C::field('id, catname, parentid, url, iconimg, target, class')
            ->where(["status" => 1, 'is_menu' => 1])
            ->order("listorder", "asc")
            // ->cache(cache_time('one_week'))
            ->select()
            ->toArray();
        $this->time = time();
        halt($this->menu);
        if (session('?user')) {
            $user           = U::get(session('user.id'));
            $this->userid   = $user['id'];
            $this->username = $user['username'];
            $this->nickname = $user['nickname'];
            $this->avatar   = $user['avatar'];
        } else {
            $this->userid   = '';
            $this->username = '';
            $this->nickname = '';
            $this->avatar   = '';
        }
        
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

        /**********************   手机底部服务展开   **********************/
        $service = C::with('services')->field('id, catname')->select([113, 110, 116]);
        View::assign('service', $service);

        View::assign('menu', getChild($this->menu));
        View::assign('continents', Continent::select());
        View::assign('controller', strtolower(request()->controller()));
        View::assign('userid', $this->userid);
        View::assign('username', $this->username);
        View::assign('nickname', $this->nickname);
        View::assign('avatar', $this->avatar);
        // 时间戳 js css 更新使用
        View::assign('time', $this->time);
        // 手机端底部工具栏是否显示 默认不显示
        View::assign('toolbar', false);
        // 微信自定义
        View::assign('wechat', $wechat);
    }

    /**
     * 用户头像
     *
     * @param int $id
     * @return string $avatar
     */
    public function getAvatar($id)
    {
        $avatar = U::where('id', $id)->value('avatar');
        return $avatar;
    }
}
