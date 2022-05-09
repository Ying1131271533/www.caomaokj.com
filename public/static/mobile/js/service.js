var pageSize = 5;
$(function() {
	if($("#serviceFaqCount").val() > 0){
		getFaq(1);
	}

	 $('.unfold').click(function(){
         if( $('.unfold i').hasClass('fa-angle-down')){
             $('.banner-about').addClass('banner-auto');
             $('.unfold span').html('收起');
             $('.unfold i').removeClass('fa-angle-down').addClass('fa-angle-up');
         }else{
             $('.banner-about').removeClass('banner-auto');
             $('.unfold span').html('展开');
             $('.unfold i').removeClass('fa-angle-up').addClass('fa-angle-down');
         }
     });

	 $('.unfold-post').click(function(){
         if( $('.unfold-post i').hasClass('fa-angle-down')){
             $('.best .banner-about').addClass('banner-auto');
             $('.unfold-post span').html('收起');
             $('.unfold-post i').removeClass('fa-angle-down').addClass('fa-angle-up');
         }else{
             $('.best .banner-about').removeClass('banner-auto');
             $('.unfold-post span').html('展开');
             $('.unfold-post i').removeClass('fa-angle-up').addClass('fa-angle-down');
         }
     });

	 $('.unfold-platform').click(function(){
			var obj = $(this);
	        if(obj.find('i').hasClass('fa-angle-down')){
	        	obj.siblings('.banner-about-p').addClass("banner-auto-p");
	        	obj.find('span').html('收起');
	        	obj.find('i').removeClass('fa-angle-down').addClass('fa-angle-up');
	        }else{
	        	obj.siblings('.banner-about-p').removeClass('banner-auto-p');
	        	obj.find('span').html('展开');
	        	obj.find('i').removeClass('fa-angle-up').addClass('fa-angle-down');
	        }
	    });

	//条件筛选
	$(".tab-ul li").click(function () {
		$(this).siblings().removeClass("active");
		$(this).addClass("active");
		var div = $(this).attr("data-div");
		$("#"+div).siblings().removeClass("active");
		$("#"+div).addClass("active");
		$("table").resize();
	});
});

function getFaq(page){
	laypage({
        cont: 'laypage',
        pages: Math.ceil($("#serviceFaqCount").val()/pageSize),
        curr:  page,
        skin: '#ff8d00',
        jump: function (obj,first) {
             if(!first){
            	 searchFaq(obj.curr);
             }
        }
    });
}

// 私信
function sendMsg(id) {
	var isLogin = $("#isLogin").val();
	if (isLogin != '1') {
		layer.msg("请先登录，正在跳转页面...");
		window.location.href = 'http://www.kuajingyan.com/login';
		return false;
	}
	layer.prompt({
		formType : 2,
		title : '请输入私信内容',
		area : [ '280px', '150px' ]
	}, function(value, index, elem) {
		var loading = layer.load();
		if(value != ''){
			$.ajax({
				url : "/home/send-msg",
				type : "POST",
				data : {
					msg : value,
					id : id
				},
				dataType : "json",
				success : function(json) {
					layer.close(loading);
					layer.msg(json.msg);
					if(json.state){
						layer.close(index);
					}
				},
				error : function() {
					layer.msg('网络错误！');
				}
			});
		}
	});
}

// function sendAsk(id){
// 	var isLogin = $("#isLogin").val();
// 	var loading = layer.load();
// 	var html = '';
// 	$.post("/api/get-warehouse-service",{serviceId:id},function(json){
// 		layer.close(loading);
// 		if(json.state){
// 			var json = json;
// 			html += '<div class="layer_div">';
// 			html += '<form class="askForm">'
// 			html += '<input type="hidden" name="serviceId" value="'+ id +'">';
// 			html += '<input class="form-control" placeholder="称呼" type="text" name="name" id="name"><br>';
// 			html += '<input class="form-control" placeholder="手机" type="text" name="phone" id="phone"><br>';
// 			if(isLogin != '1') {
// 				html += '<input type="text" class="form-control col4 code_jzc" name="code" id="code" placeholder="请输入手机验证码">';
// 				html += '<button type="button" class="form-control col5 code_jzc" id="get-code" onclick="getCode()">获取验证码</button>';
// 				html += '<br>';
// 			}
// 			html += '<input class="form-control" placeholder="公司名称" type="text" name="companyName" id="companyName"><br>';
// 			if(json.data.count > 0){
// 				html += '想了解的服务：<br>';
// 				$.each(json.data.data, function (kk, vv) {
// 					html += '<label><input type="checkbox" name="service[]"';
// 					if(json.data.count == '1'){
// 						html += ' checked ';
// 					}
// 					html += 'value="'+ kk+'">'+ vv +'</label>';
// 				});
// 			}
// 			html += '</span>';
// 			html += '</form>';
// 			html += '</div>';
// 			layerDiv(html);
// 		}
// 	});

