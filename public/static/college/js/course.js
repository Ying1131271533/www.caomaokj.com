var _pageSize = 10;
_list_count="";

//海豚首页的顶部的轮播banner
var banner = new Swiper('.doiphin-banner', {
    slidesPerView: 1,
    spaceBetween: 25,
    //播放速度
    loop: true,
    // 自动播放时间
    autoplay:true,
    //图片淡隐淡出
    effect: 'fade',
    // 播放的速度
    speed:2000,
    // init: false,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
});

$(function () {
     $('.search-course-icon').click(function () {
		 getList(1);
	 })

	// getList(1);

	$('.search-cate-box li').click(function () {
		// $(this).addClass("active").siblings().removeClass("active");
		// $('.search-tutor-box .tutor-menu').removeClass("active").first().addClass("active");
		// $('.search-time-box .time-menu').removeClass("active").first().addClass("active");
        // $('.search-course-box .status-menu').removeClass("active").first().addClass("active");
		// $('.search-city-box .city-menu').removeClass("active").first().addClass("active");
		// getList(1);

	})
	$('.search-tutor-box .tutor-menu').click(function () {
		$(this).addClass("active").siblings().removeClass("active");
		getList(1);
	})
	// $('.search-time-box .time-menu').click(function () {
	// 	$(this).addClass("active").siblings().removeClass("active");
	// 	getList(1);
	// })
    $('.search-status-box .status-menu').click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        getList(1);
    })
	$('.search-city-box .city-menu').click(function () {
		$(this).addClass("active").siblings().removeClass("active");
		getList(1);
	})


    $('.course-top-name').click(function (event) {
        $('body,html').animate({ scrollTop: 0 }, 1000);
    });
    //点击自动滚动
    $(document).on('click','.course-cate-name',function() {
        var top = $(window).scrollTop();
        if(top < 470){
            var attr_cate_data=$(this).attr('attr-cate-data');
            $(this).addClass('current_active').siblings().removeClass('current_active');
            $(this).css('border-bottom','1px solid #fc9d27').siblings().css('border-bottom','0px solid #fc9d27');
            if(attr_cate_data == 0){
                $("html, body").animate({
                    scrollTop: 0
                }, {
                    duration: 500,
                    easing: "swing"
                });
            }else{
                var height = $('.cate-'+attr_cate_data).offset().top -120 + "px";
                $("html, body").animate({
                    scrollTop: $('.cate-'+attr_cate_data).offset().top -120 + "px"
                }, {
                    duration: 500,
                    easing: "swing"
                });
            }
        }else{
            var attr_cate_data=$(this).attr('attr-cate-data');
            $(this).addClass('current_active').siblings().removeClass('current_active');
            $(this).css('border-bottom','1px solid #fc9d27').siblings().css('border-bottom','0px solid #fc9d27');
            if(attr_cate_data == 0){
                $("html, body").animate({
                    scrollTop: 0
                }, {
                    duration: 500,
                    easing: "swing"
                });
            }else{
                var height = $('.cate-'+attr_cate_data).offset().top -70 + "px";
                $("html, body").animate({
                    scrollTop: $('.cate-'+attr_cate_data).offset().top -70 + "px"
                }, {
                    duration: 500,
                    easing: "swing"
                });
            }
        }


        return false;

    });

    // 滚动固定顶部的导航
    $(window).scroll(function() {
        var top = $(window).scrollTop();
        if(top < 470){
            $('.course_nav').removeClass('nav_position_fixed');
            $('.doiphin-nav').css({'position':'static'});
            $('.header').show();
            $('.current_active').removeClass('current_active');
        } else {
            //$('.doiphin-nav').css({'position':'fixed'});
            $('.course_nav').addClass('nav_position_fixed');
            $('.header').hide();
        }
    });

    //右侧导航交互
    document.onreadystatechange = function () {
        if (document.readyState == "complete") {
            rightLayer();
        }
    }

    var rightLayer = function () {
        var $boxs = $(".search-cate-box .course-cate-name")
        var scrollRT = function (event) {
            var scrollTop = $(window).scrollTop();

            $boxs.removeClass('active');

            $(".box").each(function(index){
                console.log(index);
                var $this = $('.course-cate-nav-' + (index + 1))
                var marginTop = $(window).height() / 2;
                console.log(marginTop);
                var offsetTop = $(this).offset().top - marginTop;

                if (!$(this).is(":hidden") && scrollTop > offsetTop) {
                    $this.addClass('active').siblings().removeClass('active')
                }

            });
        }
        scrollRT();
        $(window).on('scroll', scrollRT);
    }
});

