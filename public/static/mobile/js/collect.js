var _page = 1;

$(function () {
    setTimeout(function() {
    	getMessage(_page);
    }, 200);


});

function searchFilterSubmit(obj){
	$(obj).parent().children("li").removeClass('current');
    $(obj).addClass('current');
    getMessage(_page);
}



function getMessage(pageNum){
	var loadding = layer.load(0, {shade: false});
	var type = $(".nav-title").find("li.current").attr("type");
	$.ajax({
		url: '/index/user_api/collectList',
	    type: "POST",
	    data:{page: pageNum, type:type},
	    async: false,
	    dataType:'json',
	    error: function() {
	    	layer.close(loadding);
            layer.msg('网络错误！');
	    },
	    success: function(json) {
	    	layer.close(loadding);
	    	if (json.code == 200){
	    		var html = '';
	    		if(json.data.list != ''){
	    			$.each(json.data.list, function(key, val) {
	    				html += '<li>';
	            		html += '<div class="li_title">';
	            		html += '<h4>收藏</h4>';
	            		html += '<span class="times">'+ val.time +'</span>';
	            		html += '</div>';
	            		html += '<div class="li_content">';
	            		html += '<p>'+ val.title +'</p>';
	            		html += '</div>';
	            		html += '<div>';
	                	html += '<ul class="nav nav-tabs fl">';
	                	html += '<li>';
	                	html += '<a href="javascript:void(0)" onclick="delCollect('+json.data.type+', ' + val.id + ')">取消收藏</a>';
	                	html += '</li>';
	                	html += '<li>';
	                	if(type == 1){
	                		html += '<a href="/index/home/article/id/'+ val.id +'">查看</a>';
	                	}else{
	                		html += '<a href="/index/activity/detail/id/'+ val.id +'">查看</a>';
	                	}
	                	html += '</li>';
	                	html += '</ul>';
	                	html += '</div>';
	            		html += '</li>';
		    		});
	    		}else{
	    			html += '<li class="no_msg">您还没有收藏</li>';
	    		}
	    		$(".data_ul").html(html);
				layerPage(json.data.paginator);
	    	}else{
	    		layer.msg(json.msg);
	    	}
	    }
	});
}

function delCollect(type, id){
	layer.confirm('确认要删取消收藏吗？', {
	  btn: ['确认','取消'] //按钮
	}, function(){
		var loadding = layer.load(0, {shade: false});
		$.ajax({
			url: '/index/user_api/delCollect',
			type: "POST",
			data: {type: type, id: id},
			async: false,
			dataType:'json',
			error: function() {
				layer.close(loadding);
				layer.msg('网络错误！');
			},
			success: function(json) {
				layer.close(loadding);
				layer.msg(json.msg);
				if (json.code == 200){
					_page = 1;
					getMessage(_page);
				}
			}
		});
	});
}


function layerPage(paginator){
    layui.use('laypage', function() {
		var laypage = layui.laypage;
		laypage.render({
			elem : 'laypage',
			theme : '#ff8d00',
			curr : paginator.page || 1,
			count : paginator.count,
			limit : paginator.pageSize || 10,
			jump : function(obj, first) {
				if (!first) {
					getMessage(obj.curr);
				}
			}
		});
	});
}
