<?php /*a:2:{s:45:"../application/usezan/view/logistics\add.html";i:1650522333;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
        <a class="on">内容添加</a>
        <a class="add-edit" href="<?php echo url('logistics/index'); ?>?tree=<?php echo htmlentities($tree_id); ?>">[<b class="uzfont uzico-fanhui"></b>返回列表]</a>
        <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
    <form name="myform" id="myform" action="" method="post">
        <table cellpadding=0 cellspacing=0 class="table_form" width="100%">
            <tr>
                <td width="90"><font color="red">*</font><label>所属分类：</label></td>
                <td>
                    <select class="catid" name="catid">
                        <?php if(isset($select_categorys) and $select_categorys): ?><?php echo $select_categorys; ?><?php endif; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="90"><font color="red">*</font><label>标题：</label></td>
                <td>
                    <div class="thumb_box ">
                        <div class="img-upload">
                            <img class="cthumb" width="100" onclick="up_img('cthumb','thumb','jpg,gif,png,jpeg','10485760','290px',0);" src="/static/admin/Images/upload_thumb.png" alt="上传封面">
                            <input type="hidden" class="thumb" name="thumb">
                            <input type="text" class="input-text" name="title" size="50" placeholder="标题" validate="minlength:2,maxlength:80,required:true">
                        </div>
                    </div>
                </td>
            </tr>


            <tr>
                <td width="90"><label>手机：</label></td>
                <td>
                    <input type="text" class="input-text" name="phone" value="13829730146" size="50" placeholder="手机">
                </td>
            </tr>

            <tr>
                <td width="90"><label>QQ：</label></td>
                <td>
                    <input type="text" class="input-text" name="qq" value="2881930690" size="50" placeholder="QQ">
                </td>
            </tr>

            <tr>
                <td width="90"><label>邮箱：</label></td>
                <td>
                    <input type="text" class="input-text" name="email" value="2881930690@qq.com" size="50" placeholder="邮箱">
                </td>
            </tr>

            <tr>
                <td width="90" height="100"><font color="red">*</font><label>微信二维码：</label></td>
                <td>
                    <div>
                        <div class="img-upload">
                            <img class="cwechat" width="100" onclick="up_img('cwechat','wechat','jpg,gif,png,jpeg','10485760','290px',0);" src="/storage/20210705/61b902c0426bfb37cbe7a78a651ed35c.jpg" alt="上传封面" style="cursor:pointer;">
                            <input type="hidden" class="wechat" name="wechat" value="/storage/20210705/61b902c0426bfb37cbe7a78a651ed35c.jpg" validate="required:true">
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>走货属性：</td>
                <td>
                    <?php foreach($attributes as $value): ?>
                    <label><input type="checkbox" class="input_radio" name="attributes[<?php echo htmlentities($value['id']); ?>]" value="<?php echo htmlentities($value['id']); ?>"  /> <?php echo htmlentities($value['name']); ?>&nbsp;&nbsp;</label>
                    <?php endforeach; ?>
                </td>
            </tr>

            <tr>
                <td>服务类型：</td>
                <td>
                    <?php foreach($seviceType as $value): ?>
                    <label><input type="checkbox" class="input_radio" name="service[<?php echo htmlentities($value['id']); ?>]" value="<?php echo htmlentities($value['id']); ?>"  /> <?php echo htmlentities($value['name']); ?>&nbsp;&nbsp;</label>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td width="90"><label>地址：</label></td>
                <td>
                    <input type="text" class="input-text" name="address" value="广州市白云区鹤边一路国妆电商大厦606" size="50" placeholder="地址">
                </td>
            </tr>
            <tr>
                <td>状态</td>
                <td>
                    <label><input type="radio" class="input_radio" name="status" checked value="1"> 开启</label>&nbsp;&nbsp;
                    <label><input type="radio" class="input_radio" name="status" value="0"> 关闭</label>
                </td>
            </tr>

            <!-- <tr>
                <td width="90">主推服务</td>
                <td style="width:1000px;">
                    <fieldset class="images_box">
                        <legend>图片上传</legend>
                        <div id="pics_images" class="imagesList nopic album_more"></div>
                    </fieldset>
                    <input type="button" class="button" value="图片上传" onclick="up_img('imgs','imgs','jpg,gif,png,jpeg','2097152','390px',1);">
                </td>
            </tr> -->
            
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
    </form>
</div>
<script type="text/javascript" src="/static/admin/Js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/admin/Js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="/static/admin/Js/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
    var ue = UE.getEditor('container', {
        initialFrameHeight: 300,
        allowDivTransToP:false,
        autoClearEmptyNode:false
    });
</script>