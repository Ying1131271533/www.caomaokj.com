<?php /*a:2:{s:43:"../application/usezan/view/article\add.html";i:1650683238;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
<style type="text/css">
    .akali-keyword{
        position: fixed;
        width: 100%;
        height: 100%;
        top: 50px;
        overflow-x: scroll;
        padding-bottom: 200px;
    }
</style>
<div class="mainbox animated fadeInRight">
    <div class="mainnav_title">
        <a class="on">内容添加</a>
        <a class="add-edit" href="<?php echo url('article/index'); ?>?tree=<?php echo htmlentities($tree_id); ?>">[<b class="uzfont uzico-fanhui"></b>返回列表]</a>
        <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
    <form name="myform" id="myform" action="" method="post">
        <div class="table_akali">
            <table cellpadding=0 cellspacing=0 class="table_form" width="100%">
                <tr>
                    <td width="90"><font color="red">*</font><label>所属分类：</label></td>
                    <td>
                        <select class="catid" name="catid" validate="required:true">
                            <option value="" selected>请选择分类</option>
                            <?php foreach($category as $value): ?>
                            <option value="<?php echo htmlentities($value['id']); ?>"><?php echo htmlentities($value['catname']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td width="90"><font color="red">*</font><label>标题：</label></td>
                    <td>
                        <input type="text" class="input-text" name="title" size="50" placeholder="标题" validate="minlength:2,maxlength:80,required:true">
                    </td>
                </tr>

                <tr>
                    <td width="90"><font color="red">*</font><label>描述：</label></td>
                    <td><textarea rows="5" cols="100" name="description" placeholder="内容描述" validate="required:true,maxlength:255"></textarea></td>
                </tr>
                
                <tr>
                    <td width="90" height="100"><font color="red">*</font><label>封面：</label></td>
                    <td>
                        <div>
                            <div class="img-upload">
                                <img class="cthumb" width="100" onclick="up_img('cthumb','thumb','jpg,gif,png,jpeg','10485760','290px',0);" src="/static/admin/Images/upload_thumb.png" alt="上传thumb" style="cursor:pointer;">
                                <input type="hidden" class="thumb" name="thumb" value="" >
                            </div>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td width="90"><font color="red">*</font><label>关键字：</label></td>
                    <td>
                        <input type="text" class="input-text" name="keyword" size="50" placeholder="例如：电商、开店、海外仓等等" validate="minlength:2,maxlength:10,required:true">
                    </td>
                </tr>

                <tr>
                    <td width="90"><font color="red">*</font><label>关键词列表：</label></td>
                    <td><a class="keyword-article" href="javascript:;">【 关键词列表 】</a></td>
                </tr>
                <tr>
                    <td width="90"><label>浏览数：</label></td>
                    <td>
                        <input type="text" class="input-text" name="view" size="6" placeholder="0">
                    </td>
                </tr>
                <tr>
                    <td>是否推荐：</td>
                    <td>
                        <label><input type="radio" class="input_radio" name="ispos" value="1" > 是</label>&nbsp;&nbsp;
                        <label><input type="radio" class="input_radio" name="ispos" value="0" checked> 否</label>
                    </td>
                </tr>

                <tr>
                    <td>状态</td>
                    <td>
                        <label><input type="radio" class="input_radio" name="status" value="1" checked> 开启</label>&nbsp;&nbsp;
                        <label><input type="radio" class="input_radio" name="status" value="0" > 关闭</label>
                    </td>
                </tr>
                <tr>
                    <td>添加时间</td>
                    <td>
                        <input class="Wdate input-text" name="createtime" type="text" size="25" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo date('Y-m-d H:i:s',time()); ?>">
                    </td>
                </tr>

                <tr class="editor">
                    <td>内容:</td>
                    <td style="width:1000px;"><script id="container" name="content" type="text/plain"></script></td>
                </tr>

            </table>
            <div id="bootline"></div>
            <div id="btnbox" class="btn">
                <input type="submit" value="<?php echo lang('dosubmit'); ?>" class="button" />
                <input type="reset" value="<?php echo lang('cancel'); ?>" class="button" />
            </div>
        </div>

        <!-- 关键词列表 -->
        <div id="akali-keyword" style="display:none;">
            <div class="node">

                <div class="node-list-che">
                    <div class="cd-left" style="padding-bottom: 60px;">
                        <?php foreach($list as $key => $value): ?>
                        <h3><?php echo htmlentities($key); ?></h3>
                        <?php foreach($value as $val): ?>
                        &nbsp;<span class="left-span">
                            <label><input type="checkbox" name="keywords[]" class="i-checks" value="<?php echo htmlentities($val['id']); ?>"> <?php echo htmlentities($val['name']); ?></label>
                        </span>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>

                    <div id="btnbox" class="btn" style="padding-left: 20px;line-height: 40px;height: 40px;position: fixed;">
                        <a type="submit" class="button akali_btn" style="padding: 10px 30px;">确定</a>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>
<script type="text/javascript" src="/static/admin/Js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/admin/Js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="/static/admin/Js/ueditor/lang/zh-cn/zh-cn.js"></script>

<script type="text/javascript" src="/static/admin/Js/akali.js"></script>



<script type="text/javascript">
$(".checkAll").on('click', function() {
    $(".i-checks").prop("checked", this.checked);
});
$(".nav-check").on("click", function() {
    var parents = $(this).parents("h5");
    parents.next(".node-list-che").find(".i-checks").prop("checked", this.checked);
});
$(".cd-two").on("click", function() {
    var parents = $(this).parents(".cd-left");
    parents.find(".i-checks").prop("checked", this.checked);
});
</script>