{__NOLAYOUT__}
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
    <title>提示信息</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
*{ padding:0; margin:0; font-size:12px}
a:link,a:visited{text-decoration:none;color:#0068a6}
a:hover,a:active{color:#ff6600;text-decoration: underline}
.showMsg{border: 1px solid #1e64c8; zoom:1; width:375px; height:140px;position:absolute;top:44%;left:50%;margin:-87px 0 0 -225px;overflow:hidden;}
.showMsg h5{background-image: url("/static/admin/Images/success/msg.png");background-repeat: no-repeat; color:#fff; padding-left:35px; height:25px; line-height:26px;*line-height:28px; overflow:hidden; font-size:14px; text-align:left}
.showMsg .content{ padding:36px 12px 10px 45px; font-size:14px; height:20px; text-align:left}
.showMsg .bottom{position: absolute;bottom:0;width: 100%;background:#e4ecf7; margin: 0 1px 1px 1px;line-height:26px; *line-height:30px; height:26px; text-align:center}
.showMsg .ok,.showMsg .guery{background: url("/static/admin/Images/success/msg_bg.png") no-repeat 0px -560px;}
.showMsg .guery{background-position: left -472px; display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:330px}
</style>
</head>
<body>
    <div class="showMsg" style="text-align:center">
        <h5>提示信息</h5>
        <div class="content guery">
            <?php switch ($code) {?>
            <?php case 1:?>
                <?php echo(strip_tags($msg));?>
            <?php break;?>
            <?php case 0:?>
                <?php echo(strip_tags($msg));?>
            <?php break;?>
            <?php } ?>
        </div>
        <div class="bottom">
            <a href="javascript:history.back();" >[点这里返回上一页]</a>
            系统将在&nbsp;&nbsp;<b id="wait" style="color:blue"><?php echo($wait); ?></b>&nbsp;&nbsp;秒后自动跳转，<a id="href" href="<?php echo($url);?>">如果没有跳转请点击这里</a>
        </div>
    </div>
    <script type="text/javascript" src="/static/admin/Js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/admin/Js/layer-v2.3/layer.js"></script>
    <script type="text/javascript">
    function jump() {
        var wait = document.getElementById('wait'), time = <?php echo($wait); ?>, href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            --time;
            if(time <= 0) {
                clearInterval(interval);
                window.location.href = href;
                parent.layer.closeAll();
            };
        }, 2000);
    }
    jump();
    </script>
</body>
</html>
