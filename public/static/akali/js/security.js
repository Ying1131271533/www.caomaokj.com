$(function () {
	// 想要使用erp的用户需要先绑定手机
	if($.cookie("erp_need_tel") == '1') {
		erpTips();
	}
});

function changePwd(){
	var html = '';
	html += '<div style="padding: 50px; line-height: 22px; font-weight: 300;">';
	html += '请输入原密码：<input class="form-control" type="password" id="old-password"><br>';
	html += '请输入新密码：<input class="form-control" type="password" id="new-password"><br>';
	html += '请确认新密码：<input class="form-control" type="password" id="re-new-password"><br>';
	html += '</div>';
	// 示范一个公告层
	var index = layer.open({
		type : 1,
		title : "修改密码", // 不显示标题栏
		area : '500px;',
		shade : 0.8,
		id : 'LAY_layuipro', // 设定一个id，防止重复弹出
		resize : false,
		btn : [ '确认修改', '取消' ],
		btnAlign : 'c',
		content : html,
		yes : function(index, layero) {
			// 按钮【按钮一】的回调
			var oldPwd = $("#old-password").val();
			var newPwd = $("#new-password").val();
			var reNewPwd = $("#re-new-password").val();
			if(oldPwd == '' || newPwd == '' || reNewPwd == ''){
				layer.msg("请输入密码", {icon: 0,time:1000});
				return;
			}
			if(newPwd != reNewPwd){
				layer.msg("两次密码输入不一致", {icon: 0,time:1000});
				return;
			}
			submitPwd(oldPwd, newPwd, reNewPwd, index);
			// layer.close(index);

		},
		cancel : function() {
			// 右上角关闭回调
		}
	});
}


function changeEmail(email){
	var html = '';
	html += '<div style="padding: 50px; line-height: 22px; font-weight: 300;">';
	html += '<div class="input-icon">';
	html += '<input type="text" class="form-control" id="new-email" placeholder="请输入新邮箱">';
	html += '</div>';
	html += '<div class="input-group">';
    html += '<input type="text" class="form-control" id="new-email-code" placeholder="输入验证码">';
    html += '<span class="input-group-btn">';
    html += '<button class="btn btn-code" id="get-email-code" onclick="getEmailCode()" type="button">获取验证码</button>';
    html += '</span>';
    html += '</div>';
	html += '</div>';
	// 示范一个公告层
	var index = layer.open({
		type : 1,
		title : "修改绑定邮箱", // 不显示标题栏
		area : '500px;',
		shade : 0.8,
		id : 'LAY_layuipro', // 设定一个id，防止重复弹出
		resize : false,
		btn : [ '更改绑定', '取消' ],
		btnAlign : 'c',
		content : html,
		yes : function(index, layero) {
			// 按钮【按钮一】的回调
			var newEmail = $("#new-email").val();
			var newEmailCode = $("#new-email-code").val();
			if(newEmail == '' || newEmailCode == ''){
				layer.msg("请输入验证码", {icon: 0,time:1000});
				return;
			}
			submitEmail(newEmailCode, newEmail);
			layer.close(index);
		},
		cancel : function() {
			// 右上角关闭回调
		}
	});
}


function changePhone(phone){
	var html = '';
	html += '<div style="padding: 50px; line-height: 22px; font-weight: 300;">';
	html += '<div class="input-icon">';
	html += '<input type="text" class="form-control" id="new-phone" placeholder="请输入新手机号码">';
	html += '</div>';
	html += '<div class="input-group">';
    html += '<input type="text" class="form-control" id="new-phone-code" placeholder="输入验证码">';
    html += '<span class="input-group-btn">';
    html += '<button class="btn btn-code" id="get-phone-code" onclick="getPhoneCode()" type="button">获取验证码</button>';
    html += '</span>';
    html += '</div>';
	html += '</div>';
	
	// 示范一个公告层
	var index = layer.open({
		type : 1,
		title : "修改绑定手机", // 不显示标题栏
		area : '500px;',
		shade : 0.8,
		id : 'LAY_layuipro', // 设定一个id，防止重复弹出
		resize : false,
		btn : [ '更改绑定', '取消' ],
		btnAlign : 'c',
		content : html,
		yes : function(index, layero) {
			// 按钮【按钮一】的回调
			var newPhone = $("#new-phone").val();
			var newPhoneCode = $("#new-phone-code").val();
			if(newPhone == '' || newPhoneCode == ''){
				layer.msg("请输入验证码", {icon: 0,time:1000});
				return;
			}
			submitPhone(newPhoneCode, newPhone, index);
			// layer.close(index);

		},
		cancel : function() {
			// 右上角关闭回调
		}
	});
}

