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
use think\facade\Route;


// Route::domain('www', 'index');
// Route::domain('api', 'api');
// Route::domain('admin', 'usezan');

/**********************   登录模块   **********************/

// 发送手机验证码
Route::post('index/code_api/getCode', 'index/code_api/getCode');

// 登录
Route::post('index/login_api/login', 'index/login_api/login');

// 注册
Route::post('index/login_api/register', 'index/login_api/register');

// 重置密码
Route::post('index/login_api/resetPass', 'index/login_api/resetPass');

// session 登录状态
Route::post('index/login_api/resetPass', 'index/login_api/isLogin');

// token 登录状态
Route::post('index/login_api/resetPass', 'index/login_api/isToken');

// 图片验证码
Route::get('index/login_api/verifyUser', 'index/login_api/verifyUser');
Route::get('index/login_api/verifyPhone', 'index/login_api/verifyPhone');

/**********************   首页模块   **********************/

// 轮播图点击跳转
Route::get('index/index_api/bannerClick', 'index/index_api/bannerClick');

// 左边导航列表
Route::get('index/index_api/navVisit', 'index/index_api/navVisit');

/**********************   跨境知识库模块   **********************/

// 左下角文章列表
Route::post('index/home_api/index', 'index/home_api/index');

/**********************   电商平台入驻模块   **********************/

// 入驻
Route::post('index/platform_api/join', 'index/platform_api/join');

/**********************   文章模块   **********************/

// 获取文章评论
Route::post('index/home/getArticleComment', 'index/home/getArticleComment');

// 评论文章
Route::post('index/home/articleComment', 'index/home/articleComment');

// 文章点赞
Route::post('index/home/articleLike', 'index/home/articleLike');

// 文章收藏
Route::post('index/home/articleCollect', 'index/home/articleCollect');

// 关键词关联文章
Route::post('index/home_api/keywordArticle', 'index/home_api/keywordArticle');

/**********************   跨境活动   **********************/

// 检测订单的微信支付状态
Route::post('index/activity_api/checkWxOrder', 'index/activity_api/checkWxOrder');

/**********************   草帽学院   **********************/

// 用户报名信息保存
Route::post('index/college_api/collegeJoin', 'index/college_api/collegeJoin');

/**********************   用户板块   **********************/

// 修改密码
Route::post('index/user_api/changePwd', 'index/user_api/changePwd');

// 修改手机
Route::post('index/user_api/changePhone', 'index/user_api/changePhone');

// 修改资料
Route::post('index/user_api/changeInfo', 'index/user_api/changeInfo');

// 收藏列表
// Route::post('index/user_api/collectList', 'index/user_api/collectList');

// 取消收藏
// Route::post('index/user_api/delCollect', 'index/user_api/delCollect');

// 报名活动列表
// Route::post('index/user_api/activityJoin', 'index/user_api/activityJoin');

// 报名课程列表
// Route::post('index/user_api/collegeJoin', 'index/user_api/collegeJoin');

/**********************   中间件   **********************/
// 登录
Route::rule('index/login/index', 'index/login/index')->middleware(\app\index\middleware\Login::class);

// 注册
Route::rule('index/login/register', 'index/login/register')->middleware(\app\index\middleware\Login::class);

// 我的收藏
Route::rule('index/user_api/collectList', 'index/user_api/collectList')->middleware(\app\index\middleware\IsLogin::class);
// 取消收藏
Route::rule('index/user_api/delCollect', 'index/user_api/delCollect')->middleware(\app\index\middleware\IsLogin::class);
// 活动报名
Route::rule('index/user_api/activityJoin', 'index/user_api/activityJoin')->middleware(\app\index\middleware\IsLogin::class);
// 课程报名
Route::rule('index/user_api/collegeJoin', 'index/user_api/collegeJoin')->middleware(\app\index\middleware\IsLogin::class);
// 课程报名
Route::rule('index/user_api/refundActivityOrder', 'index/user_api/refundActivityOrder')
    ->middleware(\app\index\middleware\IsLogin::class);
