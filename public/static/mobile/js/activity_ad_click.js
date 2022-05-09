$(function(){
	$('.banner_href').click(function () {
		var id = $(this).attr('data-id');
		var url = $(this).attr('data-url');
		var type = $(this).attr('data-type');
        $.ajax({
            url: "/home/activity-ad-click",
            type: "POST",
            data: {id: id,url:url,type:type},
            dataType: "json",
            async:false,
            success: function (json) {
                //layer.msg(json.msg);
                if (json.state == 1) {
                    if(type == 'pc'){
                        window.open(url);
                    }else{
                        window.location.href=url;
                    }
                }else{
                    if(type == 'pc'){
                        window.open(url);
                    }else{
                        window.location.href=url;
                    }
                }
            },
            error: function () {
                layer.msg('网络错误！');
            }
        });

    })
})





