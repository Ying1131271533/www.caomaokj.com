var CONTROLLER_NAME,
	admin_type = 'usezan';
//全选按钮
function selectall(name) {
	if ($("#check_box").prop("checked")) {
		$("input[name='"+name+"']").each(function() {
			this.checked=true;
		});
	} else {
		$("input[name='"+name+"']").each(function() {
			this.checked=false;
		});
	}
}
//TAB切换
function Tabs(id,title,content,box,on,action){
	if(action){
		  $(id+' '+title).click(function(){
			  $(this).addClass(on).siblings().removeClass(on);
			  $(content+" > "+box).eq($(id+' '+title).index(this)).show().siblings().hide();
		  });
	  }else{
		  $(id+' '+title).mouseover(function(){
			  $(this).addClass(on).siblings().removeClass(on);
			  $(content+" > "+box).eq($(id+' '+title).index(this)).show().siblings().hide();
		  });
	  }
}

function openwin(id,url,title,width,height,lock,yesdo,topurl){ 
		art.dialog.open(url, {
		id:id,
		title: title,
		lock:  lock,
		width: width,
		height: height,
		cancel: true,
		ok: function(){
			var iframeWin = this.iframe.contentWindow;
    		var topWin = art.dialog.top;
				if(yesdo || topurl){
					if(yesdo){
					    yesdo.call(this,iframeWin, topWin); 
					}else{
						art.dialog.close();
					    topWin.location.href=topurl;
					}
				}else{
					var form = iframeWin.document.getElementById('dosubmit');form.click();
				}
				return false;
			}
		});
}

//Template-关闭层
function close_x(obj){
	var pars = $(obj).parents('.dask-template');
	pars.hide();
}


/**
 * 文件上传插件方法
 * @param  {[type]} obj      [class 类名称]
 * @param  {[type]} hideimg  [隐藏域class]
 * @param  {[type]} isthumb  [文件类型]
 * @param  {[type]} fileSize [文件最大上传值]
 * @param  {[type]} height   [定义高度]
 * @param  {[type]} imgs     [上传类型 0单图,1多图]
 * @param  {[type]} auth     [1:上传七牛存储，0:本地存储] 
 * @return {[type]} 
 */
function up_img(obj,hideimg,isthumb,fileSize,height,imgs,auth){
	_url = "/"+admin_type+"/attachment/index?obj="+obj+"&hideimg="+hideimg+"&isthumb="+isthumb+"&filesize="+fileSize+"&imgs="+imgs+"&auth="+auth;
	layer.open({
        title:'文件上传',
        type: 2,
        closeBtn: false, //不显示关闭按钮
        shift: 4,
        shadeClose: true, //开启遮罩关闭
        area: ['525px', height],
        content: _url
    });
}

/**
 * 文件上传自定义方法
 * @param  {[type]} obj       [class 类名称]
 * @param  {[type]} isthumb   [文件类型]
 * @param  {[type]} fileSize  [文件最大上传值]
 * @param  {[type]} imgs     [上传类型 0单图,1多图]
 * @param {[type]} path     [Path]
 * @param {[type]} url     [Url]
 */
function up_style_file(obj,isthumb,fileSize,imgs,path,url){
    _url = "/"+admin_type+"/attachment/up_style?obj="+obj+"&isthumb="+isthumb+"&filesize="+fileSize+"&imgs="+imgs+"&path="+path+"&url="+url;
    layer.open({
        title:'文件上传',
        type: 2,
        closeBtn: false, //不显示关闭按钮
        shift: 4,
        shadeClose: true, //开启遮罩关闭
        area: ['525px', '290px'],
        content: _url
    });
}

/**
 * 移除多图节点
 * @param  {[type]} obj [ID节点]
 * @return {[type]} 
 */
function remove_this(obj){
	art.dialog.confirm("确定移除图片？", function(){
		$('#'+obj).remove();
	});
}

/**
 * 移除多图节点二
 * @param  {[type]} obj [ID节点]
 * @return {[type]}
 */
function remove_thumb(obj){
    var tath = $(obj),
		_pars = tath.parents('li');
    _pars.remove();
}

/**
 * 移除多图节点三
 * @param  {[type]} obj [ID节点]
 * @return {[type]}
 */
function remove_thumbs(obj){
    var tath = $(obj),
        _pars = tath.parents('.uplist');
    _pars.remove();
}


/**
 * 移除图片
 * @param  {[type]} $thumb [图片地址]
 * @param  {[type]} $id    [id]
 * @param  {[type]} $do_on 
 * @return {[type]} remove_id  [删除图片类] 
 */
