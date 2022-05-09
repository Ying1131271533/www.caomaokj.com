$(function() {
	$('.check_user_auth').click(function(){
        //询问框
		var url = $(this).attr('data-url');
        layer.confirm('您点击下方按钮即可获得发布活动权限！', {
        	title:'温馨提示',
			skin:'company_style',
            shadeClose: true,
            btn: ['注册服务商','注册普通企业'] //按钮
        }, function(){
            window.location.href= url+"/site/logistics-signup";
        }, function(){
            window.location.href= url+"/site/service-signup";
        });
	})

	$('.check_user_auth_not_login').click(function(){
        layer.msg('温馨提示：请先登录以后再发布，谢谢配合。2秒后跳转到登录页面！', {
            icon: 0,
            time: 2000 //2秒关闭（如果不配置，默认是3秒）
        }, function(){
            window.location.href= '/site/login';
        });
    })


	//获取到当前的页面是否有搜索项
	var get_parames = $('.get_parames').val();
	if(get_parames == 1){
        $("html, body").animate({
            scrollTop: $('.nav_div_huodong').offset().top -170 + "px"
        }, {
            duration: 500,
            easing: "swing"
        });
	}

	// 举办方点击【更多】或【收起】
	$('#h .button').on(
			'click',
			function() {
				$('#h .list-area .more-2').height(
						$("#h .list-area .more-2").height() < 90 ? 90 : $(
								"#h .list-area").height())
				if ($(this).hasClass('more')) {
					$(this).hide();
					$('#h .less').show();
					$('#less-user').removeClass('show');
					$('#more-user').addClass('show');
				}
				if ($(this).hasClass('less')) {
					$(this).hide();
					$('#h .more').show();
					$('#more-user').removeClass('show');
					$('#less-user').addClass('show');
				}
			});
	
	/*$('.i-checks').iCheck({
		checkboxClass : 'icheckbox_square-green',
		radioClass : 'iradio_square-green',
	});*/

	// 分页
	if (document.getElementById("laypage")) {
		laypage({
			cont : 'laypage',
			pages : Math.ceil($("#dataCount").val() / 12),
			curr : $("#thisPage").val(),
			skin : '#fc9d27',
			jump : function(obj, first) {
				if (!first) {
					submitSearch(obj.curr);
				}
			}
		});
	}

	$(".btn-add").click(function() {
		window.location.href = '/task';
	});

	// 条件筛选
	$(".select-list dd").click(function() {
		$(this).addClass("selected").siblings().removeClass("selected");
		submitSearch();
	});
	// 条件筛选
	$(".select-list .search").click(
			function() {
				$(this).parents(".select-list").find(".selected").removeClass(
						"selected");
				$(this).addClass("selected");
				submitSearch();
			});
	// 更多筛选
	$(".select-list .more-search").click(function() {
		$(this).parents(".more-2").find(".selected").removeClass("selected");
		$(this).addClass("selected");
		submitSearch();
	});
	// 排序
	$(".header-sort dd").click(function() {
		$(this).addClass("selected").siblings().removeClass("selected");
		submitSearch();
	});

	$(".btn-search").click(function() {
		submitSearch();
	});

	$("#keyword").keydown(function(e) {
		if (e.keyCode == 13) {
			submitSearch();
		}
	});

	$('.free').on('ifChanged', function(event) {
		submitSearch();
	});

	$('.ing').on('ifChanged', function(event) {
		submitSearch();
	});
});

