// var pageSize = 10;
var page = 1;
// var category = '';
// var country = '';
// var type = 'article';

$(function () {
    //分页
    if (document.getElementById("laypage_search")) {
        laypage({
            cont: 'laypage_search',
            pages: Math.ceil($("#count").val() / 10),
            curr: $("#thisPage").val(),
            skin: '#fc9d27',
            jump: function (obj, first) {
                if (!first) {
                    submitSearch_site(obj.curr);
                }
            }
        });
    }

    $('.searth_list_type_jzc').click(function () {
        var params = '';
        //关键词
        var keyword = $("#keyword_search").val();
        var select_type = $(this).attr('data-type');
        if (keyword == '') {
            layer.msg('温馨提示：搜索关键词不能为空！', {icon: 2});
            return false;
        }
        layer.load();
        var home_url = $('#home_url').val();
        if (keyword != '') {
            params += 'keyword=' + keyword + '&' + 'select_type=' + select_type + '&';
        }

        //分页
        if (page > 1) {
            params += 'page=' + page + '&';
        }
        //去除最后一个&
        if (params != '') {
            params = '?' + params.substr(0, params.length - 1);
        }
        //console.log(params);return;
        window.location.href = home_url + '/home/search' + params;

    })

});

//全站搜索
function submitSearch123(page) {
    //关键词
    var keyword = $("#keyword").val();
    if (keyword == '') {
        layer.msg('温馨提示：搜索关键词不能为空！', {icon: 2});
        return false;
    }
    var loadding = layer.load();
    $.ajax({
        url: '/index/search_api/index',
        data: {'keyword': keyword},
        dataType: 'json',
        type: 'post',
        success: function (res) {
            if (res.code == 1) {
                layer.close(loadding);
                console.log(res);
                window.location.href = '/index/home/search/' + keyword;
            } else {

            }
        }
    })
}

$('#fangdajing').click(function () {
    submitSearch_site();
})

$("#keyword_search").keydown(function (e) {
    if (e.keyCode == 13) {
        submitSearch_site();
        //window.location.href = '/home/search/'+$(this).val();
    }
});

function submitSearch_site(page) {
    var params = '';
    //关键词
    var keyword = $("#keyword_search").val();
    var select_type = $('.quanzhan_select').val();

    if (keyword == '') {
        layer.msg('温馨提示：搜索关键词不能为空！', {icon: 2});
        return false;
    }
    layer.load();
    var home_url = $('#home_url').val();
    if (keyword != '') {
        params += 'keyword=' + keyword + '&' + 'select_type=' + select_type + '&';
    }

    //分页
    if (page > 1) {
        params += 'page=' + page + '&';
    }
    //去除最后一个&
    if (params != '') {
        params = '?' + params.substr(0, params.length - 1);
    }
    //console.log(params);return;
    window.location.href = home_url + '/home/search' + params;
}


// ------------------ 2020-8-10 --------------------

var Page = 1; // 从第2页开始加载
$('.load-more-article').click(function () {
    var loadding = layer.load();
    var keyword = $(this).data('keyword');
    var selectType = $(this).data('type');
    if (selectType == 1) { // 资讯页从第3页开始加载更多
        Page++;
    }

    $.get('/index/home_api/searchArticle?keyword=' + keyword + '&select_type=' + selectType + '&page=' + Page, function (res) {
        if (res.data.article.length == 0) {
            $('.load-more-article').html('数据已全部加载').css({
                'pointer-events': 'none',
                "color": "#999"
            });
            return false;
        }

        var article_list = '';
        $.each(res.data['article'], function (i, v) {
            article_list += '<div class="article-box layui-row">' +
                '<div class="article-img layui-inline">' +
                '   <a href="' + v['url'] + '">' +
                '       <img src="' + v['logo'] + '" alt="' + v['title'] + '">' +
                '   </a>' +
                '</div>' +
                '<div class="article-info layui-inline">' +
                '    <p class="article-title"><a href="' + v['url'] + '">' + v['title'] + '</a></p>' +
                '    <p class="article-author">' +
                '        <span>' + v['add_user_name'] + '</span>' +
                '        <span style="display: inline-block;margin-left: 30px;color: #888">发布于 : ' + v['add_time'] + '</span>' +
                '    </p>' +
                '    <p class="article-desc">' + v['desc'] + '</p>' +
                '    <p class="article-tags">';
            if (v['extra_info']) {
                $.each(v['extra_info'], function (k, t) {
                    article_list += '<span><a href=\"/index/keyword/article/id/' + t['id'] + '\" style="color: #999">' + t['name'] + '</a></span>';
                });
            }
            article_list +=
                '        </p>' +
                '    </div>' +
                '</div>';
        });
        $('.load-more-article').before(article_list);
    });
    layer.close(loadding);
    Page++;

    return false;
});


