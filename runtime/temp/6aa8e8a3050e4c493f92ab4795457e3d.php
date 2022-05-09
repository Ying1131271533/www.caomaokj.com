<?php /*a:2:{s:45:"../application/usezan/view/community\add.html";i:1650534573;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
<link rel="stylesheet" href="/static/admin/layui/css/layui.css" tppabs="/static/admin/layui/css/layui.css" media="all">
<div class="mainbox animated fadeInRight">
    <div class="mainnav_title">
        <a class="on">内容添加</a>
        <a class="add-edit" href="<?php echo url('community/index'); ?>?tree=<?php echo htmlentities($tree_id); ?>">[<b class="uzfont uzico-fanhui"></b>返回列表]</a>
        <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
    <form name="myform" id="myform" action="" method="post">
        <table cellpadding=0 cellspacing=0 class="table_form" width="100%">
            
            <tr>
                <td width="90"><font color="red">*</font><label>名称：</label></td>
                <td width="90"><input type="text" class="input-text" name="name" value="" size="50" placeholder="名称" validate="maxlength:30,required:true"></td>
            </tr>

            <tr>
                <td width="90" height="100"><font color="red">*</font><label>logo:</label></td>
                <td>
                    <div>
                        <div class="img-upload">
                            <img class="clogo" width="100" onclick="up_img('clogo','logo','jpg,gif,png,jpeg','10485760','290px',0);" src="/static/admin/Images/upload_thumb.png" alt="上传logo" style="cursor:pointer;">
                            <input type="hidden" class="logo" name="logo" value="" >
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td width="90"><font color="red">*</font><label>描述：</label></td>
                <td>
                    <input type="text" class="input-text" name="description" value="" size="50" placeholder="描述" validate="maxlength:120,required:true">
                </td>
            </tr>

            <tr>
                <td width="90"><label>主推服务：</label></td>
                <td>
                    <input type="text" class="input-text" name="service" value="" size="50" placeholder="主推服务" validate="maxlength:50">
                </td>
            </tr>

            <tr>
                <td width="90"><font color="red">*</font><label>简介：</label></td>
                <td><textarea rows="7" cols="65" name="introduction" placeholder=" 简介" validate="required:true"></textarea></td>
            </tr>

            <tr>
                <td width="90"><font color="red">*</font><label>官方网站：</label></td>
                <td>
                    <input type="text" class="input-text" name="url" value="" size="50" placeholder="主推服务" validate="required:true,maxlength:100">
                </td>
            </tr>

            <tr>
                <td width="90"><label>地址：</label></td>
                <td>
                    <input type="text" class="input-text" name="address" value="" size="50" placeholder="地址"validate="maxlength:60,required:true">
                </td>
            </tr>

            <tr>
                <td width="90"><label>联系方式：</label></td>
                <td>
                    <input type="text" class="input-text" name="connect" value="" size="50" placeholder="联系方式"validate="maxlength:50,required:true">
                </td>
            </tr>

            <tr>
                <td width="90">图片介绍</td>
                <td style="width:1000px;">

                    <div id="community-img" style="overflow: hidden;"></div>

                    <div class="layui-btn-group" style="margin-top: calc((140px - 100px)/2);">
                        <button type="button" class="layui-btn" id="test1">添加图片</button>
                        <button type="button" class="layui-btn layui-btn-danger" onclick="del_img()">删除图片</button>
                    </div>
                </td>
            </tr>

            <tr>
                <td>状态</td>
                <td>
                    <label><input type="radio" class="input_radio" name="status" checked value="1"> 开启</label>&nbsp;&nbsp;
                    <label><input type="radio" class="input_radio" name="status" value="0"> 关闭</label>
                </td>
            </tr>
            
        </table>
        <div id="bootline"></div>
        <div id="btnbox" class="btn">
            <input type="hidden" name="category_id" value="168" />
            <input type="submit" value="<?php echo lang('dosubmit'); ?>" class="button" />
            <input type="reset" value="<?php echo lang('cancel'); ?>" class="button" />
        </div>
    </form>
</div>
<script type="text/javascript" src="/static/admin/Js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/static/admin/Js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="/static/admin/Js/ueditor/lang/zh-cn/zh-cn.js"></script>


<script>
    layui.use('upload', function(){
      var upload = layui.upload
      ,$ = layui.$
      ,layer = layui.layer;
       
      //执行实例
      var uploadInst = upload.render({
        elem: '#test1' //绑定元素
        ,url: '/usezan/upload/file?type=image' //上传接口
        ,done: function(res){
            //上传完毕回调
            if(res.status != 20000){
                layer.msg(res.msg, {icon:2});
                return false;
            }
            layer.msg('上传成功', {icon:1});
            var html = '<div class="img-upload" style="float: left;"><img width="100" src="'+res.data.path+'" alt="上传imgs" style="cursor:pointer;"><input type="hidden" name="imgs[]" value="'+res.data.path+'" ></div>';
            $('#community-img').append(html);
        }
        ,error: function(){
            //请求异常回调
            layer.msg('上传失败', {icon:2});
        }
      });
    });
    function del_img(){
        $('#community-img .img-upload:last-child').remove();
    }
</script>

<script type="text/javascript">
    var ue = UE.getEditor('container', {
        initialFrameHeight: 300,
        allowDivTransToP:false,
        autoClearEmptyNode:false
    });
</script>