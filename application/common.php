<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 打印数组
 * @param $arr
 */
function p($arr)
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

/**
 * [路径添加“/”]
 * @param  [type] $url [路径]
 * @return [type]      [string]
 * @author [小柯] <[972581428@qq.com]>
 * @version [环企优站] [www.usezan.com]
 */
function uz_url($url)
{
    if (!strstr($url, 'javascript')) {
        if (!strstr($url, "/")) {
            $url = "/" . $url;
        }
        if (!strstr($url, ".html") && !strpos($url, ":/") && !strpos($url, "www.")) {
            $url = $url . '.html';
        }
    }
    return $url;
}

/**
 * 去除字符串
 * @param $str
 */
function remove_str($str, $html = '.html')
{
    if (strpos($str, $html)) {
        return str_replace($html, '', $str);
    }
    return $str;
}

/**
 * 异位或加密解密
 * @param $value
 * @param $type 0解密 1加密
 */
function encrytion($value, $type = 0)
{
    $key = md5(config("usezan_encry"));
    if ($type) {
        return str_replace('=', '', base64_encode($value ^ $key));
    }
    $value = base64_decode($value);
    return $value ^ $key;
}

/**
 * Slide
 */
function slide()
{
    return (new \app\index\model\Slide())->get_select();
}

/**
 * banner
 */
function banner()
{
    return (new \app\common\model\Slide())->get();
}

/**
 * 广告位
 * @param $id
 */
function uads($id, $title = false)
{
    $data     = (new \app\index\model\Uads())->get_find($id);
    $nofollow = $data['nofollow'] ? 'rel="nofollow"' : '';
    $title    = $title ? "<h5>{$data['name']}</h5>" : '';
    return '<a ' . $nofollow . ' target="_blank" href="' . $data['url'] . '"><img src="' . $data['cover'] . '" alt="' . $data['name'] . '"/>' . $title . '</a>';
}

/**
 * [请求数据]
 * @param  [type] $url [远程接口路径]
 * @return [type]
 */
function curlGet($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    if (!curl_exec($ch)) {
        $data = '';
    } else {
        $data = curl_multi_getcontent($ch);
    }
    curl_close($ch);
    return $data;
}

/**
 * [提交数据]
 * @param  [type] $url [远程接口路径]
 * @return [type]
 */
function curlPost($url, $postData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    if (!curl_exec($ch)) {
        $data = '';
    } else {
        $data = curl_multi_getcontent($ch);
    }
    curl_close($ch);
    return $data;
}

/**
 * Auth_MENU
 * @author [My name 小柯]
 */
function Auth_Menu()
{
    if (session("?usezan_admin") && !session("usezan_admin.adminis")) {
        $auth_id  = (new \app\usezan\model\AuthGroup())->get_value(['id' => session('usezan_admin.group_id')], 'rules');
        $auth_arr = explode(",", $auth_id);
        $menudata = [];
        foreach ($auth_arr as $key => $v) {
            $menudata[] = (new \app\usezan\model\AuthRule())->get_where_find(['id' => $v, 'status' => 1, 'level' => ['lt', 3]]);
        }
        $menu = array_filter($menudata);
        $menu = GetLayer($menu);
    } else {
        $menu     = (new \app\usezan\model\AuthRule())->get_where_select(['ismenu' => 1]);
        $menudata = [];
        foreach ($menu as $key => $v) {
            if (!$v['status']) {
                continue;
            }

            $menudata[$key] = $v;
        }
        $menu = GetLayer($menudata);
    }
    return $menu;
}

/**
 * 列表数据
 * @param $catid
 * @param $limit
 * @param $field
 * @param string $order
 */
function list_data($catid, $module, $limit = 8, $field = null, $order = "listorder desc,createtime desc")
{
    $module   = intval($module) === 1 ? 'works' : 'content';
    $category = \think\facade\Cache::store('category')->get('category');
    $cids     = [];
    foreach ($category as $key => $vo) {
        if ($vo['status'] && $catid !== $vo['parentid']) {
            continue;
        }

        $cids[$key] = $vo['id'];
    }
    $cids  = array_merge([$catid], $cids);
    $field = $field ? $field : 'id,title,thumb,catid,description,labels,ishot,view,url';
    //where
    $data = \think\Db::name($module)
        ->where([
            ['catid', 'in', $cids],
            ['status', '=', 1],
        ])
        ->field($field)
        ->limit($limit)
        ->order($order)
    //->cache($catid.'catelist',60)
        ->select();
    return $data;
}

/**
 * 热门列表
 * @param $catid
 * @param $limit
 * @param $field
 * @param string $order
 */
