
function shareTo(stype){
    var ftit = '';
    var flink = '';
    var lk = '';
    //获取文章标题
    ftit = document.title;
    //获取网页中内容的第一张图片地址作为分享图
    flink = document.images[2].src;
    if(typeof flink == 'undefined'){
        flink='';
    }
    // 默认
    lk = flink;
    //当内容中没有图片时，设置分享图片为网站logo
    if(flink == ''){
        lk = 'http://'+window.location.host+'/static/akali/images/logo.png';
    }
    //如果是上传的图片则进行绝对路径拼接
    if(flink.indexOf('/uploads/') != -1) {
        lk = 'http://'+window.location.host+flink;
    }
    //百度编辑器自带图片获取
    if(flink.indexOf('ueditor') != -1){
        lk = flink;
    }
    //qq空间接口的传参
    if(stype=='qzone'){
        window.open('https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+document.location.href+'&title='+ftit+'&pics='+lk+'&summary='+document.querySelector('meta[name="description"]').getAttribute('content'));
    }
    //新浪微博接口的传参
    if(stype=='sina'){
        window.open('http://service.weibo.com/share/share.php?url='+document.location.href+'&title='+ftit+'&pic='+lk);
    }
    //qq好友接口的传参
    if(stype == 'qq'){
        window.open('http://connect.qq.com/widget/shareqq/index.html?url='+document.location.href+'&title='+ftit+'&pics='+lk+'&summary='+document.querySelector('meta[name="description"]').getAttribute('content')+'&desc=草帽跨境致力于为跨境电商卖家提供一个优质的跨境电商平台');
    }
    //生成二维码给微信扫描分享，php生成，也可以用jquery.qrcode.js插件实现二维码生成
    if(stype == 'wechat'){
        var qrcode_html = $('#qrcode_akali').html();
        layer.open({
            type: 1,
            title: false, //不显示标题
            skin: 'layui-layer-demo', //样式类名
            closeBtn: 0, //不显示关闭按钮
            anim: 0,
            shadeClose: true, //开启遮罩关闭
            area: ['auto','auto'], //自动宽高
            content: qrcode_html
          });
    }
}

// 生产网页二维码
jQuery(function(){
    jQuery('#qrcode').qrcode({
        render: "table",
        text: document.location.href
    });
});



