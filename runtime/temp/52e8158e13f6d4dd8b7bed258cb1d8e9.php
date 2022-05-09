<?php /*a:8:{s:44:"../application/index/view/service\index.html";i:1647846004;s:42:"../application/index/view/layout\base.html";i:1645521738;s:44:"../application/index/view/layout\header.html";i:1645066486;s:41:"../application/index/view/layout\top.html";i:1646990834;s:42:"../application/index/view/layout\menu.html";i:1647832070;s:45:"../application/index/view/layout\toolbar.html";i:1624259554;s:44:"../application/index/view/layout\footer.html";i:1642580253;s:46:"../application/index/view/layout\open_img.html";i:1645170607;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>找服务 - 草帽跨境</title>

    <meta name="description" content="草帽跨境致力于为跨境电商卖家提供一个优质的跨境电商平台，实时的给跨境电商卖家带来高质量的跨境物流公司，跨境电商活动，跨境电商资讯，跨境电商直播等内容服务。">
    
	<meta name="keywords" content="跨境电商平台,跨境电商物流,跨境电商活动,跨境电商资讯,跨境电商直播,草帽跨境">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="baidu-site-verification" content="EgoanGgarr">
    <meta name="applicable-device" content="pc">
	
    <link rel="shortcut icon" href="/static/icon/icon.ico" />
    <link rel="canonical" href="<?php echo url(); ?>" >
    
	<!-- css -->
    <link rel="stylesheet" href="/static/akali/css/font-awesome.css">
    <link rel="stylesheet" href="/static/akali/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/akali/css/share_style1_16.css">
    
    <!-- js -->
    <script src="https://zz.bdstatic.com/linksubmit/push.js"></script>
    <script src="https://hm.baidu.com/hm.js"></script>
    <script src="/static/akali/js/jquery.js"></script>
    <script src="/static/akali/js/common.js"></script>
    <script src="/static/akali/js/yii.js"></script>
    <script src="/static/akali/js/font_606323_nw08h605yuj.js"></script>
    <script src="/static/akali/js/jquery.min.js"></script>
    <script src="/static/akali/js/bootstrap.min.js"></script>
    <script src="/static/akali/js/kjy.js"></script>
    <script src="/static/akali/js/jquery.cookie.js"></script>
    <script src="/static/akali/js/chosen.jquery.js"></script>

    <!-- 网页二维码生产 -->
    <script type="text/javascript" src="/static/akali/js/jquery.qrcode.min.js"></script>
	
	<!-- layer -->
    <script src="/static/akali/layer/layer.js"></script>
	
	<!-- layui -->
    <link rel="stylesheet" href="/static/akali/layui/css/layui.css">
    <script src="/static/akali/layui/layui.js"></script>
    
    <link rel="stylesheet" href="/static/akali/css/kjy.css?<?php echo htmlentities($time); ?>">
    
	<!-- 修改底部搜索类型下拉layui样式 -->
	<style>
        .layui-form-item{
            margin-bottom: 0px;
        }
        .layui-form-item input {
            /*border-radius: 20px 0 0 20px;*/
            height: 35px;
            /*border: 1px solid #ff7600;*/
            /*border-right:none;*/
            border: 0px;
        }
        
        .layui-form-select dl dd.layui-this {
            background-color: #fff;
            color: #ff7600;
        }
		
        .layui-form-select dl dd:hover{
            background-color: #d2d2d2;
        }

        .layui-form-select dl dd {
            text-align: left;
        }
    </style>

    <!-- iconfont -->
    <link rel="stylesheet" href="/static/akali/\iconfont/iconfont.css">
    <script src="/static/akali/\iconfont/iconfont.js"></script>
    <style>
        .icon{font-size: 22px;}
        .icon {
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }
    </style>
	
	

