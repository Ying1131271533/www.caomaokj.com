
// 门票id
var tickets_id = 0;

$(function() {
	layer.photos({
		photos: '.content',
		anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
	});

	getArticleComment();

	$(document).on("click", ".comment", function() {
		var id = $(this).attr("data-id");
		var user_id = $('#user_id').val();
        if(!user_id) layer.msg('请先登录');
		if (user_id > 0) {
			$(".comment_input_" + id).toggle();
		}
	});

	//选择门票
	$(".fee_select_div").click(function() {
		//$(this).parent().children(".t_body").removeClass('choose');
		if($(this).hasClass('disabled')){
            layer.msg('温馨提示：该类门票已售罄，请选择其他门票哦！');
			return false;
		}
		tickets_id = $(this).attr('af_id');
        $(".fee_select_div").removeClass('fee_seleced_active');
        $('.price_fee_jzc').text($(this).attr('af_price'));
		$(this).addClass('fee_seleced_active').siblings().removeClass('fee_seleced_active');
	});

});



// 评论/回复
function comment(id, acid) {
    var pid = 0;
    var comment = '';
    if (acid) {
        pid = acid;
        comment = $("#comment_" + acid).val();
    } else {
        comment = $("#comment").val();
    }
    if (comment == '') {
        layer.msg('评论内容不能为空');
        return;
    }
    var loading = layer.load();
    $.ajax({
        url: "/index/college_api/collegeComment",
        type: "POST",
        data: {
            comment: comment,
            id: id,
            pid: pid
        },
        dataType: "json",
        success: function (json) {
            layer.close(loading);
            if (json.code == 200) {
                layer.msg(json.msg, { icon: 1 });
                window.location.reload();
            } else if (json.code == 300) {
                window.location.href = '/index/login/index';
                layer.msg(json.msg, { icon: 0 });
            } else {
                layer.msg(json.msg, { icon: 2 });
            }
        },
        error: function () {
            layer.close(loading);
            layer.msg('网络错误！', {
                icon: 2
            });
        }
    });
}

$('.comment_stories_list').hide();

