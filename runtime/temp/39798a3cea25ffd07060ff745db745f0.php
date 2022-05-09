<?php /*a:2:{s:46:"../application/usezan/view/auth\auth_node.html";i:1501555782;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
<div class="node">
    <div class="node-list">
    <form class="action-form" action="" method="post">
        <span class="all-check"><input type="checkbox" class="checkAll" />全选</span>
        <ul>                    
            <?php if(is_array($node) || $node instanceof \think\Collection || $node instanceof \think\Paginator): $i = 0; $__LIST__ = $node;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                <li>
                    <h5><input type="checkbox" name="rules[]" value="<?php echo htmlentities($v['id']); ?>" <?php echo htmlentities($v['check']); ?> class="nav-check i-checks" /><?php echo htmlentities($v['title']); ?></h5>
                    <div class="node-list-che">
                    <?php if(is_array($v['lower']) || $v['lower'] instanceof \think\Collection || $v['lower'] instanceof \think\Paginator): $i = 0; $__LIST__ = $v['lower'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
                        <div class="cd-left">
                            <p><input type="checkbox" <?php echo htmlentities($vv['check']); ?> name="rules[]" class="cd-two i-checks" value="<?php echo htmlentities($vv['id']); ?>" /><?php echo htmlentities($vv['title']); ?>[CD]</p>
                            <?php if(is_array($vv['lower']) || $vv['lower'] instanceof \think\Collection || $vv['lower'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vv['lower'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vvv): $mod = ($i % 2 );++$i;?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="left-span"><input type="checkbox" <?php echo htmlentities($vvv['check']); ?> name="rules[]" class="i-checks" value="<?php echo htmlentities($vvv['id']); ?>" /><?php echo htmlentities($vvv['title']); ?></span>
                            <?php if(is_array($vvv['lower']) || $vvv['lower'] instanceof \think\Collection || $vvv['lower'] instanceof \think\Paginator): if( count($vvv['lower'])==0 ) : echo "" ;else: foreach($vvv['lower'] as $key=>$vvvv): ?>
                            <span><input type="checkbox" <?php echo htmlentities($vvvv['check']); ?> name="rules[]" class="i-checks" value="<?php echo htmlentities($vvvv['id']); ?>" /><?php echo htmlentities($vvvv['title']); ?></span>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <input type="hidden" name="id" value="<?php echo htmlentities($nid); ?>" />
        <input type="submit" class="bth bth-node" value="保存" />
    </form>    
    </div>
</div>
<script type="text/javascript">
$(".checkAll").on('click', function(){
    $(".i-checks").prop("checked", this.checked);
});
$(".nav-check").on("click",function () {
    var parents = $(this).parents("h5");
        parents.next(".node-list-che").find(".i-checks").prop("checked", this.checked);
});
$(".cd-two").on("click",function () {
    var parents = $(this).parents(".cd-left");
    parents.find(".i-checks").prop("checked", this.checked);
});
</script>