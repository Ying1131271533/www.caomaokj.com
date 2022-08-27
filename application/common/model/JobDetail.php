<?php
namespace app\common\model;

use think\model\concern\SoftDelete;

class JobDetail extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';

    public function job()
    {
        return $this->belongsTo('Job');
    }
}
