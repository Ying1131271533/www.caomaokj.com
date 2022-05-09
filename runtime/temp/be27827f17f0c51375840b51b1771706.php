<?php /*a:2:{s:50:"../application/usezan/view/auth\auth_rule_add.html";i:1597906570;s:45:"../application/usezan/view/public\header.html";i:1630479318;}*/ ?>
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
	    <a class="on">权限节点添加</a>
	    <a class="add-edit" href="<?php echo url('auth/auth_rule'); ?>?tree=<?php echo htmlentities($tree_id); ?>">[<b class="uzfont uzico-fanhui"></b>返回列表]</a>
	    <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
<form id="myform" action="" method="post">
	<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
	<tr>
		<td width="120">分组</td>
		<td>
		    <select name="parentid">
		        <option value="0">一级分组</option>
		        <?php echo $rule; ?>
		    </select>
		</td>
	</tr>
	<tr>
		<td width="120">标题</td>
		<td><input type="text" class="input-text" name="title" validate="required:true,minlength:2, maxlength:50"/>{操作名称,例如：用户列表}</td>
	</tr>
	<tr>
		<td width="120">权限方法</td>
		<td><input type="text" class="input-text" name="name" validate="required:true,minlength:2, maxlength:50"/>{控制器/方法,例如：user/index}</td>
	</tr>
	<tr>
		<td width="120">Ico图标</td>
		<td><input type="text" class="input-text" name="uzico"/></td>
	</tr>
	<tr>
		<td>三级菜单</td>
		<td>
			<select name="issub">
				<option value="0">禁用</option>
				<option value="1">显示</option>
			</select>
		</td>
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
	<input type="submit"  value="<?php echo lang('dosubmit'); ?>" class="button" />
	<input type="reset"  value="<?php echo lang('cancel'); ?>" class="button" />
</div>
</form>
</div>