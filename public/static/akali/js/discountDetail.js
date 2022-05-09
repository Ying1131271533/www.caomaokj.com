$(document).ready(function () {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});

//提交表单
function submitForm(){
	var loading = layer.load();
	$.ajax({
		url : "/discount/receive",
		type : "POST",
		data : $(".receive-form").serialize(),
		dataType : "json",
		success : function(json) {
			layer.close(loading);
			if(json.code){
				layer.msg(json.msg,{icon:1});
				window.location.href = json.data;
			}else{
				layer.msg(json.msg,{icon:2});
			}
		},
		error : function() {
			layer.close(loading);
			layer.msg('网络错误！',{icon:0});
		}
	});
}