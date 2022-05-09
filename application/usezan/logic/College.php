<?php
namespace app\usezan\logic;

use app\common\model\CollegeTickets;
use app\common\model\College as CollegeModel;
use app\common\model\Tickets as TicketsModel;

class College
{
    /**
     * 添加门票
     *
     * @param  array    $params     提交数据
     * @return json                 api返回的json数据
     */
    public static function saveColege($params)
    {
        $college = new CollegeModel();
        if(isset($params['id'])){
            $college = $college->get($params['id']);
            if(!$college) jinx('课程不存在');
            // 获取关联数据
            $collegeTicketsIds = $college->tickets()->column('id');
        }
        
        $ticketsIds          = $params['ticketsId'];
        $params['start_time'] = strtotime($params['start_time']);
        $params['end_time']   = strtotime($params['end_time']);
        // halt($ticketsId);

        // 开启事务
        $college->startTrans();

        $result = $college->save($params);
        if (!$result) {
            // 回滚事务
            $college->rollback();
            jinx('添加失败');
        }
        
        // 删除原来的关联门票
        if(isset($params['id'])  && $collegeTicketsIds){
            // $collegeTicketsDel = CollegeTickets::where()->destroy($collegeTicketsIds);
            $collegeTicketsDel = $college->tickets()->detach($collegeTicketsIds);
            if (!$collegeTicketsDel) {
                $college->rollback();
                jinx('关联门票删除失败');
            }
        }
        
        // 保存门票
        $result = $college->tickets()->saveAll($ticketsIds);
        if ($result === false) {
            // 回滚事务
            $college->rollback();
            jinx('关联门票保存失败');
        }
        
        // 提交事务
        $college->commit();
        jinx('保存成功');
    }

    public static function deleteById($id)
    {
        // 查出课程数据
        $college = CollegeModel::get($id);
        if(!$college) jinx('该课程不存在');
        
        $joins = $college->joins;
        if($joins) jinx('该课程有报名数据，不能删除');
        
        $thumb = $college['thumb'];
        $ticketsIds = $college->tickets()->column('id');
        
        $college -> startTrans();
        $ticketsDel = $college->tickets()->detach($ticketsIds);
        $resultDel = $college->delete();
        if($ticketsDel && $resultDel){
            if ($thumb) del_oldthumb($thumb);
            $college->commit();
            jinx('删除成功');
        } else {
            $college -> rollback();
            jinx('删除失败');
        }
    }
}