function del_img(thumb,id,do_on,obj,remove_id) {
    layer.confirm('确定移除图片？', {
        btn: ['移除','取消'] //按钮
    }, function(){
        $.get("/"+admin_type+"/ajax/delpic",{id:id,thumb:thumb,con:CONTROLLER_NAME,do:do_on},function (data) {
            $(".success-data").addClass("on").html(data.msg);
            if (remove_id != undefined) {
                $("."+remove_id).attr("src",'/static/admin/Images/upload_thumb.png'); //移除图片
                $("."+remove_id).val(''); //移除隐藏值
                $(obj).hide();
                setTimeout(function () {
                    layer.closeAll();
                	$(".success-data").hide();
                },700);
            } else {
                setTimeout(function () {
                    window.location.reload();
                },700);
            }
        });
    });
}

function showpicbox(url){
	art.dialog({
		padding: 2,
		title: 'Image',
		content: '<img src="'+url+'" />',
		lock: true
	});
}

//获取栏目提示
function GetTips(obj){
	var catid = $(obj).val();
	if (catid) {
        $(".cate-catid").val(catid);
		// $.get("/"+admin_type+"/ajax/gettips",{catid:catid,con:CONTROLLER_NAME},function (data) {
		// 	if (data.status) {
		// 		$(".error").addClass("show").find("ul").html("栏目操作提醒："+data.msg);
		// 		setTimeout(function(){
		// 			$(".error").removeClass("show");
		// 		},50000);
		// 	} else {
		// 		$(".error").removeClass("show");
		// 	}
		// },'json');
	}
}

//ajax更新标题
function Gettitle(obj,code){
	var parents = $(obj).parents("tr"),
	    aid = parents.find(".inputcheckbox").val(),
        title = $(obj).val()+code;
    $.get("/"+admin_type+"/ajax/gettitle",{id:aid,title:title,con:CONTROLLER_NAME},function(data){
    	if (data.status) {
    		$(obj).next(".ajax-title-i").addClass(data.class);
    	} else {
    		$(obj).next(".ajax-title-i").addClass(data.class);
    	}
    	setTimeout(function () {
    		parents.find(".ajax-title-i").removeClass(data.class);
    	},2500);
    });
}

//ajax更新状态
function StatusAjax(obj){
	var _url = $(obj).attr("data-href");
	$.get(_url,{con:CONTROLLER_NAME,url:_url},function(data){
		if (data.status == 1) {
			switch(data.class){
				case 'error-c':
				    $(obj).addClass(data.class).attr("data-href", data.url);
				    break;
				case 'error-cc':
				    $(obj).removeClass("error-c").attr("data-href", data.url);
				    break;
			}
		}
    });
}

/**
 * 改变状态
 *
 * @param  this obj
 * @return $
 */
function ajax_status(obj)
{
	var id = $(obj).attr("data-id");
	var url = $(obj).attr("data-url");
	
	$.get(url, {id: id, url: url}, function(data){
	
		if(data.code == 0)
		{
			if(data.value == 1){
				$(obj).removeClass("error-c").attr("data-url", data.url);
			}else{
				$(obj).addClass("error-c").attr("data-url", data.url);
			}
		}else{
			return layer.alert(data.msg, {icon:2});
		}
		
	}, 'json');
}

//转移内容
function transfer(obj){
	var mun = $("input[type='checkbox']:checked").length,
	    data = $(".inputcheckbox:checked").map(function (i,e){return $(e).val()}).get().join(','),
	    _url = "/"+admin_type+"/ajax/transfer?id="+data+"&module="+CONTROLLER_NAME;
	if (!mun) {
		alert("没有选中任何数据,请勾选要转移的数据");
		return false;
	}
	layer.open({
        title:'数据转移',
        type: 2,
        closeBtn: false, //不显示关闭按钮
        shift: 4,
        shadeClose: true, //开启遮罩关闭
        area: ['650px', '500px'],
        content: _url
    });
}

//快速推荐
function posid(obj){
	var mun = $("input[type='checkbox']:checked").length,
	    data = $(".inputcheckbox:checked").map(function (i,e){return $(e).val()}).get().join(','),
	    _url = "/"+admin_type+"/ajax/posid?id="+data+"&module="+CONTROLLER_NAME;

    if (!mun) {
		alert("没有选中任何数据,请勾选要推荐的数据");
		return false;
	}
	layer.open({
        title:'快速推荐',
        type: 2,
        closeBtn: false, //不显示关闭按钮
        shift: 4,
        shadeClose: true, //开启遮罩关闭
        area: ['550px', '400px'],
        content: _url
    });  
}
