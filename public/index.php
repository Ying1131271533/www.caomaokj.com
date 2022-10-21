<?php

// [ 应用入口文件 ]
namespace think;

// echo '网站维护中~~~';exit;
viewPath();

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

define('ONE_DAY', 3600 * 24);
define('ONE_WEEK', 3600 * 24 * 7);
define('ONE_MONTH', 3600 * 24 * 30);

// 默认应用index
// define('VIEW_PATH', '');
// global $view_path;

// 支持事先使用静态方法设置Request对象和Config对象
// 执行应用并响应
Container::get('app')->run()->send();

// 设置视图文件路径
function viewPath()
{
    if(is_cli()){
        define('VIEW_PATH', '');
    } else{
        if (strstr($_SERVER['REQUEST_URI'], 'usezan')) {
            define('VIEW_PATH', '../application/usezan/view/');
        } else {
            // 模版文件路径是PC端还是移动端
            if (isMobile()) {
                define('VIEW_PATH', '../application/mobile/view/');
            } else {
                define('VIEW_PATH', '../application/index/view/');
            }
        }
    }
    
}

// 是否命令行模式
function is_cli(){
    return preg_match("/cli/i", php_sapi_name()) ? 1 : 0;
}

// 判断是手机端浏览还是web端浏览
function isMobile()
{
    $agent      = strtolower($_SERVER['HTTP_USER_AGENT']);
    $is_pc      = (strpos($agent, 'windows nt')) ? true : false;
    $is_mac     = (strpos($agent, 'mac os')) ? true : false;
    $is_iphone  = (strpos($agent, 'iphone')) ? true : false;
    $is_android = (strpos($agent, 'android')) ? true : false;
    $is_ipad    = (strpos($agent, 'ipad')) ? true : false;
    if ($is_pc) {
        return false;
    }
    if ($is_mac) {
        return true;
    }
    if ($is_iphone) {
        return true;
    }
    if ($is_android) {
        return true;
    }
    if ($is_ipad) {
        return true;
    }
}
