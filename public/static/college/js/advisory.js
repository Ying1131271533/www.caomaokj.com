$(function () {
	var swiper = new Swiper('.swiper-container', {
		slidesPerView: 4,
		spaceBetween: 25,
		//播放速度
		loop: true,
		// 自动播放时间
		autoplay:true,
		// 播放的速度
		speed:2000,
		// init: false,
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
	});

});
function addDemand(){
	var loadding = layer.load();
	$.ajax({
		url: "/dolphin/add-demand",
		type: "POST",
		data:$(".demandForm").serialize(),
		dataType: "json",
		success: function (json) {
			layer.close(loadding);
			layer.msg(json.msg);
			if(json.code == 200){
			  setTimeout(function () {
				  window.location.reload();
			  },2000);
			}else if(json.code == 2) {
				setTimeout(function () {
					window.location.href=_login_url;
				},2000);

			}

		},
		error: function () {
			layer.close(loadding);
			layer.msg('网络错误！');
		}
	});
}
function getCode(){

    var phone = $(".type_phone").val();
    if (phone == '') {
        layer.msg('请输入手机号码',{icon:0});
        $(".type_phone").focus();
        return false;
    }
    if (!phone.match(/^(13[0-9]{9})|(18[0-9]{9})|(19[0-9]{9})|(16[0-9]{9})|(14[0-9]{9})|(17[0-9]{9})|(15[0-9]{9})$/)) {
        layer.msg('手机号码填写不正确',{icon:0});
        $(".type_phone").focus();
        return false;
    }

    $('.btn-code').attr('disabled', 'disabled');
    var i = 59;
    var flag = setInterval(function() {
        $('.btn-code').text("重新发送(" + i + "s)");
        if (i-- == 0) {
            clearInterval(flag);
            $('.btn-code').removeAttr('disabled').text("获取验证码");
        }
    }, 1000);
    $.ajax({
        url : "/api/send-sms",
        type : "POST",
        data : {phone : phone},
        dataType : "json",
        success : function(json) {
            if (json.code == 200) {
                layer.msg('验证码已发送，请注意查收',{icon:1});
            } else {
                layer.msg(json.msg);
                clearInterval(flag);
                $('.btn-code').removeAttr('disabled').text("获取验证码");
            }
        },
        error : function() {
            layer.msg('网络错误！',{icon:0});
        }
    });
}