<?php

namespace app\index\logic;

use app\common\model\Article;
use app\common\model\Attributes;
use app\common\model\Category;
use app\common\model\Logistics as ModelLogistics;
use app\common\model\LogisticsServiceType;

class Logistics
{
    /**
     * 获取物流资源数据
     *
     * @return array    $params   参数
     */
    public static function getLogisticsData($params)
    {
        // 渠道类型
        $categoryData = Category::where(['parentid' => 48, 'status' => 1])
            ->field('id, catname')
            ->order(['listorder' => 'desc', 'id' => 'asc'])
            ->select();

        // 走货属性
        $attributesData = Attributes::where(['status' => 1])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        // 服务类型
        $seviceTypeData = LogisticsServiceType::where(['status' => 1])
            ->field('id, name')
            ->order(['sort' => 'desc', 'id' => 'asc'])
            ->select();

        // 接收参数
        $cate = isset($params['cate']) ? $params['cate'] : null;

        $where = [];
        // 组装条件
        empty($params['cate']) or $where[] = ['catid', '=', $params['cate']];
        // halt($where);

        $logistics = ModelLogistics::where($where)
            ->order(['listorder' => 'desc', 'id' => 'desc'])
            ->select();
        // halt($logistics);

        $data = [
            'cate'           => $cate,
            'categoryData'   => $categoryData,
            'attributesData' => $attributesData,
            'seviceTypeData' => $seviceTypeData,
            'logistics'      => $logistics,
            'toolbar'        => true, // 手机端显示底部工具栏
        ];

        //  如果有分类id，则显示物流相关文章
        if ($cate) {
            $article = Article::where(['catid' => $cate, 'status' => 1])
                ->order(['listorder' => 'desc', 'id' => 'desc'])
                ->paginate(12);

            $data['article'] = $article;
            $data['page']    = $article->currentPage();
            $data['count']   = $article->total();
        }
        // halt($article->toArray());

        // 返回数据
        return $data;
    }
}
