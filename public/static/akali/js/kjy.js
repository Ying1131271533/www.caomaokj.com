//全站js
$(function() {
    $('.header-drop-down-a').mouseover(function() {
            $(this).find('.header-drop-down-list-box').show();
        }).mouseout(function() {
            $(this).find('.header-drop-down-list-box').hide();
        });
	//var menu_logo_url = $('.menu_logo').attr('src');
    //$('.menu_logo').attr('src',menu_logo_url+'?v='+Math.random());
    $(".dropdown_jzc").mouseover(function() {
        $(this).find('.dropdown-menu').show();
    }).mouseout(function() {
          $(this).find('.dropdown-menu').hide();
    });

	$(".forum-item").mouseover(function() {

		$('.forum-item>.item-box').css({'opacity':'1','visibility':'visible'});
		//$(".forum-item .item-box").show();
	});
	$(".forum-item").mouseout(function() {
		$('.forum-item>.item-box').css({'opacity':'0','visibility':'hidden'});

	});
	$(".service-item").mouseover(function() {

		$('.service-item>.item-box').css({'opacity':'1','visibility':'visible'});
	});
	$(".service-item").mouseout(function() {
		$('.service-item>.item-box').css({'opacity':'0','visibility':'hidden'});
	});

        $(".resource-item").mouseover(function() {

		$('.resource-item>.item-box').css({'opacity':'1','visibility':'visible'});
	});
	$(".resource-item").mouseout(function() {
		$('.resource-item>.item-box').css({'opacity':'0','visibility':'hidden'});
	});
         $(".logistics-item").mouseover(function() {

		$('.logistics-item>.item-box').css({'opacity':'1','visibility':'visible'});
	});
	$(".logistics-item").mouseout(function() {
		$('.logistics-item>.item-box').css({'opacity':'0','visibility':'hidden'});
	});

	$(".message").mouseover(function() {
		$(".msg-menu").show();
	}).mouseout(function() {
		$(".msg-menu").hide();
	});
	$(".user-li").mouseover(function() {
		$(".user-menu").show();
	}).mouseout(function() {
		$(".user-menu").hide();
	});
    $('.nav-menu .dropdown .dropdown-menu li a').mouseover(function() {
		$(this).parent().removeClass('menu_dropdown_li_selected');
		$(this).parent().siblings().removeClass('menu_dropdown_li_selected');
		$(this).parent().siblings().find('a').removeClass('nav-menu_hover');
		$(this).addClass('nav-menu_hover');
	}).mouseout(function() {
		$(this).removeClass('nav-menu_hover');
		setTimeout(function() {
			var len=$('.nav-menu_hover').length;
			if(len==0){
				$('.common-tab-selected').addClass('menu_dropdown_li_selected');
			}

		}, 500);

	});
	// 当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
	$(window).scroll(function() {
		if ($(window).scrollTop() > 100) {
			$(".go_top").fadeIn(500);
		} else {
			$(".go_top").fadeOut(500);
		}
	});

	// 当点击跳转链接后，回到页面顶部位置
	$(".go_top").click(function() {
		$('body,html').animate({
			scrollTop : 0
		}, 1000);
		return false;
	});

	window._bd_share_config = {
		"common" : {
			"bdSnsKey" : {
				"tsina" : "3429961073"
			},
			"bdText" : "",
			"bdMini" : "2",
			"bdMiniList" : false,
			"bdPic" : "",
			"bdStyle" : "1",
			"bdSize" : "16"
		},
		"share" : {}
	};
	
});

function layerPage(paginator){
    layui.use('laypage', function() {
		var laypage = layui.laypage;
		laypage.render({
			elem : 'laypage',
			theme : '#fc9d27',
			curr : paginator.page || 1,
			count : paginator.count,
			limit : paginator.pageSize || 10,
			jump : function(obj, first) {
				if (!first) {
					getList(obj.curr);
				}
			}
		});
	});
}

//统计首页发布需求的按钮的点击数量
function statistics_task(position='',home_url=''){
    $.ajax({
        url:home_url+'/api/post-task-count',
        data:{
            position:position
        },
        type:'post',
        dataType:'json',
        success:function (res) {
            if(res.status == 1){
                console.log(res);
            }
        }
    })
}



