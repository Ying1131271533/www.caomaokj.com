var _page = 1;
var pageSize = 10;

$(function () {
    setTimeout(function() {
    	//getList(_page);
    }, 200);
});

function getList(pageNum){
	var loadding = layer.load();
	$.ajax({
		url: '/index/user_api/message',
	    type: "POST",
	    data:{page: pageNum,pageSize:pageSize},
	    dataType:'json',
	    error: function() {
	    	layer.close(loadding);
            layer.msg('网络错误！');
	    },
	    success: function(json) {
	    	layer.close(loadding);
	    	if (json.state){
	    		var html = '';
	    		var tbody = $('.message-box');
	    		if(json.data.data != ''){
	    			$.each(json.data.data, function(key, val) {
	    				html += '<div class="media-list">';
    					html += '<div class="media-left">';
						html += '<a title="进入个人主页" href="/user/'+val.msg_send_user_id+'">';
						html += '<img class="media-object" src="/default/avatar?userId='+val.msg_send_user_id+'">';
						html += '<div>'+val.send_name+'</div>';
						html += '</a>';
						html += '</div>';
						html += '<div class="media-body">';
						html += '<p><a title="进入对话" href="/message/talk/'+val.msg_send_user_id+'">'+val.msg_content+'</a></p>';
						html += '<div class="bot_tips clearfix">';
						html += '<span class="post-label"> '+val.msg_create_time+'发布 </span>';
						html += '<div class="pull-right">';
						html += '<a title="进入对话" href="/message/talk/'+val.msg_send_user_id+'">';
						if(val.not_read > 0){
							html += '<span class="bubble-dot-red">'+val.not_read+'</span>';
						}
						if(val.other_not_read){
							html += '<span class="bubble-dot-red" style="border-radius: 5px;background-color: #777;">对方未读</span>';
						}
						html += '<span>共 <span class="numbers">'+val.total+'</span> 句对话';
						html += '</span></a> <span>|</span> <a class="del" href="javascript:;" onclick="delMessage('+val.msg_send_user_id+')">删除</a>';
						html += '</div>';
						html += '</div>';
						html += '</div>';
						html += '</div>';
		    		});
	    		}else{
	    			html += '<div class="media-list">';
	    			html += '暂无数据';
	    			html += '</div>';
	    		}
	    		tbody.html(html);
	    		layerPage(json.data.paginator);
	    	}else{
	    		layer.msg(json.msg);
	    	}
	    }
	});
}

function replyMessage(id){
	layer.prompt({
		formType : 2,
		title : '请输入要回复的内容',
	}, function(value, index, elem) {
		var loadding = layer.load(0, {shade: false});
			$.ajax({
				url: '/message/reply',
			    type: "POST",
			    data:{id: id, val: value},
			    dataType:'json',
			    error: function() {
			    	layer.close(loadding);
		            layer.msg('网络错误！', {icon: 2});
			    },
			    success: function(json) {
			    	layer.close(loadding);
			    	if(json.state){
			    		layer.msg(json.msg, {icon: 1});
			    		layer.close(index);
			    	}else{
			    		layer.msg(json.msg, {icon: 2});
			    	}
			    }
			});
	});
}

function delMessage(id){
	layer.confirm('确认要删除与对方的消息记录吗？', {
		  btn: ['删除','取消'] //按钮
		}, function(){
			var loadding = layer.load();
			$.ajax({
				url: '/message/del',
			    type: "POST",
			    data:{id: id},
			    dataType:'json',
			    error: function() {
			    	layer.close(loadding);
		            layer.msg('网络错误！', {icon: 2});
			    },
			    success: function(json) {
			    	layer.close(loadding);
			    	if(json.state){
			    		layer.msg(json.msg, {icon: 1});
			    		getList(_page);
			    	}else{
			    		layer.msg(json.msg, {icon: 2});
			    	}
			    }
			});
		});
}