function list_sorts_data($id, $limit = 8, $field = null, $order = "listorder desc,createtime desc")
{
    $field = $field ? $field : 'id,title,thumb,subtitle,catid,labels,ishot,view,url';
    //data
    $data = \think\Db::name('content')
        ->where([
            ['ismenu', 'in', $id],
            ['status', '=', 1],
        ])
        ->field($field)
        ->limit($limit)
        ->order($order)
        ->select();
    foreach ($data as $key => $vo) {
        $data[$key] = $vo;
        if ($vo['labels']) {
            $data[$key]['labels'] = array_filter(explode("#", json_decode($vo['labels'])));
        }
    }
    return $data;
}

/**
 * [传递一个父级分类ID返回所有子级分类]
 * @param  [type] $id   [父级ID]
 * @return [type]       [返回处理完成的数组]
 */
function getChilds($pid = 1, $id = null)
{
    $cate = \app\common\model\Category::select();
    $arr  = [];
    foreach ($cate as $key => $v) {
        if ($v['status'] && $v['parentid'] === $pid) {
            $arr[$key] = $v;
            $arr       = array_merge($arr, getChilds($cate, $v['id']));
        }
    }
    return $arr;
}

/**
 * 删除图片
 * @param  string    $path 图片路径
 * @return bool        布尔值
 */
function del_oldthumb($path)
{
    $pathurl = '.' . $path;
    if (file_exists($pathurl)) {
        @unlink($pathurl);
        return true;
    }
}

/**
 * 删除旧图片or去除数组的图片字段
 * @param  array    $data   参数数组
 * @return array    $data   处理好的参数
 */
function del_old_img($data)
{
    if ($data['oldthumb'] === $data['thumb']) {
        unset($data['thumb']);
    } else {
        del_oldthumb($data['oldthumb']);
    }
    unset($data['oldthumb']);
    return $data;
}

/**
 * 删除单张旧图片
 *
 * @param  array    $old_image   旧图片路径
 * @return array    $new_image   新图片路径
 */
function del_img($old_image, $new_image)
{
    if ($old_image !== $new_image) {
        del_oldthumb($old_image);
    }
}

/**
 * 删除多张旧图片
 *
 * @param  array    $data   图片数组
 * @return array    $data   处理好的参数
 */
function del_imgs($old_images, $new_images)
{
    $del_images = array_diff($old_images, $new_images);
    foreach ($del_images as $value) {
        del_oldthumb($value);
    }
}

/**
 * [删除文件或者文件夹]
 * @param  [type] $dir [文件、文件夹]
 * @return [type]
 */
function dir_delete($dir)
{
    if (!is_dir($dir)) {
        return false;
    }

    $handle = opendir($dir); //打开目录
    while (($file = readdir($handle)) !== false) {
        if ($file == '.' || $file == '..') {
            continue;
        }

        $d = $dir . DIRECTORY_SEPARATOR . $file;
        is_dir($d) ? dir_delete($d) : @unlink($d);
    }
    closedir($handle);
    return @rmdir($dir);
}

/**
 * [检查文件或者目录]
 * @param  [type] $path [文件路径]
 * @param  string $exts [类型]
 * @param  array  $list [返回数组]
 * @return [type]
 */
function dir_list($path, $exts = '', $d = '', $list = array())
{
    $path  = dir_path($path);
    $files = glob($path . '*');
    foreach ($files as $v) {
        $fileext = fileext($v);
        if (!$exts || preg_match("/\.($exts)/i", $v)) {
            $list[] = $v;
            if (is_dir($v)) {
                $list = dir_list($v, $exts, $list);
                if ($d && count(scandir($v)) == 2) {
                    @rmdir($v);
                }
            }
        }
    }
    return $list;
}

/**
 * [检查文件名称]
 * @param  [type] $filename [文件名称]
 * @return [type]
 */
function fileext($filename)
{
    return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * [检查目录是否合法]
 * @param  [type] $path [目录路径]
 * @return [type]
 */
function dir_path($path)
{
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/') {
        $path = $path . '/';
    }

    return $path;
}

/**
 * [单位大小]
 * @param  [type]  $input []
 * @param  integer $dec   []
 * @return [type]         []
 */
function byte_format($input, $dec = 0)
{
    $prefix_arr = array("B", "K", "M", "G", "T");
    $value      = round($input, $dec);
    $i          = 0;
    while ($value > 1024) {
        $value /= 1024;
        $i++;
    }
    $return_str = round($value, $dec) . $prefix_arr[$i];
    return $return_str;
}

