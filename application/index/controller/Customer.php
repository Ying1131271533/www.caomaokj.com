<?php
namespace app\index\controller;

use app\common\model\Slide;
use think\facade\View;

class Customer extends Base
{
    /**
     * 联系客服
     *
     * @param
     * @return view
     */
    public function contact()
    {

        return View::fetch();
    }

    /**
     * 意见反馈
     *
     * @param
     * @return view
     */
    public function suggestion()
    {
        return View::fetch();
    }

    /**
     * 电商平台入驻咨询
     *
     * @param
     * @return view
     */
    public function platform()
    {
        return View::fetch();
    }

    /**
     * 活动报名咨询
     *
     * @param
     * @return view
     */
    public function activity()
    {
        return View::fetch();
    }

    /**
     * 活动报名咨询
     *
     * @param
     * @return view
     */
    public function college()
    {
        return View::fetch();
    }

}
