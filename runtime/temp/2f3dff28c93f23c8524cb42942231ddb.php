<?php /*a:1:{s:48:"../application/usezan/view/attachment\index.html";i:1617549284;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title>文件上传</title>
	<link rel="stylesheet" type="text/css" href="/static/admin/Css/style.css" />
	<link rel="stylesheet" href="/static/admin/Js/zyUpload/skins/zyupload-1.0.0.css " type="text/css">
	<script type="text/javascript" src="/static/admin/Js/jquery.min.js"></script>
	<script type="text/javascript" src="/static/admin/Js/layer-v2.3/layer.js"></script>
</head>
<body>
	<div class="usezan_upload">
		<div id="usezan_upload"></div>
    </div>
<script type="text/javascript" src="/static/admin/Js/zyUpload/zyupload.basic-1.0.0.js"></script>
</body>
<script type="text/javascript">
$(function(){
	// 初始化插件
	var usezan_album = "<?php echo config('usezan_album'); ?>",
		usezan_class="<?php echo htmlentities($upload['obj']); ?>", //类名
        usezan_hideimg="<?php echo htmlentities($upload['hideimg']); ?>", //表单名称
        usezan_type = "<?php echo htmlentities($upload['isthumb']); ?>", //文件类型
        usezan_size = "<?php echo htmlentities($upload['filesize']); ?>", //大小
        usezan_imgs = "<?php echo htmlentities($upload['imgs']); ?>", //多图、单图
        usezan_auth = "<?php echo !empty($upload['auth']) ? htmlentities($upload['auth']) :  0; ?>"; //存储方式
    var Upload = "/usezan/attachment/usezan_upload?type="+usezan_type+"&inputname="+usezan_hideimg+"&filesize="+usezan_size+"&imgs="+usezan_imgs+"&usezan_auth="+usezan_auth; //上传地址
	$("#usezan_upload").zyUpload({
		width            :   "510px",                 // 宽度
		height           :   "226px",                 // 宽度
		itemWidth        :   "140px",                 // 文件项的宽度
		itemHeight       :   "115px",                 // 文件项的高度
		url              :   Upload,              // 上传文件的路径
		fileType         :   [<?php echo $upload['type']; ?>],// 上传文件的类型
		fileSize         :   <?php echo htmlentities($upload['filesize']); ?>,   // 上传文件的大小
		multiple         :   true,                    // 是否可以多个文件上传
		dragDrop         :   false,                   // 是否可以拖动上传文件
		tailor           :   false,                   // 是否可以裁剪图片
		del              :   true,                    // 是否可以删除文件
		finishDel        :   false,  				  // 是否在上传文件完成后删除预览
		/* 外部获得的回调接口 */
		onSelect: function(selectFiles, allFiles){    // 选择文件的回调方法  selectFile:当前选中的文件  allFiles:还没上传的全部文件
		},
		onDelete: function(file, files){              // 删除一个文件的回调方法 file:当前删除的文件  files:删除之后的文件
			console.info("当前删除了此文件：");
			console.info(file.name);
		},
		onSuccess: function(file, response){
			var data = $.parseJSON(response);
			//1多图上传
			if (data.imgs == 1) {
                var html ='';
                if (parseInt(usezan_album) > 0){
                    html += '<div class="uplist">' +
                        '<img src="'+data.thumb+'" width="150" height="60" style="float:left;margin-right:10px"/>' +
                        '<input type="hidden" size="10" class="input-text" name="imgs[]" value="'+data.thumb+'"  />' +
                        '<input type="text" class="input-text" placeholder="排序" name="imgs_order[]" size="2" />' +
                        '<input type="text" class="input-text" placeholder="标题" name="imgs_title[]" size="40" />' +
                        '<textarea name="imgs_remark[]" row="4" style="resize:none;" placeholder="简介" cols="60"></textarea>&nbsp;' +
                        '<a onclick="remove_thumbs(this)" href="javascript:;">移除</a></div>';
                } else {
                    html += '<li>'+
                        '<img src="'+data.thumb+'">'+
                        '<input type="hidden" name="pics[]" value="'+data.thumb+'"/>'+
                        '<span onclick="remove_thumb(this)" class="remove-x"></span>'+
                        '</li>';
                }
                parent.$('#pics_images').append(html);
			} else if (data.imgs == 2) {
				//单文件
				var ico = "/static/admin/Images/fileType/"+data.ext+".png";
				parent.$("."+usezan_class).attr("src",ico);
			    parent.$("."+usezan_hideimg).val(data.thumb);
			} else if (data.imgs == 3){
				//多文件上传
				var ico = "/static/admin/Images/fileType/"+data.ext+".png";
				var html ='';
				    html += '<div class="uplist"><img src="'+ico+'" width="64" style="float:left;margin-right:10px"/><input type="hidden" size="10" class="input-text" name="'+data.inputname+'[]" value="'+data.thumb+'"  /><input type="text" class="input-text" placeholder="排序" name="'+data.inputname+'_name[]" size="8" /><input type="text" class="input-text" placeholder="标题" name="'+data.inputname+'_title[]" size="50" /><textarea name="'+data.inputname+'_intro[]" row="4" style="resize:none;" placeholder="简介" cols="70"></textarea>&nbsp;<a href="javascript:;">移除</a></div>';
				parent.$('#'+data.inputname+'_images').append(html);
			} else {
				//单图
				parent.$("."+usezan_class).attr("src",data.thumb);
			    parent.$("."+usezan_hideimg).val(data.thumb);
			}
			
		},
		onFailure: function(file, response){          // 文件上传失败的回调方法
			console.info("此文件上传失败：");
			console.info(file.name);
		},
		onComplete: function(response){
			setTimeout(function () {
				parent.layer.closeAll();
			},500); 
		}
	});
	
});
</script>
</html>