<!-- css -->
<link rel="stylesheet" href="/static/amz/css/swiper.min.css">
<link rel="stylesheet" type="text/css" href="/static/amz/css/font_2020772_ylpwq57cfto.css">
<link rel="stylesheet" type="text/css" href="/static/amz/css/wuliu.css">

<!-- js -->
<script src="/static/amz/js/swiper-02.min.js"></script>

</head>
<body>
    <div class="wrap">
        <!-- 头部文件载入 -->
        <!-- 头部文件 -->
<header class="header">
    <nav class="nav-menu navbar-fixed-top navbar" style="height:100px;">
        <!-- 最顶部 -->
        <!-- 最顶部 -->
<div class="container kjy_top_nav" style="border:none;height: 100px;background-color: #fff;">
    <ul id="w1" class="navbar-nav navbar-left nav">
        <li>
            <div>
                <a href="/" style="background-color: none">
                    <img src="/static/akali/images/logo.png" class="menu_logo" alt="草帽跨境">
                    <img src="/static/akali/images/top_2.png" class="menu_logo_right_abc" alt="草帽跨境">
                </a>
            </div>
        </li>
        <!-- <li class="items select_li_padding"> -->
        <li class="sousuoItem" style="display:flex;flex-direction:column;">
            <div class="home_search_word" style="width: 520px;">
                <div class="searchShowCont" style="float: left;height: 32px;">
                    
                    <div class="layui-form" style="width: 210px;margin-left: -95px;">
                        <form action="<?php echo url('search'); ?>" method="post" name="example">
                            <div class="layui-form-item">
                                <div class="layui-input-block" style="height: 30px">
                                    <select name="quanzhan_select" class="quanzhan_select" lay-verify="required">
                                        <option value="0" <?php if(input('select_type') =="0"): ?>selected<?php endif; ?>>站内搜索</option>
                                        <option value="1" <?php if(input('select_type') =="1"): ?>selected<?php endif; ?>>头条</option>
                                        <option value="2" <?php if(input('select_type') =="2"): ?>selected<?php endif; ?>>活动</option>
                                        <option value="3" <?php if(input('select_type') =="3"): ?>selected<?php endif; ?>>学院</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="searchShowCont" style="float: left;height: 32px;">
                    <input id="keyword_search" autofocus="autofocus" value="<?php echo input('keyword/s', ''); ?>" type="text"
                        placeholder="请输入搜索内容" class="keyword_search_input" style="width:295px;">
                    <span id="fangdajing">搜索</span>
                </div>
            </div>
            <!--搜索输入框下部-->
            <div class="cif-header__word cifnews_block">
                <a href="<?php echo url('platform/detail', ['id' => 52]); ?>">速卖通</a>
                <a href="<?php echo url('platform/detail', ['id' => 5]); ?>">亚马逊</a>
                <a href="<?php echo url('platform/detail', ['id' => 6]); ?>">Shopee</a>
                <a href="<?php echo url('platform/detail', ['id' => 7]); ?>">Wish</a>
                <a href="<?php echo url('platform/detail', ['id' => 9]); ?>">eBay</a>
                <a href="<?php echo url('platform/detail', ['id' => 10]); ?>">Shopify</a>
                <a href="<?php echo url('college/index#cate-1'); ?>">速卖通培训</a>
                <a href="<?php echo url('logistics/detail', ['id' => 1]); ?>">美国海外仓</a>
            </div>

        </li>
        <li class="task-li taskliMenu" style="width:150px;">
            <div class="header-right-nav-box rightNav-box">

                <span class="header-drop-down-a select-dropdown">
                    商务合作 <svg class="icon" style="width:14px;height:12px;" aria-hidden="true">
                        <use xlink:href="#icon-arrowdown"></use>
                    </svg>
                    <div class="header-drop-down-list-box" style="width: 90px; display: none;">
                        <a href="<?php echo url('customer/contact'); ?>">商务对接</a>
                        <a href="<?php echo url('customer/platform'); ?>">电商入驻</a>
                        <a href="<?php echo url('customer/activity'); ?>">活动报名</a>
                        <a href="<?php echo url('customer/college'); ?>">课程报名</a>
                        <!-- <a href="<?php echo url('customer/suggestion'); ?>">意见反馈</a> -->
                    </div>
                </span>

                <!-- <span><a class="navigation" href="https://www.caomaokj.com/navigation-tool/tool">网站导航</a></span>
                <span class="official-dropdown">
                    <span class="officialText"> 关注公众号</span>
                    <div class="officials">
                        <div class="officialContainer">
                            <div class="officialItem">
                                <img src="/static/akali/images/QR_code/code_caomao_public.jpg" title="草帽跨境公众号"
                                    alt="草帽跨境公众号.jpg">
                                <p>草帽跨境公众号</p>
                            </div>
                            <div class="officialItem">
                                <img src="/static/akali/images/QR_code/code_smt_public.jpg" title="速卖通草帽公众号"
                                    alt="速卖通草帽公众号.png">
                                <p>速卖通草帽公众号</p>
                            </div>
                            <div class="officialItem">
                                <img src="/static/akali/images/QR_code/code_wsy_video.jpg" title="威速易视频号"
                                    alt="威速易视频号.jpg">
                                <p>威速易视频号</p>
                            </div>
                            <div class="officialItem">
                                <img src="/static/akali/images/QR_code/code_wsy_public.jpg" title="威速易公众号"
                                    alt="威速易公众号.png">
                                <p>威速易公众号</p>
                            </div>
                        </div>
                    </div>
                </span> -->
            </div>
        </li>
        
        <input type="hidden" name="user_type" value="0" class="user_type">
        <input type="hidden" name="user_service_id" value="0" class="user_service_id">
        
        <?php if(empty($userid) || (($userid instanceof \think\Collection || $userid instanceof \think\Paginator ) && $userid->isEmpty())): ?>
        <!-- 未登录头像 -->
        <li class="btn-operation pull-right" id="padding0_nav">
            <p>
                <a href="<?php echo url('login/index'); ?>">
                     <button type="button" id="denglu_btn" class=" btn-header">登录</button>
                </a>
                <a href="<?php echo url('login/register'); ?>">
                     <button type="button" id="denglu_btn" class=" btn-header">注册</button>
                </a>
            </p>
        </li>
        <?php else: ?>
        <!-- 已登录头像 -->
        <li class="user-menu-container">
            <div class="userInfo">
                <a href="<?php echo url('user/index'); ?>" id="user-center">
                    <img src="<?php echo htmlentities($avatar); ?>" style="max-width:38px;">
                </a>
            </div>
            <div class="user-menu-top" aria-labelledby="user-center" style="display: none;">
                <p><a href="<?php echo url('user/index'); ?>">个人中心</a></p>
                <p><a href="<?php echo url('user/info'); ?>">我的资料</a></p>
                <p><a href="<?php echo url('user/collect'); ?>">我的收藏</a></p>
                <p><a href="<?php echo url('user/activity'); ?>">我的报名</a></p>
                <p><a href="<?php echo url('user/college'); ?>">我的课程</a></p>
                <p><a href="javascript:logout();">退出</a></p>
            </div>
        </li>
        
        <li class="user-menu-container message" style="width:34px;">
            <!-- <div class="userInfo" style="height:60px;position:relative">
                <a href="<?php echo url('user/message'); ?>" id="message">
                    <span class="span-svg">
                        <img style="width:26px;" src="/static/akali/images/ccd8eb55a2d9b8b6.png" title="1606962473978757.png" alt="提醒.png">
                    </span>
                </a>
            </div>
            <div class="user-menu-top" aria-labelledby="message" style="display: none;">
                <p class="more-message">
                    <a href="<?php echo url('user/message'); ?>">查看全部消息</a>
                </p>
            </div> -->
        </li>
        
        <?php endif; ?>

    </ul>
