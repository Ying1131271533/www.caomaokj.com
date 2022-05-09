
$(function (){
    //首页搜索
    $('#fangdajing').click(function () {
        submitSearch_site();
    });
    $("#search-form").on("submit",function () { //这是写死的不用改 直接在下面调AJAX就行
        submitSearch_site();
    });

});

function submitSearch_site(page) {
    var params = '';
    //关键词
    var keyword = $("#keyword_search").val();
    var select_type = 1;
    if(keyword ==''){
        layer.msg('温馨提示：搜索关键词不能为空！', {icon:2});
        return false;
    }
    layer.load();
    var home_url = HOME_URL;
    if (keyword !='') {
        params += 'keyword=' + keyword + '&'+'select_type='+select_type + '&';
    }

    //分页
    if (page > 1) {
        params += 'page=' + page + '&';
    }
    //去除最后一个&
    if (params !='') {
        params = '?' + params.substr(0, params.length - 1);
    }
    //console.log(params);return;
    // window.location.href = home_url+'/home/search' + params;
    window.location.href = '/index/home/search' + params;
}
