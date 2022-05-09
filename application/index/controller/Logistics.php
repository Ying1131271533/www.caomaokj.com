<?php
namespace app\index\controller;

use app\common\model\Article;
use app\common\model\Attributes as A;
use app\common\model\Category as C;
use app\common\model\Logistics as L;
use app\common\model\LogisticsServiceType as LS;
use think\facade\View;

class Logistics extends Base
{
    /*
     * 物流资源列表
     *
     * index
     */
    public function index()
    {
        /**********************   渠道类型   **********************/
        $categoryData = C::where(['parentid' => 48, 'status' => 1])
            ->field('id, catname')
            ->order(['listorder' => 'desc', 'id' => 'asc'])
            ->select();

        /**********************   走货属性   **********************/
        $attributesData = A::where(['status' => 1])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        /**********************   服务类型   **********************/
        $seviceTypeData = LS::where(['status' => 1])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        // 接收参数
        $cate = input('cate/d', '');
        $attr = input('attr/d', '');
        $type = input('type/d', '');

        $where                     = [];
        !empty($cate) and $where[] = ['c.id', '=', $cate];
        !empty($attr) and $where[] = ['a.id', '=', $attr];
        !empty($type) and $where[] = ['lst.id', '=', $type];

        $logistics = L::alias('l')
            ->join('category c', 'l.catid=c.id')
            ->join('logistics_attributes la', 'l.id=la.logistics_id')
            ->join('attributes a', 'a.id=la.attributes_id')
            ->join('logistics_service ls', 'l.id=ls.logistics_id')
            ->join('logistics_service_type lst', 'lst.id=ls.logistics_service_type_id')
            ->where($where)
            ->group('l.id') // 以logistics表的id进行分组统计，去除重复数据
            ->order(['l.listorder' => 'desc', 'l.id' => 'desc'])
            ->field('l.*')
            ->paginate(12);
        // dump($logistics->toArray());return;

        /**********************   变量赋值   **********************/
        View::assign([
            'cate'           => $cate,
            'attr'           => $attr,
            'type'           => $type,
            'categoryData'   => $categoryData,
            'attributesData' => $attributesData,
            'seviceTypeData' => $seviceTypeData,
            'logistics'      => $logistics,
            'page'           => $logistics->currentPage(),
            'count'          => $logistics->total(),
            'toolbar'        => true, // 手机端显示底部工具栏
        ]);

        return View::fetch();
    }

    /*
     * 物流资源详情
     *
     * detail
     */
    public function detail()
    {
        /**********************   接收参数   **********************/
        $id = input('id/d');
        empty($id) and akali('参数错误');

        /**********************   跨境物流资源数据   **********************/
        $logistics = L::where('status', 1)
            ->field('id, title, thumb, address, qq, phone, email, wechat, content')
            ->get($id);
        empty($logistics) and akali('物流资源不存在');

        /**********************   新闻中心   **********************/
        $article = Article::where(['status' => 1, 'catid' => 68])
            ->field('id, title, thumb, description, createtime')
            ->limit(3)
            ->order(['listorder' => 'desc', 'id' => 'desc'])
            ->select();

        /**********************   变量赋值   **********************/
        View::assign('logistics', $logistics);
        View::assign('article', $article);
        return view();
    }

}
