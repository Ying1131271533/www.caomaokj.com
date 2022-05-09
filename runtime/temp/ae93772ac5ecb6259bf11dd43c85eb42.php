<?php /*a:2:{s:44:"../application/usezan/view/college\join.html";i:1645782483;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
	    <a class="on">课程报名</a>
	    <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
	<table  class="search_table" width="100%">
		<tr>
			<td class="search">
				<form action="<?php echo url('college/join'); ?>" method="post">
					<input id="title" type="text" class="input-text" name="college_id" placeholder="课程id搜索" value="<?php echo input('college_id'); ?>" style="float:left;">
					<input id="title" type="text" class="input-text" name="title" placeholder="课程标题搜索" value="<?php echo input('title/s'); ?>" style="float:left;">
					<input id="order_sn" type="text" class="input-text" name="order_sn" placeholder="订单号搜索" value="<?php echo input('order_sn/s'); ?>" style="float:left;">
					<input id="phone" type="text" class="input-text" name="phone" placeholder="手机号码搜索" value="<?php echo input('phone/s'); ?>" style="float:left;">
					<input id="member_id" type="text" class="input-text" name="member_id" placeholder="用户id搜索" value="<?php echo input('member_id'); ?>" style="float:left;">
					<select class="catid" name="limit">
						<option value="20" <?php if($limit == '20'): ?>selected<?php endif; ?>>20 条/页</option>
						<option value="50" <?php if($limit == '50'): ?>selected<?php endif; ?>>50 条/页</option>
						<option value="100" <?php if($limit == '100'): ?>selected<?php endif; ?>>100 条/页</option>
						<option value="200" <?php if($limit == '200'): ?>selected<?php endif; ?>>200 条/页</option>
					</select>
					<select class="catid" name="pay_status">
						<option value="" <?php if($pay_status == ''): ?>selected<?php endif; ?>>支付状态</option>
						<option value="0" <?php if($pay_status == '0'): ?>selected<?php endif; ?>>未支付</option>
						<option value="1" <?php if($pay_status == '1'): ?>selected<?php endif; ?>>已支付</option>
						<option value="2" <?php if($pay_status == '2'): ?>selected<?php endif; ?>>退款</option>
					</select>
					<select class="catid" name="sms_status">
						<option value="" <?php if($sms_status == ''): ?>selected<?php endif; ?>>短信通知</option>
						<option value="0" <?php if($sms_status == '0'): ?>selected<?php endif; ?>>未通知</option>
						<option value="1" <?php if($sms_status == '1'): ?>selected<?php endif; ?>>已通知</option>
					</select>
					<select class="catid" name="connect_status">
						<option value="" <?php if($connect_status == ''): ?>selected<?php endif; ?>>联系状态</option>
						<option value="0" <?php if($connect_status == '0'): ?>selected<?php endif; ?>>未联系</option>
						<option value="1" <?php if($connect_status == '1'): ?>selected<?php endif; ?>>已联系</option>
					</select>
					<input type="hidden" name="tree" value="<?php echo htmlentities($tree_id); ?>"/>
					<input type="submit" value="<?php echo lang('chaxun'); ?>" class="button" />
					<a href="<?php echo url('college/join'); ?>" class="button">重置</a>
					<a href="javascript:;" onclick="sms_send()" class="button">发送短信</a>
					<a href="javascript:;" onclick="export_execl()" class="button">导出Execl表格</a>
				</form>
			</td>
		</tr>
	</table>
	<form name="myform" id="myform" action="<?php echo url('college/sendGroupMessage'); ?>" method="post">
		<div class="table-list">
		    <table width="100%" cellspacing="0">
				<thead>
		            <tr>
						<!-- <th width="20"><label><input type="checkbox" name="id">全选</label></th> -->
						<th width="40"><label><input type="checkbox" name="id"> ID</label></th>
						<th width="40">uid</th>
						<th width="80">订单号</th>
						<th width="50">姓名</th>
						<th width="80">联系电话</th>
						<th width="150" align="left">公司名称</th>
						<th width="250" align="left">需求/问题</th>
						<th width="40">数量</th>
						<th width="50">支付状态</th>
						<th width="50">短信通知</th>
						<th width="230">课程名称</th>
						<th width="50">联系状态</th>
						<th width="150">报名时间</th>
						<th width="20">操作</th>
					</tr>
		        </thead>
		      	<tbody>
					<?php foreach($list as $value): ?>
					<tr height="70">
						<td align="center"><label><input type="checkbox" name="ids[]" value="<?php echo htmlentities($value['id']); ?>"> <?php echo htmlentities($value['id']); ?></label></td>
						<!-- <td align="center"><?php echo htmlentities($value['id']); ?></td> -->
						<td align="center"><?php echo htmlentities($value['member_id']); ?></td>
						<td align="center"><?php echo htmlentities($value['order_sn']); ?></td>
						<td align="center"><?php echo htmlentities($value['name']); ?></td>
						<td align="center"><?php echo htmlentities($value['phone']); ?></td>
						<td align="left"><?php echo htmlentities($value['company']); ?></td>
						<td align="left"><?php echo htmlentities($value['demand']); ?></td>
						<td align="center"><?php echo htmlentities($value['number']); ?></td>

						<td align="center">
						<?php if($value['pay_status'] == 0): ?>
						<font color="red">未支付</font>
						<?php elseif($value['pay_status'] == 1): ?>
						<font color="green">已支付</font>
						<?php elseif($value['pay_status'] == 2): ?>
						<font color="#999">已退款</font>
						<?php endif; ?>
						</td>

						<td align="center">
						<?php if($value['sms_status'] == 0): ?>
						<font color="red">未通知</font>
						<?php elseif($value['sms_status'] == 1): ?>
						<font color="green">已通知</font>
						<?php endif; ?>
						</td>
						<td align="center"><a href="<?php echo url('index/college/detail', ['id' => $value['college_id']]); ?>" target="_blank"><?php echo htmlentities($value['title']); ?></a></td>

						<td align="center">
		                    <a onclick="ajax_status(this)" data-id="<?php echo htmlentities($value['id']); ?>" data-url="<?php echo url('ajax/status', ['id' => $value['id'], 'value' => $value['connect_status'], 'field' => 'connect_status', 'db' => 'college_join']); ?>" class="bth-a ajax-status <?php echo $value['connect_status']==1 ? ''  :  'error-c'; ?>"></a>
		                </td>

						<td align="center"><?php echo htmlentities($value['create_time']); ?></td>
						<td align="center" style="width: 30px;">
							<input value="查看详情" data-url="<?php echo url('college/joinDetail', ['id' => $value['id']]); ?>" class="keyword-article button">
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



function sms_send(){
	layer.confirm('确定要发送短信吗', function(index){
		$("#myform").submit();
		layer.close(index);
	});
}

// 导出execl
function export_execl(){
	layer.confirm('确定要导出吗', function(index){
		$('#myform').attr('action', 'export');
		$("#myform").submit();
		layer.close(index);
	});
}

$('input[name="id"]').click(function(){
	var id_status = $('input[name="id"]').is(":checked");
	$('input[name="ids[]"]').prop('checked', id_status);
});

$('input[name="ids[]"]').click(function(){
	var length = $('input[name="ids[]"]').not("input:checked").length;
	if(length < 1)
	{
		$('input[name="id"]').prop('checked', true);
	}else
	{
		$('input[name="id"]').prop('checked', false);
	}
});


$(".keyword-article").on("click",function () {
    var node_url = $(this).attr("data-url");
    layer.open({
        title:'报名详情',
        type: 2,
        closeBtn: false, //不显示关闭按钮
        shift: 2,
        shadeClose: true, //开启遮罩关闭
        area: ['60%', '80%'],
        content: node_url
    });
});
</script>