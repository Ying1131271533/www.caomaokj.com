<?php /*a:2:{s:44:"../application/usezan/view/category\add.html";i:1650354470;s:45:"../application/usezan/view/public\header.html";i:1630479318;}*/ ?>
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
<style type="text/css">
.wap-banner{display:block;line-height:113px;float:left;margin-left:100px;}
</style>
<div class="mainbox animated fadeInRight">
  <div class="mainnav_title">
      <a class="on">栏目添加</a>
      <a class="add-edit" href="<?php echo url('category/index'); ?>?tree=<?php echo htmlentities($tree_id); ?>">[<b class="uzfont uzico-fanhui"></b>栏目列表]</a>
      <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
<form name="myform" id="myform" action="<?php echo url('category/add'); ?>" method="post">
<div id="tabs" style="margin-top:10px;">
<div class="title">
	<ul style="margin-left:30px;">
		<li class="on"><a href="javascript:void(0);">基本设置</a></li>
		<li style="margin-left:10px;"><a href="javascript:void(0);">SEO设置</a></li>
    </ul>
</div>
<div class="content_2">
	<div class="tabbox" style="display:block;">
		<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
			<tr>
				<td width="90">栏目分类</td>
				<td>
					<select name="parentid">
					<option value="">一级栏目</option>
					<?php if(isset($select_categorys) and $select_categorys): ?><?php echo $select_categorys; ?><?php endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td width="90">模型分类</td>
				<td>
					<select name="module">
						<?php if(is_array($module) || $module instanceof \think\Collection || $module instanceof \think\Paginator): $i = 0; $__LIST__ = $module;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
						<option value="<?php echo htmlentities($key); ?>" <?php if($vo['module'] == $inmd): ?>selected<?php endif; ?>><?php echo htmlentities($vo['name']); ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td>栏目名称</td>
				<td><input type="text" name="catname" id="catname" class="input-text  required" minlength="1"  maxlength="30"  /></td>
			</tr>
			<tr id="module_url">
				<td>栏目路径</td>
				<td><input type="text" id="url" name="url" class="input-text required" minlength="1"  maxlength="150"/></td>
			</tr>
			<tr>
				<td>图标</td>
				<td style="position:relative;">
					<img class="up_iconimg curpic" width="50" onclick="up_img('up_iconimg','iconimg','jpg,gif,png,jpeg','10485760','290px', 0);" src="/static/admin/Images/upload_thumb.png" alt="上传封面" /><input type="hidden" class="iconimg" name="iconimg" />
				</td>
			</tr>
			<tr>
				<td>自定义链接</td>
				<td><input type="text" name="custom" class="input-text" /></td>
			</tr>
			<tr>
				<td>css类名</td>
				<td><input type="text" name="class" class="input-text" /></td>
			</tr>
			<tr>
				<td>打开方式</td>
				<td>
					<select name="target">
						<option value="_self" selected>本窗口</option>
						<option value="_blank">新窗口</option>
					</select>
				</td>
			</tr>
		</table>
	</div>
<!-- SEO 设置 -->
	<div class="tabbox" style="display:none;">
		<table cellpadding="0" cellspacing="0" class="table_form"width="100%">
		<tr>
		    <td>SEO栏目标题</td>
		    <td style="width:90%"><input name='seo_title' type='text' class="input-text" size='60' maxlength='150'></td>
		</tr>
		<tr>
		    <td>SEO栏目关键词</td>
		    <td><input name='seo_keywords' type='text' class="input-text" size='60' maxlength='150'></td>
		</tr>
		<tr>
		    <td>SEO栏目简介</td>
		    <td><textarea name='seo_description' rows="5" cols="60"></textarea></td>
		</tr>
		</table>
	</div>
<!-- /SEO 设置 -->	
</div>
<div class="btn">
	<input type="submit" value="<?php echo lang('dosubmit'); ?>" class="button" />
	<input type="reset"  value="<?php echo lang('cancel'); ?>" class="button" />
</div>
</div>
</form>
</div>
<script type="text/javascript" src="/static/admin/Js/pinyin.js"></script>
<script type="text/javascript">
//拼音转换
$("#catname").keyup( function () {
	$("#url").val(pinyin.go($(this).val(),1));
});
//tab切换
new Tabs("#tabs",".title ul li",".content_2",".tabbox","on",1);
</script>