//数据列表请求
function getList(page){
	var loadding = layer.load(0, {shade: false});
	var cate_id=$('#cate_id').val();
	if(cate_id<=0){
		cate_id=$('.search-cate-box li.active').attr('cate-id');
	}
	var search=$('#search-course').val();
	var is_online=$('#is_online').val();
	var tutor_id=$('.search-tutor-box div.active').attr('tutor-id');
	// var time_id=$('.search-time-box div.active').attr('time-id');
    var status_id=$('.search-status-box div.active').attr('status-id');
	var city_id=$('.search-city-box div.active').attr('city-id');
	$.ajax({
		url: "/index/college_api/courseList",
		type: "POST",
		async : false,
		data: {
			page: page,
			cate_id: cate_id,
			tutor_id: tutor_id,
			city_id: city_id,
            status_id: status_id,
			// time_id: time_id,
			is_online: is_online,
			search: search,

		},
		dataType: "json",
		success: function (json) {
			layer.close(loadding);
			$('#cate_id').val(0);
			if (json.code == 200) {

				var html = '';
				if(json.data != ''){
					$.each(json.data, function (key, val) {
                        if(val.list!=''){
                        html += '<div class="box cate-'+(key+1)+'" cate-attr="'+(key+1)+'">';
                        html += '<div class="cate-box">';
                        html += '<span class="cate-box-span1">'+val.cate_name+'</span>';
                        html += '<span class="cate-box-span2">发散思维 提升员工执行力</span>';
                        html += '</div>';
                        html += '<ul class="cate-ul-1">';
                         $.each(val.list,function (k,v) {
                             html += '<li>';
                             html += '<div class="filter_con_img">';
                             html +='<a href="/dolphin/course/'+v.activity_id+'"><img src="'+v.activity_thumb+'" /></a>';
                             if(v.course_end_sign !=''){
                                 html +='<span class="course-end-sign course-sign-2" >已结束</span>';
                             }else{
                                 html +='<span class="course-end-sign" >进行中</span>';
                             }
                             html += '</div>';
                             html += '<div class="pd20">';
                             html += '<div class="filter_con_one">';
                             html += '<div class="filter_con_one"><a class="course-name" href="/dolphin/course/'+v.activity_id+'">'+v.activity_name+'</a></div>';
                             html += '</div>';
                             html += '<div class="filter_con_tow clearfix">';
                             html += '<div class="fl_l">'+v.activity_tutor_name+' <span class="line">|</span><span>'+v.activity_tutor_title+'</span></div>';
                             if(parseFloat(v.fee.af_price)>0){
                                 html += '<div class="fl_r price">￥'+v.fee.af_price+'</div>';
                             }else{
                                 html += '<div class="fl_r free">免费</div>';
                             }
                             html += '</div>';
                             html += '<div class="filter_con_btn clearfix">';
                             html += '<div class="btn_time"> '+v.time+'</div>';
                             if(v.btn_name=='观看回放'||v.btn_name=='再开课通知我'){
                                 html += '<div class="btn_Signup btn-name-1"><a href="'+v.activity_url+'" target="_blank">'+v.btn_name+'</a></div>';
                             }else if(v.btn_name=='我要报名'){
                                 html += '<div class="btn_Signup"><a href="'+v.activity_url+'" target="_blank">'+v.btn_name+'</a></div>';
                             }

                             html += '</div>';
                             html += '</div>';
                             html += '</li>';
                         })
                        html += '</ul>';
                        html += '</div>';
                          $('.course-cate-nav-'+(key+1)).show();
                        }else{
                          $('.course-cate-nav-'+(key+1)).hide();
                        }

					});

                    $(".main-data").html(html);
				}else{
                    // $(".cate-1").hide();
                    // $('[attr-cate-data="1"]').hide();

				}



			} else {
				layer.msg(data.msg);
			}
		},
		error: function () {
			layer.close(loadding);
			layer.msg('网络错误！');
		}
	});
}
