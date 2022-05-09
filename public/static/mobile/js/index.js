$(function() {
	//分页
	if(document.getElementById("laypage")){
		laypage({
	        cont: 'laypage',
	        pages: Math.ceil($("#dataCount").val()/12),
	        curr:  $("#thisPage").val(),
	        skin: '#ff8d00',
            groups: 2,//连续显示分页数
	        jump: function (obj,first) {
	             if(!first){
	            	 submitSearch(obj.curr);
	             }
	        }
	    });
	}

	$(".add-task").click(function(){
		window.location.href = '/task';
	});

	$(".btn-ok").click(function(){
		submitSearch();
	});

	//排序
	$(".header-sort dd").click(function () {
		$(this).addClass("selected").siblings().removeClass("selected");
		submitSearch();
	});

	//国家-服务内容
	$(".sel-btn").click(function (e) {
		var isOpen= $(this).find(".outside-span").hasClass('outside-box');
		var type= $(this).attr("data-type");
		if(isOpen){  //关闭
			$('.sel-btn .outside-span').removeClass('outside-box');
			$("#"+type).hide();
			$(".cover-pannel").hide();
			$(".select-items").removeClass('select-border');
			$(".a-box").css('background','#f3f3f3');
		}else{      //展开
			$('.sel-btn .outside-span').removeClass('outside-box');
			$(".select-items").addClass('select-border');
			$(".a-box").css('background','#f3f3f3');
			$(this).find(".outside-span").addClass('outside-box')
			$(this).find(".a-box").css('background','transparent');
			$(".dropdown-box").hide();
			$("#"+type).show();
			$(".cover-pannel").show();
		}
		  e.stopPropagation();
    });
	$("body").click(function(){
		$('.sel-btn .outside-span').removeClass('outside-box');
		$("#s,#c").hide();
		$(".cover-pannel").hide();
	})

	//支持大货
	$("#free").click(function(){
		$(this).toggleClass("cur");
		submitSearch();
	});

	//支持大货
	$("#ing").click(function(){
		$(this).toggleClass("cur");
		submitSearch();
	});

	$(".types-menu").click(function(e){
		$(this).addClass("cur").siblings().removeClass("cur");
		e.stopPropagation();
	});
});

function submitSearch(page){
	layer.load();
	var params = '';
	//关键词
	var keyword = $("#keyword").val();
	if(keyword != ''){
		params += 'keyword='+keyword+'&';
	}
	//筛选条件
	$(".select-items li.sel-btn").each(function(){
		var name = $(this).attr("data-type");
		if(name){
			var val = $("#"+name).find(".cur").attr("data-id");
			if(val != '0'){
				params += name+'='+val+'&';
			}
		}
	});
	// 免费
	if($("#free").hasClass("cur")){
		params += 'free=1&';
	}
	// 未结束
	if($("#ing").hasClass("cur")){
		params += 'ing=1&';
	}
	//排序
	var sortType = $(".header-sort").find(".selected").attr("data-type");
	if(sortType && sortType > 0 && sortType != 'undefined'){
		params += 'by='+sortType+'&';
	}
	//分页
	if(page > 1){
		params += 'page='+page+'&';
	}
	//去除最后一个&
	if(params != ''){
		params = '?'+params.substr(0,params.length-1);
	}
	window.location.href = '/index/activity/index'+params;
}