// }
// function layerDiv(html){
// 	// 示范一个公告层
// 	var index = layer.open({
// 		type : 1,
// 		title : "询盘", // 不显示标题栏
// 		area : '80%',
// 		shade : 0.8,
// 		id : 'LAY_layuipro', // 设定一个id，防止重复弹出
// 		resize : false,
// 		btn : [ '提交询盘', '取消' ],
// 		btnAlign : 'c',
// 		content : html,
// 		yes : function(index, layero) {
// 			// 按钮【按钮一】的回调
// 			var name = $("#name").val();
// 			var phone = $("#phone").val();
// 			var companyName = $("#companyName").val();
// 			if(name == ""){
// 				layer.msg("请输入名称");
// 				$("#name").focus();
// 				return false;
// 			}
// 			if(phone == ""){
// 				layer.msg("请输入手机号码");
// 				$("#phone").focus();
// 				return false;
// 			}
// 			if (!phone.match(/^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/)) {
// 				layer.msg('手机号码填写不正确');
// 				$("#phone").focus();
// 				return false;
// 			}
// 			if(companyName == ""){
// 				layer.msg("请输入公司名称");
// 				$("#companyName").focus();
// 				return false;
// 			}
// 			var loadding = layer.load();
// 			$.ajax({
// 				url : "/api/send-ask",
// 				type : "POST",
// 				data : $(".askForm").serialize(),
// 				dataType : "json",
// 				success : function(json) {
// 					layer.close(loadding);
// 					layer.msg(json.msg);
// 					if(json.state){
// 						layer.close(index);
// 					}
// 				},
// 				error : function() {
// 					layer.close(loadding);
// 					layer.msg('网络错误！');
// 				}
// 			});
// 		},
// 		cancel : function() {
// 			// 右上角关闭回调
// 		}
// 	});
// }


