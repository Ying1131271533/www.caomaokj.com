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
// | 应用设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 应用名称
    'app_name'                => '',
    // 应用地址
    'app_host'                => '',
    // 应用调试模式
    'app_debug'               => true,
    // 应用Trace
    'app_trace'               => false,
    // 是否支持多模块
    'app_multi_module'        => true,
    // 入口自动绑定模块
    'auto_bind_module'        => false,
    // 注册的根命名空间
    'root_namespace'          => [],
    // 默认输出类型
    'default_return_type'     => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'     => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'   => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'       => 'callback',
    // 默认时区
    'default_timezone'        => 'Asia/Shanghai',
    // 是否开启多语言
    'lang_switch_on'          => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'          => '',
    // 默认语言
    'default_lang'            => 'cn',
    // 应用类库后缀
    'class_suffix'            => false,
    // 控制器类后缀
    'controller_suffix'       => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'          => 'index',
    // 禁止访问模块
    'deny_module_list'        => ['common'],
    // 默认控制器名
    'default_controller'      => 'Index',
    // 默认操作名
    'default_action'          => 'index',
    // 默认验证器
    'default_validate'        => '',
    // 默认的空模块名
    'empty_module'            => '',
    // 默认的空控制器名
    'empty_controller'        => 'Error',
    // 操作方法前缀
    'use_action_prefix'       => false,
    // 操作方法后缀
    'action_suffix'           => '',
    // 自动搜索控制器
    'controller_auto_search'  => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'            => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'          => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'           => '/',
    // HTTPS代理标识
    'https_agent_name'        => '',
    // IP代理获取标识
    'http_agent_ip'           => 'X-REAL-IP',
    // URL伪静态后缀
    'url_html_suffix'         => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'        => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'          => 0,
    // 是否开启路由延迟解析
    'url_lazy_route'          => false,
    // 是否强制使用路由
    'url_route_must'          => false,
    // 合并路由规则
    'route_rule_merge'        => false,
    // 路由是否完全匹配
    'route_complete_match'    => false,
    // 使用注解路由
    'route_annotation'        => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'         => 'caomao.com',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'             => true,
    // 默认的访问控制器层
    'url_controller_layer'    => 'controller',
    // 表单请求类型伪装变量
    'var_method'              => '_method',
    // 表单ajax伪装变量
    'var_ajax'                => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'                => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'           => false,
    // 请求缓存有效期
    'request_cache_expire'    => null,
    // 全局请求缓存排除规则
    'request_cache_except'    => [],
    // 是否开启路由缓存
    'route_check_cache'       => false,
    // 路由缓存的Key自定义设置（闭包），默认为当前URL和请求类型的md5
    'route_check_cache_key'   => '',
    // 路由缓存类型及参数
    'route_cache_option'      => [],

    // 默认跳转页面对应的模板文件
    //'dispatch_success_tmpl'  => Env::get('think_path') . 'tpl/dispatch_jump.tpl',
    //'dispatch_error_tmpl'    => Env::get('think_path') . 'tpl/dispatch_jump.tpl',
    //'exception_tmpl'         => Env::get('app_path') . 'usezan/view/tpl/think_exception.tpl',
    'exception_tmpl'          => Env::get('think_path') . 'tpl/think_exception.tpl',
    'dispatch_success_tmpl'   => Env::get('app_path') . 'usezan/view/tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl'     => Env::get('app_path') . 'usezan/view/tpl/dispatch_jump.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'           => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'          => true,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'        => '',

    //异常
    'http_exception_template' => [
        404 => './404.php',
    ],

    //文件路径
    'static_url'              => [
        'rooturl' => \think\facade\Env::get('root_path') . 'public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR,
        'surl'    => DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR,
    ],
    //Usezan
    'usezan_keys'             => 'lX2o1mv+g9rEup~ck5T$inew83u6NO',
    'usezan_encry'            => 'NKBZ^XdGfM#9HlBP@chICx%Wot$NO8',
    //分页数
    'usezan_page'             => 15,
    //后台验证方式
    'usezan_verif'            => 1,
    //0:简洁,1:多字段
    'usezan_album'            => 1,
    //0:默认,1:当前栏目
    'usezan_return'           => 0,
    'usezan_admin'            => 'www.usezan.com',
    'usezan_admin_super'      => 1,
    //极验验证码[配置参数]
    'captcha_id'              => "d03951b1217e8c4cc4b957f1fc3547c4",
    'private_key'             => "6c2818ed5cf94e9284689e6f6e6b6dbb",
    //无需权限验证控制器
    'auth_controller'         => ['Login', 'Index'],
    /*+----------------------
    +  Auth验证
     */
    'auth_config'             => [
        'auth_on'           => true,
        'auth_type'         => 1,
        'auth_group'        => 'auth_group',
        'auth_group_access' => 'auth_group_access',
        'auth_rule'         => 'auth_rule',
        'auth_user'         => 'user',
    ],

    // 加密解密密钥
    'encrypt_key'             => 'Akali',
    'sign'                    => 'Akali',
    'token_key'               => 'nU2OpdWDyzQ4iSUWaCAaXaGg3qEzR00Qv3fwMkkWKQ5CXjIWLJTmg8g==',

    'page' => 1,
    'size' => 20,

    // rabbitmq连接配置
    'rabbitmq'         => [
        'host'     => '106.52.77.54',
        // 'host'     => '127.0.0.1',
        // 'host'     => '172.16.0.9',
        // 'host'     => 'rabbitmq',
        'port'     => 5672,
        // 'port'     => 5673,
        // 'port'     => 5674,
        'login'    => 'akali',
        'password' => 'O8idPl^iY?i4/3d@n.T-idGkL22jE',
        'vhost'    => '/',
    ],
];
