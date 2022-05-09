$(function() {
	// 查看登录状态
	// var login_status = isToken();
}); 
/* 
function getCode(){
	var phone = $("#phone").val();
	if (phone == '') {
		layer.msg('请输入手机号码',{icon:0});
		$("#phone").focus();
		return false;
	}
	if (!phone.match(/^(13[0-9]{9})|(18[0-9]{9})|(19[0-9]{9})|(16[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/)) {
		layer.msg('手机号码填写不正确',{icon:0});
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
		url: "https://www.caomaokj.com/index/code_api/getCode",
		type : "POST",
		data : {phone : phone},
		dataType : "json",
		success : function(json) {
			if (json.code == 200) {
				layer.msg('验证码已发送，请注意查收',{icon:1});
			} else {
				layer.msg(json.msg);
				clearInterval(flag);
				$('#get-code').removeAttr('disabled').text("获取验证码");
			}
		},
		error : function() {
			layer.msg('网络错误，请刷新页面重试！',{icon:0});
		}
	});
}
 */


// token登录状态
function isToken(){
	var jwt = storage('token');
	if(jwt)
	{
		$.ajax({
			type: "GET",
			url: "https://www.caomaokj.com/index/login_api/isToken",
			beforeSend: function(xhr) {
			  xhr.setRequestHeader("Authorization", jwt);
			},
			success: function(json) {
				if(json.data.code == 0)
				{
					// token验证通过
					// layer.msg(json.data.msg, {icon: 1});
					return true
				}else{
					// token验证未通过
					// layer.msg(json.data.msg, {icon: 2});
					return false;
				}
			}
		});
	}
}

// 退出登录
function logout(){
	layer.msg("正在注销...");
	storage('token', null);
	window.location.href = "/index/login/logout";
}
 
// 本地储存
function storage(key, value){
	if(!key)
	{
		return false;
	}
	
	if(typeof value != "undefined"){
		window.localStorage.setItem(key, value);
	}else{
		var jwt = window.localStorage.getItem(key);
		return jwt;
	}
}
