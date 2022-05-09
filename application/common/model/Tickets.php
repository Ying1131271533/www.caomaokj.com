<?php
namespace app\common\model;

class Tickets extends BaseModel
{
    public function colleges()
    {
        return $this->belongsToMany('College', 'CollegeTickets');
    }

    public function joins()
    {
        return $this->hasMany('CollegeJoin');
    }
}