function submitSearch(page) {
	//layer.load();
	var params = '';
	// 关键词
	var keyword = $("#keyword").val();
	if (keyword != '') {
		params += 'keyword=' + keyword + '&';
	}
	var h_flag = 0;
	// 在更多主办方里筛选
	if ($('#h .more-info').is(":visible")) {
		$("#h .more-2").each(function() {
			var name = $(this).attr("id");
			var val = $("#" + name).find(".selected").attr("data-id");
			// console.dir(val);
			if (val != '0') {
				params += name + '=' + val + '&';
			} else {
				h_flag = 1;
			}
		});
	}
	// 筛选条件
	$(".select li").each(function() {
		var name = $(this).attr("id");
		var val = $("#" + name).find(".selected").attr("data-id");
		if (val != '0') {
			if (name == 'h' && h_flag == 1) {
				return true;
			}
			params += name + '=' + val + '&';
		}
	});

	// console.dir(params);
	// 排序
	if ($(".header-sort").find(".selected").length > 0) {
		var type = $(".header-sort").find(".selected").attr("data-type");
		if (type > 0) {
			params += 'by=' + type + '&';
		}
	}
	// 免费
	if ($(".free").is(':checked')) {
		params += 'free=1&';
	}
	// 未结束
	if ($(".ing").is(':checked')) {
		params += 'ing=1&';
	}
	// 分页
	if (page >= 1) {
		params += 'page=' + page + '&';
	}
	// 去除最后一个&
	if (params != '') {
		params = params.substr(0, params.length - 1);
	}
	//console.log(params);return;

	$.ajax({
		url:'/index/activity_api/getActivityList',
		data:params,
		dataType:'json',
		type:'post',
		success:function(json){
			console.log(json);
			if(json.code == 200){
				html = '';
                $.each(json.data.data, function (key, val) {
                    html += '<div class="img-box">';
                    html += '<div class="item-img">';
                    if(val.activity_id == 656){
                        html += '<a title="'+val.activity_name+'" href="/site/two-meeting">';
                    }else{
                        html += '<a title="'+val.activity_name+'" href="/index/activity/detail/id/'+val.activity_id+'">';

                    }

                    if(val.course_id > 0){
                        html += '<a target="_blank" title="'+val.activity_name+'" href="index/dolphin/course/id/'+val.course_id+'">';

                    }else{
                        html += '<a title="'+val.activity_name+'" href="/index/activity/detail/id/'+val.activity_id+'">';

                    }

                    html += '<img src="'+val.activity_thumb+'?x-oss-process=image/resize,w_280,h_152,limit_0" alt="'+val.activity_name+'">';
                    html += '</a>';
                    if(val.time == '已结束'){
                        html += '<span class="activity-sign end-activity-sign">'+val.time+'</span>';

                    }else{
                        html += '<span class="activity-sign">'+val.time+'</span>';

                    }
                    html += '</div>';
                    html += '<div class="item-text">';
                    html += '<h4>';

                    if(val.activity_id == 656){
                        html += '<a title="'+val.activity_name+'" href="/site/two-meeting">';

                    }else{
                        html += '<a title="'+val.activity_name+'" href="/index/activity/detail/id/'+val.activity_id+'">';

                    }

                    if(val.course_id > 0){
                        html += '<a target="_blank"  title="'+val.activity_name+'" href="index/dolphin/course/id/'+val.course_id+'">';

                    }else{
                        html += '<a title="'+val.activity_name+'" href="/index/activity/detail/id/'+val.activity_id+'">';

                    }

                    html += val.activity_name+'</a>';
                    html += '</h4>';

                    // if(val.activity_id == 656){
                    //     html += ' <p class="font14">';
                    //     html += '亲爱的卖家朋友们，由于疫情的影响，主办方已调整本次展会时间，展会将延迟举办，具体时间待官方通知，给大家带来不便还望谅解，感谢大家的支持';
                    //     html += '</p>';
                    // }else{
                        html += '<p class="font14">';
                        html += '<i class="fa fa-clock-o"></i> '+val.time+'</p>';
                        html += '<p class="font14">';
                        html += '<i class="fa fa-map-marker"></i> <span class="activity-local">'+val.activity_local+'</span>';
                        html += '<span class="pull-right orange-font"><!--<span class="activity-original-price"><?= $a[\'original_price_name\'] ?></span>-->'+val.priceName+'</span>';
                        html += '</p>';
                    //}
                    html += '</div>';
                    html += '</div>';
				})
                $('.activity_list_div').html(html);
                $('#dataCount').val(json.data.count);
                $("#thisPage").val(page);

                var totalPages = Math.ceil(json.data.count/12);

                if(totalPages > 1){
                    $('#laypage').show();
                    laypage({
                        cont : 'laypage',
                        pages : Math.ceil($("#dataCount").val() / 12),
                        curr : $("#thisPage").val(),
                        skin : '#fc9d27',
                        jump : function(obj, first) {
                            if (!first) {
                                submitSearch(obj.curr);
                            }
                        }
                    });
                }else{
                	$('#laypage').hide();
                }



			}else{
				layer.msg(json.msg);
			}


		}


	})

	//window.location.href = '/' + params;
}