//发送邮箱验证码
function getEmailCode(){
	var newEmail = $("#new-email").val();
	if (newEmail == '') {
		layer.msg('请输入新邮箱帐号', {icon: 0,time:1000});
		$("#new-email").focus();
		return false;
	}

	$('#get-email-code').attr('disabled', 'disabled');
	var i = 59;
	var flag = setInterval(function() {
		$('#get-email-code').text("重新发送(" + i + "s)");
		if (i-- == 0) {
			clearInterval(flag);
			$('#get-email-code').removeAttr('disabled').text("获取验证码");
		}
	}, 1000);

	$.ajax({
		url : "/api/get-email-code",
		type : "POST",
		data : {
			email : newEmail
		},
		dataType : "json",
		success : function(json) {
			if (json.state == 1) {
				layer.msg('验证码已发送，请注意查收', {icon: 1});
				$("#new-email").prop("disabled", true);
			} else {
				layer.msg(json.msg);
				clearInterval(flag);
				$('#get-email-code').removeAttr('disabled').text("获取验证码");
			}
		},
		error : function() {
			clearInterval(flag);
			$('#get-email-code').removeAttr('disabled').text("获取验证码");
			layer.msg('网络错误，请重新发送', {icon: 0});
		}
	});
}


//发送手机验证码
function getPhoneCode(){
	var newPhone = $("#new-phone").val();
	if (newPhone == '') {
		layer.msg('请输入新手机号', {icon: 0, time:1000});
		$("#new-phone").focus();
		return false;
	}
	
	$('#get-phone-code').attr('disabled', 'disabled');
	var i = 59;
	var flag = setInterval(function() {
		$('#get-phone-code').text("重新发送(" + i + "s)");
		if (i-- == 0) {
			clearInterval(flag);
			$('#get-phone-code').removeAttr('disabled').text("获取验证码");
		}
	}, 1000);

	$.ajax({
		url : "/index/code_api/getCode",
		type : "POST",
		data : {
			phone : newPhone,
			type : 6
		},
		dataType : "json",
		success : function(json) {
			if (json.code == 200) {
				layer.msg('验证码已发送，请注意查收', {icon: 1});
				$("#new-phone").prop("disabled", true);
			} else {
				layer.msg(json.msg);
				clearInterval(flag);
				$('#get-phone-code').removeAttr('disabled').text("获取验证码");
			}
		},
		error : function() {
			clearInterval(flag);
			$('#get-phone-code').removeAttr('disabled').text("获取验证码");
			layer.msg('网络错误，请重新发送', {icon: 0});
		}
	});
}




// 修改密码
function submitPwd(oldPwd, newPwd, reNewPwd, index) {
	var loadding = layer.load();
	$.ajax({
		url : '/index/user_api/changePwd',
		type : "POST",
		data : {
			oldPwd : oldPwd,
			newPwd : newPwd,
			reNewPwd : reNewPwd
		},
		dataType : 'json',
		error : function() {
			layer.close(loadding);
			layer.msg('网络错误！', {icon: 2});
		},
		success : function(json) {
			if (json.code == 200) {
				layer.close(loadding);
				layer.close(index);
				layer.msg(json.msg, {icon: 1});
			} else {
				layer.close(loadding);
				layer.msg(json.msg, {icon: 2});
			}
		}
	});
}

// 修改邮箱
function submitEmail(code, newEmail) {
	$.ajax({
		url : '/home/change-email',
		type : "POST",
		data : {
			code : code,
			newEmail : newEmail
		},
		async : false,
		dataType : 'json',
		error : function() {
			layer.msg('网络错误！', {icon: 2});
		},
		success : function(json) {
			if (json.state) {
				layer.msg(json.msg, {icon: 1});
			} else {
				layer.msg(json.msg, {icon: 2});
			}
		}
	});
}

// 修改手机
function submitPhone(code, newPhone, index) {
	$.ajax({
		url : '/index/user_api/changePhone',
		type : "POST",
		data : {
			code : code,
			phone : newPhone,
			// url:window.location.href,
		},
		async : false,
		dataType : 'json',
		error : function() {
			layer.msg('网络错误！', {icon: 2});
		},
		success : function(json) {
			if (json.code == 200) {
				$('#phone').text(newPhone);
				layer.close(index);
				layer.msg(json.msg, {icon: 1});
				// window.location.href = json.data;
			} else {
				layer.msg(json.msg, {icon: 2});
			}
		}
	});
}

//提醒绑定手机
function erpTips() {
	layer.msg("使用云ERP系统请您先绑定手机",{icon:0});
	$.cookie("erp_need_tel", "");
}


