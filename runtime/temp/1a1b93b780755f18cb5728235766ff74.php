<?php /*a:10:{s:43:"../application/index/view/home\article.html";i:1652855706;s:42:"../application/index/view/layout\base.html";i:1652509834;s:44:"../application/index/view/layout\header.html";i:1645066486;s:41:"../application/index/view/layout\top.html";i:1646990834;s:42:"../application/index/view/layout\menu.html";i:1647832070;s:45:"../application/index/view/layout\article.html";i:1652757357;s:45:"../application/index/view/layout\keyword.html";i:1637217498;s:45:"../application/index/view/layout\toolbar.html";i:1624259554;s:44:"../application/index/view/layout\footer.html";i:1652509779;s:46:"../application/index/view/layout\open_img.html";i:1645170607;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php echo htmlentities($article['title']); ?> - 草帽跨境</title>

    <meta name="description" content="<?php echo htmlentities($article['description']); ?>">
    
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
    
    <link rel="stylesheet" href="/static/akali/css/kjy.css">
    
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
<link rel="stylesheet" type="text/css" href="/static/akali/css/article.css">
<!-- js -->
<!-- 暂时不做评论、点赞等等操作 -->
<script type="text/javascript" src="/static/akali/js/article.js"></script>
<script type="text/javascript" src="/static/akali/js/share_article.js"></script>
<script type="text/javascript" src="/static/akali/js/jquery.lazyload.js"></script>

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
        
<div class="container body-container">
    <style>
        .star-and-top {
            background: #fff;
            clear: both;
            border-bottom: 1px solid #ddd;
            width: 260px !important;
        }
    </style>
    <div class="site-index">
        <ol class="crumbs">
            <li>
                <a href="/">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-shouye2"></use>
                    </svg>
                    首页
                </a>
            </li>
            <li><a href="<?php echo url('/index/home'); ?>"> 干货 </a></li>
            <li><a class="color-555"> <?php echo htmlentities($article['title']); ?> </a></li>
        </ol>
        <div class="article-detail clearfix">
            <h1><?php echo htmlentities($article['title']); ?></h1>
            <input type="hidden" id="user_id" value="<?php echo htmlentities($userid); ?>">
            <input type="hidden" id="al_id" value="<?php echo htmlentities($article['id']); ?>">
            <input type="hidden" id="shareContent" value="【<?php echo htmlentities($article['title']); ?>】<?php echo htmlentities($article['description']); ?>">
            <div class="article-tips clearfix color-aaa">
                <div class="sub-tips fl">
                    <span class="pr15 ">作者：草帽跨境</span>
                    <span class="pl15 color-aaa">发布于：<?php echo date('Y-m-d', $article['createtime']); ?></span>
                </div>
                <div class="like">
                    <!-- <a href="javascript:void(0)" title="评论" onclick="goComment()">
                        <span class="fa fa-comment-o"></span>
                        <span class="al_comment_num"><?php echo htmlentities($article['comment_num']); ?></span>
                    </a> -->
                    <a href="javascript:void(0)" title="收藏" alt="收藏" onclick="collect(<?php echo htmlentities($article['id']); ?>)">
                        <span class="fa fa-heart-o"></span>
                        <span class="al_collect_num"><?php echo htmlentities($article['collect_num']); ?></span>
                    </a>
                    <a href="javascript:void(0)" title="点赞" alt="点赞" onclick="articlelike(<?php echo htmlentities($article['id']); ?>)">
                        <span class="fa fa-thumbs-o-up"></span>
                        <span class="al_like_num"><?php echo htmlentities($article['like_num']); ?></span>
                    </a>

                    <!-- 分享 -->
                    <a href="javascript:void(0)" title="分享" alt="分享" class="share_div">
                        <span><img src="/static/akali/images/1558936301648195.png" style="width: 40px;"></span>
                    </a>

                    <div class="bdsharebuttonbox share_jzc bdshare-button-style1-16" style="display: none;height:50px;margin-top: -10px;" data-bd-bind="1620294840738">
                        <span>分享到：</span>
                        <a onclick="shareTo('sina')" class="bds bds_tsina" title="分享到新浪微博"></a>
                        <!-- <a onclick="shareTo('wechat')" class="bds bds_weixin" title="分享到微信"></a> -->
                        <a data-cmd="weixin" class="bds bds_weixin" title="分享到微信"></a>
                        <div id="qrcode_akali" style="display: none; width: 350px; height: 350px;background-color: #fff;border: #757575;"><div id="qrcode"></div></div>
                        <a onclick="shareTo('qzone')" class="bds bds_qzone" title="分享到QQ空间"></a>
                        <a onclick="shareTo('qq')" class="bds bds_sqq" title="分享到QQ好友"></a>
                    </div>
                    
                    <!-- <div class="bdsharebuttonbox share_jzc bdshare-button-style1-16" style="display: none;height:50px;margin-top: -10px;" data-bd-bind="1620294840738">
                        <span>分享到：</span>
                        <a data-cmd="tsina" class="bds bds_tsina" title="分享到新浪微博"></a>
                        <a data-cmd="weixin" class="bds bds_weixin" title="分享到微信"></a>
                        <a data-cmd="qzone" class="bds bds_qzone" title="分享到QQ空间"></a>
                        <a data-cmd="sqq" class="bds bds_sqq" title="分享到QQ好友"></a>
                        <a data-cmd="renren" class="bds bds_renren" title="分享到人人网"></a>
                        <a data-cmd="fbook" class="bds bds_fbook" title="分享到Facebook"></a>
                        <a data-cmd="twi" class="bds bds_twi" title="分享到Twitter"></a>
                        <a data-cmd="evernotecn" class="bds bds_evernotecn" title="分享到印象笔记"></a>
                        <a data-cmd="linkedin" class="bds bds_linkedin" title="分享到linkedin"></a>
                    </div> -->
                    
                </div>
                <div class="tag" style="clear: both;margin-top: 30px;text-align: left;">
                    <?php foreach($article -> keywords as $key => $value): if($key != '0'): ?>／<?php endif; ?>
                    <a style="border:1px solid #ddd;color:#757575;border-radius: 20px;padding: 2px 10px;" href="<?php echo url('keyword/article', ['id' => $value['id']]); ?>"><?php echo htmlentities($value['name']); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- 内容部分 -->
            <div class="sub-text"><?php echo htmlentities($article['description']); ?></div>
            <div class="article-content" style="align-items: center; text-align: center;">
                <img id="share_img" src="<?php echo htmlentities($article['thumb']); ?>">
            </div>
            <div class="article-content clearfix"><?php echo $article['content']; ?></div>


            <!-- 评论区 -->
            <div class="article-detail">
                <div class="h16 bg-color"></div>
                <!-- 评论框 -->
                <div class="comment-textarea-div">
                    <textarea class="input-text" type="text" id="comment" name="comment"
                        placeholder="说点什么吧..."></textarea>
                    <div class="clearfix">
                        <div class="Avatar">
                            <img src="<?php echo htmlentities($avatar); ?>" class="fl">
                            <div class="c_aaa fl">
                                神织知更 </div>
                        </div>
                        <div class="fr sub-btn" onclick="comment(20210, 0)">发表评论</div>
                    </div>
                </div>

                <div class="comment_stories_list" style="display: block;">
                    <div class="comment-div">
                        <ul class="stories_list">
                            <li class="clearfix">
                                <div class="Avatar fl"><img src="<?php echo htmlentities($avatar); ?>">
                                </div>
                                <div class="fr stories_con">
                                    <div class="blockquote_wrap"><a target="_blank"
                                            href="https://www.kuajingyan.com/user/167556">神织知更</a> : 文章写得不错
                                    </div>
                                    <div class="comment_subt">刚好需要</div>
                                    <div class="clearfix tools">
                                        <div class="fl">
                                            <div class="name fl mr30"><a
                                                    href="https://www.kuajingyan.com/user/167556">神织知更</a></div>
                                            <div class="time fl">发布于刚刚</div>
                                        </div>
                                        <div class="fr tools2">
                                            <div class="comment" data-id="754">回复</div>
                                        </div>
                                    </div>
                                    <div class="comment_input comment_input_754"><input
                                        type="text" id="comment_754" placeholder="回复　神织知更："
                                        class="c_input"><button class="Reply_btn"
                                        onclick="comment(20210, 754)">回复</button>
                                    </div>
                                </div>
                            </li>
                            <li class="clearfix">
                                <div class="Avatar fl"><img src="<?php echo htmlentities($avatar); ?>">
                                </div>
                                <div class="fr stories_con">
                                    <div class="comment_subt">文章写得不错</div>
                                    <div class="clearfix tools">
                                        <div class="fl">
                                            <div class="name fl mr30"><a
                                                    href="https://www.kuajingyan.com/user/167556">神织知更</a></div>
                                            <div class="time fl">发布于2分钟前</div>
                                        </div>
                                        <div class="fr tools2">
                                            <div class="comment" data-id="753">回复</div>
                                        </div>
                                    </div>
                                    <div class="comment_input comment_input_753"><input
                                            type="text" id="comment_753" placeholder="回复　神织知更："
                                            class="c_input"><button class="Reply_btn"
                                            onclick="comment(20210,753)">回复</button></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!--上一篇 & 下一篇-->
            <div class="page-turning clearfix bordered">

                <a class="col-md-6 text-center page-prev" href="<?php echo !empty($topArticle['id']) ? url('home/article', ['id' => $topArticle['id']])  :  'javascript:;'; ?>">
                    <div class="page-title">
                        <i class="fa fa-angle-left"></i> <span>上一篇</span>
                    </div>
                    <div class="article-title"> <?php echo !empty($topArticle['title']) ? htmlentities($topArticle['title']) :  '没有了'; ?> </div>
                </a>

                <a class="col-md-6 text-center page-next" href="<?php echo !empty($bottomArticle['id']) ? url('home/article', ['id' => $bottomArticle['id']])  :  'javascript:;'; ?>">
                    <div class="page-title">
                        <span>下一篇</span> <i class="fa fa-angle-right"></i>
                    </div>
                    <div class="article-title"> <?php echo !empty($bottomArticle['title']) ? htmlentities($bottomArticle['title']) :  '没有了'; ?> </div>
                </a>

            </div>

            <?php if(!(empty($banner['thumb']) || (($banner['thumb'] instanceof \think\Collection || $banner['thumb'] instanceof \think\Paginator ) && $banner['thumb']->isEmpty()))): ?>
            <div style="padding: 0px;text-align: center;">
                <img style="width: 64%;" src="<?php echo htmlentities($banner['thumb']); ?>">
            </div>
            <?php endif; ?>

            <!-- <div class="statement_jzc">
                <center>
                    <p class="font15 mb10" style="font-weight: bold">免责声明</p>
                </center>
                1、凡本网注明来源：草帽跨境www.caomaokj.com的所有作品，版权均属草帽跨境所有，转载请注明来源，违者本网将追究相关法律责任。<br>
                2、本网其他来源作品，均转载自其他媒体，目的在于传递更多信息，不表明证实其描述或赞同其观点，文章内容仅供参考不构成投资建议，投资者据此操作，风险自担。<br>
                3、若因《<?php echo htmlentities($article['title']); ?>》版权等问题需要与本网联络，请在30日内联系我们，联系电子邮件：3004630833@qq.com，我们会在第一时间删除。
            </div> -->

            <!-- 打开下面那个专题时，删除这里 -->
            <div class="article-after clearfix"></div>

            <!--所属专题-->
            <!-- <div class="article-after clearfix">
                <span class="included_span">本文被以下专题收录：</span>
                <div class="included_div">
                    <div class="included_img">
                        <a href="https://www.caomaokj.com/subject/439">
                            <img src="/static/akali/images/659c657b145961c1.jpg" style="width: 100%;height: 100%;">
                        </a>
                    </div>
                    <div class="included_desc_div">
                        <div class="included_title_div">
                            <a href="https://www.caomaokj.com/subject/439">shopee马来西亚站</a>
                        </div>
                        <div class="included_desc">
                            <a href="https://www.caomaokj.com/subject/439">shopee马来西亚站专题汇集了shopee马来西亚卖家开店流程与运营技巧，收集了关于马来西亚地区shopee平台最新的时事热点及政策规则。</a>
                        </div>
                    </div>
                    <div class="included_btn_div">
                        <a href="https://www.caomaokj.com/subject/439">
                            <button class="zhuanti_btn">进入专题</button>
                        </a>
                    </div>
                </div>
            </div> -->

            <div class="erweima-box clearfix"></div>
            <div class="stories_bg"></div>

            <!-- 评论区 -->
            <!-- 未登录显示 -->
            <?php if(empty($userid) || (($userid instanceof \think\Collection || $userid instanceof \think\Paginator ) && $userid->isEmpty())): ?>
            <!-- <div class="comment-textarea-div">
                <div class="prompt">
                    <span><a href="/login">登录</a>后参与评论</span>
                </div>
            </div> -->

            <!-- 登录时显示 -->
            <?php else: ?>
            <!-- <div class="comment-textarea-div">
                <textarea class="input-text" type="text" id="comment" name="comment" placeholder="说点什么吧..."></textarea>
                <div class="clearfix">
                    <div class="Avatar">
                        <img src="/static/akali/images/avatar.jpg" class="fl">
                        <div class="c_aaa fl"> 樱之节 </div>
                    </div>
                    <div class="fr sub-btn" onclick="comment('16907', 0)">发表评论</div>
                </div>
            </div> -->
            <?php endif; ?>

            <!-- 评论区容器 -->
            <!-- <div class="comment_stories_list" style="display: none;">
                <div class="h16 bg-color"></div>
                <div class="comment-div">
                    <ul class="stories_list">
                    </ul>
                </div>
            </div>
            
            <div class="h16 bg-color"></div> -->

            <!-- 相关阅读 -->
            <div class="related-reading">
                <h4>相关阅读</h4>
                <div class="media media-side clearfix">

                    <?php foreach($relatedArticle as $value): ?>
                    <div class="media-box col-md-4">
                        <div class="media-menu media_box_about">
                            <div class="media-cell media-top">
                                <a href="<?php echo url('home/article', ['id' => $value['id']]); ?>" title="<?php echo htmlentities($value['title']); ?>">
                                    <img class="warehouse_logo lazy" data-original="<?php echo htmlentities($value['thumb']); ?>" alt="<?php echo htmlentities($value['title']); ?>" style="display: inline;">
                                </a>
                            </div>
                            <div class="media-cell media-body clearfix text-center">
                                <p class="warehouses about_title_jzc" style="height: 45px;text-align: left">
                                    <span><a href="<?php echo url('home/article', ['id' => $value['id']]); ?>"><?php echo htmlentities($value['title']); ?></a></span>
                                </p>
                                <p class="about_desc">
                                    <span><a href="<?php echo url('home/article', ['id' => $value['id']]); ?>" ><?php echo htmlentities($value['description']); ?></a></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <div class="index-right">

            <div class="right-company">

                <!-- <a class="hot-img">
                    <img src="/static/icon/icon-caomao-small.png" alt="草帽跨境">
                </a>
                <div class="hot-text">
                    <h4>草帽跨境</h4>
                    <div class="subs">跨境卖家综合服务平台。找海外仓、找专线、找海外资源、找干货、找活动、找平台，就上草帽跨境</div>
                </div> -->

                <?php foreach($rightArticle as $value): ?>
                <div class="media">
                    <div class="media-left">
                        <a href="<?php echo url('home/article', ['id' => $value['id']]); ?>">
                            <div class="media-object media_object" style="position:relative;width:80px;height: 55px;background: url(<?php echo htmlentities($value['thumb']); ?>) no-repeat;background-size: cover;background-position: center center;">
                                <span class="article_tag"><?php echo getCateName($value['catid']); ?></span>
                            </div>
                        </a>
                    </div>
                    <div class="media-body media_body">
                        <a href="<?php echo url('home/article', ['id' => $value['id']]); ?>">
                            <p class="media-heading media_title"><?php echo htmlentities($value['title']); ?></p>
                        </a>
                        <p class="media_time">
                            <img src="/static/akali/images/57295c19757d0a2a.jpg">&nbsp;
                            <?php echo postTime($value['createtime']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
                <!-- <div class="hot-btn mt20">
                    <a target="_blank" class="look-btn" href="https://www.caomaokj.com/user/105794">查看他的主页</a>
                </div> -->

            </div>
            <!-- star -->
            <div style="margin-top: 20px;">
            </div>

            
<!-- 热门文章 最新文章 -->
<div style="">
    <style>
        .index-right h4 {
            overflow: hidden;
            color: #3f3f3f;
            font-size: 16px;
            font-weight: 500;
            padding: 0 10px;
        }

        .star_and_top_jzc .hot-article ul {}

        .star_and_top_jzc .hot-article ul li {
            /*border-bottom: 1px solid #ddd;*/
            font-size: 14px;
            /*padding: 10px 0;*/
            color: #555555;
            white-space: nowrap;
            overflow: hidden;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .star_and_top_jzc .hot-article ul li {
            color: #555555;
        }

        .star_and_top_jzc .hot-article b {
            display: inline-block;
            width: 22px;
            height: 20px;
            color: #ff7600;
            text-align: center;
            font-weight: bold;
            margin-right: 5px;
        }

        .star_and_top_jzc .hot-title li a {
            font-size: 18px;
            color: #333;
        }

        .star_and_top_jzc #user .logo-box img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
        }

        .star_and_top_jzc #user ul li {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .star_and_top_jzc #user ul li .user-btn {
            line-height: 45px;
            padding-left: 0;
        }

        .star_and_top_jzc #user ul li .user-home {
            display: inline-block;
            width: 64px;
            background: #ff7600;
            color: #fff;
            border-radius: 4px;
            height: 28px;
            line-height: 28px;
            text-align: center;
        }

        .star_and_top_jzc .tab-content ul li {
            border-bottom: 0px solid #ddd;
        }

        .star_and_top_jzc .nav-pills>li.active>a,
        .nav-pills>li.active>a:focus,
        .nav-pills>li.active>a:hover {
            color: #ff7600;
            background-color: transparent;
        }

        .star_and_top_jzc #article ul li,
        #article_new ul li {
            padding: 10px 0;
        }

        .star_and_top_jzc #article ul li:hover{
            /*border-left: 3px solid #ff7600;*/
        }

        .star_and_top_jzc {
            background: #fff;
            clear: both;
            /*border-bottom: 1px solid #ddd;*/
        }

        .star_and_top_jzc .hot-article {
            padding: 0 10px;
        }

        .star_and_top_jzc .nav-pills li,
        .nav-pills li.active {
            width: 49%;
            text-align: center;
        }

        .star_and_top_jzc>.nav-pills li,
        .nav-pills li.active a {
            /*font-size: 18px !important;*/
            color: #ff7600 !important;
        }

        .hot_title_jzc {
            border-bottom: 1px solid #F5F5F5;
        }

        .hot_title_jzc .active {
            border-bottom: 2px solid #ff7600;
        }

        .hot-article ul li a:hover {
            color: #ff7600;
        }
    </style>
    <div class="star_and_top_jzc">
        <ul class="nav nav-pills hot-title hot_title_jzc" role="tablist">
            <li class="active"><a href="<?php echo url('home/index'); ?>#article" data-toggle="pill">跨境头条</a>
            </li>
            <li><a href="<?php echo url('home/index'); ?>#article_new" data-toggle="pill">最新头条</a></li>
        </ul>

        <div class="tab-content">
            <div id="article" class="tab-pane hot-article active">
                <ul>

                    <?php foreach($hotArticle as $key => $value): ?>
                    <li><b class=""><?php echo htmlentities($value['num']); ?>F</b>
                        <a title="<?php echo htmlentities($value['title']); ?>" target="_blank" href="<?php echo url('home/article', ['id' => $value['id']]); ?>"><?php echo htmlentities($value['title']); ?></a>
                    </li>
                    <?php endforeach; ?>
                    
                </ul>
            </div>
            <div id="article_new" class="tab-pane hot-article">
                <ul>

                    <?php foreach($newArticle as $key => $value): ?>
                    <li><b class="icon-num<?php echo htmlentities($value['num']); ?>"><?php echo htmlentities($value['num']); ?>F</b>
                        <a title="<?php echo htmlentities($value['title']); ?>" target="_blank" href="<?php echo url('home/article', ['id' => $value['id']]); ?>"> <?php echo htmlentities($value['title']); ?> </a>
                    </li>
                    <?php endforeach; ?>

                </ul>
            </div>
        </div>

    </div>
