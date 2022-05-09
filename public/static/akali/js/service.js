var pageSize = 5;
$(function() {
	if ($("#serviceFaqCount").val() > 0) {
		getFaq(1);
	}
});

function getFaq(page) {
	laypage({
		cont : 'laypage',
		pages : Math.ceil($("#serviceFaqCount").val() / pageSize),
		curr : page,
		skin : '#fc9d27',
		jump : function(obj, first) {
			if (!first) {
				searchFaq(obj.curr);
			}
		}
	});
}

// 私信
function sendMsg(id) {
	var loading = layer.load();
	var isLogin = $("#isLogin").val();
	if (isLogin != '1') {
		layer.msg("请先登录，正在跳转页面...");
		window.location.href = 'http://www.kuajingyan.com/login';
		return false;
	}
	layer.close(loading);
	layer.prompt({
		formType : 2,
		title : '请输入私信内容',
		area : [ '300px', '200px' ]
	}, function(value, index, elem) {
		var loading = layer.load();
		if (value != '') {
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
					if (json.code) {
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

function wechat(wechat_img) {
	var isLogin = $("#isLogin").val();
	var html = '';
	html += '<div class="layer_div">';
	html += '<img src="' + wechat_img + '" width="250" height="250"/>';
	html += '<div class="layui-layer-title" style="padding: 0 5px;font-size:17px;">添加草帽客服微信号，了解详情</div>';
	html += '</div>';
	layerWechat(html);
}

function layerWechat(html) {
	// 示范一个公告层
	var index = layer.open({
		type : 1,
		title : "询盘", // 不显示标题栏
		area : '300px;',
		shade : 0.8,
		id : 'LAY_layuipro', // 设定一个id，防止重复弹出
		resize : false,
		btn : [ '关闭窗口' ],
		btnAlign : 'c',
		content : html,
		cancel : function() {
			// 右上角关闭回调
			
		}
	});
}

function sendAsk(id) {
	var isLogin = $("#isLogin").val();
	var loading = layer.load();
	var html = '';
	$.post("/index/logistics_api/getService", {serviceId : id}, function(json) {
		layer.close(loading);
		if (json.code) {
			html += '<div class="layer_div">';
			html += '<form class="askForm">'
			html += '<input type="hidden" name="serviceId" value="' + id + '">';
			html += '<input class="form-control" placeholder="称呼" type="text" name="name" id="name">';
			html += '<input class="form-control" placeholder="手机" type="text" name="phone" id="phone">';
			if(isLogin != '1') {
				html += '<input type="text" class="form-control col4" name="code" id="code" placeholder="请输入手机验证码">';
				html += '<button type="button" class="form-control col5" id="get-code" onclick="getCode()">获取验证码</button>';
			}
			html += '<input class="form-control" placeholder="公司名称" type="text" name="companyName" id="companyName">';
			if (json.data.count > 0) {
				html += '想了解的服务：<br>';
				$.each(json.data.data, function(kk, vv) {
					html += '<label><input type="checkbox" name="service[]"';
					if (json.data.count == '1') {
						html += ' checked ';
					}
					html += 'value="' + kk + '">' + vv + '</label>';
				});
			}
			html += '</form>';
			html += '</div>';
			layerDiv(html);
		}
	});
}

//海外仓两会资源询盘
function sendResourcesAsk(id,company_type) { 
    var isLogin = $("#isLogin").val();
    var loading = layer.load();
    var html = '';
    $.post("/api/get-warehouse-service", {serviceId : id}, function(json) {
        layer.close(loading);
        if (json.code) {
            html += '<div class="layer_div">';
            html += '<form class="askForm">'
            html += '<input type="hidden" name="serviceId" value="' + id + '">';
            html += '<input type="hidden" name="company_type" value="' + company_type + '">';
            html += '<input class="form-control" placeholder="称呼" type="text" name="name" id="name">';
            html += '<input class="form-control" placeholder="手机" type="text" name="phone" id="phone">';
            if(isLogin != '1') {
                html += '<input type="text" class="form-control col4" name="code" id="code" placeholder="请输入手机验证码">';
                html += '<button type="button" class="form-control col5" id="get-code" onclick="getCode()">获取验证码</button>';
            }
            html += '<input class="form-control" placeholder="公司名称" type="text" name="companyName" id="companyName">';
            if (json.data.count > 0) {
                html += '想了解的服务：<br>';
                $.each(json.data.data, function(kk, vv) {
                    html += '<label><input type="checkbox" name="service[]"';
                    if (json.data.count == '1') {
                        html += ' checked ';
                    }
                    html += 'value="' + kk + '">' + vv + '</label>';
                });
            }
            html += '</form>';
            html += '</div>';
            layerResourcesDiv(html);
        }
    });
}
//海外仓两会资源询盘表单提交
function layerResourcesDiv(html) {
    // 示范一个公告层
    var index = layer.open({
        type : 1,
        title : "询盘", // 不显示标题栏
        area : '400px;',
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
            if (name == "") {
                layer.msg("请输入名称");
                $("#name").focus();
                return false;
            }
            if (phone == "") {
                layer.msg("请输入手机号码");
                $("#phone").focus();
                return false;
            }
            if (!phone.match(/^(13[0-9]{9})|(14[0-9]{9})|(16[0-9]{9})|(17[0-9]{9})|(18[0-9]{9})|(19[0-9]{9})|(15[0-9]{9})$/)) {
                layer.msg('手机号码填写不正确');
                $("#phone").focus();
                return false;
            }
            if (companyName == "") {
                layer.msg("请输入公司名称");
                $("#companyName").focus();
                return false;
            }
            var loading = layer.load();
            $.ajax({
                url : "/api/send-ask-resources",
                type : "POST",
                data : $(".askForm").serialize(),
                dataType : "json",
                success : function(json) {
                    layer.close(loading);
                    layer.msg(json.msg);
                    if (json.code) {
                        layer.msg(json.msg, {icon:1});
                    } else {
                        layer.msg(json.msg, {icon:2});
                    }
                    layer.close(index);
                },
                error : function() {
                    layer.close(loading);
                    layer.msg('网络错误！');
                }
            });
        },
        cancel : function() {
            // 右上角关闭回调
        }
    });
}

function layerDiv(html) {
	// 示范一个公告层
	var index = layer.open({
		type : 1,
		title : "询盘", // 不显示标题栏
		area : '400px;',
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
			if (name == "") {
				layer.msg("请输入名称");
				$("#name").focus();
				return false;
			}
			if (phone == "") {
				layer.msg("请输入手机号码");
				$("#phone").focus();
				return false;
			}
			if (!phone.match(/^(13[0-9]{9})|(14[0-9]{9})|(16[0-9]{9})|(17[0-9]{9})|(18[0-9]{9})|(19[0-9]{9})|(15[0-9]{9})$/)) {
				layer.msg('手机号码填写不正确');
				$("#phone").focus();
				return false;
			}
			if (companyName == "") {
				layer.msg("请输入公司名称");
				$("#companyName").focus();
				return false;
			}
			var loading = layer.load();
			$.ajax({
				url : "/api/send-ask",
				type : "POST",
				data : $(".askForm").serialize(),
				dataType : "json",
				success : function(json) {
					layer.close(loading);
					layer.msg(json.msg);
					if (json.code) {
						layer.msg(json.msg, {icon:1});
					} else {
						layer.msg(json.msg, {icon:2});
					}
					layer.close(index);
				},
				error : function() {
					layer.close(loading);
					layer.msg('网络错误！');
				}
			});
		},
		cancel : function() {
			// 右上角关闭回调
		}
	});
}

// 下载文件
function downLoadFile(id) {
	window.open("/home/download-file?id=" + id);
}

// 搜索问题
function searchFaq(page) {
	if (page == '' || page == undefined) {
		page = 1;
	}
	var keyword = $("#input_question").val();
	var loadding = layer.load(0, {
		shade : false
	});
	$.ajax({
		url : "/home/get-question",
		type : "POST",
		data : {
			page : page,
			keyword : keyword,
			serviceId : $("#serviceId").val()
		},
		dataType : "json",
		success : function(json) {
			layer.close(loadding);
			if (json.code) {
				$("#serviceFaqCount").val(json.count);
				var html = '';
				$.each(json.data, function(k, v) {
					html += '<ul class="question-list">';
					html += '<li>';
					html += '<span class="icon icon-q">Q</span>';
					html += '<span>' + v.question + '</span>';
					html += '</li>';
					html += '<li>';
					html += '<span class="icon icon-a">A</span>';
					html += '<span>' + v.answer + '</span>';
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

// 点击查看email
function viewEmail(serviceId) {
	$.ajax({
		url : "/api/view-email",
		type : "POST",
		data : {
			serviceId : serviceId
		},
		dataType : "json",
		success : function(json) {
			if (json.code) {
				$("#service_email").text(json.data).css('color','#fff');
				$(".view_service_email").empty();
			} else {
				layer.msg(json.msg, {
					icon : 2
				})
			}
		},
		error : function() {
			layer.msg("网络错误", {
				icon : 2
			})
		}
	});
}

$('.xun_btn').hover(function(){
	$(this).css('background','#000');
    $(this).find('.fa').css('color','#fff');
	$(this).find('span').css('color','#fff');
}, function(){
    $(this).css('background','#fff');
    $(this).find('.fa').css('color','#000');
    $(this).find('span').css('color','#000');
})
// 点击查看email
function viewPhone(serviceId) {
	$.ajax({
		url : "/api/view-phone",
		type : "POST",
		data : {
			serviceId : serviceId
		},
		dataType : "json",
		success : function(json) {
			if (json.code) {
				$("#service_phone").text(json.data).css('color','#fff');
				$(".view_service_phone").empty();
			} else {
				layer.msg(json.msg, {
					icon : 2
				})
			}
		},
		error : function() {
			layer.msg("网络错误", {
				icon : 2
			})
		}
	});
}

function activeDetail(name) {
	$("." + name + "-a").click();
}
