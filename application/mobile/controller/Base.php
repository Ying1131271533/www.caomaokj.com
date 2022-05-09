<?php
namespace app\mobile\controller;

use app\common\model\Category;
use app\common\model\Member as U;
use app\common\model\Menu;
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
    // 头像
    protected $avatar;
    // 模版路径
    protected $view;

    /**
     * 初始化
     *
     * @param
     * @return
     */
    public function initialize()
    {
        parent::initialize();
        if (session('?user')) {
            $this->userid   = session('user')['id'];
            $this->username = session('user')['username'];
            $this->avatar   = $this->getAvatar($this->userid);
        } else {
            $this->userid   = '';
            $this->username = '';
            $this->avatar   = '';
        }

        View::assign([
            'controller' => strtolower(request()->controller()),
            'userid'     => $this->userid,
            'username'   => $this->username,
            'avatar'     => $this->avatar,
            'time'       => time(), // 时间戳 js css 更新使用
        ]);
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
