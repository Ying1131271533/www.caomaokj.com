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
return [
    //左侧导航
    'menu' => [
        ['curr'=>'index','url'=>url('index/member/index'),'name'=>'个人信息'],
        ['curr'=>'usignup','url'=>url('index/member/usignup'),'name'=>'报名信息'],
        ['curr'=>'unotice','url'=>url('index/member/unotice'),'name'=>'消息通知'],
        ['curr'=>'collection','url'=>url('index/member/collection'),'name'=>'我的收藏'],
        ['curr'=>'uaccount','url'=>url('index/member/uaccount'),'name'=>'账号密码'],
        ['curr'=>'logout','url'=>url('index/login/logout'),'name'=>'退出']
    ]
];
