<?php /*a:2:{s:44:"../application/usezan/view/member\index.html";i:1648539643;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
<style>
	#search_form input{
		float: left;
	}
</style>
<div class="mainbox animated fadeInRight">
	<div class="mainnav_title">
	    <a class="on">用户列表</a>
		<a class="add-edit " href="<?php echo url('member/add'); ?>">[<b class="uzfont uzico-add"></b>用户添加]</a>
	    <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
<table  class="search_table" width="100%">
	<tr>
		<td class="search">
			<form action="<?php echo url('member/index'); ?>" method="post" id="search_form">
				<input id="user_id" type="text" class="input-text" name="user_id" placeholder="用户id" value="<?php echo input('user_id'); ?>" />
				<input id="username" type="text" class="input-text" name="username" placeholder="用户名称" value="<?php echo input('username'); ?>" />
				<select class="catid" name="status">
					<option value="" <?php if($status == ''): ?>selected<?php endif; ?>>状态</option>
					<option value="1" <?php if($status == '1'): ?>selected<?php endif; ?>>开启</option>
					<option value="2" <?php if($status == '2'): ?>selected<?php endif; ?>>关闭</option>
				</select>
				<input type="hidden" name="tree" value="<?php echo htmlentities($tree_id); ?>"/>
				<input type="submit" value="<?php echo lang('chaxun'); ?>"  class="button" />
				<!-- <input type="reset" value="<?php echo lang('reset'); ?>" class="button"  /> -->
				<a href="<?php echo url('member/index'); ?>" class="button">重置</a>
			</form>
		</td>
	</tr>
</table>
<form name="myform" id="myform" action="" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
		<thead>
            <tr>
				<th width="20">ID</th>
				<th width="200">名称</th>
				<th width="200">昵称</th>
				<th width="200">手机号码</th>
				<th width="200">邮箱</th>
				<th width="200">头像</th>
				<th width="200"><?php echo lang('createtime'); ?></th>
				<th width="200"><?php echo lang('STATUS'); ?></th>
			</tr>
        </thead>
      	<tbody>
			<?php foreach($list as $value): ?>
			<tr height="70">
				<td width="20" align="center"><?php echo htmlentities($value['id']); ?></td>

				<td align="center"><?php echo htmlentities($value['username']); ?></td>
				<td align="center"><?php echo htmlentities($value['nickname']); ?></td>
				<td align="center"><?php echo htmlentities($value['phone']); ?></td>
				<td align="center"><?php echo !empty($value['email']) ? htmlentities($value['email']) :  '————'; ?></td>

				<td align="center">
					<img height="70" src="<?php echo !empty($value['avatar']) ? htmlentities($value['avatar']) : '/static/admin/Images/nodata.svg'; ?>" style="cursor:pointer;" onclick="open_img(this)" />
				</td>


				<td align="center"><?php echo htmlentities($value['create_time']); ?></td>

				<td align="center">
                    <a onclick="ajax_status(this)" data-id="<?php echo htmlentities($value['id']); ?>" data-url="<?php echo url('ajax/status', ['id' => $value['id'], 'value' => $value['status'], 'field' => 'status', 'db' => 'member']); ?>" class="bth-a ajax-status <?php echo $value['status']==1 ? htmlentities($value['status']) : 'error-c'; ?>"></a>
                </td>

			</tr>
			<?php endforeach; ?>
      	</tbody>
    </table>
</div>
</form>
</div>
<div id="pages" class="page"><?php echo $list; ?></div>

<script type="text/javascript">
$(".keyword-article").on("click",function () {
    var node_url = $(this).attr("data-url");
    layer.open({
        title:'关键词分配',
        type: 2,
        closeBtn: false, //不显示关闭按钮
        shift: 2,
        shadeClose: true, //开启遮罩关闭
        area: ['60%', '80%'],
        content: node_url
    });
});


function del(id) {
    layer.confirm('确定要删除吗', function(index) {
        window.location.href = "<?php echo url('member/del'); ?>?id=" + id;
        layer.close(index);
    });
}
</script>