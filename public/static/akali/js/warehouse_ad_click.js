$(function(){
	$('.banner_href_warehouse').click(function () {
		var id = $(this).attr('data-id');
		var url = $(this).attr('data-url');
		var type = $(this).attr('data-type');
		var ad_type = $(this).attr('data-ad-type');
		
		// 暂时直接跳转
		window.location.href = url;
		
        $.ajax({
            url: "/home/warehouse-ad-click",
            type: "POST",
            data: {id: id,url: url,type: type,ad_type: ad_type},
            dataType: "json",
            success: function (json) {
                //layer.msg(json.msg);
                if (json.code == 200) {
                    window.location.href = url;
                }else{
                    window.location.href = url;
                }
            },
            error: function () {
                layer.msg('网络错误！');
            }
        });

    })
})





