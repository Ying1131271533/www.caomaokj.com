$(function() {
	setTimeout(function() {
		if($.cookie('signup') == '1'){
			layerOpen();
			$.cookie("signup",null)
		}
	}, 100)

	$("body").on("click",".chosen_item",function () {
		$(this).toggleClass("select");
	});
});

function layerOpen() {
	layer.open({
		closeBtn : 0,
		title : false,
		fixed : true,
		type : 1,
		area : [ '680px', '410px' ],
		//shadeClose : true, // 点击遮罩关闭
		content : '\<div class="skip pull-right clearfix"><button onclick = "layer.closeAll()" class="btn btn-default">跳过\</button></div>'
				+ '\<div class="layer-content text-center">'
				+ '\<h3>您希望了解的内容\</h3>'
				+ '\<div class="sub-h">(多选)\</div>'
				+ '\<div class="banner-item layer1 clearfix">'
				+ '\<a data-id="1" class="items chosen_item col-sm-3 select">\<div class="item1">\</div>\<p>找海外仓\</p>\<i></i></a>'
				+ '\<a data-id="2" class="items chosen_item col-sm-3">\<div class="item3">\</div>\<p>文章干货\</p>\<i></i></a>'
				+ '\<a data-id="3" class="items chosen_item col-sm-3">\<div class="item4">\</div>\<p>线下活动\</p>\<i></i></a>'
				+ '\<a data-id="4" class="items chosen_item col-sm-3">\<div class="item5">\</div>\<p>跨境红人\</p>\<i></i></a>'
				+ '\</div>'
				+ '\<a class="next-btn" onclick="subLayer()">下一步\</a>'
				+ '</div>',
	});
}

function subLayer() {
	sendLike();
	layer.open({
		title : false,
		fixed : true,
		closeBtn : 0,
		type : 1,
		area : [ '680px', '410px' ],
//		shadeClose : true, // 点击遮罩关闭
		content : '\<div class="layer-content text-center"><h3>现在您是否发布需求?\</h3>'
				+ '\<div class="banner-item layer2 clearfix">'
				+ '\<a href="/add-task" class="items col-sm-6">\<div class="item6">\</div>\<p>发布我的第一个需求\</p>\</a>'
				+ '\<a class="items col-sm-6" onclick="layer.closeAll()">\<div class="item7">\</div>\<p>暂无需求\</p>\</a>'
				+ '\</div></div>',
	})
}

function sendLike() {
	var likes = [];
	$('.layer1 .select').each(function(){
		likes.push($(this).attr("data-id"));
	});
	$.ajax({
		url : "/api/user-likes",
		type : "POST",
		data : {"likes": likes},
		dataType : "json",
		success : function(json) {
			if (json.code == 200) {
			} else {
			}
		}
	});
}