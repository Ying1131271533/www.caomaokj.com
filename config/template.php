<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板引擎类型 支持 php think 支持扩展
    'type'               => 'Think',
    // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写 3 保持操作方法
    'auto_rule'          => 1,
    // 模板路径
    // 'view_path'          => '',
    'view_path'          => VIEW_PATH,
    // 模板后缀
    'view_suffix'        => 'html',
    // 模板文件名分隔符
    'view_depr'          => DIRECTORY_SEPARATOR,
    // 模板引擎普通标签开始标记
    'tpl_begin'          => '{',
    // 模板引擎普通标签结束标记
    'tpl_end'            => '}',
    // 标签库标签开始标记
    'taglib_begin'       => '{',
    // 标签库标签结束标记
    'taglib_end'         => '}',
    //全局替换
    'tpl_replace_string' => [
        '__ROOT__'     => './',
        '__CSS__'      => '/static/uzstyle/css',
        '__IMG__'      => '/static/uzstyle/images',
        '__JS__'       => '/static/uzstyle/js',
        '__UCSS__'     => '/static/admin/Css',
        '__UFONT__'    => '/static/admin/font',
        '__UJS__'      => '/static/admin/Js',
        '__UIMG__'     => '/static/admin/Images',
        '__ICON__'     => '/static/icon',

        // 阿卡丽
        '__ACSS__'     => '/static/akali/css',
        '__AFONTS__'   => '/static/akali/fonts',
        '__AJS__'      => '/static/akali/js',
        '__ALAYER__'   => '/static/akali/layer',
        '__ALAYUI__'   => '/static/akali/layui',
        '__AIMG__'     => '/static/akali/images',
        '__AIF__'      => '/static/akali/\iconfont',

        // 学院
        '__XCSS__'     => '/static/college/css',
        '__XFONTS__'   => '/static/college/fonts',
        '__XJS__'      => '/static/college/js',
        '__XLAYER__'   => '/static/college/layer',
        '__XLAYUI__'   => '/static/college/layui',
        '__XIMG__'     => '/static/college/images',

        // 亚马逊
        '__AMZCSS__'   => '/static/amz/css',
        '__AMZFONTS__' => '/static/amz/fonts',
        '__AMZJS__'    => '/static/amz/js',
        '__AMZLAYER__' => '/static/amz/layer',
        '__AMZLAYUI__' => '/static/amz/layui',
        '__AMZIMG__'   => '/static/amz/images',

        // 移动端
        '__MCSS__'     => '/static/mobile/css',
        '__MFONTS__'   => '/static/mobile/fonts',
        '__MJS__'      => '/static/mobile/js',
        '__MLAYER__'   => '/static/mobile/layer',
        '__MLAYUI__'   => '/static/mobile/layui',
        '__MIMG__'     => '/static/mobile/images',

        // 零散
        '__SCSS__'     => '/static/scattered/css',
        '__SFONTS__'   => '/static/scattered/fonts',
        '__SJS__'      => '/static/scattered/js',
        '__SLAYER__'   => '/static/scattered/layer',
        '__SLAYUI__'   => '/static/scattered/layui',
        '__SIMG__'     => '/static/scattered/images',

        // 跨境知道
        '__ZDCSS__'    => '/static/kjzd/css',
        '__ZDJS__'     => '/static/kjzd/js',
        '__ZDIMG__'    => '/static/kjzd/images',

        // 卖家之家
        '__MJCSS__'    => '/static/mjzj/css',
        '__MJJS__'     => '/static/mjzj/js',
        '__MJIMG__'    => '/static/mjzj/images',
    ],

];
