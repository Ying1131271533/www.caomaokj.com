$(function () {

	$(".doiphin-login-box a").mouseover(function() {
		$(this).addClass('doiphin-login-btn');
	}).mouseout(function() {
		$(this).removeClass('doiphin-login-btn');
	});
	$('.search-course-icon').click(function () {
		var search_course=$('#dolphin-search-course').val()
		var search_val=$('#search-course-text-input').val();
		if(search_course ==''){
			if(search_val!=''){
				window.location.href='/dolphin/search?search_name='+search_val;
			}else{
				window.location.href='/dolphin/search';
			}
		}


	});
})



