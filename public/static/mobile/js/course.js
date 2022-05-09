var _pageSize = 10;
_list_count="";
$(function () {
     $('.search-course-icon').click(function () {
		 getList(1);
	 })


	function lay_page(){
		if(_list_count ==''){
			_count=$("#list_count").val();
		}else{
			_count=_list_count;
		}
		laypage({
			cont: 'laypage', //容器。值支持id名、原生dom对象，jquery对象。【如该容器为】：<div id="page1"></div>
			pages: Math.ceil(_count/_pageSize), //通过后台拿到的总页数
			curr:  1,
			skin: '#ff8d00',
			first: '首页', //若不显示，设置false即可
			last: '尾页', //若不显示，设置false即可
			prev: '<', //若不显示，设置false即可
			next: '>', //若不显示，设置false即可
			jump: function (obj,first) { //触发分页后的回调
				if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
					getList(obj.curr);
				}
			}
		});
	}
	getList(1);
	lay_page();
	$('.select_course_ul li').click(function () {
		$(this).addClass("active").siblings().removeClass("active");
		getList(1);

	})

});



$(".btn-ok").click(function(){
    $(this).parents().find('.dropdown-box').hide();
    getList(1);
});

//数据列表请求
function getList(page){
	var loadding = layer.load(0, {shade: false});

	//var cate_id=$('#cate_id').val();
	//if(cate_id<=0){
		cate_id=$('.search-cate-box li.active').attr('cate-id');
	//}
	var search=$('#search-course').val();
	//var is_online = $('#is_online').val();
	var tutor_id = $('#a ul li ul').find('.cur').attr('data-id');
	var tutor_name = $('#a ul li ul').find('.cur').attr('data-name');
	var status_id = $('#s ul li ul').find('.cur').attr('data-id');
	var time_name = $('#s ul li ul').find('.cur').attr('data-name');
	var city_id = $('#c ul li ul').find('.cur').attr('data-id');
	var city_name = $('#c ul li ul').find('.cur').attr('data-name');

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
			//is_online: is_online,
			search: search,
		},
		dataType: "json",
		success: function (json) {
			layer.close(loadding);
			$('#cate_id').val(0);
			if (json.code == 200) {

				var html = '';
				if(json.banner != ''){
					$('.course_ad img').attr('src',json.banner);
				}
				
				if(json.data.collegeList != ''){
					$.each(json.data.collegeList, function (key, val) {
						if((key+1)%2 == 1){
							var position = 'fl';
						}else{
                            var position = 'fr';
						}
						html += '<div class="activity-box '+position+'">';
                        html += '<div class="img-box">';
                        html += '<div class="item-img"> ';
                        html += '<a href="/index/college/detail/id/'+val.id+'">';
                        html += '<img src="'+val.thumb+'" alt="'+val.title+'">';
                        html += '</a>';
                        if(val.end_time < json.data.time){
                            html +='<span class="course-end-sign course-sign-2" >已结束</span>';
                        }else{
                            html +='<span class="course-end-sign" >进行中</span>';
						}
                        html += '</div>';
                        html += '<div class="item-text">';
                        html += '<h4>'+val.title+'</h4>';

                        if(parseFloat(val['tickets'][0]['discount_price']) > 0){
                            af_price ='￥'+val['tickets'][0]['discount_price']
                        }else{
                            af_price = '免费';
                        }
                        html += '<p><span class="activity-local">'+val.address+'</span> <span class="pull-right">'+af_price+'</span></p>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
					});
				}else{
                    html+='<div class="no-data"><p>暂无课程</p><p>敬请期待</p></div>';
				}
				$(".filter_con_jzc").html(html);
				$("#list_count").val(json.data.count);
				_list_count=json.data.count;
				//把搜索项展示在搜索框里面
				$('.sel-btn').each(function(){
					if($(this).attr('data-type') == 'a'){
						$(this).find('.a-box').text(tutor_name);
					}
                    if($(this).attr('data-type') == 's'){
                        $(this).find('.a-box').text(time_name);
                    }
                    if($(this).attr('data-type') == 'c'){
                        $(this).find('.a-box').text(city_name);
                    }
				})
				if(json.data.count <= 10){
					$('.laypage').hide();
				}else{
					$('.laypage').show();
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

$(".types-menu").click(function(e){
    $(this).addClass("cur").siblings().removeClass("cur");
    e.stopPropagation();

});

$('.growth-case-nav a').click(function () {
    $('.growth-case-nav a').css('color','rgba(85,85,85,1)');
    $(this).css('color','rgba(39,130,215,1)');
    $('.growth-case .growth-case-content').hide();
    var _class=$(this).attr('data-attr');
    $('.growth-case .'+_class).show();


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
