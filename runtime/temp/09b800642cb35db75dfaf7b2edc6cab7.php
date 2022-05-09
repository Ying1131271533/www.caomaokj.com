<?php /*a:3:{s:47:"../application/usezan/view/logistics\index.html";i:1625474517;s:45:"../application/usezan/view/public\header.html";i:1650422517;s:49:"../application/usezan/view/public\menu_right.html";i:1574769382;}*/ ?>
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
	    <a class="on">内容列表</a>
		<?php if(isset($auth_tree) and $auth_tree): if(is_array($auth_tree) || $auth_tree instanceof \think\Collection || $auth_tree instanceof \think\Paginator): $i = 0; $__LIST__ = $auth_tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
<a class="add-edit class-<?php echo htmlentities($vo['id']); if($current_url == $vo['name']): ?> onn<?php endif; ?>" href="<?php echo url($vo['name']); ?>?tree=<?php echo htmlentities($vo['parentid']); ?>">[<b class="uzfont <?php echo htmlentities($vo['uzico']); ?>"></b><?php echo htmlentities($vo['title']); ?>]</a>
<?php endforeach; endif; else: echo "" ;endif; ?>
<?php endif; ?>
	    <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
<table  class="search_table" width="100%">
	<tr>
		<td class="search">
			<form action="<?php echo url('logistics/index'); ?>" method="get">
				<input id="title" type="text" class="input-text" name="keyword" placeholder="关键字搜索" value="<?php echo htmlentities(app('request')->get('keyword')); ?>" />
				<select class="catid" name="status">
					<option value="0" <?php if($keys['status'] == '-1'): ?>selected<?php endif; ?>>状态</option>
					<option value="1" <?php if($keys['status'] == '0'): ?>selected<?php endif; ?>>隐藏</option>
					<option value="2" <?php if($keys['status'] == '1'): ?>selected<?php endif; ?>>显示</option>
				</select>
				<input type="hidden" name="tree" value="<?php echo htmlentities($tree_id); ?>"/>
				<input type="submit" value="<?php echo lang('chaxun'); ?>"  class="button" />
				<a href="<?php echo url('logistics/index'); ?>" class="button">重置</a>
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
				<th width="50"><?php echo lang('listorder'); ?></th>
				<th width="50">首页推荐</th>
				<th width="80" align="left">封面/ICON</th>
				<th width="350" align="left">标题</th>
				<th width="100" align="center">栏目分类</th>
				<th width="150"><?php echo lang('createtime'); ?></th>
				<th width="50"><?php echo lang('STATUS'); ?></th>
				<th align="left"><?php echo lang('manage'); ?></th>
			</tr>
        </thead>
      	<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
			<tr>
				<td width="20" align="center"><?php echo htmlentities($v['id']); ?></td>

				<td  width="50" align='center'><input name='listorders[<?php echo htmlentities($v['id']); ?>]' type='text' size='3' value='<?php echo htmlentities($v['listorder']); ?>' class='input-text-c'></td>

				<td  width="50" align='center'>
					<a onclick="ajax_status(this)" data-id="<?php echo htmlentities($v['id']); ?>" data-url="<?php echo url('ajax/status', ['id' => $v['id'], 'value' => $v['recommend'], 'field' => 'recommend', 'db' => 'logistics']); ?>" class="bth-a ajax-status <?php echo $v['recommend']==1 ? htmlentities($v['recommend']) : 'error-c'; ?>"></a>
				</td>

				<td align="left">
					<img src="<?php echo !empty($v['thumb']) ? htmlentities($v['thumb']) : '/static/admin/Images/nodata.svg'; ?>" width="50" style="cursor:pointer;" onclick="open_img(this)" />
				</td>

				<td align="left"><?php echo htmlentities($v['title']); ?></td>
				<td align="center"><?php echo isset($category[$v['catid']]) ? $category[$v['catid']]['catname'] : '<font color="red">已删除</font>'; ?></td>
				<td align="center"><?php echo htmlentities($v['createtime']); ?></td>
				<td align="center">
					<?php if($v['status'] == '1'): ?>
					<a class="bth-a ajax-status" onclick="StatusAjax(this);" data-href="<?php echo url('ajax/statusajax'); ?>?id=<?php echo htmlentities($v['id']); ?>&status=0"></a>
					<?php else: ?>
					<a class="bth-a error-c ajax-status" onclick="StatusAjax(this);" data-href="<?php echo url('ajax/statusajax'); ?>?id=<?php echo htmlentities($v['id']); ?>&status=1"></a>
					<?php endif; ?>
				</td>
				<td align="left">
					<a href="<?php echo url('logistics/edit'); ?>?id=<?php echo htmlentities($v['id']); ?>&tree=<?php echo htmlentities($tree_id); ?>"><?php echo lang('edit'); ?></a> |
	            	<a data-url="<?php echo url('keyword/logisticsKeyword', ['id' => $v['id']]); ?>" class="keyword-article" href="javascript:;">服务类型列表</a> | 
					<a href="javascript:confirm_delete('<?php echo url('logistics/del'); ?>?id=<?php echo htmlentities($v['id']); ?>&tree=<?php echo htmlentities($tree_id); ?>')"><?php echo lang('delete'); ?></a>
				</td>
			</tr>
			<?php endforeach; endif; else: echo "" ;endif; ?>
      	</tbody>
    </table>
    <div class="btn">
	    <input  type="button" class="button" value="<?php echo lang('listorder'); ?>" onclick="myform.action='<?php echo url('logistics/listorder'); ?>';$('#myform').submit();" />
    </div>
</div>
</form>
</div>

<div id="pages" class="page"><?php echo $list; ?></div>
<script type="text/javascript">
$(".keyword-article").on("click",function () {
    var node_url = $(this).attr("data-url");
    layer.open({
        title:'服务类型分配',
        type: 2,
        closeBtn: false, //不显示关闭按钮
        shift: 2,
        shadeClose: true, //开启遮罩关闭
        area: ['60%', '80%'],
        content: node_url
    });
});
</script>