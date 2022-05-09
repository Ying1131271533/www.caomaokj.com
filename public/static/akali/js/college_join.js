$(document).ready(function () {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});

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
		url: "/index/code_api/getCode",
		type : "POST",
		data : {phone : phone, type: 6},
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


$('.number_up').change(function () {
	var amount = $('.amount_jzc').val();
	var num = $(this).val();
	$('.actual_price').text('￥'+(amount*num).toFixed(2));
	$('.total').text('￥'+(amount*num).toFixed(2));
});

var flag = 1;
if(flag == 1){
	function join(id){
		if(!($('.is_check').is(":checked"))){
            layer.msg('温馨提示：只有阅读并接受协议才能报名', {
                icon: 0,
                time: 2000 //2秒关闭（如果不配置，默认是3秒）
            });
            return false;
        }
		flag = 0;
		$('.btn-join').attr('disabled',true);
		var loadding = layer.load();
		$.ajax({
			url : "/index/college_api/collegeJoin",
			type : "POST",
			data : $(".joinForm").serialize(),
			dataType : "json",
			success : function(json) {
				layer.close(loadding);
				if(json.code == 200){
					flag = 1;
					$('.btn-join').attr('disabled',true);
					layer.msg(json.msg,{icon:1});
					window.location.href = json.data.url;
				}else{
					flag = 1;
					$('.btn-join').attr('disabled',false);
					layer.msg(json.msg,{icon:2});
				}
			},
			error : function() {
				layer.close(loadding);
				layer.msg('网络错误！',{icon:0});
			}
		});
	}
}

