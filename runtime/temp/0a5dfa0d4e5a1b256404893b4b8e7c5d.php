<?php /*a:2:{s:45:"../application/usezan/view/keyword\index.html";i:1646211415;s:45:"../application/usezan/view/public\header.html";i:1650422517;}*/ ?>
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
        <a class="on">关键词列表</a>
        <a class="add-edit " href="<?php echo url('keyword/add'); ?>">[<b class="uzfont uzico-add"></b>关键词添加]</a>
        <a class="bth-rand fr" href="javascript:location.replace(location.href);" title="刷新"><b class="uzfont uzico-shuaxin"></b></a>
    </div>
    <table class="search_table" width="100%">
        <tbody>
            <tr>
                <td class="search">
                    <form action="<?php echo url('keyword/index'); ?>" method="post">
                        <input id="title" type="text" class="input-text" name="keyword" placeholder="关键字搜索" value="">
                        <input type="submit" value="查询" class="button">
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    <form name="myform" action="<?php echo url('keyword/sort'); ?>" method="post">
        <div class="table-list">
            <table width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="40">排序</th>
                        <th width="40">ID</th>
                        <th width="100" align="center">热门关键词</th>
                        <th align="left">关键词名称</th>
                        <th align="left"><?php echo lang('manage'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($list as $key => $value): ?>
                    <tr>
                        <td width="40" align="center"><input name="sort[<?php echo htmlentities($value['id']); ?>]" type="text" size="3" value="<?php echo htmlentities($value['sort']); ?>" class="input-text-c"></td>
                        <td align='center'><?php echo htmlentities($value['id']); ?></td>
                        <!-- <td width="100" align="center"><?php echo !empty($value['hot_spot']) ? '是'  :  '否'; ?></td> -->
                        <td align="center">
                            <a onclick="ajax_status(this)" data-id="<?php echo htmlentities($value['id']); ?>" data-url="<?php echo url('ajax/status', ['id' => $value['id'], 'value' => $value['hot_spot'], 'field' => 'hot_spot', 'db' => 'keyword']); ?>" class="bth-a ajax-status <?php echo $value['hot_spot']==1 ? htmlentities($value['hot_spot']) : 'error-c'; ?>"></a>
                        </td>
                        <td><?php echo htmlentities($value['name']); ?></td>
                        <td align="center">
                            <a href="<?php echo url('keyword/edit', ['id' => $value['id']]); ?>">修改</a> |
                            <a href="javascript:del(<?php echo htmlentities($value['id']); ?>);">删除</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="btn"><input type="submit" class="button" name="dosubmit" value="<?php echo lang('listorder'); ?>" /></div>
        </div>
    </form>
</div>
<div id="pages" class="page"><?php echo $list; ?></div>
<script>
function del(id) {
    layer.confirm('确定要删除关键词吗', function(index) {
        window.location.href = "<?php echo url('keyword/del'); ?>?id=" + id;
        layer.close(index);
    });
}
</script>