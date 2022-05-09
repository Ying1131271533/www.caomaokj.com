var _pageSize = 10;
_list_count="";
$(function () {
    $('.search-course-icon').click(function () {
        getList(1);
    })
    function lay_page(){
        if(_list_count ==''){
            _count=$("#list_count").val();
        }else{
            _count=_list_count;
        }
        laypage({
            cont: 'laypage', //容器。值支持id名、原生dom对象，jquery对象。【如该容器为】：<div id="page1"></div>
            pages: Math.ceil(_count/_pageSize), //通过后台拿到的总页数
            curr:  1,
            skin: '#fc9d27',
            first: '首页', //若不显示，设置false即可
            last: '尾页', //若不显示，设置false即可
            prev: '<', //若不显示，设置false即可
            next: '>', //若不显示，设置false即可
            jump: function (obj,first) { //触发分页后的回调
                if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                    getList(obj.curr);
                }
            }
        });
    }
    getList(1);
    lay_page();


    $('.wenzhang_nav .fenlei_article_style').click(function () {
        $(this).addClass("current_select_type").siblings().removeClass("current_select_type");
        getList(1);

    });

});
//数据列表请求
function getList(page){
    var loadding = layer.load(0, {shade: false});
    var cate_id=$('.wenzhang_nav .current_select_type').attr('data-type');
    var search_course=$('#search-course-text-input').val();
    if(search_course==''){
        search_course =  $('#search-name').val();
    }


    $.ajax({
        url: "/dolphin/get-search",
        type: "POST",
        async : false,
        data: {
            page: page,
            cate_id: cate_id,
            search_course: search_course,

        },
        dataType: "json",
        success: function (json) {
            layer.close(loadding);
            if (json.code == 200) {

                var html = '';

                if(json.data != ''){
                    $.each(json.data, function (key, val) {
                        html += '<li class="article_li">';
                        html += '<dl class="wz_info clearfix">';
                        html += '<dt>';
                        html += '<a href="'+val.url+'" title="'+val.title+'">';
                        html += '<img class="lazy" src="'+val.logo+'" alt="'+val.title+'" style="display: inline;">';
                        html += '</a>';
                        html += '</dt>';
                        html += '<dd>';
                        html += '<h4>';
                        html += '<a href="'+val.url+'" title="'+val.title+'">'+val.title+'</a>';
                        html += '</h4>';
                        html += '<p><a href="'+val.url+'" title="'+val.title+'">'+val.desc+'</a></p>';
                        html += '<div class="bot_tips">';
                        html += '<span class="color-aaa font12">';
                        html += '<a href="javascript:void(0)" class="pr15 a-small">'+val.add_user_name+'</a>'+val.add_time+'<span class="fr pr10">';
                        html += '<span></span>';
                        html += '</span>';
                        html += '</span>';
                        html += '</div>';
                        html += '</dd>';
                        html += '</dl>';
                        html += '</li>';
                    });
                }

                $("#search-content-list").html(html);
                if(search_course !=''){
                    $('#search-course-text-input').val(search_course);
                }else{
                    search_course='无';
                }
                var search_name= $('#search-name').val();
                if(search_name){
                    $('#search-course-text-input').val(search_name);
                }
                $("#list_count").val(json.count);
                $("#course-search-text-name").html(search_course);
                $("#span-course-list-count").html(json.count);
                _list_count=json.count;
                if(json.count<=10){
                    $('.laypage').hide();
                }else{
                    $('.laypage').show();
                }

            } else {
                layer.msg(data.msg);
            }
        },
        error: function () {
            layer.close(loadding);
            layer.msg('网络错误！');
        }
    });
}
