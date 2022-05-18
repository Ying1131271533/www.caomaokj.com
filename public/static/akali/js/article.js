$(function () {
    layer.photos({
        photos: '.article-content'
        , anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
    });

    getArticleComment();

    $(document).on("click", ".comment", function () {
        var id = $(this).attr("data-id");
        var user_id = $('#user_id').val();
        if (user_id > 0) {
            $(".comment_input_" + id).toggle();
        }
    });

});

// 评论/回复
function comment(id, acid) {
    var pid = 0;
    var comment = '';
    if (acid) {
        pid = acid;
        comment = $("#comment_" + acid).val();
    } else {
        comment = $("#comment").val();
    }
    if (comment == '') {
        layer.msg('评论内容不能为空');
        return;
    }
    var loading = layer.load();
    $.ajax({
        url: "/index/home_api/articleComment",
        type: "POST",
        data: {
            comment: comment,
            id: id,
            pid: pid
        },
        dataType: "json",
        success: function (json) {
            layer.close(loading);
            if (json.code == 200) {
                layer.msg(json.msg, { icon: 1 });
                window.location.reload();
            } else if (json.code == 300) {
                window.location.href = '/index/login/index';
                layer.msg(json.msg, { icon: 0 });
            } else {
                layer.msg(json.msg, { icon: 2 });
            }
        },
        error: function () {
            layer.close(loading);
            layer.msg('网络错误！', {
                icon: 2
            });
        }
    });
}

$('.comment_stories_list').hide();
function getArticleComment() {
    var id = $("#al_id").val();
    if (id == '') {
        layer.msg('inner error.');
        return;
    }
    /*//<div class="mod-footer">\
    <div class="meta">\
    <span class="pull-right text-color-999">'+ val.acom_add_time +'</span>\
    <a>举报</a>\
    <a>回复</a>\
</div>\*/
    $.ajax({
        url: "/index/home_api/getArticleComment",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (json) {
            if (json.code == 200) {
                $('.comment_stories_list').show();
                var html = '';
                //刷新评论内容
                $.each(json.data.comments, function (key, val) {
                    html += '<li class="clearfix">';
                    html += '<div class="Avatar fl">';
                    html += '<img src="' + val.user_head + '">';
                    html += '</div>';
                    html += '<div class="fr stories_con">';
                    if (val.parent.user_id) {
                        html += '<div class="blockquote_wrap">';
                        html += '<a target="_blank" href="/user/' + val.parent.user_id + '">' + val.parent.user_name + '</a> : ' + val.parent.acom_comment;
                        html += '</div>';
                    }
                    html += '<div class="comment_subt">' + val.acom_comment + '</div>';
                    html += '<div class="clearfix tools">';
                    html += '<div class="fl">';
                    html += '<div class="name fl mr30">';
                    html += '<a href="/user/' + val.user_id + '">' + val.user_name + '</a>';
                    html += '</div>';
                    html += '<div class="time fl">';
                    html += '发布于' + val.time;
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="fr tools2">';
                    html += '<div class="comment" data-id="' + val.acom_id + '">回复</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="comment_input comment_input_' + val.acom_id + '" style="display: none;">';
                    html += '<input type="text" id="comment_' + val.acom_id + '" placeholder="回复　' + val.user_name + '：" class="c_input">';
                    html += '<button class="Reply_btn" onclick="comment(' + val.al_id + ',' + val.acom_id + ')">回复</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</li>';

                });

            } else {
                $('.comment_stories_list').hide();
                //html = '<li class="clearfix">暂无评论，来说点什么吧</li>';
            }
            $(".stories_list").html(html);
            $(".al_comment_num").text(json.data.count);
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });
}

//去评论
function goComment() {
    $("#comment").focus();
}

// 点赞
function articlelike(id) {
    $.ajax({
        url: "/index/home_api/articleLike",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (json) {
            layer.msg(json.msg);
            if (json.code == 200) {
                $(".al_like_num").text(json.data);
            } else if (json.code == 204) {
                window.location.href = '/index/login/index';
            }
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });
}

// 收藏
function collect(id) {
    $.ajax({
        url: "/index/home_api/articleCollect",
        type: "POST",
        data: { id: id },
        dataType: "json",
        success: function (json) {
            layer.msg(json.msg);
            if (json.code == 200) {
                $(".al_collect_num").text(json.data);
            } else if (json.code == 204) {
                window.location.href = '/index/login/index';
            }
        },
        error: function () {
            layer.msg('网络错误！');
        }
    });
}

var pageSize = 10;
var page = 1;
var category = '';
var country = '';
var type = 'article';

$(function () {
    //文章类型
    $(".article_type").click(function () {
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

    //海外观察
    $(".overseas").click(function () {
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
        if (scrollTop + windowHeight == scrollHeight) {
            //getMoreArticle();
        }
    });

});



//加载更多首页文章
function getMoreArticle() {
    page++;
    $("#index_more").text("正在加载...");
    if (type == 'overseas') {
        getOverseas("more");
    } else {
        getArticle("more");
    }
}

function getArticle(type) {
    var loadding = layer.load();
    $.ajax({
        url: "/index/home_api/article",
        type: "POST",
        data: {
            page: page,
            category: category,
        },
        dataType: "json",
        success: function (json) {
            layer.close(loadding);
            if (json.code == 200) {
                var html = '';
                $.each(json.data, function (key, val) {
                    html += '<li>';
                    html += '<dl class="wz_info clearfix">';
                    if (val.al_type == 1) {
                        var url = "/activity/" + val.activity_id;
                    } else {
                        var url = "/article/" + val.al_id;
                    }
                    if (val.al_thumb != '') {
                        html += '<dt>';
                        html += '<a title="' + val.al_title + '" href="' + url + '">';
                        html += '<img class="lazy" data-original="' + val.al_thumb + '" style="display: inline;">';
                        html += '</a>';
                        html += '</dt>';
                        html += '<dd>';
                    }
                    html += '<h4>';
                    html += '<a href="' + url + '" title="' + val.al_title + '">' + val.al_title + '</a>';
                    html += '</h4>';
                    html += '<a href="' + url + '" title="' + val.al_title + '"><p>' + val.al_desc + '</p></a>';
                    html += '<div class="tags_div_jzc">';
                    if ((val.keywords != '')) {
                        $.each(val.keywords, function (k, v) {
                            html += '<span class="keyword_span"><a href="/keyword/' + v.keyword_id + '">' + v.keyword_name + '</a> </span></span>';
                        })
                    }
                    html += '</div>';
                    html += '<div class="bot_tips">';
                    html += '<span class="color-a font12">';
                    html += '<a  href="/user/' + val.al_add_user_id + '" class="pr15 a-small">' + val.add_name + '</a>' + val.al_post_time;
                    html += '<span class="fr pr10">';
                    if (val.al_type == 1) {
                        html += '<span>活动</span>';
                    }
                    html += '</span>';
                    html += '</span>';

                    html += '</div>';
                    if (val.al_thumb != '') {
                        html += '</dd>';
                    }
                    html += '</dl>';
                    html += '</li>';
                });
                if (type == "change") {
                    $(".article_body").html(html);
                } else {
                    $(".article_body").append(html);
                }
                $("#index_more").text("点击加载更多");
                $("#index_more").attr("onclick", "getMoreArticle();");
                var totalPages = Math.ceil(json.count / pageSize) || 1;
                if (totalPages <= page) {
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





