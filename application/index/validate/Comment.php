<?php
namespace app\index\validate;
use think\Validate;
class Comment extends Validate
{
    protected $rule = [
        'catid'       => 'require|number',
        'title'       => 'require|min:2|max:20|chsAlpha',
        'subtitle'    => 'require|min:5|max:50|chsAlpha',
        'ourl'        => 'require|url',
        'verify'      => 'require|alphaNum',
        '__token__'   => 'token'
    ];

    protected $message  =   [
        'catid.require' => '请选择分类',
        'catid.number'  => 'error illegals',
        'title.require'       => '请输入网站名称',
        'subtitle.require'    => '请输入网站描述',
        'verify.require'      => '请输入验证码',
        'ourl.require'        => '请输入网址',
        //'__token__.token'     => 'token error'
    ];


}