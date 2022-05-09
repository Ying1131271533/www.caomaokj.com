<?php

namespace app\index\validate;

use think\Validate;

class Activity extends Validate
{

    protected $rule = [
        'name'      => 'require|chsAlpha|length:2,20',
        'phone'     => 'require|mobile|unique:activity_join,phone^activity_id', //  中间表不能用 因为模型没有继承继承 model 不能查询
        'code'      => 'require|length:6|number',
        'company'   => 'require|chs|length:2,30',
        'demand'    => 'require|max:255',
        'endtime'   => 'require|checkEndtime',
        'apply_num' => 'require|lt:tickets_num',
        'number'    => 'require|elt:shop_num|egt:1',
    ];

    protected $message = [
        // 姓名
        'name.require'      => '请输入姓名',
        'name.chsAlpha'     => '姓名只能是汉字和英文',
        'name.length'       => '姓名长度为2-20个字符',

        // 手机号码
        'phone.require'     => '请输手机号码',
        'phone.mobile'      => '手机号格式错误',
        'phone.unique'      => '该活动您已报名',

        // 手机验证码
        'code.require'      => '请输入验证码',
        'code.length'       => '请输入6位验证码',
        'code.number'       => '验证码只能为数字',

        // 公司名称
        'company.require'   => '请输入公司名称',
        'company.length'    => '公司名称长度为2-30个字符',
        'company.chs'       => '公司名称只能为汉字',

        // 需求
        'demand.require'    => '请输入管理需求/问题',
        'demand.max'        => '管理需求/问题不能超过255个字符',

        // 活动时间
        'endtime.require'   => '缺少活动时间',
        'endtime.max'       => '管理需求/问题不能超过255个字符',

        // 报名人数
        'apply_num.require' => '缺少报名人数',
        'apply_num.lt'      => '该活动的报名人数已满',

        // 报名数量
        'number.require'    => '报名数量',
        'number.elt'        => '报名数量太多',
        'number.egt'        => '报名数量不能少于1',

    ];

    // 活动是否结束
    protected function checkEndtime($value, $data = [])
    {
        return $value > time() ? true : '活动已过期';
    }
}
