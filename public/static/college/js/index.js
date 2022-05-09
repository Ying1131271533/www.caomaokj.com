$(function () {
	var swiper = new Swiper('.swiper_container_huaxu', {
		slidesPerView: 4,
		spaceBetween: 25,
		//播放速度
		loop: true,
		// 自动播放时间
		autoplay:true,
		// 播放的速度
		speed:2000,
		// init: false,
		pagination: {
			el: '.swiper_pagination_huaxu',
			clickable: true,
		},
	});

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

    //海豚首页的顶部的轮播banner
    var banner_daoshi = new Swiper('.swiper_container_daoshi', {
        slidesPerView: 5,
        //slidesPerColumn: 2,
        spaceBetween: 0,
        loop: true,
        //自动播放时间
        autoplay:true,
        pagination: {
            el:'.swiper_pagination_daoshi',
            clickable: true,
		}
    });


	$('.growth-case-nav a').hover(function () {
		$('.growth-case-nav a').css('color','rgba(85,85,85,1)');
		$(this).css('color','rgba(39,130,215,1)');
		$('.growth-case .growth-case-content').hide();
		var _class=$(this).attr('data-attr');
		$('.growth-case .'+_class).show();

	});

});