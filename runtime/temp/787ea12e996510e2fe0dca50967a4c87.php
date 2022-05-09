<?php /*a:2:{s:44:"../application/usezan/view/service\edit.html";i:1647678705;s:45:"../application/usezan/view/public\header.html";i:1630479318;}*/ ?>
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
        <a class="add-edit" href="<?php echo url('service/index'); ?>?tree=<?php echo htmlentities($tree_id); ?>">[<b class="uzfont uzico-fanhui"></b>返回列表]</a>
        <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
    <form name="myform" id="myform" action="" method="post">
        <table cellpadding=0 cellspacing=0 class="table_form" width="100%">
            <tr>
                <td width="90"><font color="red">*</font><label>所属分类：</label></td>
                <td>
                    <select class="catid" name="category_id" validate="required:true">
                        <option value="" selected>请选择分类</option>
                        <?php foreach($category as $value): ?>
                        <option value="<?php echo htmlentities($value['id']); ?>" <?php if($value['id'] == $service['category_id']): ?>selected<?php endif; ?>><?php echo htmlentities($value['catname']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="90"><font color="red">*</font><label>名称：</label></td>
                <td>
                    <div class="thumb_box ">
                        <div class="img-upload">
                            <img class="cthumb" width="100" onclick="up_img('cthumb','thumb','jpg,gif,png,jpeg','10485760','290px',0,'<?php echo htmlentities($extend_config['buck_on']); ?>');" src="<?php echo empty($service['image']) ? '/static/admin/Images/upload_thumb.png' : $service['image']; ?>" alt="上传封面">
                            <input type="hidden" class="thumb" name="image" value="<?php echo htmlentities($service['image']); ?>">
                            <input type="text" class="input-text" name="name" value="<?php echo htmlentities($service['name']); ?>" size="50" placeholder="名称" validate="minlength:2,maxlength:80,required:true">
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td width="90"><label>描述：</label></td>
                <td><textarea rows="7" cols="65" name="description" placeholder="内容描述"><?php echo htmlentities($service['description']); ?></textarea></td>
            </tr>

            <tr>
                <td width="90"><label>官网链接：</label></td>
                <td>
                    <input type="text" class="input-text" name="url" value="<?php echo htmlentities($service['url']); ?>" size="50" placeholder="官网链接">
                </td>
            </tr>

            <tr>
                <td width="90"><label>手机：</label></td>
                <td>
                    <input type="text" class="input-text" name="phone" value="<?php echo htmlentities($service['phone']); ?>" size="50" placeholder="手机">
                </td>
            </tr>

            <tr>
                <td width="90"><label>QQ：</label></td>
                <td>
                    <input type="text" class="input-text" name="qq" value="<?php echo htmlentities($service['qq']); ?>" size="50" placeholder="QQ">
                </td>
            </tr>


            <tr>
                <td width="90" height="100"><font color="red">*</font><label>微信二维码：</label></td>
                <td>
                    <div>
                        <div class="img-upload">
                            <img class="cwechat" width="100" onclick="up_img('cwechat','wechat','jpg,gif,png,jpeg','10485760','290px',0,'<?php echo htmlentities($extend_config['buck_on']); ?>');" src="<?php echo empty($service['wechat']) ? '/static/admin/Images/upload_thumb.png' : $service['wechat']; ?>" alt="上传封面" style="cursor:pointer;">
                            <input type="hidden" class="wechat" name="wechat" value="<?php echo htmlentities($service['wechat']); ?>" validate="required:true">
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td width="90"><label>二维码备注：</label></td>
                <td>
                    <input type="text" class="input-text" name="remarks" value="<?php echo htmlentities($service['remarks']); ?>" size="50" placeholder="二维码扫码联系，备注">
                </td>
            </tr>

            <?php if(!(empty($service['detail']['featured']) || (($service['detail']['featured'] instanceof \think\Collection || $service['detail']['featured'] instanceof \think\Paginator ) && $service['detail']['featured']->isEmpty()))): ?>
            <tr>
                <td width="90">主推服务</td>
                <td style="width:1000px;">
                    <fieldset class="images_box">
                        <legend>图片上传</legend>
                        <div id="pics_images" class="imagesList nopic album_more">
                            <?php foreach($service['detail']['featured'] as $value): ?>
                            <div class="uplist">
                                <img src="<?php echo htmlentities($value['0']); ?>" width="150" height="60" style="float:left;margin-right:10px">
                                <input type="hidden" size="10" class="input-text" name="imgs[]" value="<?php echo htmlentities($value['0']); ?>">
                                <input type="text" class="input-text" placeholder="排序" name="imgs_order[]" size="2" value="<?php echo htmlentities($value['1']); ?>">
                                <input type="text" class="input-text" placeholder="标题" name="imgs_title[]" size="40" value="<?php echo htmlentities($value['2']); ?>">
                                <textarea name="imgs_remark[]" row="4" style="resize:none;" placeholder="简介" cols="60"><?php echo htmlentities($value['3']); ?></textarea>&nbsp;<a onclick="remove_thumbs(this)" href="javascript:;">移除</a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>
                    <input type="button" class="button" value="图片上传" onclick="up_img('imgs','imgs','jpg,gif,png,jpeg','2097152','390px',1,'<?php echo htmlentities($extend_config['buck_on']); ?>');">
                </td>
            </tr>
            
            <?php else: ?>

            <tr>
                <td width="90">主推服务</td>
                <td style="width:1000px;">
                    <fieldset class="images_box">
                        <legend>图片上传</legend>
                        <div id="pics_images" class="imagesList nopic album_more"></div>
                    </fieldset>
                    <input type="button" class="button" value="图片上传" onclick="up_img('imgs','imgs','jpg,gif,png,jpeg','2097152','390px',1,'<?php echo htmlentities($extend_config['buck_on']); ?>');">
                </td>
            </tr>
            <?php endif; ?>



            <!-- <tr>
                <td width="90"><label>企业介绍：</label></td>
                <td><textarea rows="8" cols="100" name="company" placeholder="企业介绍"><?php echo htmlentities($service['detail']['company']); ?></textarea></td>
            </tr> -->

            <tr>
                <td>状态</td>
                <td>
                    <label><input type="radio" class="input_radio" name="status" value="1" <?php echo $service['status']==1 ? 'checked'  :  ''; ?>> 开启</label>&nbsp;&nbsp;
                    <label><input type="radio" class="input_radio" name="status" value="0" <?php echo $service['status']==0 ? 'checked'  :  ''; ?>> 关闭</label>
                </td>
            </tr>
            
            <tr class="editor">
                <td>服务介绍:</td>
                <td style="width:1000px;"><script id="container" name="content" type="text/plain"><?php echo $service['detail']['content']; ?></script></td>
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
        autoClearEmptyNode:false,
    });
</script>