function getArticleComment() {
    var id = $("#al_id").val();
    if (id == '') {
        layer.msg('inner error.');
        return;
    }
    /*//<div class="mod-footer">\
    <div class="meta">\
    <span class="pull-right text-color-999">'+ val.acom_add_time +'</span>\
    <a>举报</a>\
    <a>回复</a>\
</div>\*/
    $.ajax({
        url: "/index/college_api/getCollegeComment",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (json) {
            if (json.code == 200) {
                $('.comment_stories_list').show();
                var html = '';
                //刷新评论内容
                $.each(json.data.comments, function (key, val) {
                    html += '<li class="clearfix">';
                    html += '<div class="Avatar fl">';
                    html += '<img src="' + val.user_head + '">';
                    html += '</div>';
                    html += '<div class="fr stories_con">';
                    if (val.parent.user_id) {
                        html += '<div class="blockquote_wrap">';
                        html += '<a href="javascript:;">' + val.parent.user_name + '</a> : ' + val.parent.acom_comment;
                        // html += '<a target="_blank" href="/user/' + val.parent.user_id + '">' + val.parent.user_name + '</a> : ' + val.parent.acom_comment;
                        html += '</div>';
                    }
                    html += '<div class="comment_subt">' + val.acom_comment + '</div>';
                    html += '<div class="clearfix tools">';
                    html += '<div class="fl">';
                    html += '<div class="name fl mr30">';
                    html += '<a href="javascript:;">' + val.user_name + '</a>';
                    // html += '<a href="/user/' + val.user_id + '">' + val.user_name + '</a>';
                    html += '</div>';
                    html += '<div class="time fl">';
                    html += '发布于' + val.time;
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="fr tools2">';
                    html += '<div class="comment" data-id="' + val.acom_id + '">回复</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="comment_input comment_input_' + val.acom_id + '" style="display: none;">';
                    html += '<input type="text" id="comment_' + val.acom_id + '" placeholder="回复　' + val.user_name + '：" class="c_input">';
                    html += '<button class="Reply_btn" onclick="comment(' + val.al_id + ',' + val.acom_id + ')">回复</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</li>';

                });

            } else {
                $('.comment_stories_list').hide();
                //html = '<li class="clearfix">暂无评论，来说点什么吧</li>';
            }
            $(".stories_list").html(html);
            $(".al_comment_num").text(json.data.count);
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });
}


// 报名
function join(){

	if(!tickets_id){
		layer.msg('请选择门票');
		return false;
	}

	var id = $('input[name="id"]').val();
	if(!id){
		layer.msg('参数有误，请刷新页面');
		return false;
	}
	window.location.href = '/index/college/join/college_id/'+id+'/tickets_id/'+tickets_id;
}

function joinActivity(id) {
	var af_id = $(".fee_seleced_active").attr("af_id");
	if (af_id == '' || af_id == undefined) {
		layer.msg("请先选择门票类型", {icon: 0});
		return false;
	}
	window.location.href = '/home/join?id=' + id + '&af_id=' + af_id;

}

function goActivity(id) {
	var loadding = layer.load();
	var af_title = $(".choose", ".activity_fee").attr("af_title");
	var af_price = $(".choose", ".activity_fee").attr("af_price");
	var af_id = $(".choose", ".activity_fee").attr("af_id");
	var tips = '门票类型：' + af_title + '（￥' + af_price + ')';
	var html = '';
	$.post("/home/get-question", {
		activityId: id
	},
	function(json) {
		layer.close(loadding);
		if (json.code) {
			var json = json;
			html += '<div class="layer_div" style="padding: 20px;">';
			html += '<form class="joinForm">';
			html += '<input type="hidden" name="af_id" value="' + af_id + '">';
			html += '<input type="hidden" name="activityId" value="' + id + '">';
			html += '<span class="price_tips">' + tips + '</span><br>';
			$.each(json.data,
			function(k, v) {
				html += v.question_name;
				if (v.is_must == 1) {
					html += '<span class="red">*</span>';
					var tips = '必填';
				} else {
					var tips = '选填';
				}
				if (v.question_type == 1) {
					html += '<input class="form-control" placeholder="' + tips + '" type="text" name="question_' + v.question_id + '">'
				} else if (v.question_type == 2) {
					html += '<br>';
					$.each(v.options,
					function(kk, vv) {
						html += '<label><input type="radio" name="question_' + v.question_id + '[]" value="' + vv.option_name + '">' + vv.option_name + '</label>';
					});
					html += '<br>';
				} else if (v.question_type == 3) {
					html += '<br>';
					$.each(v.options,
					function(kk, vv) {
						html += '<label><input type="checkbox" name="question_' + v.question_id + '[]" value="' + vv.option_name + '">' + vv.option_name + '</label>';
					});
					html += '<br>';
				}
			});
			html += '</form>';
			html += '</div>';
			layerDiv(html);
		}
	});
}

function layerDiv(html) {
	var index = layer.open({
		type: 1,
		title: "活动报名",
		area: '400px;',
		shade: 0.8,
		id: 'LAY_layuipro',
		resize: false,
		btn: ['报名', '取消'],
		btnAlign: 'c',
		content: html,
		yes: function(index, layero) {
			var loadding = layer.load();
			$.ajax({
				url: "/home/activity-join",
				type: "POST",
				data: $(".joinForm").serialize(),
				dataType: "json",
				success: function(json) {
					layer.close(loadding);
					layer.msg(json.msg);
					if (json.code) {
						layer.close(index);
						window.location.href = json.url;
					}
				},
				error: function() {
					layer.close(loadding);
					layer.msg('网络错误！');
				}
			});
		},
		cancel: function() {
			// 右上角关闭回调
		}
	});
}

//去评论
function goComment() {
	$("#comment").focus();
}

// 收藏
function collect(id) {
	var loadding = layer.load();
	$.ajax({
		url: "/index/college_api/collegeCollect",
		type: "POST",
		data: { id: id },
		dataType: "json",
		success: function(json) {
			layer.close(loadding);
			if (json.code == 200) {
				layer.msg(json.msg);
				$("#collect").attr("class", "glyphicon fa fa-heart red").next("span").text("已收藏");
				$(".al_collect_num").text(json.data);
			} else {
				layer.msg(json.msg);
			}
		},
		error: function() {
			layer.close(loadding);
			layer.msg('网络错误！');
		}
	});
}

//点赞
function like(id) {
	$.ajax({
		url: "/index/college_api/collegeLike",
		type: "POST",
		data: { id: id },
		dataType: "json",
		success: function(json) {
			if (json.code == 200) {
				layer.msg(json.msg);
				$(".al_like_num").text(json.data);
			} else {
				layer.msg(json.msg);
			}
		},
		error: function() {
			layer.msg('网络错误！');
		}
	});
}















