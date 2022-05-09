$(function () {
     $('#logistics-a').addClass('active');
     $('#resource-a').removeClass('active');
    //揽货范围点击【更多】或【收起】
        $('#a .button').on('click', function() {
            $('#a .list-area .more-city-area').height($("#a .list-area .more-city-area").height() < 90 ? 90 : $("#a .list-area").height())
            if ($(this).hasClass('more')) {
                $(this).hide();
                $('#a .less').show();
                $('#less-city').removeClass('show');
                $('#more-city').addClass('show');
            }
            if ($(this).hasClass('less')) {
                $(this).hide();
                $('#a .more').show();
                $('#more-city').removeClass('show');
                $('#less-city').addClass('show');
            }
        });

        //地区鼠标移动事件
        $('.region').hover(function() {
            var c_id = $(this).attr('data-id');
            if (c_id == 0) {
                $('.city').show();
            } else {
                $('.city').hide();
                $('.city-' + c_id).show();
            }
            $('.region').removeClass('selected');
            $(this).addClass('selected');

        });
        
        //专线服务点击【更多】或【收起】
        $('#p .button').on('click', function() {
            $('#p .list-area .more-trans-area').height($("#p .list-area .more-trans-area").height() < 90 ? 90 : $("#p .list-area").height())
            if ($(this).hasClass('more')) {
                $(this).hide();
                $('#p .less').show();
                $('#less-trans').removeClass('show');
                $('#more-trans').addClass('show');
            }
            if ($(this).hasClass('less')) {
                $(this).hide();
                $('#p .more').show();
                $('#more-trans').removeClass('show');
                $('#less-trans').addClass('show');
            }
        });

        //大洲鼠标移动事件
        $('.continent').hover(function() {
            var c_id = $(this).attr('data-id');
            if (c_id == 0) {
                $('.trans').show();
            } else {
                $('.trans').hide();
                $('.trans-' + c_id).show();
            }
            $('.continent').removeClass('selected');
            $(this).addClass('selected');

        });
        
	//分页
	if(document.getElementById("laypage")){
		laypage({
	        cont: 'laypage',
	        pages: Math.ceil($("#count").val()/12),
	        curr:  $("#thisPage").val(),
	        skin: '#fc9d27',
	        jump: function (obj,first) {
	             if(!first){
	            	 submitSearch(obj.curr);
	             }
	        }
	    });
	}

	//条件筛选
	$(".select-list dd").click(function () {
		$(this).parents(".select-list").find(".selected").removeClass("selected");
		$(this).addClass("selected");
		submitSearch();
	});
        //条件筛选
        $(".select-list .search").click(function () {
		$(this).parents(".select-list").find(".selected").removeClass("selected");
		$(this).addClass("selected");
		submitSearch();
	});
        //更多筛选
	$(".select-list .more-search").click(function () {
		$(this).parents(".more-2").find(".selected").removeClass("selected");
		$(this).addClass("selected");
		submitSearch();
	});
	//排序
	$(".header-sort dd").click(function () {
		$(this).addClass("selected").siblings().removeClass("selected");
		submitSearch();
	});

	$(".btn-search").click(function(){
		submitSearch();
	});

	$("#keyword").keydown(function(e) {
        if (e.keyCode == 13) {
        	submitSearch();
        }
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
				if(json.code){
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
				if(json.code){
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

});

function submitSearch(page){
	
	layer.load();
	var params = '';
	//关键词
	// var keyword = $("#keyword").val();
	// if(keyword != ''){
		// params += 'keyword='+keyword+'&';
	// }
	
	var a_flag = 0;
	var p_flag = 0;
	 //在更多揽收范围选择的信息
	if($('#a .more-info').is(":visible")){
		$("#a .more-1").each(function(){
			var name = $(this).attr("id");
			var val = $("#"+name).find(".selected").attr("data-id");
			if(val != '0'){
				params += name+'='+val+'&';
			}
		});
		
		$("#a .more-2").each(function(){
			var name = $(this).attr("id");
			var val = $("#"+name).find(".selected").attr("data-id");
			if(val != '0'){
				params += name+'='+val+'&';
			}else{
				a_flag = 1;
			}
		});
	}

	//在更多专线服务选择的信息
	if($('#p .more-info').is(":visible")){
		$("#p .more-1").each(function(){
			var name = $(this).attr("id");
			var val = $("#"+name).find(".selected").attr("data-id");
			if(val != '0'){
				params += name+'='+val+'&';
			}
		});
		$("#p .more-2").each(function(){
			var name = $(this).attr("id");
			var val = $("#"+name).find(".selected").attr("data-id");
			if(val != '0'){
				params += name+'='+val+'&';
			}else{
				p_flag = 1;
			}
		});
	}
	
	// 筛选条件
	$(".select li").each(function(){
		var name = $(this).attr("id");
		// 选择最后一个select类名
		var val = $("#"+name).find(".selected:last").attr("data-id");
		
		if(val != '0'){
			if(name == 'a' && a_flag == 1){
				return true;
			}
			if(name == 'p' && p_flag == 1){
				return true;
			}
			params += name+'='+val+'&';
		}
	});

        //排序
	// var sortType = $(".header-sort").find(".selected").attr("data-type");
	// if(sortType != '1'){
		// params += 'type='+sortType+'&';
	// } 
	//分页
	if(page > 1){
		params += 'page='+page+'&';
	}
	//去除最后一个&
	if(params != ''){
		params = '?'+params.substr(0,params.length-1);
	}
	window.location.href = '/index/logistics/index'+params;
}
// 右侧边栏 随鼠标滚动固定位置不变 20171607
    function getScrollTop() {  
        var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;  
        return scrollTop;  
   }  
    var offsetTop = $('#right-sidebar').offset().top;
    $(window).on('scroll',function(){
        var height = getScrollTop()
        var top  = height> offsetTop ?  (height - offsetTop + 100) : 0;
        if(top > $('.main-list-group .left').height() - $('.main-list-group .right').height()){
            top = $('.main-list-group .left').height() - $('.main-list-group .right').height() + 40
        }
		// 修复左短右长产生的bug
		if(top<0){
			top = 0;
		}
       document.querySelector('#right-sidebar').style.top = top + 'px';
    })