function sendResourcesAsk(id, company_type){
    var isLogin = $("#isLogin").val();
    var loading = layer.load();
    var html = '';
    $.post("/api/get-warehouse-service",{serviceId:id},function(json){
        layer.close(loading);
        if(json.state){
            var json = json;
            html += '<div class="layer_div">';
            html += '<form class="askForm">'
            html += '<input type="hidden" name="serviceId" value="'+ id +'">';
            html += '<input type="hidden" name="company_type" value="'+ company_type +'">';
            html += '<input class="form-control" placeholder="称呼" type="text" name="name" id="name"><br>';
            html += '<input class="form-control" placeholder="手机" type="text" name="phone" id="phone"><br>';
            if(isLogin != '1') {
                html += '<input type="text" class="form-control col4 code_jzc" name="code" id="code" placeholder="请输入手机验证码">';
                html += '<button type="button" class="form-control col5 code_jzc" id="get-code" onclick="getCode()">获取验证码</button>';
                html += '<br>';
            }
            html += '<input class="form-control" placeholder="公司名称" type="text" name="companyName" id="companyName"><br>';
            if(json.data.count > 0){
                html += '想了解的服务：<br>';
                $.each(json.data.data, function (kk, vv) {
                    html += '<label><input type="checkbox" name="service[]"';
                    if(json.data.count == '1'){
                        html += ' checked ';
                    }
                    html += 'value="'+ kk+'">'+ vv +'</label>';
                });
            }
            html += '</span>';
			html+='<section>请详细描述您的需求(非必填)：<br><textarea style="width: 100%;box-sizing: border-box;padding: 5px 10px;border: 1px solid #ccc;outline-color:#2878ff" rows="4" name="remark"></textarea></section>';
            html += '</form>';
            html += '</div>';
            layerResourcesDiv(html);
        }
    });

}
function layerResourcesDiv(html){
    // 示范一个公告层
    var index = layer.open({
        type : 1,
        title : "询盘", // 不显示标题栏
        area : '80%',
        shade : 0.8,
        id : 'LAY_layuipro', // 设定一个id，防止重复弹出
        resize : false,
        btn : [ '提交询盘', '取消' ],
        btnAlign : 'c',
        content : html,
        yes : function(index, layero) {
            // 按钮【按钮一】的回调
            var name = $("#name").val();
            var phone = $("#phone").val();
            var companyName = $("#companyName").val();
            if(name == ""){
                layer.msg("请输入名称");
                $("#name").focus();
                return false;
            }
            if(phone == ""){
                layer.msg("请输入手机号码");
                $("#phone").focus();
                return false;
            }
            if (!phone.match(/^(13[0-9]{9})|(18[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/)) {
                layer.msg('手机号码填写不正确');
                $("#phone").focus();
                return false;
            }
            if(companyName == ""){
                layer.msg("请输入公司名称");
                $("#companyName").focus();
                return false;
            }
            var loadding = layer.load();
            $.ajax({
                url : "/api/send-ask-resources",
                type : "POST",
                data : $(".askForm").serialize(),
                dataType : "json",
                success : function(json) {
                    layer.close(loadding);
                    layer.msg(json.msg);
                    if(json.state){
                        layer.close(index);
                    }
                },
                error : function() {
                    layer.close(loadding);
                    layer.msg('网络错误！');
                }
            });
        },
        cancel : function() {
            // 右上角关闭回调
        }
    });
}

//下载文件
function downLoadFile(id) {
	window.open("/home/download-file?id="+id);
}

//搜索问题
function searchFaq(page){
	if(page == '' || page == undefined){
		page = 1;
	}
	var keyword = $("#input_question").val();
	var loadding = layer.load(0, {shade: false});
	$.ajax({
		url : "/home/get-question",
		type : "POST",
		data : {page: page, keyword: keyword, serviceId: $("#serviceId").val()},
		dataType : "json",
		success : function(json) {
			layer.close(loadding);
			if(json.state){
				$("#serviceFaqCount").val(json.count);
				var html = '';
				$.each(json.data, function (k, v) {
					html += '<ul class="question-list">';
					html += '<li>';
					html += '<span class="icon icon-q">Q</span>';
					html += '<span>'+ v.question +'</span>';
					html += '</li>';
					html += '<li>';
					html += '<span class="icon icon-a">A</span>';
					html += '<span>'+ v.answer +'</span>';
					html += '</li>';
					html += '</ul>';
				});
				$(".quetion_div").html(html);
				getFaq(page);
			}
		},
		error : function() {
			layer.close(loadding);
			layer.msg('网络错误！');
		}
	});
}

//点击查看email
function viewEmail(serviceId){
	$.ajax({
		url : "/api/view-email",
		type : "POST",
		data : {
			serviceId : serviceId
		},
		dataType : "json",
		success : function(json) {
			if(json.state){
				$("#service_email").text(json.data).css('width','auto');
				$(".view_service_email").empty();
			}else{
				layer.msg(json.msg, {icon:2})
			}
		},
		error : function() {
			layer.msg("网络错误", {icon:2})
		}
	});
}

//点击查看email
function viewPhone(serviceId){
	$.ajax({
		url : "/api/view-phone",
		type : "POST",
		data : {
			serviceId : serviceId
		},
		dataType : "json",
		success : function(json) {
			if(json.state){
				$("#service_phone").text(json.data);
				$(".view_service_phone").empty();
			}else{
				layer.msg(json.msg, {icon:2})
			}
		},
		error : function() {
			layer.msg("网络错误", {icon:2})
		}
	});
}

//点击按钮加载更多文章
$(".show_jzc").click(function(){
	var count = Math.ceil($('.article_jzc_new').length/5);//获取已经显示的数据条数
	var id = $('.id').val();
	$.ajax({
		url:'/home/get-more-news',
		data:{'count':count,'id':id},
		type:'post',
		dataType:'json',
		success:function(res){
			if(res.state == 1){
				var html = '';
                for(i in res.msg.data){
					html = '<div class="col-md-6">';
                    html +='<div class="media article_jzc_new">';
                    html +='<div class="media-left media-middle">';
                    html +='<a href="/article/'+res.msg.data[i]['al_id']+'">';
                    html +='<img class="media-object" src="'+res.msg.data[i]['al_thumb']+'" alt="..." style="width:153px;height:120px; ">';
                    html +='</a>';
                    html +='</div>';
                    html +='<div class="media-body">';
                    html +='<h4 class="media-heading"><a href="/article/'+res.msg.data[i]['al_id']+'">'+res.msg.data[i]['al_title']+'</a></h4>';
                    html +='<p class="art-txt">'+res.msg.data[i]['al_desc']+'..</p>';
                    html +='<span class="art-sub">'+res.msg.data[i]['al_post_time']+'</span>';
                    html +='</div>';
                    html +='</div>';
                    html +='</div>';
                    $('.article_body').append(html);
				}
				console.log(html);

			}else{
				layer.msg('温馨提示：数据已经加载完毕！');
			}
		}
	})
})
