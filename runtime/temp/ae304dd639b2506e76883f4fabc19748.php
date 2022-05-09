<?php /*a:2:{s:46:"../application/usezan/view/index\welcomed.html";i:1623463148;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
<!DOCTYPE html>

<html lang="zh-cn">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="shortcut icon" href="/static/icon/icon.ico" />

<title><?php echo lang('usezan_title'); ?></title>

<link rel="stylesheet" type="text/css" href="/static/admin/Css/style.css" />

<link rel='stylesheet' type='text/css' href='/static/admin/font/uzfont.css' />

<script type="text/javascript" src="/static/admin/Js/jquery.min.js"></script>

<script type="text/javascript" src="/static/admin/Js/jquery.form.js"></script>

<script type="text/javascript" src="/static/admin/Js/jquery.validate.js"></script>

<script type="text/javascript" src="/static/admin/Js/my.js"></script>

<script type="text/javascript" src="/static/admin/Js/layer-v2.3/layer.js"></script>

<script type="text/javascript" src="/static/admin/Js/MyDate/WdatePicker.js"></script>

<script type="text/javascript" src="/static/akali/layui/css/layui.css"></script>
<script type="text/javascript" src="/static/akali/layui/layui.js"></script>


<script language="JavaScript">

var PUBLIC = '/static/admin/';

var CONTROLLER_NAME = '<?php echo request()->controller(); ?>';

var ACTION_NAME = '<?php echo request()->action(); ?>';

function confirm_delete(url){

    layer.confirm("<?php echo lang('real_delete'); ?>", {

        title:'~~删除提示~~',

        btn: ['删除','算了吧']

    }, function(){

        location.href = url;

    });

}

</script>

</head>

<body width="100%">

<!-- Template Ceng Start -->

<div class="dask-template">

    <div class="lists">

        <span class="load-ing"></span>

        <span class="close-x" onclick="close_x(this);"></span>

        <ul id="lists-temp"></ul>

    </div>

</div>

<!-- Template Ceng End -->

<!-- 查看图片 -->
<div id="akali" class="hide layui-layer-wrap" style="display: none;">
    <img src="" id=""/>
</div>
<script>
    function open_img(obj)
    {
        var src = obj.src;
        $('#akali img').attr('src', src);
        
        // 获取图片的真实宽高
        $('<img/>').attr("src", $('#akali img').attr("src")).load(function() {

             // 获取图片的宽度 不能超过1280px
            var pic_real_width = this.width > 1280 ? 1280 : this.width;  // Note: $(this).width() will not
            var pic_real_height = this.height; // work for in memory images.
            
            // 设置图片的宽度 不能超过1280px
            $('#akali img').attr('width', pic_real_width);
            
            // 页面层-佟丽娅
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                area: pic_real_width + 'px',
                skin: 'layui-layer-nobg', //没有背景色
                shadeClose: true,
                content: $('#akali')
            });
        });
    }
</script>
<div class="welcome">
	<div class="wel_list">
	    <div class="wel-li">
			<h1><b>管理员信息</b></h1>
			<ul>
			<?php if(is_array($userinfo) || $userinfo instanceof \think\Collection || $userinfo instanceof \think\Paginator): $i = 0; $__LIST__ = $userinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
			    <li><span><?php echo lang($key); ?>:</span><?php echo htmlentities($v); ?></li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
	</div>

	<div class="wel_list">
		<div class="wel-li">
			<h1><b>系统信息</b></h1>
			<ul>
				<?php if(is_array($server_info) || $server_info instanceof \think\Collection || $server_info instanceof \think\Paginator): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
					<li><span><?php echo lang($key); ?>:</span><?php echo htmlentities($v); ?></li>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
	</div>
	
	<!-- <div class="wel_list">
		<div class="wel-li">
			<h1><b>技术支持</b></h1>
			<ul>
			    <li><span>官方网站:</span><a href="https://www.h7uz.com" target="_blank">https://www.h7uz.com</a></li>
			    <li><span>客服电话:</span>135-3395-7190(周先生) 135-3395-7191(李小姐)</li>
			    <li><span>客服QQ:</span>
					<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1847355369&site=qq&menu=yes">
						<img style="vertical-align:sub;" border="0" src="http://wpa.qq.com/pa?p=1:1847355369:4" alt="点击这里给我发消息" height="16" align="absmiddle">
						(优优)
					</a>
				</li>
				<li><span>客服QQ:</span>
					<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=366532931&site=qq&menu=yes">
						<img style="vertical-align:sub;" border="0" src="http://wpa.qq.com/pa?p=1:366532931:4" alt="点击这里给我发消息" height="16" align="absmiddle">
						(李小姐)
					</a>
				</li>
			    <li>如需技术支持请联系我们的专业客服</li>
			</ul>
		</div>
	</div>
	<div class="wel_list version">
	    <div class="wel-li">
			<h1><b>系统详情</b></h1>
			<ul>
			    <li><span>程序版本:</span>USEZANCMS&nbsp;<?php echo htmlentities($version['pc_version']); ?></li>
			    <li><span>更新日期:</span><?php echo htmlentities($version['pc_release']); ?></li>
			    <li><span>更新版本:</span><?php echo htmlentities($version['pc_release']); ?></li>
			    <li><span>版权所有:</span>环企优站科技有限公司</li>
			    <li><span>程序开发:</span>环企优站科技有限公司</li>
			</ul>
		</div>
	</div> -->
</div>	
