
$("form").submit(function(e){
    var val = $("input[name='thumb']").val();
    if(val == ''){
        layer.msg('封面不能为空', {icon:2});
        return false;
    }

    var keywords = $("input[name='ticketsId[]']:checked").val();
    if(!keywords) {
        layer.msg('关键词列表不能为空', {icon:2});
        return false;
    }
});

var ue = UE.getEditor('container', {
    initialFrameHeight: 300,
    allowDivTransToP:false,
    autoClearEmptyNode:false
});


$(".keyword-article").on("click", function () {
    $('.table_akali').hide();
    $('#akali-keyword').show();
});

$(".akali_btn").on("click", function () {
    $('.table_akali').show();
    $('#akali-keyword').hide();
    $(document).scrollTop(0);
});
