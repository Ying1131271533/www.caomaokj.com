$(function() {
	// getArticleComment();
	//评论
	$(".sub-btn").click(function() {
		var comment = $("#comment").val();
		var url = window.location.href;
		var id = url.substring(url.lastIndexOf("/") + 1);
		var pid = $('#pid').val();
		if (comment == '') {
			layer.msg('评论内容不能为空', {icon: 0, time: 1000});
			return;
		}
		if (id == '') {
			layer.msg('inner error.');
			return;
		}
		var loadding = layer.load();
		$.ajax({
			url: "/api/activity-comment",
			type: "POST",
			data: {
				comment: comment,
				id: id,
				pid: pid
			},
			dataType: "json",
			success: function(json) {
				layer.close(loadding);
				if (json.state == 1) {
					$("#comment").val("");
					layer.msg(json.msg, {icon: 1});
					getArticleComment();
				} else {
					layer.msg(json.msg, {icon: 2});
				}
			},
			error: function() {
				layer.close(loadding);
				layer.msg('网络错误！', {icon: 2});
			}
		});
	});

	$('body').click(function() {
		$('.btn-close').click();
	});

    $('.disabled').click(function(){
        layer.msg('温馨提示：该门票已售罄！');
        return false;
    })

	//选择门票
	$('.sale').click(function (e) {
		$('.sale').removeClass('selected')
		$(this).addClass('selected')
		var price = $(this).attr('data-price')
		$('.count_price').text(' ￥ ' + price)
		e.stopPropagation()
	  })
	
	  $('.btn-join,.ticket-price').click(function (e) {
		$('.join-operation ').hide()
		$('.choose-price').show()
		e.stopPropagation()
	  })
	
	  $('.btn-close').click(function (e) {
		$('.choose-price').hide()
		$('.join-operation').show()
		e.stopPropagation()
	  })
	
	  $('.shareTips').on('click', function () {
		$('.shareTips').hide()
	  })
	
	  $('.nativeShare').on('click', function () {
		$('.shareTips').show()
	  })

	//主图高度自适应
	var imgWidth = $(window).width() - 20;
	$(".banner-img").height(imgWidth / 1.875);

});

function getArticleComment() {
	var id = $("#activityId").val();
	if (id == '') {
		layer.msg('inner error.');
		return;
	}
	$.ajax({
		url: "/api/get-activity-comment",
		type: "POST",
		data: {
			id: id
		},
		dataType: "json",
		success: function(json) {
			if (json.state == 1) {
				var html = '';
				//刷新评论内容
				html += '<h4 class="normal-title">评论 <span> ' + json.count + ' </span></h4>';
				html += '<ul>';
				$.each(json.data,
				function(key, val) {
					html += '<li>';
					html += '<div class="clearfix head_story">';
					html += '<div class="fl">';
					html += '<img src="/default/avatar?userId=' + val.user_id + '" />';
					html += '<div class="name">' + val.user_name + '</div>';
					html += '</div>';
					html += '<div class="fr">发布于' + val.add_time + '</div>';
					html += '</div>';
					if (val.parent.user_id) {
						html += '<div class="Triangle">';
						html += '<div class="Triangle_bg"></div>';
						html += '<div class="comment_con">';
						html += '<div class="name">' + val.parent.user_name + '：' + val.parent.acom_comment + '</div>';
						html += '</div>';
						html += '</div>';
					}
					html += '<div class="con_story">';
					html += '<div class="story_c">';
					html += '<div class="comment_text">' + val.acom_comment + '</div>';
					html += '</div>';
					html += '</div>';
					html += '<div class="footer_story clearfix">';
					html += '<div class="fr">';
					html += '<div class="fl" id="acom_' + val.acom_id + '" data-id="' + val.acom_id + '" user_name="' + val.user_name + '" onclick="goComment(' + val.acom_id + ')">回复</div>';
					html += '</div>';
					html += '</div>';
					html += '</li>';
				});
				html += '</ul>';
				$(".comment-div").html(html);
				$(".foot_tips").text(json.count);
			}
		},
		error: function() {}
	});
}

function joinActivity() {
	var id = $("#college_id").val();
	var af_id = $(".selected", ".activity-ticket").attr("af_id");
	if (af_id == '' || af_id == undefined) {
		layer.msg("请先选择门票类型", {icon: 0, time: 1000});
		return false;
	}
	var loadding = layer.load();
	window.location.href = '/index/college/join/college_id/' + id + '/tickets_id/' + af_id;
}

//去评论
function goComment(pid) {
	var evt = window.event;
	if ($("#userId").val() > 0) {
		$('#pid').val(pid);
		$("#comment").focus();
		var str = '说点什么吧...'
		if (pid > 0) {
			var user_name = $('#acom_' + pid).attr('user_name');
			str = '回复 ' + user_name + '：';
		}
		$('#comment').attr('placeholder', str);
	} else {
		layer.msg("正在跳转登录");
		window.location.href = "/login";
	}

}

//收藏
function collect(id) {
	var loadding = layer.load();
	$.ajax({
		url: "/api/activity-collect",
		type: "POST",
		data: {
			id: id
		},
		dataType: "json",
		success: function(json) {
			layer.close(loadding);
			if (json.state == 1) {
				layer.msg(json.msg, {icon: 1});
				$("#collect").attr("class", "glyphicon fa fa-heart red").next("span").text("已收藏");
			} else {
				layer.msg(json.msg, {icon: 2});
			}
		},
		error: function() {
			layer.close(loadding);
			layer.msg('网络错误！', {icon: 2});
		}
	});
}

//点赞
function like(id) {
	$.ajax({
		url: "/api/activity-like",
		type: "POST",
		data: {
			id: id
		},
		dataType: "json",
		success: function(json) {
			if (json.state == 1) {
				layer.msg(json.msg, {icon: 1});
				$(".al_like_num").text(json.data);
			} else {
				layer.msg(json.msg, {icon: 2});
			}
		},
		error: function() {
			layer.msg('网络错误！', {icon: 2});
		}
	});
}
