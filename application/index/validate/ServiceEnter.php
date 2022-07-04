<?php

namespace app\index\validate;

use think\Validate;

class ServiceEnter extends Validate
{
    protected $rule = [
        'category_id|服务类别'              => 'require|number|gt:0',
        'logo'                          => 'require',
        'thumb|封面'                      => 'require',
        'name|公司名称'                     => 'require|max:20|chs',
        'introduction|公司介绍'             => 'require|max:200',
        'operator_name|运营人姓名'           => 'require|max:15',
        'phone|手机号'                     => 'require|max:20',
        'customer_qr_code|客服二维码'        => 'require',
        'business_license|营业执照'         => 'require',
        'authorization_letter|授权函'      => 'require',
        'service_introduction|服务介绍'     => 'require',
        'service_enter_featured|主推服务图片' => 'require',
    ];

    protected $message = [
        // 服务类别
        'category_id.require'            => '请选择服务类别',
        'category_id.number'             => '请选择服务类别',
        'category_id.gt'                 => '请选择服务类别',
        // logo
        'logo.require'                   => '请上传logo',
        // 封面
        'thumb.require'                  => '请上传封面',
        // 公司名称
        'introduction.require'           => '请输入公司名称',
        'introduction.max'               => '公司名称不能超过20字符',
        'introduction.chs'               => '公司名称必须为中文',
        // 公司介绍
        'introduction.require'           => '请输入公司介绍',
        'introduction.max'               => '公司介绍不能超过200字符',
        // 运营人姓名
        'operator_name.require'          => '请输入运营人姓名',
        'operator_name.max'              => '运营人姓名不能超过15字符',
        'introduction.max'               => '公司介绍不能超过200字符',
        // 手机号
        'phone.require'                  => '请输入手机号',
        'phone.max'                      => '手机号不能超过20字符',
        // 客服二维码
        'customer_qr_code.require'       => '请上传客服二维码',
        // 营业执照
        'business_license.require'       => '请上传营业执照',
        // 授权函
        'authorization_letter.require'   => '请上传授权函',
        // 服务介绍
        'service_introduction.require'   => '请输入服务介绍',
        // 服务介绍
        'service_enter_featured.require' => '请上传主推服务',
    ];

    // 主推服务图片是否为空
    protected function isEmptyFeatured($value, $rule, $data = [])
    {
        if (empty($value)) {
            return 'service_introduction';
        }
        return true;
    }
}
