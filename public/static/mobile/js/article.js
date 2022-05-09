$(function () {
	// getArticleComment();
	//评论
	$(".sendComment").click(function(){
		var comment = $("#comment").val();
		var id = $("#al_id").val();
                var pid = $('#pid').val();
		if(comment == ''){
			layer.msg('评论内容不能为空');
			return;
		}
		if(id == ''){
			layer.msg('inner error.');
			return;
		}
		var loadding = layer.load(0, {shade: false});
		$.ajax({
	        url: "/api/article-comment",
	        type: "POST",
	        data: {
	            comment: comment,
                id: id,
                pid:pid,
                _csrf:$('.getCsrfTokenJzc').val(),
            },
	        dataType: "json",
	        success: function (json) {
	        	layer.close(loadding);
	            if (json.state == 1) {
	            	$("#comment").val("");
	            	layer.msg(json.msg,{icon:1});
	            	getArticleComment();
	            } else {
	                layer.msg(json.msg,{icon:2});
	            }
	        },
	        error: function () {
	        	layer.close(loadding);
	            layer.msg('网络错误！',{icon:2});
	        }
	    });
	});

	$(".shareTips").on("click", function () {
        $(".shareTips").hide();
    });

	$(".nativeShare").on("click", function () {
		$(".shareTips").show();
	});


});

function getArticleComment(){
	var id = $("#al_id").val();
	if(id == ''){
		return;
	}
	$.ajax({
        url: "/api/get-article-comment",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
            if (json.state == 1) {
            	var html = '';
            	//刷新评论内容
            	html += '<h4 class="normal-title">评论 <span> '+json.count+' </span></h4>';
            	html += '<ul>';
            	$.each(json.data, function (key, val) {
            		html += '<li>';
                        html += '<div class="clearfix head_story">';
                        html += '<div class="fl">';
                        html += '<img src="'+val.user_head+'" />';
                        html += '<div class="name">'+val.user_name+'</div>';
                        html += '</div>';
                        html += '<div class="fr">发布于'+val.time+'</div>';
                        html += '</div>';
                        if(val.parent.user_id){
                                html += '<div class="Triangle">';
                                html += '<div class="Triangle_bg"></div>';
                                html += '<div class="comment_con">';
                                //html += '<div class="name">'+val.parent.user_name+'：</div>';
                                html += '<div class="name">'+val.parent.user_name+'：'+val.parent.acom_comment+'</div>';
                                html += '</div>';
                                html += '</div>';
                        }
                        html += '<div class="con_story">';
                        html += '<div class="story_c">';
                        html += '<div class="comment_text">'+val.acom_comment+'</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="footer_story clearfix">';
                        html += '<div class="fr">';
                        html += '<div class="fl comment" id="acom_'+val.acom_id+'" data-id="'+val.acom_id+'" user_name="'+val.user_name+'" onclick="goComment('+val.acom_id+')">评论</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</li>';
            	});
        		html += '</ul>';
            	$(".comment-div").html(html);
            	$(".foot_tips").text(json.count);
            } else {
            }
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });
}

//去评论
function goComment(pid){
        var evt=window.event;
        if($("#userId").val() > 0){
            $('#commentInput').prop('disabled',true);
            $('#commentDetail').addClass('is-open').slideToggle();
            $('#commentDetail textarea').focus();
            $('#pid').val(pid);
            var str = '说点什么吧...'
            if(pid>0){
                var user_name = $('#acom_'+pid).attr('user_name');
                str = '回复 '+user_name+'：';
            }
            
            $('#comment').attr('placeholder',str);
            evt.stopPropagation();
        }else{
			layer.msg("正在跳转登录");
			window.location.href="/login";
		}
}


function articlelike(id) {
    var loadding = layer.load(0, {shade: false});
    $.ajax({
        url: "/api/article-like",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.state == 1) {
            	layer.msg(json.msg,{icon:1});
            	$(".al_like_num").text(json.data);
            }else if(json.state == 2){
            	window.location.href = '/login';
            } else {
                layer.msg(json.msg,{icon:2});
            }
        },
        error: function () {
        	layer.close(loadding);
            layer.msg('网络错误！',{icon:2});
        }
    });
}

function collect() {
	var id = $("#al_id").val();
	if(id == ''){
		return;
	}
	var loadding = layer.load(0, {shade: false});
    $.ajax({
        url: "/api/article-collect",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.state == 1) {
            	layer.msg(json.msg,{icon:1});
            	$(".al_collect_num").text(json.data);
            } else {
                layer.msg(json.msg,{icon:2});
            }
        },
        error: function () {
        	layer.close(loadding);
            layer.msg('网络错误！',{icon:2});
        }
    });
}





