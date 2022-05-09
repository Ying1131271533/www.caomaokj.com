// alert(location.href.split('#')[0]);

// 微信配置参数
var appid = $('#appid').val();
var timestamp = $('#timestamp').val();
var nonceStr = $('#nonceStr').val();
var signature = $('#signature').val();

// 自定义分享// alert(appid);
// alert(timestamp);
// alert(nonceStr);
// alert(signature);

var link = $('#link').val();
var imgUrl = $('#imgUrl').val();
var title = $('#title').val();
var desc = $('#desc').val();

// 配置
wx.config({
    // debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: appid, // 必填，公众号的唯一标识
    timestamp: timestamp, // 必填，生成签名的时间戳
    nonceStr: nonceStr, // 必填，生成签名的随机串
    signature: signature,// 必填，签名
    jsApiList: [
        'JSAPI',
        'checkJsApi',
        
        'onMenuShareAppMessage', // 分享给朋友
        'onMenuShareTimeline', // 分享到朋友圈

        'updateAppMessageShareData', // 自定义“分享给朋友”及“分享到QQ”按钮的分享内容
        'updateTimelineShareData', // 自定义“分享到朋友圈”及“分享到QQ空间”按钮的分享内容（1.4.0）
        
    ], // 必填，需要使用的JS接口列表
    success: function(res) {
    // 以键值对的形式返回，可用的api值true，不可用为false
    // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
    }
});

wx.ready(function () {   // 需在用户可能点击分享按钮前就先调用
    // 判断当前客户端版本是否支持指定JS接口
    /* wx.checkJsApi({
        jsApiList: [
            'updateAppMessageShareData',
            'updateTimelineShareData',
            'onMenuShareAppMessage',
            'onMenuShareTimeline'
        ], // 需要检测的JS接口列表，所有JS接口列表见附录2,
        success: function(res) {
            // 以键值对的形式返回，可用的api值true，不可用为false
            // console.log(res);
            // alert(link);
            // alert(title);
            // alert(desc);
            alert('配置成功');
        },
        fail: function(res) {
            // 以键值对的形式返回，可用的api值true，不可用为false
            console.log(res);
            alert('配置失败');
        },
    }); */

    // 分享给朋友
    if (wx.updateAppMessageShareData) {
        wx.updateAppMessageShareData({
            // title: '阿卡丽', // 分享标题
            desc: desc, // 分享描述
            link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: imgUrl, // 分享图标
            success: function () {
                layer.msg('分享成功');
            }
        });
      } else {
        wx.onMenuShareAppMessage({
            // title: '阿卡丽', // 分享标题
            desc: desc, // 分享描述
            link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: imgUrl, // 分享图标
            success: function () {
                layer.msg('分享成功');
            }
        });
      }

      // 分享给朋友圈
      if (wx.updateTimelineShareData) {
        wx.updateTimelineShareData({
            // title: '阿卡丽', // 分享标题
            desc: '阿卡丽', // 分享描述
            link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: imgUrl, // 分享图标
            success: function () {
                layer.msg('分享成功');
            }
        });
      } else {
        wx.onMenuShareTimeline({
            // title: '阿卡丽', // 分享标题
            desc: '阿卡丽', // 分享描述
            link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: imgUrl, // 分享图标
            success: function () {
                layer.msg('分享成功');
            }
        });
      }

});

// 返回错误信息
wx.error(function(res){
    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
    // alert(res);
    console.log(res);
});