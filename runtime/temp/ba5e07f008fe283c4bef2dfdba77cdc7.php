<?php /*a:3:{s:46:"../application/usezan/view/category\index.html";i:1624847905;s:45:"../application/usezan/view/public\header.html";i:1650422517;s:49:"../application/usezan/view/public\menu_right.html";i:1574769382;}*/ ?>
<!DOCTYPE html>

<html lang="zh-cn">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<link rel="shortcut icon" href="/static/icon/icon.ico" />

<title><?php echo lang('usezan_title'); ?></title>

<link rel="stylesheet" type="text/css" href="/static/admin/Css/style.css" />

<link rel='stylesheet' type='text/css' href='/static/admin/font/uzfont.css' />

<script type="text/javascript" src="/static/admin/Js/jquery.min.js"></script>

<script type="text/javascript" src="/static/admin/Js/jquery.form.js"></script>

<script type="text/javascript" src="/static/admin/Js/jquery.validate.js"></script>

<script type="text/javascript" src="/static/admin/Js/my.js"></script>

<script type="text/javascript" src="/static/admin/Js/layer-v2.3/layer.js"></script>

<script type="text/javascript" src="/static/admin/Js/MyDate/WdatePicker.js"></script>

<script type="text/javascript" src="/static/akali/layui/css/layui.css"></script>
<script type="text/javascript" src="/static/akali/layui/layui.js"></script>


<script language="JavaScript">

var PUBLIC = '/static/admin/';

var CONTROLLER_NAME = '<?php echo request()->controller(); ?>';

var ACTION_NAME = '<?php echo request()->action(); ?>';

function confirm_delete(url){

    layer.confirm("<?php echo lang('real_delete'); ?>", {

        title:'~~删除提示~~',

        btn: ['删除','算了吧']

    }, function(){

        location.href = url;

    });

}

</script>

</head>

<body width="100%">

<!-- Template Ceng Start -->

<div class="dask-template">

    <div class="lists">

        <span class="load-ing"></span>

        <span class="close-x" onclick="close_x(this);"></span>

        <ul id="lists-temp"></ul>

    </div>

</div>

<!-- Template Ceng End -->

<!-- 查看图片 -->
<div id="akali" class="hide layui-layer-wrap" style="display: none;">
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
<div class="mainbox animated fadeInRight">
    <div class="mainnav_title">
        <a class="on">栏目列表</a>
        <?php if(isset($auth_tree) and $auth_tree): if(is_array($auth_tree) || $auth_tree instanceof \think\Collection || $auth_tree instanceof \think\Paginator): $i = 0; $__LIST__ = $auth_tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
<a class="add-edit class-<?php echo htmlentities($vo['id']); if($current_url == $vo['name']): ?> onn<?php endif; ?>" href="<?php echo url($vo['name']); ?>?tree=<?php echo htmlentities($vo['parentid']); ?>">[<b class="uzfont <?php echo htmlentities($vo['uzico']); ?>"></b><?php echo htmlentities($vo['title']); ?>]</a>
<?php endforeach; endif; else: echo "" ;endif; ?>
<?php endif; ?>
        <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
    <form name="myform" action="<?php echo url('category/listorder'); ?>" method="post">
        <div class="pad-lr-10">
            <div class="table-list">
                <table width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40">排序</th>
                            <th width="40">catid</th>
                            <th align="center" width="50">导航显示</th>
                            <th align="left" width="300">栏目名称</th>
                            <th align="left" width="200">栏目路径</th>
                            <th align="left" width="90">所属模型</th>
                            <th width="60">状态</th>
                            <th align="left">管理操作</th>
                        </tr>
                    </thead>
                    <tbody id="categorys">
                        <?php if(isset($cate) and $cate): ?><?php echo $cate; ?><?php endif; ?>
                    </tbody>
                </table>
                <div class="btn"><input type="submit" class="button" name="dosubmit" value="<?php echo lang('listorder'); ?>" /></div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
$(function() {
    var id = '';
    $(".open").on("click", function() {
        var parents = $(this).parents(".open-tr");
        data = parents.attr("data-child"),
            dataarr = data.split(',');
        if (parents.hasClass("open-on")) {
            $("#categorys>tr").each(function(i, k) {
                id = $(this).attr("data-id");
                if (-1 != $.inArray(id, dataarr)) {
                    $(".tr-" + id).removeClass("hide");
                }
            });
            parents.removeClass("open-on");
        } else {
            $("#categorys>tr").each(function(i, k) {
                id = $(this).attr("data-id");
                if (-1 != $.inArray(id, dataarr)) {
                    $(".tr-" + id).addClass("hide");
                }
            });
            parents.addClass("open-on");
        }
    });
})
</script>