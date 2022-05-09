/*
 * http://api.map.baidu.com/lbsapi/getpoint/index.html
 * 百度，经纬度查询地址
 */

//创建和初始化地图函数：
/*var point_Longitude = $("#address_Longitude").val();
var point_Latitude = $("#address_Latitude").val();
if (point_Longitude) {
    function initMap() {
        createMap();// 创建地图
        setMapEvent();// 设置地图事件
    }

    function createMap() {
        map = new BMap.Map("map");
        // 此处使用的是经纬度定位
        map.centerAndZoom(new BMap.Point(point_Longitude, point_Latitude), 12);
    }

    function setMapEvent() {
        map.enableScrollWheelZoom();
        map.enableKeyboard();
        map.enableDragging();
        map.enableDoubleClickZoom()
    }

    var map;
    initMap();

    var point = new BMap.Point(point_Longitude, point_Latitude);//默认  可以通过Icon类来指定自定义图标
    var marker = new BMap.Marker(point);
    map.addOverlay(marker);
}*/

$(function () {
    $('.key_list_box .key_nav li').hover(function () {
        showRight($(this));
        $('.country-more-box .actionLineSarch').removeClass('orange_color');
        $('.warehouse_country>.actionLineSarch').removeClass('orange_color');
        $('.warehouse_country>.actionLineSarch').eq(0).addClass('orange_color');
    });

    function showRight(dom) {

        $('.key_list_box .key_list_con > div').eq(dom.index()).addClass('showIn').siblings().removeClass('showIn');

        dom.find('a').addClass('on').parent().siblings().find('a').removeClass('on');
    }

    $('.unfold').click(function () {
        if ($('.unfold i').hasClass('fa-angle-down')) {
            $('.banner-about').addClass('banner-auto');
            $('.unfold span').html('收起');
            $('.unfold i').removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            $('.banner-about').removeClass('banner-auto');
            $('.unfold span').html('展开');
            $('.unfold i').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });

    $('.unfold-post').click(function () {
        if ($('.unfold-post i').hasClass('fa-angle-down')) {
            $('.best .banner-about').addClass('banner-auto');
            $('.unfold-post span').html('收起');
            $('.unfold-post i').removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            $('.best .banner-about').removeClass('banner-auto');
            $('.unfold-post span').html('展开');
            $('.unfold-post i').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });
});

//注册DHL电子商务账号
function dhlReg() {
    var loading = layer.load();
    $.ajax({
        url: "/dhl-ecommerce-apply/apply-status",
        type: "GET",
        dataType: "json",
        success: function (json) {
            layer.close(loading);
            if (json.code == 200) {
                setTimeout(function () {
                    window.location.href = '/dhl-ecommerce-apply/account-open';
                }, 500);
            } else if (json.code == 2) {
                layer.msg("请先登录，正在跳转页面...");
                window.location.href = json.data;
            } else {
                layer.msg(json.msg, {"icon": 2});
            }
        },
        error: function () {
            layer.close(loading);
            layer.msg('网络错误', {"icon": 2, "time": 1000});
        }
    });
}

$(".view_service_phone").click(function(){
    var obj = $(this);
    var serviceId = $(this).attr("data-id");
    $.ajax({
        url : "/api/view-phone",
        type : "POST",
        data : {
            serviceId : serviceId
        },
        dataType : "json",
        success : function(json) {
            if(json.code){
                $(".service_phone_"+serviceId).text(json.data);
                obj.remove();
            }else{
                layer.msg(json.msg, {icon:2})
            }
        },
        error : function() {
            layer.msg("网络错误", {icon:2})
        }
    });
});

