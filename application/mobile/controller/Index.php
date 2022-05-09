<?php
namespace app\mobile\controller;

use app\common\model\Activity;
use app\common\model\Article;
use app\common\model\Category;
use app\common\model\College;
use app\common\model\Continent;
use app\common\model\Keyword;
use app\common\model\Links;
use app\common\model\Logistics;
use app\common\model\Platform as P;
use app\common\model\Service;
use app\common\model\Slide;
use think\facade\View;

class Index extends Base
{
    /**
     * 首页
     *
     * @param
     * @return view
     */
    public function index()
    {
        return $this->fetch();
    }

}
