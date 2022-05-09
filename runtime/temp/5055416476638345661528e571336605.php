<?php /*a:1:{s:43:"../application/usezan/view/login\index.html";i:1625280869;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>网站后台管理系统</title>
<link rel="stylesheet" type="text/css" href="/static/admin/Css/login.css" />
<script type="text/javascript" src="/static/admin/Js/jquery.min.js"></script>
</head>
<body>
<div class="main">
    <div class="login_box" style="margin: 50px 0 0 780px;">
        <div class="bcok" style="background: url(/static/akali/images/logo.png) no-repeat left top; height:75px">
            <a href="/" target="_blank" class="logo">&nbsp;</a>
        </div>
        <div class="form">
            <form method='post' name="login" id="form1" action="<?php echo url('Login/getLogin'); ?>">
                <ul>
                    <li>
                        <label>账号：</label>
                        <input type="text" name="username" class="username input" placeholder="请输入您的用户名"/>
                    </li>
                    <li>
                        <label>密码：</label>
                        <input type="password" name="password" class="password input" placeholder="***********" />
                    </li>
                    <?php if(config('usezan_verif')): ?>
                        <li class="captcha"><div id="popup-captcha"></div></li>
                    <?php else: ?>
                        <li class="verify">
                            <label>验证码：</label>
                            <input class="input verifyfor" type="text" name="verify"><img src="<?php echo captcha_src(); ?>">
                        </li>
                        <input class="verify-submit" type="submit" value="登录" />
                    <?php endif; ?>
                    <li>
                        <label>&nbsp;</label>
                        <div class="msg"><div id="result" class="result"></div></div>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
<?php if(config('usezan_verif')): ?>
    <script type="text/javascript" src="/static/admin/Js/gt.js"></script>
    <script type="text/javascript">
        var handlerPopup = function (captchaObj) {
            // 成功的回调
            captchaObj.onSuccess(function () {
                var validate = captchaObj.getValidate();
                $.ajax({
                    url: "<?php echo url('Login/getLogin'); ?>", // 进行二次验证
                    type: "post",
                    dataType: "json",
                    data: {
                        type: "pc",
                        username: $.trim($(".username").val()),
                        password: $.trim($(".password").val()),
                        geetest_challenge: validate.geetest_challenge,
                        geetest_validate: validate.geetest_validate,
                        geetest_seccode: validate.geetest_seccode
                    },
                    success: function (data) {
                        if (data.status) {
                            $('#result').html('<span class="success">'+data.info+'</span>').show();
                            setTimeout(function(){ window.location.href = data.url; },1500);
                        } else {
                            $('#result').html(data.info).show();
                        }
                    }
                });
            });
            captchaObj.appendTo("#popup-captcha");
        };
        //一验
        $.ajax({
            url: "<?php echo url('login/Captcha'); ?>?type=pc&t=" + (new Date()).getTime(),
            type: "get",
            dataType: "json",
            success: function (data) {
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    product: "float",
                    offline: !data.success
                }, handlerPopup);
            }
        });
    </script>
<?php else: ?>
    <script type="text/javascript">
        $(".verify img").on("click",function (){
            var tath = $(this),data_src = "<?php echo captcha_src(); ?>?";
            tath.attr("src",data_src+ new Date().getTime());
        });
        $(".verify-submit").on("click",function () {
            var data_url = $("#form1").attr("action"),
                user = $.trim($(".username").val()),
                pwd = $.trim($(".password").val()),
                verfiy = $.trim($(".verifyfor").val());
            if (user =='' || pwd =='' || verfiy == '') {
                $('#result').html("请输入完整信息").show();return false;
            } else {
                $('#result').html("").hide();
            }
            $.ajax({
                url:data_url,
                type: "post",
                dataType: "json",
                data:{username:user,password:pwd,verfiy:verfiy},
                success:function (data){
                    if (data.status) {
                        $('#result').html('<span class="success">'+data.info+'</span>').show();
                        setTimeout(function(){ window.location.href = data.url; },1500);
                    } else {
                        $('#result').html(data.info).show();
                    }
                }
            })
            return false;
        });
    </script>
<?php endif; ?>
<!--[if lt IE 7]>
    <script type="text/javascript"> 
        layer.msg('您的IE浏览器版本太低，请先升级吧！<br />推荐浏览器：<a href="http://www.google.com/chrome" target="_blank">谷歌</a>、<a href="http://www.firefox.com.cn/download" target="_blank">火狐</a>、<a href="http://windows.microsoft.com/zh-cn/internet-explorer/download-ie" target="_blank">IE8+</a>、<a href="http://www.apple.com/cn/safari/" target="_blank">safari</a>', 3000, 8, function(){
            window.location.href = 'http://windows.microsoft.com/zh-cn/internet-explorer/download-ie';
        });
    </script>
<![endif]-->
</body>
</html>