<?php
namespace app\usezan\controller;

use think\Db;
use think\Env;

class Index extends Base
{

    public function index()
    {
        return $this->fetch();
    }

    //欢迎界面
    public function welcomed()
    {
        //管理员信息
        $userinfo = [
            'username'  => session("usezan_admin.username"),
            'realname'  => session("usezan_admin.realname"),
            'email'     => session("usezan_admin.email"),
            'logintime' => date("Y-m-d", session("usezan_admin.last_logintime")),
            'last_ip'   => session("usezan_admin.last_ip"),
        ];
        //系统信息
        $info = [
            'os'                  => '5.0.0',
            'php_version'         => PHP_VERSION,
            'mysql_version'       => $this->_mysql_version(),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'max_execution_time'  => ini_get('max_execution_time') . lang('miao'),
        ];
        //版本号
        $version = include 'version.php';
        $this->assign('version', $version);
        $this->assign('userinfo', $userinfo);
        $this->assign('server_info', $info);
        return $this->fetch();
    }

    //Mysql版本
    private function _mysql_version()
    {
        $_version = Db::query('select version() as ver');
        $version  = array_pop($_version);
        return $version['ver'];
    }

    //清理缓存
    public function syscache()
    {
        $log   = $this->clearCache("log"); //日志缓存
        $cache = $this->clearCache("cache"); //系统缓存
        $temp  = $this->clearCache("temp"); //模板缓存
        if ($log && $cache && $temp) {
            $this->success("缓存清理完成！");
        } else {
            $this->error("缓存清理失败！");
        }
    }
    /**
     * 清理缓存
     * @param  string $TYPE [类型]
     * @return [type]       []
     */
    protected function clearCache($type = "cache")
    {
        $path = \think\facade\Env::get('runtime_path');
        switch ($type) {
            case 'temp':
                $files = dir_list($path . 'temp/', "php");
                foreach ($files as $key => $file) {
                    $filename = basename($file);
                    @unlink($path . 'temp/', basename($file));
                }
                return true;
                break;
            case 'cache':
                dir_list($path . 'log/', '', 1);
                return true;
                break;
            case 'log':
                $files = dir_list($path . 'log/');
                foreach ($files as $key => $file) {
                    @unlink($file);
                }
                return true;
                break;
        }
    }

}
