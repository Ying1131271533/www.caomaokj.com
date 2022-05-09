<?php
namespace app\index\validate;
use think\Validate;
class Links extends Validate
{
    protected $rule = [
        'name'       => 'require|min:2|max:20|chsAlpha',
        'url'        => 'require|url'
    ];

    protected $message  =   [
        'name.require'        => '请输入网站名称',
        'url.require'         => '请输入网站网址'
        //'__token__.token'     => 'token error'
    ];


}