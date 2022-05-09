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
// | 腾讯短信
// +----------------------------------------------------------------------
return [
    'url'          => 'sms.tencentcloudapi.com', //apiurl
    'appid'        => '1400503572', //SDKAppID 1254202189(不知道后面这是啥)
    'sign'         => '草帽跨境',
    'secretid'     => 'AKIDoQlouqv0C8sJPFeLyHJ8aRApcEfvIs8T', // SecretID
    'secretkey'    => 'e9UkohR4VTIvPi2CF8A2jGcWGjxNcfUF', // SecretKey
    'senderid'     => '',
    'templateid_1' => '914757', // 注册 code:{1}
    'templateid_2' => '914759', // 登录 code:{1},time:{2}
    'templateid_3' => '914765', // 找回密码 code:{1}
    'templateid_4' => '1181681', // 活动短信通知模板
    'templateid_5' => '1181760', // 课程短信通知模板
    'templateid_6' => '1185298', // 通用短信通知模板
];
