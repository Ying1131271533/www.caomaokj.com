var page = 1;

$(function () {
	//分页
	if(document.getElementById("laypage")){
		laypage({
	        cont: 'laypage',
	        pages: Math.ceil($("#count").val()/10),
	        curr:  $("#thisPage").val(),
	        skin: '#ff8d00',
	        jump: function (obj,first) {
	             if(!first){
	            	 submitSearch(obj.curr);
	             }
	        }
	    });
	}

	$(".add-task").click(function(){
		window.location.href = 'http://task.kuajingyan.com/task';
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
			$(".a-box").css('background','#fff');
		}else{      //展开
			$('.sel-btn .outside-span').removeClass('outside-box');
			$(".select-items").addClass('select-border');
			$(".a-box").css('background','#fff');
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
		$("#a,#p,#t").hide();
		$(".cover-pannel").hide();
	})

	//支持大货
	$("#big").click(function(){
		$(this).toggleClass("cur");
		submitSearch();
	});

	$(".types-menu").click(function(e){
		$(this).addClass("cur").siblings().removeClass("cur");
		e.stopPropagation();
	});

	$(".view_service_email").click(function(){
		var obj = $(this);
		var serviceId = $(this).attr("data-id");
		$.ajax({
			url : "/api/view-email",
			type : "POST",
			data : {
				serviceId : serviceId
			},
			dataType : "json",
			success : function(json) {
				if(json.state){
					$(".service_email_"+serviceId).text(json.data);
					obj.remove();
				}else{
					layer.msg(json.msg, {icon:2})
				}
			},
			error : function() {
				layer.msg("网络错误", {icon:2})
			}
		});
	});

	$(".view_service_phone").click(function(){
		var obj = $(this);
		var serviceId = $(this).attr("data-id");
		$.ajax({
			url : "/api/view-phone",
			type : "POST",
			data : {
				serviceId : serviceId
			},
			dataType : "json",
			success : function(json) {
				if(json.state){
					$(".service_phone_"+serviceId).text(json.data);
					obj.remove();
				}else{
					layer.msg(json.msg, {icon:2})
				}
			},
			error : function() {
				layer.msg("网络错误", {icon:2})
			}
		});
	});
       //选中地区、城市
       var r_id  = $('#r_val').val();
       regionSelect(r_id);
       
       //初始化选周、专线
       var z_id  = $('#z_val').val();
       transSelect(z_id);
});
       //选择地区
       $(".select_first_ul .region").click(function(e){
            var r_id = $(this).attr('data-id');
            regionSelect(r_id);
            e.stopPropagation();
        });
        
        //选择城市
        $(".select_second_ul>li").click(function(e){
            var choose = $(this).text();
	    $(this).addClass("focusli").siblings("li").removeClass("focusli");
            $(this).addClass("selected").siblings("li").removeClass("selected");
            $(this).addClass("cur").siblings().removeClass("cur");
            e.stopPropagation();
        });
        
        //根据选中的地区id切换城市
        function regionSelect(r_id){
            if (r_id == 0) {
                $('.city').show();
            } else {
                $('.city').hide();
                $('.city-' + r_id).show();
            }
            $('.region-'+r_id).addClass("focus-p").siblings(".region").removeClass("focus-p");
            $('.region-'+r_id).addClass("selected").siblings(".region").removeClass("selected");
            $('.region-'+r_id).addClass("r-cur").siblings(".region").removeClass("r-cur");
        }
        
         //选择洲
       $(".select_first_ul .continent").click(function(e){
            var z_id = $(this).attr('data-id');
            transSelect(z_id);
            e.stopPropagation();
        });
        
        //选择专线
        $(".select_second_ul>li").click(function(e){
            var choose = $(this).text();
	    $(this).addClass("focusli").siblings("li").removeClass("focusli");
            $(this).addClass("selected").siblings("li").removeClass("selected");
            $(this).addClass("cur").siblings().removeClass("cur");
            e.stopPropagation();
        });
        
        //根据选中的地区id切换城市
        function transSelect(z_id){
            if (z_id == 0) {
                $('.trans').show();
            } else {
                $('.trans').hide();
                $('.trans-' + z_id).show();
            }
            $('.continent-'+z_id).addClass("focus-p").siblings(".continent").removeClass("focus-p");
            $('.continent-'+z_id).addClass("selected").siblings(".continent").removeClass("selected");
            $('.continent-'+z_id).addClass("z-cur").siblings(".continent").removeClass("z-cur");
        }
        
function submitSearch(page){
	layer.load();
	var params = '';
	//关键词
	var keyword = $("#keyword").val();
	if(keyword != ''){
		params += 'keyword='+keyword+'&';
	}
         //地区
        var r_val = $(".select_first_ul").find(".r-cur").attr("data-id");
        if(r_val != '0'){
            params += 'r='+r_val+'&';
        }
        //洲
        var z_val = $(".select_first_ul").find(".z-cur").attr("data-id");
        if(z_val != '0'){
            params += 'z='+z_val+'&';
        }
	//筛选条件
	$(".select-items li.sel-btn").each(function(){
		var name = $(this).attr("data-type");
		if(name){
			var val = $("#"+name).find(".cur").attr("data-id");
			if(val != '0' && val !=undefined){
				params += name+'='+val+'&';
			}
		}
	});
        
        //排序
	var sortType = $(".header-sort").find(".selected").attr("data-type");
	if(sortType != '1' && sortType != undefined){
		params += 'type='+sortType+'&';
	} 
	//分页
	if(page > 1){
		params += 'page='+page+'&';
	}
	//去除最后一个&
	if(params != ''){
		params = '?'+params.substr(0,params.length-1);
	}
	window.location.href = '/index/logistics/index/'+params;
}
