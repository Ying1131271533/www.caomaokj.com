$(function () {
    //国家点击【更多】或【收起】
    $('#c .button').on('click', function () {
        $('#c .list-area .more-country-area').height($("#c .list-area .more-country-area").height() < 90 ? 90 : $("#c .list-area").height())
        if ($(this).hasClass('more')) {
            $(this).hide();
            $('#c .less').show();
            $('#less-country').removeClass('show');
            $('#more-country').addClass('show');
        }
        if ($(this).hasClass('less')) {
            $(this).hide();
            $('#c .more').show();
            $('#more-country').removeClass('show');
            $('#less-country').addClass('show');
        }
    });

    //大洲鼠标移动事件
    $('.continent').hover(function () {
        var c_id = $(this).attr('data-id');
        if (c_id == 0) {
            $('.coun').show();
        } else {
            $('.coun').hide();
            $('.country-' + c_id).show();
        }
        $('.continent').removeClass('selected');
        $(this).addClass('selected');

    });
    //服务类型点击【更多】或【收起】
    $('#s .button').on('click', function () {
        $('#s .list-area .more-service-area').height($("#s .list-area .more-service-area").height() < 90 ? 90 : $("#s .list-area").height())
        if ($(this).hasClass('more')) {
            $(this).hide();
            $('#s .less').show();
            $('#less-service').removeClass('show');
            $('#more-service').addClass('show');
        }
        if ($(this).hasClass('less')) {
            $(this).hide();
            $('#s .more').show();
            $('#more-service').removeClass('show');
            $('#less-service').addClass('show');
        }
    });

    //分页
    if (document.getElementById("laypage")) {
        laypage({
            cont: 'laypage',
            pages: Math.ceil($("#count").val() / 12),
            curr: $("#thisPage").val(),
            skin: '#fc9d27',
            jump: function (obj, first) {
                if (!first) {
                    submitSearch(obj.curr);
                }
            }
        });
    }

    //条件筛选
    $(".select-list .search").click(function () {
        $(this).parents(".select-list").find(".selected").removeClass("selected");
        $(this).addClass("selected");
        submitSearch();
    });

    //更多筛选
    $(".select-list .more-search").click(function () {
        $(this).parents(".more-2").find(".selected").removeClass("selected");
        $(this).addClass("selected");
        submitSearch();
    });
    //排序
    $(".header-sort dd").click(function () {
        $(this).addClass("selected").siblings().removeClass("selected");
        submitSearch();
    });

    $(".btn-search").click(function () {
        submitSearch();
    });

    $("#keyword").keydown(function (e) {
        if (e.keyCode == 13) {
            submitSearch();
        }
    });

    $(".view_service_email").click(function () {
        var obj = $(this);
        var serviceId = $(this).attr("data-id");
        $.ajax({
            url: "/api/view-email",
            type: "POST",
            data: {
                serviceId: serviceId
            },
            dataType: "json",
            success: function (json) {
                if (json.code) {
                    $(".service_email_" + serviceId).text(json.data);
                    obj.remove();
                } else {
                    layer.msg(json.msg, {icon: 2})
                }
            },
            error: function () {
                layer.msg("网络错误", {icon: 2})
            }
        });
    });

    $(".view_service_phone").click(function () {
        var obj = $(this);
        var serviceId = $(this).attr("data-id");
        $.ajax({
            url: "/api/view-phone",
            type: "POST",
            data: {
                serviceId: serviceId
            },
            dataType: "json",
            success: function (json) {
                if (json.code) {
                    $(".service_phone_" + serviceId).text(json.data);
                    obj.remove();
                } else {
                    layer.msg(json.msg, {icon: 2})
                }
            },
            error: function () {
                layer.msg("网络错误", {icon: 2})
            }
        });
    });

});

function submitSearch(page) {
    layer.load();
    var params = '';
    //关键词
    var keyword = $("#keyword").val();
    if (keyword != '') {
        params += 'keyword=' + keyword + '&';
    }
    var c_flag = 0;
    var s_flag = 0;
    //在更多国家里筛选
    if ($('#c .more-info').is(":visible")) {
        $("#c .more-1").each(function () {
            var name = $(this).attr("id");
            var val = $("#" + name).find(".selected").attr("data-id");
            if (val != '0') {
                params += name + '=' + val + '&';
            }
        });
        $("#c .more-2").each(function () {
            var name = $(this).attr("id");
            var val = $("#" + name).find(".selected").attr("data-id");
            if (val != '0') {
                params += name + '=' + val + '&';
            } else {
                c_flag = 1;
            }
        });
    }
    //在更多服务类型里搜索
    if ($('#s .more-info').is(":visible")) {
        $("#s .more-2").each(function () {
            var name = $(this).attr("id");
            var val = $("#" + name).find(".selected").attr("data-id");
            if (val != '0') {
                params += name + '=' + val + '&';
            } else {
                s_flag = 1;
            }
        });
    }
    //筛选条件
    $(".select li").each(function () {
        var name = $(this).attr("id");
        var val = $("#" + name).find(".selected").attr("data-id");
        if (val != '0') {
            if (name == 'c' && c_flag == 1) {
                return true;
            }
            if (name == 's' && s_flag == 1) {
                return true;
            }
            params += name + '=' + val + '&';
        }
    });

    //排序
    var type = $('.data-body #type').val();
    var sort = $('.data-body #sort').val();
    var content_type = $('.data-body #content_type').val();

    params += 'type=' + type + '&sort=' + sort + '&content_type=' + content_type + '&';
    //分页
    if (page > 1) {
        params += 'page=' + page + '&';
    }
    //去除最后一个&
    if (params != '') {
        params = '?' + params.substr(0, params.length - 1);
    }

    window.location.href = '/warehouse' + params;
}

// 右侧边栏 随鼠标滚动固定位置不变 20171607
function getScrollTop() {
    var scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
    return scrollTop;
}

var offsetTop = $('#right-sidebar').offset().top;
$(window).on('scroll', function () {
    var height = getScrollTop()
    var top = height > offsetTop ? (height - offsetTop + 100) : 0;
    if (top > $('.main-list-group .left').height() - $('.main-list-group .right').height()) {
        top = $('.main-list-group .left').height() - $('.main-list-group .right').height() + 40
    }
    if (top < 0) {
        top = 0;
    }
    document.querySelector('#right-sidebar').style.top = top + 'px';
})