/**
 * [组合一维数组]
 * @param  [type]  $cate  [要处理的数组]
 * @param  string  $html  [默认的字符串]
 * @param  integer $parentid   [默认为0，将id=pid数组里面压]
 * @param  integer $level [等级，默认0，回调+1]
 * @return [type]  $cate  [返回处理完成的数组]
 * Name xiaoke
 */
function GetLevel($cate, $html = '└──', $pid = 0, $level = 0)
{
    $arr = array();
    foreach ($cate as $v) {
        if ($v['parentid'] == $pid) {
            $v['level'] = $level;
            if ($v['level'] >= 1) {
                $v['html'] = "&nbsp;&nbsp;&nbsp;└" . str_repeat("─", $level);
            } else {
                $v['html'] = str_repeat($html, $level);
            }
            $arr[] = $v;
            //合并成一个数组
            $arr = array_merge($arr, GetLevel($cate, $html, $v['id'], $level + 1));
        }
    }
    return $arr;
}

/**
 * [组合多维数组]
 * @param  [type]  $cate [要处理的数组]
 * @param  string  $name [默认的值]
 * @param  integer $pid  [默认为0，将id=pid数组里面压]
 * @return [type]        [返回处理完成的数组]
 * Name xiaoke
 */
function GetLayer($cate, $pid = 0, $name = 'lower')
{
    $arr = array();
    foreach ($cate as $v) {
        if ($v['parentid'] == $pid) {
            $v[$name] = GetLayer($cate, $v['id'], $name);
            $arr[]    = $v;
        }
    }
    return $arr;
}

/**
 * [md5 加密函数]
 * @param  [type] $str  [加密字符串]
 * @param  string $key  [秘钥]
 * @param  string $type [加密方式]
 * @return [type] $str
 */
function sysmd5($str, $key = '', $type = 'sha1')
{
    $key = $key ? $key : config('usezan_keys');
    return hash($type, $str . $key);
}

/**
 * [字符串截取]
 * @param string $str       需要截取的字符串
 * @param int $len          截取长度
 * @param string $next      自定义后缀符号
 * @param int $start        第一个字符的位置
 * @param string $pre       自定义前缀符号
 * @param string $charset   字符串编码
 * @return string
 * @author [小柯] <[972581428@qq.com]>
 **/
function cutstr($str, $len, $next = '...', $start = 0, $pre = '', $charset = 'utf8')
{
    $str_len = (strlen($str) + mb_strlen($str, $charset)) / 2;

    if ($str_len <= $len && $start == 0) {
        return $str;
    }
    $substr = mb_substr($str, $start, $len, $charset);
    if ($str == $substr) {
        return $substr;
    }
    if ($str_len >= $len && $start == 0) {
        return $substr . $next;
    }
    if ($str_len > ($len + $start) && $start != 0) {
        return $pre . $substr . $next;
    }
    if ($str_len >= $len && $start > 0) {
        return $pre . mb_substr($str, $start, $len, $charset);
    }
    return $str;
}

/**
 * 随机数
 * @param $length
 * @return null|string
 */
function getRandChar($length, $type = null)
{
    $str = null;
    if ($type) {
        $strPol = "0123456789";
    } else {
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    }
    $max = strlen($strPol) - 1;

    for ($i = 0;
        $i < $length;
        $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}

// 加密解密函数
if (!function_exists('encrypt_akali')) {
    function encrypt_akali($string, $operation, $key = '')
    {
        $key           = md5($key);
        $key_length    = strlen($key);
        $string        = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey        = $box        = array();
        $result        = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i]    = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j       = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp     = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a       = ($a + 1) % 256;
            $j       = ($j + $box[$a]) % 256;
            $tmp     = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }

}

// 递归
function getChild($array, $pid = 0)
{
    $temp = [];
    foreach ($array as $key => $value) {
        if ($value['parentid'] == $pid) {
            $value['child'] = getChild($array, $value['id']);
            $temp[]         = $value;
        }
    }
    return $temp;
}

function getIp()
{
    return $_SERVER['REMOTE_ADDR'];
}

function get_date($value)
{
    return date("Y-m-d H:i:s", $value);
}

