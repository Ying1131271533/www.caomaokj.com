var pageSize = 10;
var page = 1;
var category = '';
var country = '';
var type = 'article';

$(function () {

	//文章类型
	$(".navbar-title-ul .navbar-title-li a").click(function(){
		$(".navbar-title-ul .navbar-title-li a").removeClass('navbar-title-li-a-default');
		$(this).addClass('navbar-title-li-a-default');
		category = $(this).attr("category");
		page = 1;
		$(this).parent("ul").find("li").removeClass("active");
		$(this).addClass("active");
		getArticle("change");
		type = 'article';
	});

	//海外观察
	$(".overseas").click(function(){
		page = 1;
		$(this).parent("ul").find("li").removeClass("active");
		$(this).addClass("active");
		getOverseas("change");
		type = 'overseas';
	});

    //知识分享
    $(".knowledge").click(function(){
        page = 1;
        $(this).parent("ul").find("li").removeClass("active");
        $(this).addClass("active");
        getKnowledge("change");
        type = 'knowledge';
    });

	// $(window).scroll(function () {
	//
	//     var scrollTop = $(this).scrollTop();
	//     var scrollHeight = $(document).height();
	//     var windowHeight = $(this).height();
	//     if (scrollTop + windowHeight == scrollHeight ) {
	//     	getMoreArticle();
	//     }
	//  });
});

//加载更多首页文章
function getMoreArticle(){
	page++;
	$(".more").text("正在加载...");
	if(type == 'overseas'){
		getOverseas("more");
	}else if(type == 'knowledge'){
        getKnowledge("more");
    }else{
		category = $('.navbar-title-li-a-default').attr("category");
		getArticle("more");
	}
}

