<?php
namespace app\common\model;

use think\model\concern\SoftDelete;

class Job extends BaseModel
{
    use SoftDelete;
    protected $autoWriteTimestamp = true;
    protected $deleteTime = 'delete_time';
   /*  public function getEducationBackgroundAttr($value, $data)
    {
        $education_background = [
            0 => '学历不限',
            1 => '高中以下',
            2 => '高中',
            3 => '大专',
            4 => '本科',
            5 => '硕士',
            6 => '博士',
            7 => '学历不限'
        ];
        return $education_background[$data['education_background']];
    } */

    public function detail()
    {
        return $this->hasOne('JobDetail');
    }
}