function akali($msg, $url = '', $wait = 3)
{
    //如果 $url 为空，则给url赋值为来源页面
    if (empty($url)) {
        $url = lastUrl();
    }

    //定界符
    $html = <<<AKALI


	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
		<title>跳转提示</title>
		<link rel="stylesheet" type="text/css" href="/static/uzstyle/css/leaf.css">
		<style>
			.ruiwen dd{
				margin-right:15px;
				margin-bottom:15px;
			}
			.ruiwen ad{
				margin-right:15px;
				margin-bottom:15px;
			}
			.LAB_maincontW{padding:24px 30px 200px;background: #fff none;}
			.LAB_maincontW .myOperating{padding:0;}
		</style>

	</head>
	<body>
	<div class="main">
		<div class="weChat_main" style="padding-bottom: 0px;">

		  <!-- head-search end -->
		   <div class="weChat_content2" >
				<div class="LAB_maincont2 cf LAB_maincontW">
					<div class="noData cf">
						<dl>
							<dt><img src="\static\uzstyle\images/noDataIco.png" alt=""></dt>
							<dd>
								<p>{$msg}</p>
								<p>
									页面自动 <a id="href" href="{$url}">跳转</a> 等待时间： <b id="wait">{$wait}</b>
								</p>
							</dd>
						</dl>
					</div>
				</div>
		   </div>
		</div>
	</div>

	<script type="text/javascript">
		(function(){
			var wait = document.getElementById('wait'),
				href = document.getElementById('href').href;
			var interval = setInterval(function(){
				var time = --wait.innerHTML;
				if(time <= 0) {
					location.href = href;
					clearInterval(interval);
				};
			}, 1000);
		})();
	</script>

	</body>
	</html>

AKALI;
    die($html);
}

function jinx($msg = '操作成功', $url = '', $wait = 3)
{
    //如果 $url 为空，则给url赋值为来源页面
    if (empty($url)) {
        $url = $_SERVER['HTTP_REFERER'];
    }

    //定界符
    $html = <<<AKALI


	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
		<title>跳转提示</title>
		<link rel="stylesheet" type="text/css" href="/static/uzstyle/css/leaf.css">
		<style>
			.ruiwen dd{
				margin-right:15px;
				margin-bottom:15px;
			}
			.ruiwen ad{
				margin-right:15px;
				margin-bottom:15px;
			}
			.LAB_maincontW{padding:24px 30px 200px;background: #fff none;}
			.LAB_maincontW .myOperating{padding:0;}
		</style>

	</head>
	<body>
	<div class="main">
		<div class="weChat_main" style="padding-bottom: 0px;">

		  <!-- head-search end -->
		   <div class="weChat_content2" >
				<div class="LAB_maincont2 cf LAB_maincontW">
					<div class="noData cf">
						<dl>
							<dt><img src="\static\uzstyle\images/noDataIco.png" alt=""></dt>
							<dd>
								<p>{$msg}</p>
								<p>
									页面自动 <a id="href" href="{$url}">跳转</a> 等待时间： <b id="wait">{$wait}</b>
								</p>
							</dd>
						</dl>
					</div>
				</div>
		   </div>
		</div>
	</div>

	<script type="text/javascript">
		(function(){
			var wait = document.getElementById('wait'),
				href = document.getElementById('href').href;
			var interval = setInterval(function(){
				var time = --wait.innerHTML;
				if(time <= 0) {
					location.href = href;
					clearInterval(interval);
				};
			}, 1000);
		})();
	</script>

	</body>
	</html>

AKALI;
    die($html);
}

// 获取上一个页面的连接
function lastUrl()
{
    if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != request()->url(true)) {
        return $_SERVER['HTTP_REFERER'];
    } else {
        return request()->domain();
    }
}

// 时间戳转为多少分钟前 几个小时前 几天前
function postTime($time = null)
{
    $text = '';
    $time = $time === null || $time > time() ? time() : intval($time);
    $t    = time() - $time; //时间差 （秒）

    $y = date('Y', $time) - date('Y', time()); //是否跨年
    switch ($t) {
        case $t == 0:
            $text = '刚刚';
            break;
        case $t < 60:
            $text = $t . '秒前'; // 一分钟内
            break;
        case $t < 60 * 60:
            $text = floor($t / 60) . '分钟前'; //一小时内
            break;
        case $t < 60 * 60 * 24:
            $text = floor($t / (60 * 60)) . '小时前'; // 一天内
            break;
        case $t < 60 * 60 * 24 * 3:
            $text = floor($time / (60 * 60 * 24)) == 1 ? '昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time); // 昨天和前天
            break;
        case $t < 60 * 60 * 24 * 30:
            $text = date('Y-m-d', $time); //一个月内
            break;
        case $t < 60 * 60 * 24 * 365 && $y == 0:
            $text = date('Y-m-d', $time); //一年内
            break;
        default:
            $text = date('Y-m-d', $time); //一年以前
            break;
    }

    return $text;
}

// 获取文章分类名称
function getCateName($cid)
{
    $text = '热点资讯';
    switch ($cid) {
        case $cid == 92:
            $text = '独立站';
            break;
        case $cid == 91:
            $text = '沃尔玛';
            break;
        case $cid == 90:
            $text = '速卖通';
            break;
        case $cid == 62:
            $text = '亚马逊';
            break;
        case $cid == 68:
            $text = '跨境物流';
            break;
        case $cid == 61:
            $text = '平台政策';
            break;
        case $cid == 60:
            $text = '行业动态';
            break;
        case $cid == 59:
            $text = '热点资讯';
            break;
        case $cid == 121:
            $text = '俄罗斯海外仓';
            break;
        case $cid == 157:
            $text = '海外仓';
            break;
    }

    return $text;
}

/**
 * 重定向地址
 * param srting $data
 * return srting
 */
function akaliUrl()
{
    // 基本参数
    $request = request();
    // $host = request()->host(); // 域名
    // $module     = $request->module(); // 模块名
    $module     = is_mobile() ? 'mobile' : 'index'; // 模块名
    $controller = $request->controller(); // 控制器名
    $action     = $request->action(); // 方法名
    $param      = $request->param(); // 参数

    $url = url($module . '/' . $controller . '/' . $action); // '模块/控制器/方法名/参数';
    return $url;
}

// PHP 判断是手机端浏览还是web端浏览
function is_mobile()
{

    //正则表达式,批配不同手机浏览器UA关键词。

    $regex_match = "/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";

    $regex_match .= "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";

    $regex_match .= "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";

    $regex_match .= "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";

    $regex_match .= "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";

    $regex_match .= ")/i";

    return isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']) or preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT'])); //如果UA中存在上面的关键词则返回真。

}

