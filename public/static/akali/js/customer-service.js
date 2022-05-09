$(function () {




    layui.use(['element','layer','form'],function(){


        $(document).on('click','#suggestion-form .suggestion-btn-box',function () {
            var loading = layer.load();
            $.ajax({
                url: "/customer-service/edit-suggestion",
                type: "POST",
                data:$('#suggestion-form').serialize(),
                dataType: "json",
                success: function (json) {

                    if(json.code==0){
                        $('#suggestion-form')[0].reset();
                    }
                    layer.msg(json.msg);
                    layer.close(loading);
                },
                error: function () {
                    layer.close(loading);
                    layer.msg('网络错误！');
                }
            });
        });
    });

});