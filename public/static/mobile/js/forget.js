$(function() {

	$("#rePassword").keydown(function(event) {
		event = document.all ? window.event : event;
		if ((event.keyCode || event.which) == 13) {
			$("#sub-login").click();
		}
	});

	// 登录
	$("#sub-login").click(function() {
		var username = $("#username").val();
		var password = $("#password").val();
		var remeber = $("#remeber").val();
		if (username == '' || password == '') {
			layer.msg('请输入登录信息');
			return;
		}
		var loadding = layer.load(0, {shade: false});
		$.ajax({
			url : "/user-api/login-check",
			type : "POST",
			data : {
				username : username,
				password : password,
				remeber : $('#remeber')[0].checked,
				returnUrl : $('#return-url').val(),
                _csrf:$('.getCsrfTokenJzc').val(),
			},
			dataType : "json",
			jsonp : "callback",
			success : function(json) {
				layer.close(loadding);
				if (json.state == 1) {
					window.location.href = json.data;
				} else {
					layer.msg(json.msg);
				}
			},
			error : function() {
				layer.close(loadding);
				layer.msg('网络错误！');
			}
		});
	});

	// 发送验证码
	$("#get-code").click(function() {
		var phone = $("#phone").val();
		if (phone == '') {
			layer.msg('请输入手机或者邮箱');
			$("#phone").focus();
			return false;
		}
		var cemail = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
		var cphone = /(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/;
		if(!phone.match(cemail) && !phone.match(cphone)){
			layer.msg('手机号码或者邮箱填写不正确');
			$("#phone").focus();
			return false;
		}

		$('#get-code').attr('disabled', 'disabled');
		var i = 59;
		var flag = setInterval(function() {
			$('#get-code').text("重新发送(" + i + "s)");
			if (i-- == 0) {
				clearInterval(flag);
				$('#get-code').removeAttr('disabled').text("获取验证码");
			}
		}, 1000);

		$.ajax({
			url : "/user-api/get-code",
			type : "POST",
			data : {
				phone : phone,
				type : 'forget',
                _csrf:$('.getCsrfTokenJzc').val(),
			},
			dataType : "json",
			success : function(json) {
				if (json.state == 1) {
					layer.msg('验证码已发送，请注意查收');
				} else {
					layer.msg(json.msg);
					clearInterval(flag);
					$('#get-code').removeAttr('disabled').text("获取验证码");
				}
			},
			error : function() {
				layer.msg('网络错误！');
			}
		});
	});

	// 提交注册
	$("#sub-forget").click(function() {
		// 验证手机号
		var phone = $("#phone").val();
		if (phone == '') {
			layer.msg('请输入手机或者邮箱');
			$("#phone").focus();
			return false;
		}
		var cemail = /^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/;
		var cphone = /(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/;
		if(!phone.match(cemail) && !phone.match(cphone)){
			layer.msg('手机号码或者邮箱填写不正确');
			$("#phone").focus();
			return false;
		}
		if ($("#code").val() == '') {
			layer.msg('请填写验证码');
			$("#code").focus();
			return false;
		}
		var password = $("#password").val();
		if (password == '') {
			layer.msg('请填写密码');
			$("#password").focus();
			return false;
		}
		if (!password.match(/^[a-zA-Z0-9]{8,20}$/)) {
			layer.msg('密码不符合规范，长度必须为8-20');
			$("#password").focus();
			return false;
		}
		var rePassword = $("#rePassword").val();
		if(password != rePassword){
			layer.msg('两次密码输入不一致');
			$("#rePassword").focus();
			return false;
		}
		var loadding = layer.load(0, {shade: false});
		$.ajax({
			url : "/user-api/forget",
			type : "POST",
			data : $(".forgetForm").serialize()+'&_csrf='+$('.getCsrfTokenJzc').val(),
			dataType : "json",
			success : function(json) {
				layer.close(loadding);
				if (json.state == 1) {
					layer.msg('密码重置成功，正在自动登录...');
					setTimeout(function() {
						window.location.href = json.data;
					}, 1000)
				} else {
					layer.msg(json.msg);
				}
			},
			error : function() {
				layer.close(loadding);
				layer.msg('网络错误！');
			}
		});
	});

});