// 用于用户名的生成不重复的随机字符串
function user_number($start = 0, $end = 9, $len = 10)
{
    $co  = 0;
    $arr = $reArr = array();
    while ($co < $len) {
        $arr[] = mt_rand($start, $end);
        $reArr = array_unique($arr);
        $co    = count($reArr);
    }
    return implode('', $reArr);
}

// 订单状态
function order_status($status)
{
    $srt = '';
    switch ($status) {
        case 0:
            $srt = '未支付';
            break;
        case 1:
            $srt = '已支付';
            break;
        case 2:
            $srt = '取消订单';
            break;
        default:
            $srt = '异常';
            break;
    }
    return $srt;
}

// 活动状态
function activity_status($status, $end_time)
{
    $srt = $status == 1 && $end_time > time() ? '进行中' : '已结束';
    return $srt;
}

/**
 * 获取随机字符串
 *
 * @param  int      $length     字符串长度
 * @return json                 pi返回的json数据
 */
function get_rand_char(int $length)
{
    $str    = '';
    $strPol = 'QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm';
    $max    = strlen($strPol) - 1;
    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}

/**
 * curl的GET请求方式
 *
 * @param string    $url    请求链接
 * @return json             返回结果
 */
function curl_get($url)
{
    $header = array(
        'Accept: application/json',
    );
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    // 超时设置,以秒为单位
    curl_setopt($curl, CURLOPT_TIMEOUT, 1);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //执行命令
    $data = curl_exec($curl);

    // 返回信息
    curl_close($curl);
    return $data;
}

/**
 * curl的POST请求方式
 *
 * @param string    $url        请求链接
 * @param string    $postdata   需要传输的数据，数组格式
 * @return json                 返回结果
 */
function curl_post($url, $postdata)
{

    $header = array(
        'Accept: application/json',
    );

    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    // 超时设置
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    // 超时设置，以毫秒为单位
    // curl_setopt($curl, CURLOPT_TIMEOUT_MS, 500);

    // 设置请求头
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    //执行命令
    $data = curl_exec($curl);

    // 返回信息
    curl_close($curl);
    return $data;
}

if (!function_exists('html_dcode')) {
    function html_dcode($str)
    {
        return htmlspecialchars_decode($str);
    }
}

/**
 * @description:  オラ!オラ!オラ!オラ!⎛⎝≥⏝⏝≤⎛⎝
 * @author: 神织知更
 * @time: 2022/02/26 15:57
 *
 * 验证参数
 *
 * @param  string    $root        应用目录
 * @return array                返回参数
 */
