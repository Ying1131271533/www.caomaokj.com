<?php
namespace app\usezan\logic;

use app\common\model\CollegeTickets;
use app\common\model\Tickets as TicketsModel;

class Tickets
{
    /**
     * 添加门票
     *
     * @param  array    $params     门票数据
     * @return json                 api返回的json数据
     */
    public static function saveTickets()
    {
        // 获取数据
        $params = check_param();
        $tickets = new TicketsModel();
        if(isset($params['id'])){
            $tickets = $tickets->find($params['id']);
            if(!$tickets) jinx('数据不存在');
        }
        $result = $tickets->save($params);
        if(!$result) jinx('操作失败');
        jinx();
    }

    public static function deleteById($id)
    {
        // 查出门票数据
        $tickets = TicketsModel::get($id);
        if(!$tickets){
            jinx('该门票不存在');
        }

        // 找到关联的课程
        $ticketsColleges = CollegeTickets::where('tickets_id', $id)->find();
        if($ticketsColleges){
            jinx('有课程在占用门票');
        }
        
        // 删除数据
        $result = $tickets->delete();
        if (!$result) {
            jinx('删除失败');
        }
        jinx();
    }
}
