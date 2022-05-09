var scene = '';
var QrcodeShow = false;

$(function () {
    //tab切换
    $(".tabnav").click(function () {
        $(".tabnav").removeClass("active");
        $(this).addClass("active");
        $(".tab-pane").removeClass("active");
        var code = $(this).attr("data-code");
        $("#" + code).addClass("active");
    });

    //微信登录
    $("#wechat-login").click(function () {
        $('.nav-tabs').hide();
        $('.otheway').show();
        $('.logo-level-box').hide();
        if ($(".wechat-div").length > 0) {
            return false;
        }
        $.ajax({
            url: "/useapi/get-qcode",
            type: "GET",
            dataType: "json",
            success: function (json) {
                if (json.scene != '') {
                    var html = '';
                    html += '<div class="wechat-div">';
                    html += '<div class="title">微信登录</div>';
                    html += '<div>';
                    html += '<img src="' + json.url + '">';
                    html += '</div>';
                    html += '<div class="tips">打开微信，扫描二维码</div>';
                    html += '<div class="re" id="uselogin">返回账号登录</div>';
                    html += '</div>';
                    $(".uselogin").hide();
                    $(".tab-content").after(html);
                    scene = json.scene;
                    QrcodeShow = true;
                    checkLogin(0);
                } else {
                    layer.msg("二维码加载失败，请重试", {icon: 0});
                }
            },
            error: function () {
                layer.msg("二维码加载失败，请重试", {icon: 0});
            }
        });
    });

    //返回账号登录
    $(document).on("click", "#uselogin", function () {
        $(".uselogin").show();
        $(".wechat-div").remove();
        $('.nav-tabs').show();
        $('.otheway').hide();
        $('.logo-level-box').show();
    });

    $("#password").keydown(function (event) {
        event = document.all ? window.event : event;
        if ((event.keyCode || event.which) == 13) {
            $("#sub-login").click();
        }
    });

    $("#sms-code").keydown(function (event) {
        event = document.all ? window.event : event;
        if ((event.keyCode || event.which) == 13) {
            $("#sub-login-code").click();
        }
    });

    // 账户密码登录
    $("#sub-login").click(function () {
        var usercode = $("#usercode").val();
        var password = $("#password").val();
        var verifyCode = $("#verifyCode_username").val();
        if (usercode == '' || password == '') {
            layer.msg('请输入登录信息', {icon: 0});
            return false;
        }

        if(verifyCode == ''){
            layer.msg('请输入图片验证码', {icon: 0});
            return false;
        }

        if($('.rember').is(':checked')){
            rember = true;
        }else{
            rember = false;
        }

        var loadding = layer.load();
        $.ajax({
            url: "/index/login_api/login",
            type: "POST",
            data: {
                usercode: usercode,
                password: password,
                rember: rember,
                verifyCode:verifyCode,
				type: 2,
				lasturl: $('#lasturl').val(),
                _csrf:$('.getCsrfTokenJzc').val(),
            },
            dataType: "json",
            jsonp: "callback",
            success: function (json) {
                layer.close(loadding);
                if (json.code == 200) {
					// 保存jwt
					storage('token', json.data.token);
					layer.msg('登录成功，正在跳转页面...', {icon: 1});
					setTimeout(function () {
						window.location.href = $('#lasturl').val();
					}, 500);
				} else {
					$('#captcha_login').trigger('click');
					layer.msg(json.msg, {icon: 2});
				}
            },
            error: function () {
				$('#sub-login').trigger('click');
                $('#captcha_login').trigger('click');
                //layer.close(loadding);
                //layer.msg("网络错误！", {icon: 2});
            }
        });
    });



    // 短信验证码登录
    $("#sub-login-code").click(function () {
        var phone = $("#phone").val();
        var password = $("#sms-code").val();
        var verifyCode = $("#verifyCode").val();
        if (phone == '' || password == '') {
            layer.msg('请输入登录信息', {icon: 0});
            return;
        }

        if(verifyCode == ''){
            layer.msg('请输入图片验证码', {icon: 0});
            return false;
        }

        if($('.rember').is(':checked')){
            rember = true;
        }else{
            rember = false;
        }

        var loadding = layer.load();
        $.ajax({
            url: "/index/login_api/login",
            type: "POST",
            data: {
                phone: phone,
                code: password,
                rember: rember,
                verifyCode:verifyCode,
				type: 1,
                lasturl: $('#lasturl').val(),
                _csrf:$('.getCsrfTokenJzc').val(),
            },
            dataType: "json",
            jsonp: "callback",
            success: function (json) {
                layer.close(loadding);
                if (json.code == 200) {
                    layer.msg("登录成功，正在跳转页面", {icon: 1});
                    window.location.href = $('#lasturl').val();
                } else {
					$('#captchaimg').trigger('click');
                    layer.msg(json.msg, {icon: 2});
                }
            },
            error: function () {
                layer.close(loadding);
                layer.msg('网络错误！', {icon: 2});
            }
        });
    });

    // 发送验证码
    $("#get-code").click(function () {
        var phone = $("#phone").val();
        if (phone == '') {
            layer.msg('请输入正确的手机号码', {icon: 0});
            $("#phone").focus();
            return false;
        }
		// if (!phone.match(/^(13[0-9]{9})|(14[0-9]{9})|(15[0-9]{9})|(16[0-9]{9})|(18[0-9]{9})|(19[0-9]{9})|(17[0-9]{9})$/)) {
		// 	layer.msg('手机号码填写不正确',{icon:0});
		// 	$("#phone").focus();
		// 	return false;
		// }

        $('#get-code').attr('disabled', 'disabled');
        var i = 59;
        var flag = setInterval(function () {
            $('#get-code').text("重新发送(" + i + "s)");
            if (i-- == 0) {
                clearInterval(flag);
                $('#get-code').removeAttr('disabled').text("获取验证码");
            }
        }, 1000);

        $.ajax({
            url: "/index/code_api/getCode",
            type: "POST",
            data: {
                phone: phone,
                type: $(this).attr("data-item"),
            },
            dataType: "json",
            success: function (json) {
                if (json.code == 200) {
                    layer.msg('验证码已发送，请注意查收', {icon: 1});
                } else {
                    layer.msg(json.msg);
                    clearInterval(flag);
                    $('#get-code').removeAttr('disabled').text("获取验证码");
                }
            },
            error: function () {
                layer.msg('网络错误！', {icon: 2});
            }
        });
    });

    // 提交注册
    $("#sub-register").click(function () {
        // 验证手机号
        var phone = $("#phone").val();
        if (phone == '') {
            layer.msg('请输入正确的手机号码', {icon: 0});
            $("#phone").focus();
            return false;
        }
		// if (!phone.match(/^(13[0-9]{9})|(14[0-9]{9})|(15[0-9]{9})|(16[0-9]{9})|(18[0-9]{9})|(19[0-9]{9})|(17[0-9]{9})$/)) {
		// 	layer.msg('手机号码填写不正确',{icon:0});
		// 	$("#phone").focus();
		// 	return false;
		// }
        if ($("#code").val() == '' || $("#code").val() == "undefined") {
            layer.msg('请填写验证码', {icon: 0});
            $("#code").focus();
            return false;
        }
        var username = $("#username").val();
        if (username == '') {
            layer.msg('请填写用户名', {icon: 0});
            $("#username").focus();
            return false;
        }
        if (!username.match(/^[a-zA-Z][a-zA-Z0-9]{5,19}$/)) {
            layer.msg('用户名只能为英文字母和数字，首字母不能为数字，长度6-20', {icon: 0});
            $("#username").focus();
            return false;
        }
        var password = $("#password").val();
        if (password == '') {
            layer.msg('请填写密码', {icon: 0});
            $("#password").focus();
            return false;
        }
        if (!password.match(/^[a-zA-Z0-9]{8,20}$/)) {
            layer.msg('密码只能为英文字母或者数字，长度必须为8-20', {icon: 0});
            $("#password").focus();
            return false;
        }
        var agreement = $('#agreement')[0].checked;
        if (!agreement) {
            layer.msg('请阅读并同意用户协议', {icon: 0});
            return false;
        }
        var loading = layer.load();
        $.ajax({
            url: "/index/login_api/register",
            type: "POST",
            data: $(".registerForm").serialize()+'&_csrf='+$('.getCsrfTokenJzc').val(),
            dataType: "json",
            success: function (json) {
                layer.close(loading);
                if (json.code == 200) {
					// 保存jwt
					storage('token', json.data.token);
                    layer.msg('注册成功，正在自动登录...', {icon: 1});
                    setTimeout(function () {
                        window.location.href = $('#lasturl').val();
                    }, 500)
                } else {
                    layer.msg(json.msg, {icon: 2});
                }
            },
            error: function () {
                layer.close(loading);
                layer.msg('网络错误！', {icon: 2});
            }
        });
    });
});

function checkLogin(timer) {
    if (timer >= 180) {
        $(".uselogin").show();
        $(".wechat-div").remove();
        return;
    }
    $.ajax({
        url: "/useapi/wechat-login",
        type: "POST",
        data: {
            token: scene,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
            if (json.state == 1) {
                QrcodeShow = false;
                window.location.href = json.data;
            } else if (json.state == 2) {
                QrcodeShow = false;
                layer.msg(json.msg, {icon: 2});
            } else {
                setTimeout(checkLogin, 1000);
            }
        },
        error: function () {
            setTimeout(checkLogin, 1000);
        }
    });
}