function check_param($root = 'usezan')
{
    $request = request();
    // $root       = $request->root();; // 应用目录
    // $root       = str_replace('/', '\\', $root); // 替换斜杠
    $controller = $request->controller(); // 控制器
    $action     = $request->action(); // 方法名
    $params     = $request->param(); // 获取当前参数

    // 拼接验证类名，注意路径不要出错
    $validateClassName = 'app\\' . $root . '\validate\\' . $controller;
    // 判断当前验证类是否存在
    if (class_exists($validateClassName)) {
        $validate = new $validateClassName;
        // 仅当存在验证场景才校验
        if ($validate->hasScene($action)) {

            /* try {
            validate($validateClassName)->scene($action)->check($params);
            } catch (ValidateException $e) {
            // throw new Params(['msg' => '阿卡丽', 'code' => 202, 'status' => 10002]);
            throw new \Exception($e->getMessage());
            } */
            // 设置当前验证场景
            $validate->scene($action);
            // 校验不通过则直接返回错误信息
            if (!$validate->check($params)) {

                jinx($validate->getError());

            } else {
                return $params;
            }
        }
    } else {
        return $params;
    }
}

/**
 * 返回api接口数据
 *
 * @param  string    $smg       描述信息
 * @param  int       $code      http状态码
 * @param  int       $status    程序状态码
 * @param  notype    $data      返回的数据
 * @return json                 api返回的json数据
 */
function show(string $msg, int $code = 200, int $status = 20000, $data = [])
{
    // 组装数据
    $resultData = [
        'status' => $status,
        'msg'    => $msg,
        'data'   => $data,
    ];
    // 返回数据
    return json($resultData, $code);
}

/**
 * 返回成功的api接口数据
 *
 * @param  array|string|int     $data      返回的数据
 * @param  string               $smg       描述信息
 * @param  int                  $status    程序状态码
 * @param  int                  $code      http状态码
 * @return json                 api返回的json数据
 */
function success($data = [], int $status = 20000, int $code = 200, string $msg = '成功')
{
    // 组装数据
    if (is_string($data) && (int) ($data) == 0) {
        $resultData = [
            'status' => $status,
            'msg'    => $data,
            // 'data'   => [],
        ];
    } else {
        $resultData = [
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data,
        ];
    }
    // 返回数据
    // echo json($resultData, $code);exit;
    return json($resultData, $code);
}

/**
 * 返回失败的api接口数据
 *
 * @param  string    $smg       描述信息
 * @param  int       $status    程序状态码
 * @param  int       $code      http状态码
 * @return json                 api返回的json数据
 */
function fail(string $msg = '失败', int $status = 30000, int $code = 200)
{
    // 组装数据
    $resultData = [
        'status' => $status,
        'msg'    => $msg,
    ];
    // 返回数据
    // echo json_encode($resultData, $code);exit;
    return json($resultData, $code);
}

/**
 * @description:  オラ!オラ!オラ!オラ!⎛⎝≥⏝⏝≤⎛⎝
 * @author: 神织知更
 * @time: 2022/03/31 21:56
 *
 * 递归找子级数据
 *
 * @param  array    $data        二维数组
 * @param  int      $pid        父级id
 * @return array                返回处理好的数组
 */
function get_child($data, $pid = 0)
{
    $tmp = [];
    foreach ($data as $value) {
        if ($value['parent_id'] == $pid) {
            $value['child'] = get_child($data, $value['id']);
            $tmp[]          = $value;
        }
    }
    return $tmp;
}

/**
 * @description:  オラ!オラ!オラ!オラ!⎛⎝≥⏝⏝≤⎛⎝
 * @author: 神织知更
 * @time: 2022/03/31 21:58
 *
 * layui的树形图数据处理
 *
 * @param  array    $data            二维数组
 * @param  int      $parentid        父级id
 * @param  bool     $spread            节点是否全部展开
 * @return string                    返回处理好的字符串
 */
function get_child_tree_data($data, $parentid = 0, $spread = false)
{
    $tmp = '';
    foreach ($data as $value) {
        if ($value['parentid'] == $parentid) {
            $tmp .= "{";
            $tmp .= "label: '{$value['catname']}', id: {$value['id']}, pid: {$parentid},";
            if ($spread) {
                $tmp .= 'spread: true,';
            }

            $child = get_child_tree_data($data, $value['id']);
            if ($child) {
                $tmp .= 'children:[' . $child . ']';
            }

            $tmp .= "},";
        }
    }

    return $tmp;
}

/**
 * 缓存时间
 *
 * @return  integer     返回时间戳
 */