function getArticle(type){
	var loadding = layer.load(0, {shade: false});
	$.ajax({
        url: "/index/home_api/index",
        type: "POST",
        data: {
        	page: page,
            category: category,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.code == 200) {
            	var html = '';
            	$.each(json.data.articleData, function (key, val) {
            		if(val.al_type == 1){
            			var url = '/index/activity/detail/id/'+ val.activity_id;
            		}else{
            			var url = '/index/home/article/id/'+ val.al_id;
            		}
            		html += '<li>';
            		if(val.al_thumb != ''){
            			html += '<a class="alink" href="'+ url +'">';
                		html += '<img alt="'+ val.al_title +'" src="'+ val.al_thumb +'">';
                		html += '</a>';
            		}
            		html += '<div class="art_con">';
            		html += '<h2>';
            		html += '<a class="title" href="'+ url +'">'+ val.al_title +'</a>';
            		html += '</h2>';
            		html += '<a href="/index/home/article/id/' + val.al_id + '">';
            		html += '<img src="'+ val.user_thumb +'">';
            		html += '<h3>'+ val.add_name +'</h3>';
            		html += '</a>';
            		html += '<span class="times">'+ val.al_post_time_m +'</span>';
            		if(val.al_type == '1'){
            			html += '<span class="hot">活动</span>';
            		}
                    html += '</div>';
                    html += '</li>';
            	});
            	if(type == "change"){
            		$(".list_article").html(html);
            	}else{
            		$(".list_article").append(html);
            		$(".more").text("加载更多");
            	}
            	var totalPages = Math.ceil(json.count/pageSize) || 1;
            	if(totalPages <= page){
            		$(".more").text("没有更多了...");
            		$(".more").removeAttr("onclick");
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

function getOverseas(type){
	var loadding = layer.load(0, {shade: false});
	$.ajax({
        url: "/home/get-overseas",
        type: "POST",
        data: {
        	page: page,
        	country: '',
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.code == 1) {
            	var html = '';
            	$.each(json.data, function (key, val) {
            		html += '<li class="overseas overseas_'+ val.id +'">';
            		html += '<div class="overseas_title">';
            		html += '<a href="/user/'+ val.add_user_id+'"><img src="'+ val.user_thumb +'" alt="'+ val.add_user_name +'"/></a>';
            		html += '<a href="/user/'+ val.add_user_id+'"><h4>'+ val.add_user_name +'</h4></a>';
            		html += '<span class="times">'+ val.post_time_m +'</span>';
            		html += '</div>';
            		html += '<div class="overseas_content">';
            		html += '<p><a href="/talk/'+val.id+'">'+ val.content +'</a></p>';
            		html += '</div>';
            		html += '<div class="overseas_img viewImg">';
            		$.each(val.imgs, function (k, v) {
	                  	html += '<a href="'+ v.img_path +'"><img src="'+ v.img_path +'" alt="海外观察"></a>';
	                })
            		html += '</div>';
            		html += '<div>';
                	html += '<ul class="nav nav-tabs fl">';
                	html += '<li>';
                	html += '<a href="/talk/'+val.id+'"><i class="fa fa-comment-o"></i> <span> '+ val.comment_num +' </span></a>';
                	html += '</li>';
                	html += '<li>';
                	html += '<a href="javascript:void(0)" onclick="collectOverseas('+ val.id +', this)"><i class="fa fa-heart-o"></i> <span class="num"> '+ val.collect_num +' </span></a>';
                	html += '</li>';
                	html += '<li>';
                	html += '<a href="javascript:void(0)" onclick="likeOverseas('+ val.id +', this)"><i class="fa fa-thumbs-o-up"></i> <span class="num"> '+ val.like_num +' </span></a>';
                	html += '</li>';
                	html += '</ul>';
                	html += '</div>';
            		html += '</li>';
            	});
            	if(type == "change"){
            		$(".home-main").html(html);
            	}else{
            		$(".home-main").append(html);
            		$(".more").text("阅读更多");
            	}
            	$(".viewImg").each(function(){
                    $(this).find("a").touchTouch();
                });
            	var totalPages = Math.ceil(json.count/pageSize) || 1;
            	if(totalPages <= page){
            		$(".more").text("没有更多了...");
            		$(".more").removeAttr("onclick");
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


//海外观察收藏
function collectOverseas(id, obj) {
	var loadding = layer.load(0, {shade: false});
    $.ajax({
        url: "/api/overseas-collect",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.code == 1) {
            	layer.msg(json.msg);
            	$(obj).children(".num").text(json.data);
            }else if(json.code == 2){
            	window.location.href = '/login';
            } else {
                layer.msg(json.msg);
            }
        },
        error: function () {
        	layer.close(loadding);
            layer.msg('网络错误！');
        }
    });
}

//海外观察点赞
function likeOverseas(id, obj){
  var loadding = layer.load(0, {shade: false});
  $.ajax({
        url: "/api/overseas-like",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.code == 1) {
            	layer.msg(json.msg);
            	$(obj).children(".num").text(json.data);
            } else {
                layer.msg(json.msg);
            }
        },
        error: function () {
        	layer.close(loadding);
            layer.msg('网络错误！');
        }
    });
}

function articlelike(id, obj) {
	var loadding = layer.load(0, {shade: false});
    $.ajax({
        url: "/api/article-like",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.code == 1) {
            	layer.msg(json.msg);
            	$(obj).children(".num").text(json.data);
            } else {
                layer.msg(json.msg);
            }
        },
        error: function () {
        	layer.close(loadding);
            layer.msg('网络错误！');
        }
    });
}

function collect(id, obj) {
	var loadding = layer.load(0, {shade: false});
    $.ajax({
        url: "/api/article-collect",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.code == 1) {
            	layer.msg(json.msg);
            	$(obj).children(".num").text(json.data);
            } else {
                layer.msg(json.msg);
            }
        },
        error: function () {
        	layer.close(loadding);
            layer.msg('网络错误！');
        }
    });
}

/************/
function getKnowledge(type){
    var loadding = layer.load(0, {shade: false});
    $.ajax({
        url: "/home/get-knowledge",
        type: "POST",
        data: {
            page: page,
            country: '',
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
            layer.close(loadding);
            if (json.code == 1) {
                var html = '';
                $.each(json.data, function (key, val) {
                    html += '<li class="overseas overseas_'+ val.id +'">';
                    html += '<div class="overseas_title">';
                    html += '<a href="/user/'+ val.add_user_id+'"><img src="'+ val.user_thumb +'" alt="'+ val.add_user_name +'"/></a>';
                    html += '<a href="/user/'+ val.add_user_id+'"><h4>'+ val.add_user_name +'</h4></a>';
                    html += '<span class="times">'+ val.post_time_m +'</span>';
                    html += '</div>';
                    html += '<div class="overseas_content">';
                    html += '<p><a href="/knowledge/'+val.id+'">'+ val.content +'</a></p>';
                    html += '</div>';
                    html += '<div class="overseas_img viewImg">';
                    $.each(val.imgs, function (k, v) {
                        html += '<a href="'+ v.img_path +'"><img src="'+ v.img_path +'" alt="海外观察"></a>';
                    })
                    html += '</div>';
                    html += '<div>';
                    html += '<ul class="nav nav-tabs fl">';
                    html += '<li>';
                    html += '<a href="/knowledge/'+val.id+'"><i class="fa fa-comment-o"></i> <span> '+ val.comment_num +' </span></a>';
                    html += '</li>';
                    html += '<li>';
                    html += '<a href="javascript:void(0)" onclick="collectKnowledge('+ val.id +', this)"><i class="fa fa-heart-o"></i> <span class="num"> '+ val.collect_num +' </span></a>';
                    html += '</li>';
                    html += '<li>';
                    html += '<a href="javascript:void(0)" onclick="likeKnowledge('+ val.id +', this)"><i class="fa fa-thumbs-o-up"></i> <span class="num"> '+ val.like_num +' </span></a>';
                    html += '</li>';
                    html += '</ul>';
                    html += '</div>';
                    html += '</li>';
                });
                if(type == "change"){
                    $(".home-main").html(html);
                }else{
                    $(".home-main").append(html);
                    $(".more").text("阅读更多");
                }
                $(".viewImg").each(function(){
                    $(this).find("a").touchTouch();
                });
                var totalPages = Math.ceil(json.count/pageSize) || 1;
                if(totalPages <= page){
                    $(".more").text("没有更多了...");
                    $(".more").removeAttr("onclick");
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

//知识分享收藏
function collectKnowledge(id, obj) {
    var loadding = layer.load(0, {shade: false});
    $.ajax({
        url: "/api/knowledge-collect",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
            layer.close(loadding);
            if (json.code == 1) {
                layer.msg(json.msg);
                $(obj).children(".num").text(json.data);
            }else if(json.code == 2){
                window.location.href = '/login';
            } else {
                layer.msg(json.msg);
            }
        },
        error: function () {
            layer.close(loadding);
            layer.msg('网络错误！');
        }
    });
}

//知识分享点赞
function likeKnowledge(id, obj){
    var loadding = layer.load(0, {shade: false});
    $.ajax({
        url: "/api/knowledge-like",
        type: "POST",
        data: {
            id: id,
            _csrf:$('.getCsrfTokenJzc').val(),
        },
        dataType: "json",
        success: function (json) {
            layer.close(loadding);
            if (json.code == 1) {
                layer.msg(json.msg);
                $(obj).children(".num").text(json.data);
            } else {
                layer.msg(json.msg);
            }
        },
        error: function () {
            layer.close(loadding);
            layer.msg('网络错误！');
        }
    });
}
/*************/