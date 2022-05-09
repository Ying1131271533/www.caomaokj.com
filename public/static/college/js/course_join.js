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

var flag = 1;
if(flag == 1){
	function join(id){
		flag = 0;
		$('.btn-join').attr('disabled',true);

		var data_arr = $(".joinForm").serialize();
		console.log(data_arr);
		var loadding = layer.load();
		$.ajax({
			url : "/dolphin/course-join",
			type : "POST",
			data : $(".joinForm").serialize(),
			dataType : "json",
			success : function(json) {
				layer.close(loadding);
				if(json.code){
					flag = 1;
					$('.btn-join').attr('disabled',true);
					layer.msg(json.msg,{icon:1});

                    var payType = $(".payType").find(".thisOver").attr("id");

                    location.href = json.url+"&pay="+payType;

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

//当用户填写信息的时候，自动生成展示门票给用户
$('.activity_question_list input').change(function(){
	var index = $(this).index();
	alert($(this).val());
})

