<?php
namespace app\usezan\logic;

use app\common\model\Member as ModelMember;

class Member
{
    /**
     * @description:  オラ!オラ!オラ!オラ!⎛⎝≥⏝⏝≤⎛⎝
     * @author: 神织知更
     * @time: 2022/03/29 15:40
     *
     * 获取用户列表
     *
     * @param  array        $params     参数
     * @return objct        $list       用户数据列表
     */
    public static function getMemberList($params)
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
        
        $list = ModelMember::getMemberDate($where);
        return $list;
    }
}