function cache_time(string $type = 'dawn_rand_time')
{
    switch ($type) {
        // 6小时
        case 'six_hour':
            $time = 3600 * 6;
            break;
        // 12小时 半天
        case 'half_day':
            $time = 3600 * 12;
            break;
        // 一天
        case 'one_day':
            $time = 3600 * 24;
            break;
        // 一周
        case 'one_week':
            $time = 3600 * 24 * 7;
            break;
        // 一个月
        case 'one_month':
            $time = 3600 * 24 * 30;
            break;
        // 一年
        case 'one_year':
            $time = 3600 * 24 * 365;
            break;
        // 随机 3-9 小时
        case 'rand_time':
            $time = rand(3600 * 3, 3600 * 9);
            break;
        // 凌晨0点
        case 'over_day':
            $time = 86400 - (time() + 8 * 3600) % 86400;
            break;
        // 凌晨3点
        case 'dawn_time':
            $time = 86400 - (time() + 8 * 3600) % 86400 + 3600 * 3;
            break;
        // 凌晨3点 + 随机时间
        case 'dawn_rand_time':
            $time = 86400 - (time() + 8 * 3600) % 86400 + 3600 * 3 + rand(1, 3600);
            break;
        // 默认：凌晨3点 + 随机时间
        default:
            $time = 86400 - (time() + 8 * 3600) % 86400 + 3600 * 3 + rand(1, 3600);
            break;
    }

    return $time;
}

/**
 * 返回黎明三点的随机时间
 *
 * @param  integer|string   $id     一般使用数据的id
 * @return integer                  返回距离黎明三点的剩余时间戳
 */
function dawn_time($id)
{
    $number = substr(crc32($id), 6);
    $time   = 86400 - (strtotime(date('Ymd H:i:30')) + 8 * 3600) % 86400 + 3600 * 3 + (int) $number;
    return $time;
}

/*********************************** Job招聘 ***********************************/

