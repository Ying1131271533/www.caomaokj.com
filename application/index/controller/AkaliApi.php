<?php
declare (strict_types = 1);
namespace app\index\controller;

use think\cache\driver\Memcache;
use think\facade\Cache;

class AkaliApi
{
    public function index()
    {
        // $memcache = Cache::store('redis')->set('akali', '阿卡丽');
        // halt($memcache);
        echo '神织恋 - 首页';
    }
}
