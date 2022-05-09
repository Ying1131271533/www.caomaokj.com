$(function(){
	//列表
	var dir_string = dir_str(root_path,'');
	if(dir_string ==''){
		dir_string = '<li id="root_init">您的空间还没有任何文件，快上传试试吧</li>'+dir_string;
	}
	dir_string = '<li id="root_path" data-path="" data-len="-1" onmousedown="right_quick(event,this)" ondblclick="change_dir(this)"></li>'+dir_string;
	$('#file_wrap').html(dir_string);
	
	//单击右键触发 contextmenu 事件
	$('.wrap').bind("contextmenu", function(){
	    return false;
	});

	//上传
    $('#upload_input').change(function(){
        $('#upload_form').submit();
        $('#zhezhao_msg').show();
    })

});
/*
 * 查看文件夹
 	t this对象
 	allshow 赋值true 不隐藏 就是显示
 */
function change_dir(t,allshow){
	var data_len = $(t).attr('data-len');
	var is_find = is_find_child(t);
	var path = $(t).attr('data-path');
	if(!is_find){
		$.ajax({
			'url':changeDir,
			'dataType':'json',
			'data':{'path':path},
			'success':function(re){
				open_dir(t);
				$(t).after(dir_str(re,path))
			}
		})
	} else {
		var nexts_obj = find_child_obj(t);  //要判断下面一个是不是同级
		if(nexts_obj.is(":hidden")){
			open_dir(t);
			nexts_obj.stop().show();
		}else{
			if(!allshow){
				close_dir(t);
				nexts_obj.stop().fadeOut();
			}
		}
	}
	
}
//关闭按钮
function close_dir(t){
	$(t).find('.find_child').css('background-image','url('+STATICS+'open_dir.png)');
}
//开启按钮
function open_dir(t){
	$(t).find('.find_child').css('background-image','url('+STATICS+'close_dir.png)');
}
/**
 * 加号点击
 */
function pdbclick_like(t){
	var db_t = $(t).parents('li');
	change_dir(db_t)
}
/**
 * 判断当前对象下有无子文件
 */
function is_find_child(t){
	var data_len = $(t).attr('data-len');
	var next_level = $(t).next().attr('data-len');
	var is_find_child = next_level > data_len;
	return is_find_child;
}
/**
 * 返回当前目录下所有子文件的li对象
 */
function find_child_obj(t){
	var data_len = $(t).attr('data-len');
	var select_str = '';
	for(var i = data_len ; i>=0 ;i--){
		select_str += ((select_str === '') ? '' : ',' )+'[data-len="'+i+'"]' ;
	}
	var nexts_obj = $(t).nextUntil(select_str); 
	return nexts_obj;
}
/**
 *响应查看文件夹的json
  json:文件夹下所有文件信息的json对象
  path:这个文件夹的路径
 **/
function dir_str(json,path){
	var dir_len_num = path.split("/").length - 1;
		dir_len = dir_len_num*30+'px';
	var str = '';
	$.each(json,function(i,v){
		if(v.filetype == 'dir'){
			 var find_child = '';
			if (!v.isempty){
				find_child = '<div class="find_child" onclick="pdbclick_like(this)" style="background-image:url('+STATICS+'open_dir.png)"></div>';    //加号 更多
			}
			str += '<li data-path="'+path+'/'+v.filename+'" data-len="'+dir_len_num+'" onmousedown="right_quick(event,this,\'file\')" ondblclick="change_dir(this)" style=\'margin-left:'+dir_len+';background-image:url("'+STATICS+'dir.png");\'><span class="filename">'+v.filename+'</span>'+find_child+'<div onclick="rquiek_like(this,\'file\')" class="more"></div></li>'
		}else{
			str += '<li data-path="'+path+'/'+v.filename+'" data-len="'+dir_len_num+'" onmousedown="right_quick(event,this,\'dir\')" style=\'margin-left:'+dir_len+';background-image:url("'+STATICS+v.bgimg+'");\'><span class="filename">'+v.filename+'</span><span class="crea-time">'+v.ctime+'</span><div onclick="rquiek_like(this,\'dir\')" class="more"></div></li>'
		}
	});
	return str;
}
/**
 * 点击三小点
 */