</div>
        <!-- 导航栏 -->
        

<!-- 导航栏 -->
<div class="kua_menu_div">
    <div class="kua_menu_div_box">
        <input type="hidden" id="home_url" value="/index">
        <a class="color_333" href="/index">
            <div class="shouye_div">
                <svg class="icon" aria-hidden="true">
                  <use xlink:href="#icon-gengduo"></use>
                </svg>
                首页
                <div class="kua-home-nav-bottom"></div>
            </div>
        </a>
        <?php foreach($menu as $key => $value): if(empty($value['child']) || (($value['child'] instanceof \think\Collection || $value['child'] instanceof \think\Paginator ) && $value['child']->isEmpty())): ?>
        <a class="color_333" target="<?php echo htmlentities($value['target']); ?>" href="/index/<?php echo htmlentities($value['url']); ?>">
            <div class="<?php echo $value['url']!=$controller ? htmlentities($value['url']) : 'menu_active'; ?> <?php echo htmlentities($value['class']); ?> home-nav-hover">
                <?php echo htmlentities($value['catname']); ?>
                <div class="kua-home-nav-bottom"></div>
            </div>
        </a>
        <?php else: ?>
        <div class="<?php echo $value['url']!=$controller ? htmlentities($value['url']) : 'menu_active'; ?> <?php echo htmlentities($value['class']); ?> dropdown dropdown_jzc home-nav-hover">
            <a href="/index/<?php echo htmlentities($value['url']); ?>" class="dropdown-toggle color_333" target="<?php echo htmlentities($value['target']); ?>">
                <span>
                    <?php echo htmlentities($value['catname']); ?>
                    <img src="/static/akali/images/xia-copy.png" style="width: 15px;margin-left: 5px;margin-top:-3px;">
                </span>
            </a>
            <div class="kua-home-nav-bottom"></div>
            <ul class="dropdown-menu menu_dropdown_jzc" style="display: none;">
                <?php foreach($value['child'] as $k => $val): ?>
                <li><a href="<?php echo url($val['url']); ?>"><?php echo htmlentities($val['catname']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
        
    </div>
</div>
    </nav>
</header>

<!-- 如果是首页 则显示 -->
<?php if(!(empty($announcement) || (($announcement instanceof \think\Collection || $announcement instanceof \think\Paginator ) && $announcement->isEmpty()))): if($announcement['status'] == '1'): ?>
<a class="activity_href" target="_blank" href="<?php echo htmlentities($announcement['url']); ?>">
    <div class="bottom_banner_ad_jzc hidden-sm hidden-xs" style="position: fixed; bottom: 0px; height: 80px; line-height: 80px; font-size: 30px; width: 100%; z-index: 2147483647; background: url(<?php echo htmlentities($announcement['thumb']); ?>) center center / 100%; opacity: 0.8; color: rgb(255, 255, 255); display: none;">
        <img class="close_btn_img_jzc" style="width: 25px;height: 25px;float: right;margin-right: 20px;margin-top:10px;" src="/static/akali/images/7794af16170aebcd.png">
    </div>
</a>
<?php endif; ?>
<?php endif; ?>


<!-- 公众号矩阵 -->
<script src="/static/akali/js/jquery.cookie.js"></script>
<script>
    var showOfficial = false;
    $(".official-dropdown").hover(function() {
        if (!showOfficial) {
            $(".officials").css("display", "block");
            $(".officialText").css("color", "#ff7600");
            showOfficial = true;
        }

    });
    $(".official-dropdown").mouseleave(function() {
        if (showOfficial) {
            $(".officials").css("display", "none");
            $(".officialText").css("color", "#333");
            showOfficial = false;
        }
    });
    $(".user-menu-container").hover(function() {
        $(this).children(".user-menu-top").css("display", "flex")
    });
    $(".user-menu-container").mouseleave(function() {
        $(this).children(".user-menu-top").css("display", "none")
    })
    var host = window.location.href;
    var url_home = "https://www.kuajingyan.com";
    var url_home1 = "https://www.kuajingyan.com" + '/';
    var domain = ".kuajingyan.com";
    if ((host == url_home) || (host == url_home1)) {
        // 弹窗公告
        showTips();
    } else {
        // 弹窗公告
        if ($.cookie("isShowTips") != '1') {
            showTips();
        }
    }

    $('.close_btn_img_jzc').click(function() {
        $('.activity_href').removeAttr('href');
        $('.bottom_banner_ad_jzc').fadeOut(1000);
    })

    // 显示公告
    function showTips() {
        $('.bottom_banner_ad_jzc').show();
        $.cookie("isShowTips", "1", { expires: 1, path: '/', domain: domain });
    }

    $(window).scroll(function() {
        if ($(document).scrollTop() >= 3500) {
            $('.bottom_banner_ad_jzc').fadeOut(1000);
        }
    })

    var user_type = $('.user_type').val();
    var user_service_id = $('.user_service_id').val();
    var is_posted_jzc_service = localStorage.getItem("is_posted_jzc_service");
    var is_posted_jzc_company = localStorage.getItem("is_posted_jzc_company");

    if (!user_service_id || user_service_id == 'undefined') {
        localStorage.removeItem("is_posted_jzc_service");
        localStorage.removeItem("is_posted_jzc_company");
    }

    if ((user_type != 'undefined' && user_type < 2 && user_service_id != 'undefined' && user_service_id > 0) || is_posted_jzc_service == 1 || is_posted_jzc_company == 1) {
        $('.service_href').attr('href', "https://www.caomaokj.com/site/service-tips?v=service_href");
        $('.company_href').attr('href', "https://www.caomaokj.com/site/service-tips?v=company_href");
    } else if (user_type != 'undefined' && user_type == 3 && user_service_id != 'undefined' && user_service_id > 0) {
        $('.service_href').attr('href', "https://user.kuajingyan.com/warehouse/info");
        $('.company_href').attr('href', "https://user.kuajingyan.com/warehouse/info");
    } else {
        var href = $('.service_href').attr('data-href');
        $('.service_href').attr('href', href);

        var company_href = $('.company_href').attr('data-href');
        $('.company_href').attr('href', company_href);
    }
</script>1
        
        <!-- 主体内容 -->
        

    <!-- <header class="navbar navbar-expand-lg navbar-dark bg-dark" id="header"></header> -->
    <main id="body" style="margin-top: 129px;">
        <div class="container">
            <style>
                body {
                    background: #f6f6f6;
                }

                .content .container {
                    max-width: 1250px !important;
                    width: 100%;
                }

                .findpage-title {
                    text-align: center;
                }

                .navmenu {
                    background: #fff;
                    z-index: 1000;
                    position: absolute;
                    margin-bottom: 40px;
                    box-shadow: 1px 2px 12px -5px rgba(0, 0, 0, .1);
                    max-width: 1195px;
                }

                .navmenu.fixed {
                    position: fixed;
                    top: 0;
                }

                .navmenu ul {
                    margin: 0;
                    padding: 0;
                }

                .navmenu ul li {
                    list-style: none;
                    text-align: center;
                    cursor: pointer;
                }

                .navmenu ul li a {
                    color: #444;
                    padding: 15px;
                    display: -webkit-box;
                    height:55px;
                    font-size:16px;
                }

                .navmenu ul li a.active {
                    position: relative;
                    color: #fe6d00;
                    cursor: default;
                }

                .navmenu ul li a.active:after {
                    content: "";
                    width: 100%;
                    border-bottom: 2px solid #fe6d00;
                    position: absolute;
                    bottom: 0;
                    left: 0;
                }

                .catetitle {
                    margin-top: 0;
                    font-size: 24px;
                    font-weight: bold;
                }

                @media (max-width: 768px) {
                    .find-r-box .fitem {
                        width: 50%;
                    }
                }
            </style>
            
            <div class="layui-carousel" id="test1">
              <div carousel-item>
                <?php foreach($banner as $key => $value): ?>
                <div><a href="<?php echo htmlentities($value['url']); ?>"><img src="<?php echo htmlentities($value['thumb']); ?>" style="width:100%;"></a></div>
                <?php endforeach; ?>
              </div>
            </div>

            <script>
                layui.use('carousel', function(){
                  var carousel = layui.carousel;
                  //建造实例
                  carousel.render({
                    elem: '#test1'
                    ,width: '100%' //设置容器宽度
                    //,arrow: 'always' //始终显示箭头
                    //,anim: 'updown' //切换动画方式
                  });
                });
            </script>

            <div class="content">
                <div class="container" style="padding:20px 0;position: relative;max-width: 1400px;">

                    <!-- <div class="navmenu" style="width: 1195px;">
                        <nav class="nav-wrap" id="nav-wrap">
                            <ul class="clearfix" style="display: flex;flex-wrap: wrap;">
                                <?php foreach($list as $key => $value): ?>
                                <li><a class="<?php echo $key==0 ? 'active'  :  ''; ?>" href="#s<?php echo htmlentities($value['id']); ?>"><?php echo htmlentities($value['catname']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </nav>
                    </div>
                    <div style="padding-top:90px;"></div> -->

                    <?php foreach($list as $value): ?>
                    <div class="catetitle" id="s<?php echo htmlentities($value['id']); ?>"><?php echo htmlentities($value['catname']); ?></div>
                    <div class="find-r-box">
                        <div class="databox">

                            <?php foreach($value['services'] as $val): ?>
                            <div class="theme-item shoplist">
                                <a href="<?php echo url('service/detail', ['id' => $val['id']]); ?>" target="_blank">
                                    <div class="img" style="text-align: center">
                                        <img src="<?php echo htmlentities($val['image']); ?>" style="width: auto; height: auto;max-width: 100%;max-height: 100%;text-align: center;">
                                    </div>
                                    <div class="titlename">
                                        <h2><?php echo htmlentities($val['name']); ?></h2>
                                    </div>
                                    <div class="desc">
                                        <p><?php echo htmlentities($val['description']); ?></p>
                                    </div>
                                    <div class="zixun duijie">
                                        <div class="btn">我要对接</div>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; ?>
                            
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                </div>
            </div>
        </div>
    </main>
    
    <script src="/static/amz/js/nav.js"></script>
    <script>
    
    // 滚动固定顶部的导航
    $(window).scroll(function() {
        var top = $(window).scrollTop();
        if(top < 350){
            $('.header').show();
        } else {
            $('.header').hide();
        }
    });
    
    
    $('.fitem').click(function() {
        window.open($(this).attr('dhref'));
    })

    $(document).ready(function() {
        $('.navmenu').find('li').eq(0).find('a').addClass('active')
        // var navHeight = $('header').height() + $('.banner').height()+30;
        var navHeight = $('header').height() + 270;
        // console.log(navHeight);
        var left = 0;
        var width = $('.catetitle').width()
        if ($(this).scrollTop() >= navHeight) {
            $('.navmenu').addClass('fixed').css({ width: '100%' })
        } else {
            $('.navmenu').removeClass('fixed').css({ width: '100%' })
        }
        $(window).scroll(function() {
            if ($(this).scrollTop() >= navHeight) {
                $('.navmenu').addClass('fixed').css({ width: width })
            } else {
                $('.navmenu').removeClass('fixed').css({ width: width })
            }
        })
    })
    
    //内容信息导航锚点
    $('.nav-wrap').navScroll({
        mobileDropdown: true,
        mobileBreakpoint: 768,
        scrollSpy: true
    });
    </script>
    <script>
        //点击自动滚动
        $(document).on('click','.course-cate-name',function() {
            var top = $(window).scrollTop();
            if(top < 470){
                var attr_cate_data=$(this).attr('attr-cate-data');
                $(this).addClass('current_active').siblings().removeClass('current_active');
                $(this).css('border-bottom','1px solid #fc9d27').siblings().css('border-bottom','0px solid #fc9d27');
                if(attr_cate_data == 0){
                    $("html, body").animate({
                        scrollTop: 0
                    }, {
                        duration: 500,
                        easing: "swing"
                    });
                }else{
                    var height = $('.cate-'+attr_cate_data).offset().top - 120 + "px";
                    $("html, body").animate({
                        scrollTop: $('.cate-'+attr_cate_data).offset().top - 120 + "px"
                    }, {
                        duration: 500,
                        easing: "swing"
                    });
                }
                
                $('.header').show();
            }else{
                var attr_cate_data=$(this).attr('attr-cate-data');
                $(this).addClass('current_active').siblings().removeClass('current_active');
                $(this).css('border-bottom','1px solid #fc9d27').siblings().css('border-bottom','0px solid #fc9d27');
                if(attr_cate_data == 0){
                    $("html, body").animate({
                        scrollTop: 0
                    }, {
                        duration: 500,
                        easing: "swing"
                    });
                }else{
                    var height = $('.cate-'+attr_cate_data).offset().top -70 + "px";
                    $("html, body").animate({
                        scrollTop: $('.cate-'+attr_cate_data).offset().top -70 + "px"
                    }, {
                        duration: 500,
                        easing: "swing"
                    });
                }
                
                $('.header').hide();
            }


            return false;

        });
    </script>
    
        
        <!-- 右边工具栏 -->
        <!-- 右边功能条 -->
<div class="float_tool_bar">
    <div class="item sign">
        <span class="over">
            <div class="icon">
                <div class="img share_img"></div>
            </div>
            <div class="txt share_hover"></div>
        </span>
        <span class="active"></span>
        <div class="bdshare_box">
            <div class="bdshare_title">分享到</div>
            <div class="bdsharebuttonbox bdshare-button-style1-16" data-bd-bind="1619766002701">
                <a class="bds_mshare" data-cmd="mshare" title="分享到一键分享">一键分享</a>
                <a class="bds_weixin" data-cmd="weixin" title="分享到微信">微信</a>
                <a class="bds_qzone" data-cmd="qzone" title="分享到QQ空间">QQ空间</a>
                <a class="bds_tsina" data-cmd="tsina" title="分享到新浪微博">新浪微博</a>
                <a class="bds_renren" data-cmd="renren" title="分享到人人网">人人网</a>
                <a class="bds_mail" data-cmd="mail" title="分享到邮件分享">邮箱</a>
            </div>
        </div>
    </div>
    <a href="javascript:void(0);" class="item">
        <span class="over">
            <div class="icon">
                <div class="img collect_img"></div>
            </div>
            <div class="txt collect_hover"></div>
        </span>
        <span class="active"></span>
        <div class="detail" style="width: 125px; height: 125px;">
            <img class="right-wechat" src="/static/akali/images/QR_code/code_smt_public.jpg" alt="草帽跨境微信公众号">
        </div>
    </a>
    <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=3004630833&amp;site=qq&amp;menu=yes" class="item">
        <span class="over">
            <div class="icon qq">
                <div class="img qq_img"></div>
            </div>
            <div class="txt qq_hover"></div>
        </span>
        <span class="active"></span>
        <div class="detail">联系草帽跨境客服 </div>
    </a>
    <a href="javascript:void(0);" class="item">
        <span class="over">
            <div class="icon">
                <div class="img phone_img"></div>
            </div>
            <div class="txt phone_hover"></div>
        </span>
        <span class="active"></span>
        <div class="detail">13829730554</div>
    </a>

    <a href="javascript:void(0);" class="item go_top" style="display: none;">
        <span class="over">
            <div class="icon">
                <div class="img top_img"></div>
            </div>
            <div class="txt top_hover"></div>
        </span>
    </a>
</div>

        <!-- 底部文件载入 -->
        <!-- 底部 -->
<footer class="footer">
    <div class="container">
        <div class="footer_text">
            <p>合作伙伴</p>
            <ul>
                <!-- <li><a href="http://www.eccang.com/" target="_blank">易仓科技</a></li> -->
            </ul>
        </div>
        <div class="footer_text">
            <p>优秀资源</p>
            <ul>
                <li><a href="<?php echo url('logistics/index'); ?>" target="_blank">物流</a></li>
                <li><a href="<?php echo url('home/index'); ?>" target="_blank">干货</a></li>
            </ul>
        </div>
        <div class="footer_text">
            <p>草帽跨境</p>
            <ul>
                <li><a href="<?php echo url('index/about'); ?>" target="_blank">关于我们</a></li>
                <li><a href="<?php echo url('login/register'); ?>" target="_blank">企业入驻</a></li>
                <li><a href="<?php echo url('service/index'); ?>" target="_blank">生态伙伴</a></li>
            </ul>
        </div>
        <div class="footer_text">
            <p>联系我们</p>
            <ul>
                <li>电话：13829730554</li>
                <li>邮箱：3004630833@qq.com</li>
                <li>地址：广州市白云区鹤龙一横路<br>1号 6006 铺</li>
            </ul>
        </div>
        <div class="footer_text wechat">
            <p>草帽跨境公众号</p>
            <div>
                <img src="/static/akali/images/QR_code/code_caomao_public.jpg" alt="草帽跨境公众号">
            </div>
        </div>
        <div class="footer_text wechat">
            <p>速卖通草帽</p>
            <div>
                <img src="/static/akali/images/QR_code/code_smt_public.jpg" alt="速卖通草帽公众号">
            </div>
        </div>
    </div>
	<!-- 如果是首页 则显示 -->
    <?php if(request()->url() == '/'): ?>
    <div class="container pb0 color-666">
        友情链接：
        <?php foreach($links as $value): ?>
        <a class="pr5" href="<?php echo htmlentities($value['url']); ?>" target="_blank"><?php echo htmlentities($value['name']); ?></a>
        <?php endforeach; ?>
    </div>
	<?php endif; ?>
</footer>
<footer class="footer_copyright">
    <div class="container">Copyright ©2022 广州草帽跨境电子商务有限公司版权所有 &nbsp;&nbsp;
        <a href="http://beian.miit.gov.cn/" target="_blank" rel="nofollow">粤ICP备2022007460号</a>
    </div>
</footer>
<script>
    // Demo
    layui.use('form', function() {
        var form = layui.form;

        //监听提交
        form.on('submit(formDemo)', function(data) {
            layer.msg(JSON.stringify(data.field));
            return false;
        });
    });
</script>
<script type="text/javascript" src="/static/akali/js/search.js?<?php echo time(); ?>"></script>
</body>

</html>
    </div>
    <!-- 打开图片 -->
    
    
<!-- 查看图片 -->
<div id="akali" class="layui-layer-wrap" style="display: none;">
    <img src="" id=""/>
</div>
<script>
    function open_img(obj)
    {
        var src = obj.src;
        $('#akali img').attr('src', src);
        
        // 获取图片的真实宽高
        $('<img/>').attr("src", $('#akali img').attr("src")).load(function() {

             // 获取图片的宽度 不能超过1280px
            var pic_real_width = this.width > 1280 ? 1280 : this.width;  // Note: $(this).width() will not
            var pic_real_height = this.height; // work for in memory images.
            
            // 设置图片的宽度 不能超过1280px
            $('#akali img').attr('width', pic_real_width);
            
            // 页面层-佟丽娅
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                area: pic_real_width + 'px',
                skin: 'layui-layer-nobg', //没有背景色
                shadeClose: true,
                content: $('#akali')
            });
        });
    }
</script>
</body>
</html>