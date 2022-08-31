<?php
namespace app\common\model;

use think\model\concern\SoftDelete;

class Job extends BaseModel
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';

    // 获取器
    // 使用方法：$job->getAttr('welfare')
    public function getWelfareAttr($value, $data)
    {
        return explode(',', $value);
    }

    public function detail()
    {
        return $this->hasOne('JobDetail');
    }
}
