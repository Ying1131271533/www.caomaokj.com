var _page = 1;

$(function () {
    setTimeout(function() {
    	getList(_page);
    }, 200);

    //条件筛选
	$(".select-div dd").click(function () {
		$(this).addClass("selected").siblings().removeClass("selected");
		getList(_page);
	});

});

function getList(pageNum){
	var loading = layer.load();
	var type = $(".select-div").find("dd.selected").attr("data-id");
	var home_url = $('.home_url').val();
	var hd_url = $('.hd_url').val();
	$.ajax({
		url: '/index/user_api/collectList',
	    type: "POST",
	    data:{type: type, page: pageNum},
	    dataType:'json',
	    error: function() {
	    	layer.close(loading);
            layer.msg('网络错误！');
	    },
	    success: function(json) {
	    	layer.close(loading);
	    	if (json.code == 200){
	    		var html = '';
	    		var tbody = $('#table-list').find('tbody');
	    		if(json.data.list != ''){
	    			$.each(json.data.list, function(key, val) {
	    				if(json.data.type == 1){
	    					var url = home_url+'/home/article/id/' + val.id;
		    				var thtml = '文章';
		    			}
						
		    			if(json.data.type == 2){
		    				var url = hd_url+'/activity/detail/id/' + val.id;
		    				var thtml = '活动';
		    			}
						
                        if(json.data.type == 3){
                            var url = hd_url+'/college/detail/id/' + val.id;
                            var thtml = '课程';
                        }
						
		    			html += '<tr>';
		    			html += '<td>'+(key + 1)+'</td>';
		    			html += '<td><a target="_blank" href="' + url + '">' + val.title + '</a></td>';
		    			html += '<td>' + val.time + '</td>';
		    			html += '<td>' + thtml + '</td>';
		    			html += '<td><a class="layui-btn layui-btn-sm" href="javascript:delCollect('+json.data.type+', '+val.id+')">取消收藏</a></td>';
		    			html += '</tr>';
		    		});
	    		}else{
	    			html += '<tr>';
	    			html += '<td colspan="10" class="error">暂无数据</td>';
	    			html += '</tr>';
	    		}
	    		tbody.html(html);
	    		layerPage(json.data.paginator);
	    	}else{
	    		layer.msg(json.msg);
	    	}
	    }
	});
}

// 取消收藏
function delCollect(type, id){
	layer.confirm('您确定要取消收藏吗？', {
		  btn: ['确定', '取消'] // 可以无限个按钮
		}, function(index, layero){
		  //按钮【确定】的回调
			$.ajax({
				url: '/index/user_api/delCollect',
			    type: "POST",
			    data: {type: type, id: id},
			    dataType:'json',
			    error: function() {
		            layer.msg('网络错误！');
			    },
			    success: function(json) {
			    	layer.msg(json.msg);
			    	if (json.code == 200){
			    		getList(_page);
			    	}else{
			    	}
			    }
			});
		}, function(index){
		  //按钮【取消】的回调
	});
}


