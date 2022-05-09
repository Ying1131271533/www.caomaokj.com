var pageSize = 20;
var page = 1;
var category = 0;
var country = '';
var type = 'article';
var categoryLabels = {
  "68":"平台运营",
  "70":"跨境物流",
  "59":"热点资讯",
  "69":"政策解读",
  "60":"行业研究院",
  "71":"跨境",
  "62":"管理干货",
  "61":"海外观察",
}

$(function () {
	// 文章类型
	$(".article_type").click(function(){
		$(this).addClass('current_select_type').siblings().removeClass('current_select_type');
		$(".lev_curr").removeClass("lev_curr");
		$(this).addClass("lev_curr");
		category = $(this).attr("category");
		page = 1;
		getArticle("change");
		type = 'article';
		$('.ganhuo_zilei').show();
		$('.ganhuo_title').text('干货 >');
		var text_title = $(this).find('.levtxt').text();
		$('.ganhuo_zilei').text(text_title);
	});

	// 海外观察
	$(".overseas").click(function(){
		$(".lev_curr").removeClass("lev_curr");
		$(this).addClass("lev_curr");
		country = $(this).attr("country");
		page = 1;
		getOverseas("change");
		type = 'overseas';
	});

	$(window).scroll(function () {
	    var scrollTop = $(this).scrollTop();
	    var scrollHeight = $(document).height();
	    var windowHeight = $(this).height();
	    if (scrollTop + windowHeight == scrollHeight ) {
	    	//getMoreArticle();
	    }
	});

});


// 加载更多首页文章
function getMoreArticle(){
	page++;
	$("#index_more").text("正在加载...");
	if(type == 'overseas'){
		getOverseas("more");
	}else{
		getArticle("more");
	}
}


