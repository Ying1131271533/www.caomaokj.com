<?php
namespace app\index\validate;

use think\Validate;

class Job extends Validate
{
    protected $rule = [
        'name|职位名称'                 => 'require|max:50',
        'address|公司地址'              => 'require|max:50',
        'city_value|发布城市'           => 'require',
        'company|公司名称'              => 'require|max:50',
        'phone|联系方式'                => 'require|mobile',
        'company_introduction|公司简介' => 'require',
        'duty_time|到岗时间'            => 'require|number|gt:0',
        'education_background|最低学历' => 'require|number|gt:0',
        'enterprise_type|企业类型'      => 'require|number|gt:0',
        'job_description|职业描述'      => 'require',
        'logo|logo'                     => 'require',
        'people_number|公司人数'        => 'require|number|gt:0',
        'platform|平台'                 => 'require|number|gt:0',
        'position|工作职位'             => 'require|number|gt:0',
        'salary_min|最低月薪'           => 'require|number',
        'salary_max|最高月薪'           => 'require|number|egt:salary_min',
        'work_experience|工作经验'      => 'require|number|gt:0',
    ];

    protected $message = [
        // 最低月薪
        'salary_min.require' => '请输入最低月薪',
        'salary_min.number'  => '最低月薪必须为整数',
        // 最高月薪
        'salary_max.egt'     => '最高月薪必需大于等于最低月薪',
    ];

}
