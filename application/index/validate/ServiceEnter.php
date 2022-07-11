<?php

namespace app\index\validate;

use think\Validate;

class ServiceEnter extends Validate
{
    protected $regex = [
        'phone' => '/^[0-9_-]{5,18}$/',
        'qq'    => '/^\d{5,11}$/',
    ];

    protected $rule = [
        'category_id|服务类别'             => 'require|number|gt:0',
        'logo'                         => 'require',
        // 'thumb|封面'                     => 'require',
        'name|公司名称'                    => 'require|max:20|chs',
        'url|官网链接'                     => 'require|max:250|url',
        'introduction|公司介绍'            => 'require|max:200',
        'operator_name|运营人姓名'          => 'require|max:15',
        'phone|手机号'                    => 'require|phone',
        'customer_qr_code|客服二维码'       => 'require',
        // 'business_license|营业执照'        => 'require',
        // 'authorization_letter|授权函'     => 'require',
        // 'image|服务介绍'                   => 'require',
        'service_introduce|服务介绍' => 'require',
        // 'service_enter_featured|主推服务图片' => 'require',
    ];

    protected $message = [
        // 服务类别
        'category_id.require'             => '请选择服务类别',
        'category_id.number'              => '请选择服务类别',
        'category_id.gt'                  => '请选择服务类别',
        // logo
        'logo.require'                    => '请上传logo',
        // 封面
        'thumb.require'                   => '请上传封面',
        // 公司名称
        'name.require'                    => '请输入公司名称',
        'name.max'                        => '公司名称不能超过20字符',
        'name.chs'                        => '公司名称必须为中文',
        // 官网链接
        'url.require'                     => '请输入公司名称',
        'url.max'                         => '官网链接不能超过250字符',
        'url.url'                         => '请输入正确的官网链接',
        // 公司介绍
        'introduction.require'            => '请输入公司介绍',
        'introduction.max'                => '公司介绍不能超过200字符',
        // 运营人姓名
        'operator_name.require'           => '请输入运营人姓名',
        'operator_name.max'               => '运营人姓名不能超过15字符',
        'introduction.max'                => '公司介绍不能超过200字符',
        // 手机号
        'phone.require'                   => '请输入手机号',
        'phone.phone'                     => '手机号码格式不正确',
        // 客服二维码
        'customer_qr_code.require'        => '请上传客服二维码',
        // 营业执照
        'business_license.require'        => '请上传营业执照',
        // 授权函
        'authorization_letter.require'    => '请上传授权函',
        // 服务介绍
        'service_introduce.require' => '请填写服务介绍',
        // 服务介绍
        // 'service_enter_featured.require' => '请上传主推服务',
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
