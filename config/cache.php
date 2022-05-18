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
// | 缓存设置
// +----------------------------------------------------------------------

return [
    // 驱动方式
    'type'     => 'complex',
    //default
    'default'  => [
        // 文件
        // 'type'   => 'File',
        // 'path'   => '',
        // 'prefix' => '',
        // 'expire' => cache_time('one_month'),

        // redis
        'type'   => 'redis',
        // 服务器地址
        'host'       => '127.0.0.1',
        // 密码
        // 'password'  => "Ak-12]al^iY?i4/3@n.!gdTxP]al^jMGZBX2Ac/3@n.cMh",
        // 过期时间
        'expire' => cache_time('one_month'),
    ],
    //category
    'category' => [
        'type' => 'File',
        'path' => \think\facade\Env::get('runtime_path') . 'category' . DIRECTORY_SEPARATOR,
        'expire' => cache_time('one_month'),
    ],
    //rule
    'rule'     => [
        'type' => 'File',
        'path' => \think\facade\Env::get('runtime_path') . 'rule' . DIRECTORY_SEPARATOR,
        'expire' => cache_time('one_month'),
    ],
    //extend
    'extend'   => [
        'type' => 'File',
        'path' => \think\facade\Env::get('runtime_path') . 'extend' . DIRECTORY_SEPARATOR,
        'expire' => cache_time('one_month'),
        'serialize' => ['serialize', 'unserialize'],
    ],
    //extend
    'menu'     => [
        'type' => 'File',
        'path' => \think\facade\Env::get('runtime_path') . 'menu' . DIRECTORY_SEPARATOR,
        'expire' => cache_time('one_month'),
    ],
    //umember
    'umember'  => [
        'type' => 'File',
        'path' => \think\facade\Env::get('runtime_path') . 'umember' . DIRECTORY_SEPARATOR,
        'expire' => cache_time('one_month'),
    ],
    //clinks
    'clinks'   => [
        'type'   => 'File',
        'path'   => \think\facade\Env::get('runtime_path') . 'cache/clinks' . DIRECTORY_SEPARATOR,
        'expire' => 180,
    ],
    //rmlognum
    'rmlognum' => [
        'type'   => 'File',
        'path'   => \think\facade\Env::get('runtime_path') . 'rmlognum' . DIRECTORY_SEPARATOR,
        'expire' => 1800,
    ],
    //sms
    'sms'      => [
        'type'   => 'File',
        'path'   => '../runtime/sms/',
        'expire' => 300,
    ],
    //rmsms
    'rmsms'    => [
        'type'   => 'File',
        'path'   => \think\facade\Env::get('runtime_path') . 'rmsms' . DIRECTORY_SEPARATOR,
        'expire' => 300,
    ],
    //verif
    'rmverif'  => [
        'type'   => 'File',
        'path'   => \think\facade\Env::get('runtime_path') . 'rmverif' . DIRECTORY_SEPARATOR,
        'expire' => 1800,
    ],
    //verif
    'rsa'      => [
        'type'   => 'File',
        'path'   => \think\facade\Env::get('runtime_path') . 'rsa' . DIRECTORY_SEPARATOR,
        'expire' => 60,
    ],
    // wechat
    'wechat'   => [
        'type'   => 'File',
        'path'   => \think\facade\Env::get('runtime_path') . 'wechat' . DIRECTORY_SEPARATOR,
        'expire' => 5400,
    ],
    // redis缓存
    'redis'   =>  [
        // 驱动方式
        'type'   => 'redis',
        // 服务器地址
        'host'       => '127.0.0.1',
        // 密码
        // 'password'  => "Ak-12]al^iY?i4/3@n.!gdTxP]al^jMGZBX2Ac/3@n.cMh",
        // 过期时间
        'expire' => cache_time('one_month'),
    ],
    // memcahce
    'memcache' => [
        // 驱动方式
        'type'   => 'memcache',
        // 服务器地址
        'host'   => '127.0.0.1',
    ],
];
