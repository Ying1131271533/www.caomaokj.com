$(function() {
	$(".headerlist").click(function() {
		if ($(this).hasClass("left-nav-close")) {
			$(this).find(".header-menu").hide();
			$(this).find(".header-close").show();
			$(this).removeClass("left-nav-close");
			$(".nav_menu").show();
			$("html,body").css({
				height : $(window).height(),
				overflow : "hidden"
			});
		} else {
			$(this).addClass("left-nav-close");
			$(this).find(".header-close").hide();
			$(this).find(".header-menu").show();
			$(".nav_menu").hide();
			$("html,body").css({
				height : "auto",
				overflow : "auto"
			});
		}
	});

	// 底部导航栏
	$(".nav_service_c").click(function() {
		if ($(".nav_service").css("display") == "none") {
			$(".nav_service").show();
		} else {
			$(".nav_service").hide();
		}
	});
	$(".nav_service").click(function() {
		$(".nav_service").hide();
	})
	$(".nav_service_con").click(function(event) {
		event.stopPropagation();
	})
})

// baidu
var _hmt = _hmt || [];
(function() {
	var hm = document.createElement("script");
	hm.src = "https://hm.baidu.com/hm.js?dd05196a1abdf825fc7a76f577ec4144";
	var s = document.getElementsByTagName("script")[0];
	s.parentNode.insertBefore(hm, s);
})();

(function() {
	var bp = document.createElement('script');
	var curProtocol = window.location.protocol.split(':')[0];
	if (curProtocol === 'https') {
		bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
	} else {
		bp.src = 'http://push.zhanzhang.baidu.com/push.js';
	}
	var s = document.getElementsByTagName("script")[0];
	s.parentNode.insertBefore(bp, s);
})();


