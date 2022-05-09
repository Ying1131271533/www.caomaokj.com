<?php /*a:2:{s:43:"../application/usezan/view/index\index.html";i:1632901857;s:48:"../application/usezan/view/public\menu_left.html";i:1622621339;}*/ ?>
<!DOCTYPE html>

<html lang="zh-cn">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="shortcut icon" href="/static/icon/icon.ico" />

<script type="text/javascript" src="/static/admin/Js/jquery.min.js"></script>

<script type="text/javascript" src="/static/admin/Js/jquery.artDialog.js?skin=default"></script>

<script type="text/javascript" src="/static/admin/Js/iframeTools.js"></script>

<link rel='stylesheet' type='text/css' href='/static/admin/Css/style.css' />

<link rel='stylesheet' type='text/css' href='/static/admin/font/uzfont.css' />

<script type="text/javascript" src="/static/admin/Js/layer-v2.3/layer.js"></script>

<title><?php echo lang('usezan_title'); ?></title>

</head>

<style>

::-webkit-scrollbar,

::-webkit-scrollbar-thumb,

::-webkit-scrollbar-track-piece,

::-webkit-scrollbar-thumb:hover{width:0;}

.cm{float:left;color:red;margin:0;padding-left:15px;}

.header .logo a{ background: url(/static/icon/icon-logo.png) no-repeat; background-size: 150px; background-position: center;}

.header .logo.small a{width: 66px;background: url(/static/admin/Images/UI/logo.png) no-repeat;}

</style>

<body>

<div id="header" class="header">

	<div class="logo fl"><a href="/" target="_blank"></a></div>

	<div id="Main_drop">

		<a href="javascript:toggleMenu(1);" class="on"><span class="on-pic"></span></a>   

		<a href="javascript:toggleMenu(0);" class="off" style="display:none;"><span class="on-of"></span></a>

	</div>

	<div class="nav">

		<span class="user-name">欢迎你 <?php echo htmlentities(app('session')->get('usezan_admin.username')); ?></span>

		<a href="/" target="_blank">访问首页</a>

		<a href="javascript:void(0);" onclick="gocacheurl();">更新缓存</a>

		<a class="logout" href="<?php echo url('login/logout'); ?>" target="_top"></a>

	</div>

	<div class="header_footer"></div>

</div>

<div id="Main_content">

	<div id="MainBox" >

	    <div class="main_box animated fadeInRight">

			<iframe name="main" id="Main" src='<?php echo url("index/welcomed"); ?>' frameborder="false" scrolling="auto"  width="100%" height="auto" allowtransparency="true"></iframe>

		</div>

    </div>

	<div id="leftMenuBox">
	<div class="topmenu">
		<ul>
			<?php $_result=Auth_Menu();if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
			<li class="on">
			    <span style="background: #2d75c3;"><a><?php echo htmlentities($v['title']); ?></a><i></i><b class="nav-ico"><em class="uzfont <?php echo htmlentities($v['name']); ?>"></em></b></span>
				<div class="childer_none" <?php echo $v['id']==28 ? htmlentities($v['id']) : 'style="display: block;"'; ?>>
					<?php if(is_array($v['lower']) || $v['lower'] instanceof \think\Collection || $v['lower'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['lower'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
					<dd onclick="javascript:gourl($(this),'/usezan/<?php echo htmlentities($vv["name"]); ?>?tree=<?php echo htmlentities($vv['id']); ?>')"><a><?php echo htmlentities($vv['title']); ?></a></dd>
					<?php endforeach; endif; else: echo "" ;endif; ?>
			    </div>
			</li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
    </div>
</div>

    <div class="left_back"></div>

</div>

<div id="footer" class="footer">Copyright © 2016-2040 广东威速易信息科技有限公司版权所有</div>

<script type="text/javascript">

	if(!Array.prototype.map)

	Array.prototype.map = function(fn,scope) {

	  var result = [],ri = 0;

	  for (var i = 0,n = this.length; i < n; i++){

		if(i in this){

		  result[ri++]  = fn.call(scope ,this[i],i,this);

		}

	  }

	return result;

	};

	var getWindowWH = function(){

		  return ["Height","Width"].map(function(name){

		  return window["inner"+name] ||

			document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]

		});

	};

	window.onload = function (){

		if(!+"\v1" && !document.querySelector) { //IE6 IE7

		 document.body.onresize = resize;

		} else {

		  window.onresize = resize;

		}

		function resize() {

			wSize();

			return false;

		}

	};

	function wSize(){

		var str = getWindowWH();

		var strs = new Array();

		strs = str.toString().split(","); //字符串分割

		var h = strs[0] - 95 - 45;

		$('#leftMenuBox').height(h);

		$('#Main').height(h);

	}

	wSize();

	$(".left_back").css({'height':$(window).height() - 125});

	$(window).resize(function (){

		$(".left_back").css({'height':$(window).height() - 125});

	});

	//跳转地址

	function gourl(obj,url){

		obj.addClass('on').siblings().removeClass("on");

		window.main.location.href=url;

	}

	//菜单点击状态

	$(".topmenu span").on("click",function () {

		var par = $(this).parents("li"),

			parents = $(this).parents("#leftMenuBox");



		if (parents.hasClass("small")) {

				parents.removeClass("small");

				$(".logo").removeClass("small");

				$(".main_box").css('margin-left','224px');

				$('#Main_drop a.on').show();

				$('#Main_drop a.off').hide();

				par.addClass("on").siblings().removeClass("on");

				par.find(".childer_none").stop(true,true).slideDown();

				par.siblings().find(".childer_none").stop(true,true).slideUp();

		} else {

			if(par.hasClass("on")){

				par.removeClass("on");

				par.find(".childer_none").stop(true,true).slideUp();

			} else {

				par.addClass("on").siblings().removeClass("on");

				par.find(".childer_none").stop(true,true).slideDown();

				par.siblings().find(".childer_none").stop(true,true).slideUp();

			}

		}

	});

	//更新缓存

	function gocacheurl(){

		window.location.href= "<?php echo url('index/syscache'); ?>";

	}

	//收藏左菜单
	function toggleMenu(doit){

		if(doit==1){

			$('#Main_drop a.on').hide();

			$('#Main_drop a.off').show();

			$(".logo,#leftMenuBox").addClass("small");

			$('#MainBox .main_box').css('margin-left','95px');

		}else{

			$('#Main_drop a.off').hide();

			$('#Main_drop a.on').show();

			$(".logo,#leftMenuBox").removeClass("small");

			$('#MainBox .main_box').css('margin-left','224px');

		}

	}

</script>

</body>

</html> 