function getArticle(type){
	var loadding = layer.load();
	$.ajax({
        url: "/index/home_api/index",
        type: "POST",
        data: {
        	page: page,
            category: category,
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.code == 200) {
				categoryLabels = json.data.articleCategory || categoryLabels;
            	var html = '';
            	$.each(json.data.articleData, function (key, val) {
					
                    html += '<li class="article_li">';
            		html += '<dl class="wz_info clearfix">';
            		if(val.al_type == 1){
        				var url = "/index/activity/detail/id/" + val.activity_id;
        			}else{
        				var url = "/index/home/article/id/" + val.al_id;
        			}
            		if(val.al_thumb != ''){
						html += '<dt>';
						html += '<span class="labelBg'+val.ac_id+' categoryLabel" >'+categoryLabels[val.ac_id]+'</span>';
                		html += '<a title="'+ val.al_title +'" href="' + url + '">';
                		html += '<img class="lazy" src="'+ val.al_thumb +'" style="display: inline;">';
                		html += '</a>';
                		html += '</dt>';
                		html += '<dd>';
            		}
            		html += '<h4>';
                    if(val.al_is_hot_article && val.al_is_hot_article == 1){
                        html += '<span class="hot_title" style="margin-right: 5px;">热门</span>';
                    }
            		html += '<a href="' + url + '" title="'+ val.al_title +'">' + val.al_title + '</a>';
            		html += '</h4>';
                    html += '<div class="bot_tips">';
                    html += '<span class="color-a font12">';
                    html += '<a  href="/index/article/'+ val.al_id +'" class="pr15 a-small">'+ val.add_name +'</a>';
                    html += '<span class="fr pr10">';
                    if(val.al_type == 1){
                        html += '<span>活动</span>';
                    }
                    html += '</span>';
                    html += '</span>';

                    html += '</div>';
            		html += '<a href="' + url + '" title="'+ val.al_title +'"><p>' + val.al_desc + '</p></a>';
					html += '<div class="tags_div_jzc tags_and_time">';
					html += '<div class="tagItems">';

                    if((val.keywords != '')){
                        $.each(val.keywords, function (k,v) {
                            html += '<span class="keyword_span"><a href="/index/keyword/article/id/'+ v.keyword_id +'">'+ v.keyword_name +'</a> </span></span>';
                        })
					}
					   html += '</div>';
					html += '<div class="publish_time">';
				html+='<img class="publish_time-img" src="/static/akali/images/6b13cde4eec0be19.png" title="发布时间.png" alt="发布时间.png"/>';
				    html+= '<span>'+val.al_post_time+'<span>';
				    html += '</div>';
                    html += '</div>';

            		if(val.al_thumb != ''){
            			html += '</dd>';
            		}
            		html += '</dl>';
            		html += '</li>';
            	});
            	if(type == "change"){
            		$(".article_body").html(html);
            	}else{
            		$(".article_body").append(html);
            	}
            	$("#index_more").text("加载更多");
            	$("#index_more").attr("onclick","getMoreArticle();");
            	var totalPages = Math.ceil(json.data.count/pageSize) || 1;
            	if(totalPages <= page){
            		$("#index_more").text("没有更多了...");
            		$("#index_more").removeAttr("onclick");
            	}
                var document_height = document.body.clientHeight-390;
                $('.right_bottom_div').css({'top':document_height});
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
	$('.ganhuo_zilei').hide();
	$('.ganhuo_title').text('海外观察');
	var loadding = layer.load(0, {shade: false});
	$.ajax({
        url: "/home/get-overseas",
        type: "POST",
        data: {
        	page: page,
        	country: country,
        },
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
            if (json.code == 200) {
            	var html = '';
            	$.each(json.articleData, function (key, val) {
            		html += '<li class="media overseas_'+ val.id +'">';
            		html += '<div class="media-left">';
            		html += '<a href="/index/article/'+ val.add_user_id +'">';
            		html += '<img class="media-object" src="'+ val.user_thumb +'" alt="'+ val.add_user_name +'">';
            		html += '</a>';
            		html += '</div>';
            		html += '<div class="media-body">';
            		html += '<h4 class="media-heading"><a class="overseas-user" href="/index/article/'+ val.add_user_id +'">'+ val.add_user_name +'</a></h4>';
            		html += '<p><a style="color: #1b1b1b;font-size: 14px;" href="/talk/'+val.id+'">'+ val.content +'</a></p>';
                    html += '<div class="viewImg">';
                    $.each(val.imgs, function (k, v) {
                    	html += '<a href="'+ v.img_path +'"><img src="'+ v.img_path +'" alt="海外观察" layer-index="'+ k +'"></a>';
                    })
                    html += '</div>';
                    html += '<ul class="nav navbar-nav navbar-left">';
                    html += '<li><span class="navbar-time">'+ val.post_time +'</span></li>';
                    html += '</ul>';
                    html += '<ul class="nav navbar-nav navbar-right">';
                    html += '<li><a href="javascript:void(0)" title="评论" onclick="commentOverseas('+ val.id +')"><i class="fa fa-comment-o"></i><span> <span class="com_num_'+val.id+'">'+ val.comment_num +'</span></a></li>';
                    html += '<li><a href="javascript:void(0)" title="点赞" onclick="likeOverseas('+ val.id +')"><i class="fa fa-thumbs-o-up"></i> <span class="like_num_'+val.id+'">'+ val.like_num +'</span></a></li>';
                    html += '</ul>';
                    html += '</div>';
                    html += '</li>';
            	});
            	if(type == "change"){
            		$(".article_body").html(html);
            	}else{
            		$(".article_body").append(html);
            	}
            	$("#index_more").text("点击加载更多");
            	$("#index_more").attr("onclick","getMoreArticle();");
            	$(".viewImg").each(function(){
                    $(this).find("a").touchTouch();
                });
            	var totalPages = Math.ceil(json.data.count/pageSize) || 1;
            	if(totalPages <= page){
            		$("#index_more").text("没有更多了...");
            		$("#index_more").removeAttr("onclick");
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

//海外观察评论
function commentOverseas(id){
	$(".comment_div").remove();
	$(".comment_user").remove();
	var html = '';
    html += '<div class="input-group comment_div">';
    html += '<input type="text" class="form-control comment_overseas" placeholder="评论(Ctrl+Enter)"> <span class="input-group-btn"> <button type="button" onclick="sendOverseasComment('+ id +', this)" class="btn">评论</button> </span></div>';
    $(".overseas_"+id).append(html);
    $.ajax({
        url: "/api/get-overseas-comment",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function (json) {
            if (json.code == 200) {
            	var html = '';
            	$.each(json.articleData, function (key, val) {
            		html += '<div class="comment_user"><a target="_blank" href="/index/article/'+ val.user_id +'">'+ val.user_name +'</a>:'+ val.oc_comment +'</div>';
            	});
            	$(".overseas_"+id).append(html);
            } else {
            }
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });

}

//发送海外观察评论
function sendOverseasComment(id){
	var comment = $(".comment_overseas").val();
	if(comment == ''){
		layer.msg('评论内容不能为空');
		return;
	}
	if(id == ''){
		layer.msg('inner error.');
		return;
	}
	$.ajax({
        url: "/api/overseas-comment",
        type: "POST",
        data: {comment: comment, id: id},
        dataType: "json",
        success: function (json) {
        	layer.msg(json.msg);
            if (json.code == 200) {
            	$(".com_num_"+id).text(json.data);
            	commentOverseas(id);
            }else if(json.code == 2){
            	window.location.href = '/login';
            }
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });
}

//海外观察点赞
function likeOverseas(id){
  var loadding = layer.load(0, {shade: false});
  $.ajax({
        url: "/api/overseas-like",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function (json) {
        	layer.close(loadding);
        	layer.msg(json.msg);
            if (json.code == 200) {
            	$(".like_num_"+id).text(json.data);
            }else if(json.code == 2){
            	window.location.href = '/login';
            }
        },
        error: function () {
        	layer.close(loadding);
            layer.msg('网络错误！');
        }
    });
}

function articlelike(id, obj) {
    $.ajax({
        url: "/api/article-like",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function (json) {
        	layer.msg(json.msg);
            if (json.code == 200) {
            	$(obj).children(".num").text(json.data);
            }else if(json.code == 2){
            	window.location.href = '/login';
            }
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });
}

function collect(id, obj) {
    $.ajax({
        url: "/api/article-collect",
        type: "POST",
        data: {id: id},
        dataType: "json",
        success: function (json) {
        	layer.msg(json.msg);
            if (json.code == 200) {
            	$(obj).children(".num").text(json.data);
            }else if(json.code == 2){
            	window.location.href = '/login';
            }
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });
}