function rquiek_like(t,hidecname){
	var w = $('#quick_menu').width();
	var rq_e = new Object();
		rq_e.which = 3;
		rq_e.pageY = $(t).offset().top + 5;
		rq_e.pageX = $(t).offset().left-w-10;
	var rq_t = $(t).parents('li');
	right_quick(rq_e,rq_t,hidecname)
}
//右键快捷   左键隐藏
function right_quick(e,t,hidecname){
	if(e.which == 3){
		$('#quick_menu li').show();
		$('#quick_menu .'+hidecname).hide();
		$('#quick_menu').css({'top':e.pageY,'left':e.pageX+3}).show();
		cqo = $(t);
		$('#file_wrap li').css('background-color','#fff');
		$(t).css('background-color','#d1e9e9');
		//加入当前li的背景色
	}else{
		$('#quick_menu').hide()
	}
}
//隐藏右键函数
function close_quick(){
	$('#quick_menu').hide();
}
//右键下的打开、
function quick_open(){
	change_dir(cqo);
	$('#quick_menu').hide()
}
//建立文件夹
function create_dir(){
	change_dir(cqo,true);
	$('#quick_menu').hide();
	var path = cqo.attr('data-path');
	$.ajax({
		'url':createDir,
		'dataType':'json',
		'data':{'path':path},
		'type':'post',
		'success':function(re){
			if(re.status){
                layer.msg('创建成功，正在刷新页面...',{time:2500},function () {
					window.location.reload();
                });
			}else{
				layer.msg('创建失败，请刷新网页重试...',{time:2500});
			}
		}
	})
}
/**
 * 编辑文件
 */
function edit_file() {
    $('#quick_menu').hide();
    var path = $.trim(cqo.attr('data-path').substr(1));
    if (!path) {
    	layer.msg('文件获取失败，请刷新再试...');
	}
	//窗口
    layer.open({
        type: 2,
        title: path+'-编辑',
        shadeClose: true,
        shade: 0.8,
        area: ['900px', '80%'],
        content: editFile+"?file="+path+'&type='+TypeMb
    });
}
/***
 * 重命名
 * @returns {boolean}
 */
function rename_file(){
	$('#quick_menu').hide();
	if(cqo.find('[name="newname"]').length >= 1) return false;
	var filenameObj = cqo.find('.filename');
	var oldname = filenameObj.html()
		filenameObj.html('<input  onblur="submit_rename(this,\''+oldname+'\')" type="text" name="newname" value="'+oldname+'">');
	filenameObj.children('[name="newname"]').focus().select()  //自动获取焦点 自动选择	
}
function submit_rename(t,oldname){
	var newname = $(t).val();
	if(newname == oldname){
		rename_end(t,newname);
		return false;
	}
	var path = cqo.attr('data-path');
	var rename_re;
	$.ajax({
		'url':renameFile,
		'type':'post',
		'typeType':'json',
		'data':{ 'path':path,'newname':newname },
		'success':function(re){
			//rename_re = oldname;
			layer.msg(re.msg,{time:2500});
			rename_end(t,newname);
		}
	})
}
/**
 * 为上面函数做的完成后的
 * 重命名完成后 需要删掉子元素 
 */
function rename_end(t,name){
	var this_li_obj = $(t).parents('li');
	var oldpath = this_li_obj.attr('data-path');
	var newpath = oldpath.substr(0,oldpath.lastIndexOf('/'))+'/'+name;
	var child_obj = find_child_obj($(t).parents('li'));
	close_dir(this_li_obj);
	child_obj.remove();
	this_li_obj.attr('data-path',newpath);
	$(t).parent().html(name);
	$(t).remove();
}
/**
 * 删除文件
 */
function del_file(){
	$('#quick_menu').hide();
	var path = cqo.attr('data-path');
    layer.confirm('确定删除吗？删除后将不能恢复', {
    	title:'删除目录/文件',
        btn: ['确定','取消'] //按钮
    }, function(){
        $.ajax({
            url:delFile,
            type:'post',
            typeType:'json',
            data:{'path':path},
            success:function(re){
                if(re.status){
                    layer.msg('删除成功，正在刷新页面...',{time:2500},function () {
                        window.location.reload();
                    });
                } else {
                    layer.msg('删除失败,请刷新网页重试...',{time:2500});
                }
            }
        })
    });
}
/**
 * 上传文件
 */
function upload_file(){
	$('#quick_menu').hide();
	var path = cqo.attr('data-path');
    $('.path-is').val(path);
	$('#upload_input').click();
}
//成功提示
function upload_txt(txt){
    layer.msg(txt,{time:3000},function () {
    	window.location.reload();
    });
}
/**
 * 下载文件
 */
function download_file(){
	$('#quick_menu').hide();
	var path = cqo.attr('data-path');
	console.log(path);
	$('#download_input').val(path);
	$('#download_form').submit();
}
//根目录下建文件夹
function root_create(){
	var quick_e = new Object();
		quick_e.which = 3;
	var quick_t = $('#root_path');
		right_quick(quick_e,quick_t);
		create_dir();
	$('#root_init').remove();
}
//根目录下上传文件
function root_upload(){
	var quick_e = new Object();
		quick_e.which = 3;
	var quick_t = $('#root_path');
		right_quick(quick_e,quick_t);
		upload_file();
	$('#root_init').remove();
}








