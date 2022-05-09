<?php /*a:2:{s:40:"../application/usezan/view/user\add.html";i:1648886536;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
<div class="mainbox animated fadeInRight">
	<div class="mainnav_title">
	    <a class="on">管理员添加</a>
	    <a class="add-edit" href="<?php echo url('user/index'); ?>?tree=<?php echo htmlentities($tree_id); ?>">[<b class="uzfont uzico-fanhui"></b>返回列表]</a>
	    <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
	<form id="myform" action="" method="post">
		<table cellpadding=0 cellspacing=0 class="table_form" width="100%">
		<tr>
			<td width="120"><?php echo lang('username'); ?></td>
			<td><input type="text" class="input-text" name="username" validate="required:true,minlength:4, maxlength:20"/></td>
		</tr>
		<tr>
			<td><?php echo lang('password'); ?></td><td><input type="password" id="pwd" name="password" class="input-text" validate="required:true,minlength:5,maxlength:20"/></td>
		</tr>
		<tr>
			<td><?php echo lang('realname'); ?></td>
			<td><input type="text" class="input-text"  name="realname" ></td>
		</tr>
		<tr>
			<td><?php echo lang('email'); ?></td>
			<td><input type="text" class="input-text"  name="email" validate="required:true,minlength:1,maxlength:40"></td>
		</tr>
		<tr>
			<td valign="top"><?php echo lang('user_group'); ?></td>
			<td>
			<select name="group_id">
			<?php if(is_array($rules) || $rules instanceof \think\Collection || $rules instanceof \think\Paginator): $i = 0; $__LIST__ = $rules;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
				<option value="<?php echo htmlentities($v['id']); ?>"><?php echo htmlentities($v['title']); ?></option>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
			</td>
		</tr>
		<tr>
			<td><?php echo lang('sex'); ?></td>
			<td><input type="radio"  name="sex" value="1" checked /> <?php echo lang('sex1'); ?>&nbsp;<input type="radio" name="sex" value="2"/> <?php echo lang('sex2'); ?> &nbsp;<input type="radio" name="sex" value="0"/><?php echo lang('sex0'); ?></td>
		</tr>
		<tr>
			<td><?php echo lang('telephone'); ?></td>
			<td><input type="text" class="input-text"  name="tel"></td>
		</tr>
		<tr>
			<td>状态</td>
			<td>
			<select name="status">
				<option value="1">启用</option>
				<option value="0">禁用</option>
			</select>
			</td>
		</tr>
	</table>
	<div class="btn">
		<input type="hidden" name="tree" value="<?php echo htmlentities($tree_id); ?>"/>
		<input type="submit" value="<?php echo lang('dosubmit'); ?>" class="button" />
		<input type="reset" value="<?php echo lang('cancel'); ?>" class="button" />
	</div>
	</form>
</div>