// 月薪范围
function job_salary_range($var)
{
    $string = '';
    switch ($var) {
        case 1:
            $string = '3000以下';
            break;
        case 2:
            $string = '3000-5000';
            break;
        case 3:
            $string = '5000-10000';
            break;
        case 4:
            $string = '10000-15000';
            break;
        case 5:
            $string = '15000-20000';
            break;
        case 6:
            $string = '20000以上';
            break;
    }
    return $string;
}
// 获取月薪范围条件
function where_salary_range(array &$where, int $var)
{
    switch ($var) {
        case 1:
            $where[] = ['salary_min', '<=', 3000];
            $where[] = ['salary_max', '<=', 3000];
            break;
        case 2:
            $where[] = ['salary_max', '>=', 3000];
            $where[] = ['salary_min', '<=', 5000];
            break;
        case 3:
            $where[] = ['salary_max', '>=', 5000];
            $where[] = ['salary_min', '<=', 10000];
            break;
        case 4:
            $where[] = ['salary_max', '>=', 10000];
            $where[] = ['salary_min', '<=', 15000];
            break;
        case 5:
            $where[] = ['salary_max', '>=', 15000];
            $where[] = ['salary_min', '<=', 20000];
            break;
        case 6:
            $where[] = ['salary_min', '>=', 20000];
            $where[] = ['salary_max', '>=', 20000];
            break;
    }
}
// 平台要求
function job_platform($var)
{
    $string = '';
    switch ($var) {
        case 1:
            $string = 'Amazon';
            break;
        case 2:
            $string = 'Wish';
            break;
        case 3:
            $string = 'eBay';
            break;
        case 4:
            $string = 'Lazada';
            break;
        case 5:
            $string = 'Shopee';
            break;
        case 6:
            $string = '独立站';
            break;
        case 7:
            $string = '速卖通';
            break;
        case 8:
            $string = 'Alibaba';
            break;
        case 9:
            $string = '其他';
            break;
    }
    return $string;
}
// 工作职位
function job_position($var)
{
    $string = '';
    switch ($var) {
        case 1:
            $string = '运营';
            break;
        case 2:
            $string = '客服';
            break;
        case 3:
            $string = '会计';
            break;
        case 4:
            $string = 'HR';
            break;
        case 5:
            $string = '行政';
            break;
        case 6:
            $string = '产品开发';
            break;
        case 7:
            $string = '采购';
            break;
        case 8:
            $string = '物流';
            break;
        case 9:
            $string = '业务';
            break;
        case 10:
            $string = '销售';
            break;
        case 11:
            $string = '财务';
            break;
        case 12:
            $string = '前台';
            break;
        case 13:
            $string = '操作';
            break;
        case 14:
            $string = '其他';
            break;
    }
    return $string;
}
// 学历要求
function job_education_background($var)
{
    $string = '';
    switch ($var) {
        case 1:
            $string = '学历不限';
            break;
        case 2:
            $string = '高中以下';
            break;
        case 3:
            $string = '高中';
            break;
        case 4:
            $string = '大专';
            break;
        case 5:
            $string = '本科';
            break;
        case 6:
            $string = '硕士';
            break;
        case 7:
            $string = '博士';
            break;
        default:
            $string = '学历不限';
            break;
    }
    return $string;
}
// 用学历文字，获取学历值
function education_background_val(string $string)
{
    switch ($string) {
        case '学历不限':
            $val = 1;
            break;
        case '高中以下':
            $val = 2;
            break;
        case '高中':
            $val = 3;
            break;
        case '大专':
            $val = 4;
            break;
        case '本科':
            $val = 5;
            break;
        case '硕士':
            $val = 6;
            break;
        case '博士':
            $val = 7;
            break;
        default:
            $val = 0;
            break;
    }
    return $val;
}
// 工作经验
function job_work_experience($var)
{
    $string = '';
    switch ($var) {
        case 1:
            $string = '经验不限';
            break;
        case 2:
            $string = '应届毕业生';
            break;
        case 3:
            $string = '1年以上';
            break;
        case 4:
            $string = '2年以上';
            break;
        case 5:
            $string = '3年以上';
            break;
        case 6:
            $string = '5年以上';
            break;
        case 7:
            $string = '8年以上';
            break;
        case 8:
            $string = '10年以上';
            break;
        default:
            $string = '经验不限';
            break;
    }
    return $string;
}
// 用工作经验文字获取值
function work_experience_val($string)
{
    switch ($string) {
        case '经验不限':
            $val = 1;
            break;
        case '应届毕业生':
            $val = 2;
            break;
        case '1年以上':
            $val = 3;
            break;
        case '2年以上':
            $val = 4;
            break;
        case '3年以上':
            $val = 5;
            break;
        case '5年以上':
            $val = 6;
            break;
        case '8年以上':
            $val = 7;
            break;
        case '10年以上':
            $val = 8;
            break;
        default:
            $val = 0;
            break;
    }
    return $val;
}
// 到岗时间
function job_duty_time($var)
{
    $string = '';
    switch ($var) {
        case 0:
            $string = '不限';
            break;
        case 1:
            $string = '1周以内';
            break;
        case 2:
            $string = '2周以内';
            break;
        case 3:
            $string = '3周以内';
            break;
        case 4:
            $string = '1个月之内';
            break;
        case 5:
            $string = '随时到岗';
            break;
        case 6:
            $string = '待定';
            break;
        default:
            $string = '不限';
            break;
    }
    return $string;
}
// 用到岗时间文字获取值
function duty_time_val($string)
{
    switch ($string) {
        case '不限':
            $val = 1;
            break;
        case '1周以内':
            $val = 2;
            break;
        case '2周以内':
            $val = 3;
            break;
        case '3周以内':
            $val = 4;
            break;
        case '1个月之内':
            $val = 5;
            break;
        case '随时到岗':
            $val = 6;
            break;
        case '待定':
            $val = 7;
            break;
        default:
            $val = 0;
            break;
    }
    return $val;
}
// 更新时间
function job_update_time($var)
{
    $string = '';
    switch ($var) {
        case 1:
            $string = '今天';
            break;
        case 2:
            $string = '最近3天';
            break;
        case 3:
            $string = '最近7天';
            break;
        case 4:
            $string = '最近1个月';
            break;
        case 5:
            $string = '最近3个月';
            break;
        default:
            $string = '更新时间';
            break;
    }
    return $string;
}
// 用更新时间文字获取时间戳值
function update_time_val($string)
{
    $today = strtotime(date("Y-m-d"), time());
    switch ($string) {
        case '今天':
            $val = $today;
            break;
        case '最近3天':
            $val = $today - 86400 * 3;
            break;
        case '最近7天':
            $val =  $today - 86400 * 7;
            break;
        case '最近1个月':
            $val =  $today - 86400 * 30;
            break;
        case '最近3个月':
            $val =  $today - 86400 * 90;
            break;
        default:
            $val = time();
            break;
    }
    return $val;
}
// 企业类型
function job_enterprise_type($var)
{
    $string = '';
    switch ($var) {
        case 1:
            $string = '个体工商户';
            break;
        case 2:
            $string = '民营企业';
            break;
        case 3:
            $string = '合资企业';
            break;
        case 4:
            $string = '国有企业';
            break;
        case 5:
            $string = '行政与事业单位';
            break;
    }
    return $string;
}
// 公司人数
function job_people_number($var)
{
    $string = '';
    switch ($var) {
        case 1:
            $string = '1-10';
            break;
        case 2:
            $string = '10-50';
            break;
        case 3:
            $string = '50-100';
            break;
        case 4:
            $string = '100-200';
            break;
        case 5:
            $string = '200-500';
            break;
        case 6:
            $string = '500-1000';
            break;
        case 7:
            $string = '1000-2000';
            break;
        case 8:
            $string = '2000-10000';
            break;
        case 9:
            $string = '10000-100000';
            break;
    }
    return $string;
}
