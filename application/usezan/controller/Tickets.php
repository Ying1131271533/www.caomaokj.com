<?php
namespace app\usezan\controller;

use app\common\model\Tickets as TicketsModel;
use app\usezan\logic\Tickets as TicketsLogic;

class Tickets extends Base
{
    public function index()
    {
        $list = TicketsModel::paginate(30);
        return view('', ['list' => $list]);
    }

    public function add()
    {
        if (request()->isPost()) {
            TicketsLogic::saveTickets();
        }
        return view('', []);
    }

    public function edit()
    {
        $params = input();
        if (request()->isPost()) {
            TicketsLogic::saveTickets($params);
        }

        $tickets = TicketsModel::find($params['id']);
        return view('', ['tickets' => $tickets]);
    }

    public function delete($id)
    {
        TicketsLogic::deleteById($id);
    }

}
