<?php
namespace app\usezan\logic;

use app\common\model\Community as ModelCommunity;
use app\common\model\CommunityDetail;
use app\common\model\CommunityImg;

class Community
{
    /**
     * 获取行业社群列表
     *
     * @param  array        $params     参数
     * @return objct        $list       行业社群数据分页列表
     */
    public static function getCommunityList($params)
    {
        $where  = [];
        !empty($params['user_id']) and $where[] = ['id', '=', $params['user_id']];
        !empty($params['username']) and $where[] = ['username', 'like', '%'.$params['username'].'%'];
        
        if(!empty($params['status'])){
            if($params['status'] == '1'){
                $where[] = ['status', '=', 1];
            }else if($params['status'] == '2'){
                $where[] = ['status', '=', 0];
            }
        }
        
        $list = ModelCommunity::getPageList($where, 30);
        return $list;
    }

    /**
     * 保存
     *
     * @param  array    $params     参数
     * @return string               返回信息
     */
    public static function saveCommunity($params)
    {
        $community = new ModelCommunity();
        // 如果是更新方式，保存id变量和模型
        $id = isset($params['id']) ? $params['id'] : null;
        if($id){
            $community = $community->get($id);
            if (!$community) return jinx('找不到数据');
            $old_logo = $community['logo'];
            $old_imgs = $community->imgs()->column('path');
        }
        // 开启事务
        $community->startTrans();
        try {
            
            // 更新方式：删除旧数据
            if($id){
                CommunityImg::where('community_id', $id)->delete();
                CommunityDetail::where('community_id', $id)->delete();
            }

            // 保存数据
            $community->save($params);
            $community->detail()->save($params);

            if(empty($params['imgs'])) return jinx('图片介绍不能为空');
            foreach($params['imgs'] as $value){
                $community->imgs()->save(['path' => $value]);
            }
            
            $community->commit();

            // 删除旧图片
            del_img($old_logo, $params['logo']);
            del_imgs($old_imgs, $params['imgs']);

            return jinx('保存成功');
        } catch (\Throwable $th) {
            $community->rollback();
            return jinx($th->getMessage());
        }
    }

    /**
     * 删除
     *
     * @param  int      $id         行业社群id
     * @return string               返回信息
     */
    public static function deleteById($id)
    {
        $community = ModelCommunity::get($id);
        if(!$community) return jinx('数据不存在');

        // 开启事务
        $community->startTrans();
        try {
            $community->delete();
            CommunityImg::where('community_id', $id)->delete();
            CommunityDetail::where('community_id', $id)->delete();
            $community->commit();
            return jinx('删除成功');
        } catch (\Throwable $th) {
            $community->rollback();
            return jinx($th->getMessage());
        }
    }
}
