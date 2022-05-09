<?php /*a:3:{s:47:"../application/usezan/view/auth\auth_index.html";i:1622706059;s:45:"../application/usezan/view/public\header.html";i:1650422517;s:49:"../application/usezan/view/public\menu_right.html";i:1574769382;}*/ ?>
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
	    <a class="on">用户组列表</a>
		<?php if(isset($auth_tree) and $auth_tree): if(is_array($auth_tree) || $auth_tree instanceof \think\Collection || $auth_tree instanceof \think\Paginator): $i = 0; $__LIST__ = $auth_tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
<a class="add-edit class-<?php echo htmlentities($vo['id']); if($current_url == $vo['name']): ?> onn<?php endif; ?>" href="<?php echo url($vo['name']); ?>?tree=<?php echo htmlentities($vo['parentid']); ?>">[<b class="uzfont <?php echo htmlentities($vo['uzico']); ?>"></b><?php echo htmlentities($vo['title']); ?>]</a>
<?php endforeach; endif; else: echo "" ;endif; ?>
<?php endif; ?>
	    <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
      		<tr>
				<th width="40">ID</th>
				<th align="left"><?php echo lang('username'); ?></th>
				<th  width="30"><?php echo lang('status'); ?></th>
				<th  width="160"><?php echo lang('manage'); ?></th>
      		</tr>
      	</thead>
      	<tbody>
      		<?php if(is_array($ulist) || $ulist instanceof \think\Collection || $ulist instanceof \think\Paginator): $k = 0; $__LIST__ = $ulist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
      		<tr>
				<td align="center"><?php echo htmlentities($v['id']); ?></td>
				<td><?php echo htmlentities($v['title']); ?></td>
				<td align="center">
				<?php if($v['status'] == '1'): ?>
					<?php echo lang('enable'); else: ?>
					<?php echo lang('disable'); ?>
				<?php endif; ?>
				</td>
				<td align="center">
				  <a href="<?php echo url('auth/auth_edit'); ?>?id=<?php echo htmlentities($v['id']); ?>&tree=<?php echo htmlentities($tree_id); ?>"><?php echo lang('edit'); ?></a> |<?php if($v['id'] != 1): ?>
				  <a data-url="<?php echo url('auth/auth_node'); ?>?id=<?php echo htmlentities($v['id']); ?>" class="role-node" href="javascript:;">授权列表</a> |
				  <a href="javascript:confirm_delete('<?php echo url('auth/del'); ?>?id=<?php echo htmlentities($v['id']); ?>&tree=<?php echo htmlentities($tree_id); ?>')"><?php echo lang('delete'); ?></a>
				  <?php else: ?>--------------------<?php endif; ?>
              </td>      		
      		</tr>
      		<?php endforeach; endif; else: echo "" ;endif; ?>
      	</tbody>
    </table>
</div>
</div>
<div id="pages" class="page"><?php echo $ulist; ?></div>
<script type="text/javascript">
$(".role-node").on("click",function () {
    var node_url = $(this).attr("data-url");
    layer.open({
        title:'权限分配[CD代表设置左侧权限菜单]',
        type: 2,
        closeBtn: false, //不显示关闭按钮
        shift: 2,
        shadeClose: true, //开启遮罩关闭
        area: ['60%', '80%'],
        content: node_url
    });
});
</script>