</div>
            <div style="margin-top: 20px;border:1px solid #fff;background: #fff;padding:0px 0 10px 0">
                <div class="guest_you_like">
                    <div class="like_line">猜你喜欢</div>
                </div>

                <?php foreach($likeArticle as $value): ?>
                <div class="right_article_jzc">
                    <a href="<?php echo url('home/article', ['id' => $value['id']]); ?>"> <?php echo htmlentities($value['title']); ?> </a>
                    <p class="right_article_jzc_time"><?php echo postTime($value['createtime']); ?></p>
                </div>
                <?php endforeach; ?>

            </div>

            <!-- 文章热门标签 -->
            <!-- 关注热点 -->
<div style="margin-top: 15px;">
    <style>

        .content_div {
            min-width: 70px;
            height: 25px;
            float: left;
            line-height: 23px;
            text-align: center;
            margin-left: 10px;
            margin-top: 15px;
            padding: 0px 8px;
            border-radius: 20px;
            border: 1px solid #e5e5e5e5;
        }

        .tab-content {
            border: 0px solid red;
            padding-bottom: 20px;
        }

        li.active {
            width: 100%;
        }

        li.active a {
            font-size: 16px;
            display: inline-block;
        }

        .content_div:hover {
            border: 1px solid #fc9d27;
        }
    </style>
    <div class="hot-user">
        <h4 class="top_hot_div">
            <svg class="icon" aria-hidden="true" font-size="22px">
              <use xlink:href="#icon-remen"></use>
            </svg>&nbsp;
            <span style="font-size: 16px;color: #282828;">关注热点</span>
            <a style="color: #999999" href="<?php echo url('keyword/index'); ?>">更多&gt;</a>
        </h4>
        <div class="tab-content clearfix" style="border-top: 1px solid #eee;">
            <div id="article" class="active clearfix">
                <?php foreach($hotKeywords as $key => $value): ?>
                <div class="content_div">
                    <a style="color: #777777" href="<?php echo url('keyword/article', ['id' => $value['id']]); ?>"><?php echo htmlentities($value['name']); ?></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
        </div>
</div>
<style>
    .star_and_top {
        background: #fff;
        clear: both;
        border-bottom: 1px solid #ddd;
        width: 260px;
        margin-bottom: 2rem;
    }
</style>
<script>
    $(function() {
        $(".share_div,.share_jzc").mouseover(function() {
            $(".share_jzc").show();

        }).mouseout(function() {
            $(".share_jzc").hide();
        });


    })
    window._bd_share_config = {
        "common": {
            "bdSnsKey": {
                "tsina": "3429961073"
            },
            "bdText": $("#shareContent").val(),
            "bdMini": "2",
            "bdMiniList": false,
            "bdPic": "",
            "bdStyle": "1",
            "bdSize": "24"
        },
        "share": {}
    };
</script>
<script>
    window._bd_share_config = {
        "common": {
            "bdSnsKey": {
                "tsina": "3429961073"
            },
            "bdText": $("#shareContent").val(),
            "bdMini": "2",
            "bdMiniList": false,
            "bdPic": "",
            "bdStyle": "1",
            "bdSize": "24"
        },
        "share": {}
    };
    with(document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'https://www.caomaokj.com/static/akali/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
    </script>
</div>
</div>

        
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
<script type="text/javascript" src="/static/akali/js/search.js"></script>
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