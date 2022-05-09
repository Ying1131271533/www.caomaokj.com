<?php /*a:2:{s:45:"../application/usezan/view/category\tree.html";i:1650422513;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
<link rel="stylesheet" href="/static/admin/layui/css/layui.css" tppabs="/static/admin/layui/css/layui.css" media="all">
  <div class="layui-fluid">
	<div class="layui-row layui-col-sm10 layui-col-md-offset0" style="margin-bottom:20px;">
		<div class="layui-col-md9"><a onclick="add(0)" class="layui-btn layui-btn-danger">添加节点</a></div>
	</div>
	
	<div class="layui-col-sm10 layui-col-md-offset1">
		<div id="jinx" class="demo-tree"></div>
	</div>
  </div>

  <script>
	layui.use(['jquery', 'layer', 'table', 'flow', 'form', 'tree'], function(){
		var table = layui.table
		,$ = layui.$
		,layer = layui.layer
		,flow = layui.flow
		,form = layui.form
		,tree = layui.tree
		,util = layui.util
		
		//监听提交
		form.on('submit(formdemo)', function(data){
			layer.msg(json.stringify(data.field));
			return false;
		});
		
		//模拟数据
		data = [<?php echo htmlentities($tree); ?>];
		
		var checkbox = [];
		<?php foreach($ids as $value): ?>
		checkbox.push(<?php echo htmlentities($value); ?>);
		<?php endforeach; ?>

		// 树形菜单
		tree.render({
			elem: '#jinx'
			,data: data
			//,showCheckbox: true  //是否显示复选框
			,key: 'id'  //定义索引名称
			,checked: checkbox  //选中节点(依赖于 showCheckbox 以及 key 参数)。
			,spread: true  // 展开节点(依赖于 key 参数)
			,accordion: true //是否开启手风琴模式
			,isJump: true //是否允许点击节点时弹出新窗口跳转
			,edit: ['add', 'update', 'del'] //操作节点的图标
			
			//节点被点击的回调
			,click: function(obj){
				//layer.msg('状态：'+ obj.state + '<br>节点数据：' + JSON.stringify(obj.data)); //获取当前选中的节点数据
				//layer.alert(JSON.stringify(obj));
			}
			
			//复选框被点击的回调
			,oncheck: function(obj){
				console.log(obj.data); //得到当前点击的节点数据
				console.log(obj.checked); //得到当前节点的展开状态：open、close、normal
				console.log(obj.elem); //得到当前节点元素
				console.log(obj.hasChild); //当前节点下是否有子节点
			}
			
			//节点增/删/改的回调
			,operate: function(obj){
				var type = obj.type; //得到操作类型：add、edit、del
				var data = obj.data; //得到当前节点的数据
				var elem = obj.elem; //得到当前节点元素
				
				//Ajax 操作
				
				//得到节点索引
				var id = data.id ? data.id : 0;
				var data_id = elem.attr('data-id');
				var pid = data_id ? data_id : 0;
				
				//增加节点
				if(type === 'add') {
					var title = elem.find('.layui-tree-txt').html();
					add(pid, title);

				} else if(type === 'update') { //修改节点
					var title = elem.find('.layui-tree-txt').html();
					edit(id, title);
					
				} else if(type === 'del') //删除节点
				{
					del(id, pid);
				};
			}
		});

		// 自动触发修改弹窗
		$('.layui-icon-edit').click(function(){
			//延时 触发失去焦点事件.
			setTimeout(function () { $('input').trigger("blur"); }, 100);
		});

	});
	
	
	function add(pid) {
		//修改信息
		layer.open({
			id:'1',
			type: 2,
			title: '添加节点',
			shadeClose: true,
			shade: [0.8, '#000000'],
			area: ['800px', '600px'],
			content: "<?php echo url('category/add'); ?>?parentid=" + pid,
		});
	}
	
	
	function edit(id, title)
	{
		//修改信息
		layer.open({
			id:'1',
			type: 2,
			title: '节点ID:' + id,
			shadeClose: true,
			shade: [0.8, '#000000'],
			area: ['800px', '600px'],
			content: "<?php echo url('category/edit'); ?>?id=" + id + '&title=' + title,
		});
	}
	
	function del(id, pid)
	{
		if(!id) return false;
		
		layui.use(['jquery', 'layer'], function(){
			var layer = layui.layer
			,$ = layui.$
			
			$.get("<?php echo url('category/del'); ?>", {id:id, pid:pid}, function(data){
				if(data.status != 20000) {
					layer.msg(data.msg, {icon:2});
					setTimeout(function () { location.reload(); }, 1500);
					return false;
				}
				layer.msg(data.msg, {icon:1});
			}, 'json'); 
			
		});
		
	}
  </script>














