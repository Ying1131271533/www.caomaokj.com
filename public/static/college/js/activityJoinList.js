var _page = 1;

//获取到当前url的参数
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}
//如果获取到Url参数，则处理
var position_type = getQueryString('position_type');



$(function () {


    if(position_type == 1){
        $('.buy_video').trigger('click');
        $('.my_get').trigger('click');
    }else{
        setTimeout(function() {
            getList(_page);
        }, 200);
    }

    if(position_type == 2){
        $('.buy_video').trigger('click');
        $('.my_share').trigger('click');
    }else{
        setTimeout(function() {
            getList(_page);
        }, 200);
    }
});

function layerPage(paginator) {
    layui.use('laypage', function() {
        var laypage = layui.laypage;
        laypage.render({
            elem : 'laypage',
            theme : '#fc9d27',
            curr : paginator.page || 1,
            count : paginator.count,
            limit : paginator.pageSize || 10,
            jump : function(obj, first) {
                if (!first) {
                    getList(obj.curr);
                }
            }
        });
    });
}

function layerPageBuyVideo(paginator) {
    layui.use('laypage', function() {
        var laypage = layui.laypage;
        laypage.render({
            elem : 'laypage_buy_video',
            theme : '#fc9d27',
            curr : paginator.page || 1,
            count : paginator.count,
            limit : paginator.pageSize || 10,
            jump : function(obj, first) {
                if (!first) {
                    getVideoBuyList(obj.curr);
                }
            }
        });
    });
}

function layerPageShareVideo(paginator) {
    layui.use('laypage', function() {
        var laypage = layui.laypage;
        laypage.render({
            elem : 'laypage_share_video',
            theme : '#fc9d27',
            curr : paginator.page || 1,
            count : paginator.count,
            limit : paginator.pageSize || 10,
            jump : function(obj, first) {
                if (!first) {
                    getVideoBuyList(obj.curr);
                }
            }
        });
    });
}

function layerPageGetVideo(paginator) {
    layui.use('laypage', function() {
        var laypage = layui.laypage;
        laypage.render({
            elem : 'laypage_get_video',
            theme : '#fc9d27',
            curr : paginator.page || 1,
            count : paginator.count,
            limit : paginator.pageSize || 10,
            jump : function(obj, first) {
                if (!first) {
                    getVideoGetList(obj.curr);
                }
            }
        });
    });
}

//线下课程根据不同的订单状态查询
$('.select_p').click(function(){
    $('.select_p').css({'color':'','font-weight':''}).removeClass('select_course');
	$(this).addClass('select_course');
	$(this).css({'color':'#3171FF','font-weight':'bold'});
    getList(_page);
})

//购买的视频列表根据不同的订单状态查询
$('.select_p_buy').click(function(){
    $('.select_p_buy').css({'color':'','font-weight':''}).removeClass('select_course');
    $(this).addClass('select_course_buy');
    $(this).css({'color':'#3171FF','font-weight':'bold'});
    getVideoBuyList(_page);
})

layui.use('element', function(){
    var element = layui.element;

// 监听tab切换 操作：文档 - 内置模块 - 常用元素操作 element - 监听tab切换
    element.on('tab(tab-all)', function (data) {
        //console.log(this);        // 当前Tab标题所在的原始DOM元素
        //console.log(data.type);  // 得到当前Tab的所在下标
        // console.log(data.elem);   // 得到当前的Tab大容器
        var data_type = $(this).attr('data-type');
        console.log(data_type);
        if(data_type == 'offline'){

            getList(_page);

        }else if(data_type == 'my_share'){

            getVideoShareList(_page);

            //alert(111);
        }else if(data_type == 'my_get'){

            getVideoGetList(_page);

        }else if(data_type == 'buy_video'){

            getVideoBuyList(_page);

        }


    })
    //…
});

function getList(pageNum){
	var loading = layer.load();
	var select_course = $('.select_course').attr('data-id');
	var user_url = $('.user_url').val();
	$.ajax({
		url: '/dolphin/join',
	    type: "POST",
	    data:{
			page: pageNum,
            select_course:select_course,
		},
	    dataType:'json',
	    error: function() {
	    	layer.close(loading);
            layer.msg('网络错误！');
	    },
	    success: function(json) {
	    	layer.close(loading);
	    	if (json.code){
	    		var html = '';
	    		var tbody = $('#table-list').find('tbody');
	    		if(json.data != ''){
	    			$.each(json.data, function(key, val) {
	    				html += '<tr>';
	    				html += '<td>' + (key+1) + '</td>';
	    				html += '<td>';
	    				html += '<a target="_blank" title="'+val.activity_name+'" href="/activity/'+ val.activity_id +'">'+ val.activity_name_short +'</a>';
	    				if(val.at_id == '8') {
	    					html += '<span class="live_activity">live</span>';
	    				}
	    				html += '<br>';
	    				html += '时间：'+ val.activity_start_time + ' ~ '+ val.activity_end_time +'<br>';
	    				html += '活动状态：' + val.status;
	    				html += '</td>';
	    				html += '<td>' + val.af_title + '('+val.af_desc+')</td>';
	    				html += '<td>' + val.order_status_name + '</td>';
	    				html += '<td>';
	    				html += '￥'+ val.pay_amount + '<br>';
	    				if(val.need_pay == 0){
	    					html += '无需支付';
	    				}else{
	    					if(val.pay_status == 2){
	    						html += '已支付';
	    					}else{
	    						html += '未支付';
	    					}
	    				}
	    				html += '</td>';
	    				html += '<td>' + val.pay_add_date + '</td>';
	    				html += '<td class="text-center">';
	    				//1待审核状态
                        if(val.order_status == 1){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a></td>';
						}
	    				//2待付款状态
                        if(val.activity_status== 1 && val.order_status == 2){
                            html += '<a class="layui-btn layui-btn-sm layui-btn-warm pay_a" target="_blank" href="'+val.hd_url+'/dolphin/pay/'+val.ap_id+'">去支付</a><br/>';
                            html += '<a style="margin-top:10px;" class="color_info" onclick="cancelOrder('+val.ap_id+')">取消订单</a>';
                        }else if(val.activity_status== 2 && val.order_status == 2){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a></td>';
                        }
	    				//3待验票状态
                        if(val.order_status == 3){
                            html += '<a data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a show_erweima">签到二维码</a><br/>';
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a>';
                            if(val.pay_add_date>'2021-03-15 00:00:00'){
                                if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 1){
                                    html += '<a style="margin-top:10px;background: #ddd;color: #fff;" class="layui-btn layui-btn-sm layui-btn-warm" target="_blank" href="'+val.activity_invoice.pdfUrl+'">已开发票</a><br/>';
                                    //html += '<a style="margin-top:10px;" class="color_info"   onclick="DoRed('+val.ap_id+',2)">退发票</a></td>';

                                }else if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 2){
                                    //html += '<a href="javascript:void(0)" style="margin-top:10px;background: #ddd;color: #fff;border-radius: 20px;" class="layui-btn layui-btn-sm layui-btn-warm DoRed">已退发票</a>';

                                }else if(val.need_pay == 1){
                                    html += '<a style="margin-top:10px;" href="'+user_url+'/activity/invoice/'+val.ap_id+'/2"   data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a">开发票</a><br/>';
                                }
                            }
                        }
	    				//4已验票状态
                        if(val.order_status == 4){
                            html += '<a data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a show_erweima">签到二维码</a><br/>';
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a>';
                            if(val.pay_add_date>'2021-03-15 00:00:00'){
                                if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 1){
                                    html += '<a style="margin-top:10px;background: #ddd;color: #fff;" class="layui-btn layui-btn-sm layui-btn-warm" target="_blank" href="'+val.activity_invoice.pdfUrl+'">已开票</a><br/>';
                                    //html += '<a style="margin-top:10px;" class="color_info"   onclick="DoRed('+val.ap_id+',2)">退票</a></td>';

                                }else if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 2){
                                    //html += '<a href="javascript:void(0)" style="margin-top:10px;background: #ddd;color: #fff;border-radius: 20px;" class="layui-btn layui-btn-sm layui-btn-warm DoRed">已退发票</a>';

                                }else if(val.need_pay == 1){
                                    html += '<a style="margin-top:10px;" href="'+user_url+'/activity/invoice/'+val.ap_id+'/2"   data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a">开发票</a><br/>';
                                }
                            }

                        }
	    				//5已拒绝状态
                        if(val.order_status == 5){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a></td>';
                        }
	    				//6已退款状态
                        if(val.order_status == 6){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a></td>';
                        }
	    				//7已取消状态
                        if(val.order_status == 7){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a></td>';
                        }
	    				//8结束状态
                        if(val.order_status == 8){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a></td>';
                        }

		    			html += '<tr>';
		    		});
	    		}else{
	    			html += '<tr>';
	    			html += '<td colspan="10" class="error">暂无数据</td>';
	    			html += '</tr>';
	    		}
	    		console.log(json.count);
	    		// $('.count_all').text( json.paginator.count );
	    		// $('.count_daishenhe').text( json.count_daishenhe );
	    		// $('.count_daifukuan').text( json.count_pending_payment );
	    		// $('.count_daiyanpiao').text( json.count_daiyanpiao );
	    		// $('.count_yiyanpiao').text( json.count_yiyanpiao );
	    		tbody.html(html);
	    		layerPage(json.paginator);
	    	}else{
	    		layer.msg(json.msg);
	    	}
	    }
	});
}

//获取我购买的视频列表
function getVideoBuyList(pageNum){
    var user_url = $('.user_url').val();
    var loading = layer.load();
    var select_course = $('.select_course').attr('data-id');
    $.ajax({
        url: '/dolphin/get-video-buy-list',
        type: "POST",
        data:{
            page: pageNum,
            select_course:select_course,
        },
        dataType:'json',
        error: function() {
            layer.close(loading);
            layer.msg('网络错误！');
        },
        success: function(json) {
            layer.close(loading);
            if (json.code){
                var html = '';
                var tbody = $('#table-buy-list').find('tbody');
                if(json.data != ''){
                    $.each(json.data, function(key, val) {
                        html += '<tr>';
                        html += '<td>' + (key+1) + '</td>';
                        html += '<td>';
                        html += '<img src="'+val.video_thumb+'"/>';
                        html += '</td>';

                        html += '<td>';
                        html += '<a target="_blank" title="'+val.video_title+'" href="/activity/'+ val.video_id +'">'+ val.video_title +'</a>';
                        html += '</td>';
                        html += '<td>' + val.video_desc + '</td>';
                        if(val.order_status_name == '待审核'){
                            html += '<td>待付款</td>';
                        }else{
                            html += '<td>' + val.order_status_name + '</td>';
                        }

                        html += '<td>';
                        html += '￥'+ val.pay_amount + '<br>';
                        if(val.need_pay == 0){
                            html += '无需支付';
                        }else{
                            if(val.pay_status == 2){
                                html += '已支付';
                            }else{
                                html += '未支付';
                            }
                        }
                        html += '</td>';
                        html += '<td>' + val.pay_add_date + '</td>';
                        html += '<td class="text-center">';
                        //1待审核状态
                        // if(val.order_status == 1){
                        //     html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a></td>';
                        // }
                        //2待付款状态
                        if(val.order_status == 1 || val.order_status == 2){
                            html += '<a class="layui-btn layui-btn-sm layui-btn-warm pay_a" target="_blank" href="/video-pay/video-submit?order_id='+val.live_pay_id+'&pay_type=alipay">去支付</a><br/>';
                            html += '<a style="margin-top:10px;" class="color_info" onclick="cancelVideoOrder('+val.live_pay_id+')">取消订单</a>';
                        }
                        //3待验票状态
                        if(val.order_status == 3){
                            //html += '<a data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a show_erweima">签到二维码</a><br/>';
                            //html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/order/'+val.ap_id+'">查看详情</a></td>';
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/video/'+val.video_id+'">查看详情</a><br/>';
                            if(val.pay_add_date>'2021-03-15 00:00:00'){
                                if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 1){
                                    html += '<a style="margin-top:10px;background: #ddd;color: #fff;" class="layui-btn layui-btn-sm layui-btn-warm" target="_blank" href="'+val.activity_invoice.pdfUrl+'">已开票</a><br/>';
                                    //html += '<a style="margin-top:10px;" class="color_info"   onclick="DoRed('+val.live_pay_id+',3)">退票</a>';

                                }else if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 2){
                                    //html += '<a href="javascript:void(0)" style="margin-top:10px;background: #ddd;color: #fff;border-radius: 20px;" class="layui-btn layui-btn-sm layui-btn-warm DoRed">已退发票</a>';

                                }else if(val.need_pay == 1){
                                    html += '<a style="margin-top:10px;" href="'+user_url+'/activity/invoice/'+val.live_pay_id+'/3"   data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a">开发票</a>';
                                }
                            }

                        }
                        //4已验票状态
                        if(val.order_status == 4){
                            //html += '<a data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a show_erweima">签到二维码</a><br/>';
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/video/'+val.video_id+'">查看详情</a><br/>';
                            if(val.pay_add_date>'2021-03-15 00:00:00'){
                                if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 1){
                                    html += '<a style="margin-top:10px;background: #ddd;color: #fff;" class="layui-btn layui-btn-sm layui-btn-warm" target="_blank" href="'+val.activity_invoice.pdfUrl+'">已开票</a><br/>';
                                    //html += '<a style="margin-top:10px;" class="color_info"   onclick="DoRed('+val.live_pay_id+',3)">退票</a>';

                                }else if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 2){
                                    //html += '<a href="javascript:void(0)" style="margin-top:10px;background: #ddd;color: #fff;border-radius: 20px;" class="layui-btn layui-btn-sm layui-btn-warm DoRed">已退发票</a>';

                                }else if(val.need_pay == 1){
                                    html += '<a style="margin-top:10px;" href="'+user_url+'/activity/invoice/'+val.live_pay_id+'/3"   data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a">开发票</a><br/>';
                                }
                            }

                        }
                        //5已拒绝状态
                        if(val.order_status == 5){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/video/'+val.video_id+'">查看详情</a>';
                        }
                        //6已退款状态
                        if(val.order_status == 6){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/video/'+val.video_id+'">查看详情</a>';
                        }
                        //7已取消状态
                        if(val.order_status == 7){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/video/'+val.video_id+'">查看详情</a>';
                        }
                        //8结束状态
                        if(val.order_status == 8){
                            html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/video/'+val.video_id+'">查看详情</a>';
                        }

                        html += '</td></tr>';
                    });
                }else{
                    html += '<tr>';
                    html += '<td colspan="10" class="error">暂无数据</td>';
                    html += '</tr>';
                }
                console.log(json.count);
                $('.span_buy_0').text( json.paginator.count );
                $('.span_buy_1').text( json.buy_count_daishenhe );
                $('.span_buy_2').text( json.buy_count_pending_payment );
                $('.span_buy_3').text( json.buy_count_daiyanpiao );
                $('.span_buy_4').text( json.buy_count_yiyanpiao );
                tbody.html(html);
                layerPageBuyVideo(json.paginator);
            }else{
                layer.msg(json.msg);
            }
        }
    });
}

//获取我分享的视频列表
function getVideoShareList(pageNum){
    var loading = layer.load();
    var select_course = $('.select_course').attr('data-id');
    $.ajax({
        url: '/dolphin/get-video-share-list',
        type: "POST",
        data:{
            page: pageNum,
            select_course:select_course,
        },
        dataType:'json',
        error: function() {
            layer.close(loading);
            layer.msg('网络错误！');
        },
        success: function(json) {
            layer.close(loading);
            if (json.code){
                var html = '';
                var tbody = $('#table-share-list').find('tbody');
                if(json.data != ''){
                    $.each(json.data, function(key, val) {
                        html += '<tr>';
                        html += '<td>' + (key+1) + '</td>';
                        html += '<td>';
                        html += '<img src="'+val.video_thumb+'"/>';
                        html += '</td>';

                        html += '<td>';
                        html += '<a target="_blank" title="'+val.video_title+'" href="/dolphin/video/'+ val.video_id +'">'+ val.video_title +'</a>';
                        html += '</td>';
                        html += '<td>' + val.video_desc + '</td>';
                        html += '<td>' + val.add_user.user_mobile_phone + '</td>';

                        html += '<td>' + val.add_time + '</td>';
                        html += '<td class="text-center">';
                        //1待审核状态

                        html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/video/'+val.video_id+'">查看详情</a></td>';

                        html += '<tr>';
                    });
                }else{
                    html += '<tr>';
                    html += '<td colspan="10" class="error">暂无数据</td>';
                    html += '</tr>';
                }
                //console.log(json.count);
                $('.count_all').text( json.paginator.count );
                $('.count_daishenhe').text( json.count_daishenhe );
                $('.count_daifukuan').text( json.count_pending_payment );
                $('.count_daiyanpiao').text( json.count_daiyanpiao );
                $('.count_yiyanpiao').text( json.count_yiyanpiao );
                tbody.html(html);
                layerPageShareVideo(json.paginator);
            }else{
                layer.msg(json.msg);
            }
        }
    });
}

//获取我领取的视频列表
function getVideoGetList(pageNum){
    var loading = layer.load();
    var select_course = $('.select_course').attr('data-id');
    $.ajax({
        url: '/dolphin/get-video-share-list',
        type: "POST",
        data:{
            page: pageNum,
            select_course:select_course,
            user_id:1
        },
        dataType:'json',
        error: function() {
            layer.close(loading);
            layer.msg('网络错误！');
        },
        success: function(json) {
            layer.close(loading);
            if (json.code){
                var html = '';
                var tbody = $('#table-get-list').find('tbody');
                if(json.data != ''){
                    $.each(json.data, function(key, val) {
                        html += '<tr>';
                        html += '<td>' + (key+1) + '</td>';
                        html += '<td>';
                        html += '<img src="'+val.video_thumb+'"/>';
                        html += '</td>';

                        html += '<td>';
                        html += '<a target="_blank" title="'+val.video_title+'" href="/dolphin/video/'+ val.video_id +'">'+ val.video_title +'</a>';
                        html += '</td>';
                        html += '<td>' + val.video_desc + '</td>';
                        html += '<td>' + val.add_user.user_mobile_phone + '</td>';

                        html += '<td>' + val.add_time + '</td>';
                        html += '<td class="text-center">';
                        //1待审核状态

                        html += '<a style="margin-top:10px;" class="color_info" href="/dolphin/video/'+val.video_id+'">查看详情</a></td>';

                        html += '<tr>';
                    });
                }else{
                    html += '<tr>';
                    html += '<td colspan="10" class="error">暂无数据</td>';
                    html += '</tr>';
                }
                //console.log(json.count);
                $('.count_all').text( json.paginator.count );
                $('.count_daishenhe').text( json.count_daishenhe );
                $('.count_daifukuan').text( json.count_pending_payment );
                $('.count_daiyanpiao').text( json.count_daiyanpiao );
                $('.count_yiyanpiao').text( json.count_yiyanpiao );
                tbody.html(html);
                layerPageGetVideo(json.paginator);
            }else{
                layer.msg(json.msg);
            }
        }
    });
}

/**
 * 取消订单
 * auth:tianya
 * 20191025
 * @param id
 */
function cancelOrder(id){
    layer.confirm('确认要取消此订单吗？', {
        btn: ['确认','取消'] //按钮
    }, function(){
        var loading = layer.load();
        $.ajax({
            url: '/dolphin/cancel-order',
            type: "POST",
            data:{id: id},
            dataType:'json',
            error: function() {
                layer.close(loading);
                layer.msg('网络错误！');
            },
            success: function(json) {
                layer.close(loading);
                if(json.code){
                    layer.msg(json.msg, {icon:1});
                    getList(1);
                }else{
                    layer.msg(json.msg, {icon:2});
                }
            }
        });
    });
}


/**
 * 取消视频订单
 * auth:tianya
 * 20200927
 * @param id
 */
function cancelVideoOrder(id){
    layer.confirm('确认要取消此订单吗？', {
        btn: ['确认','取消'] //按钮
    }, function(){
        var loading = layer.load();
        $.ajax({
            url: '/dolphin/cancel-video-order',
            type: "POST",
            data:{id: id},
            dataType:'json',
            error: function() {
                layer.close(loading);
                layer.msg('网络错误！');
            },
            success: function(json) {
                layer.close(loading);
                if(json.code){
                    layer.msg(json.msg, {icon:1});
                    getVideoBuyList(_page);
                }else{
                    layer.msg(json.msg, {icon:2});
                }
            }
        });
    });
}

$(document).on('click','.show_erweima',function(){
    var code = $(this).attr('data-code');
    $.ajax({
        url : '/dolphin/get-activity-sign?code='+code,
        type : "get",
        data : {},
        dataType : 'json',
        error : function() {
            layer.msg('网络错误！', {icon: 2});
        },
        success : function(json) {
            layer.open({
                type: 1,
                title: '确认验票',
                area: ['454px', '400px'],
                closeBtn: 1,
                shadeClose: true,
                skin: 'layui-layer-molv',
                content: '<div><img class="text-center" src="'+json.code+'" /></div><div>入会场凭此二维码签到</div><div><a href="'+json.code+'" download=""><button class="erweima_btn " data-src="'+json.code+'">保存二维码</button></a></div>',
                end: function(index, layero) {
                    //console.log(result);
                    //getList();
                }
            });
        }
    });

    /*$.get("/default/get-activity-sign?code=" + code, function(result) {
        layer.open({
            type: 1,
            title: '确认验票',
            area: ['454px', '350px'],
            closeBtn: 1,
            shadeClose: true,
            skin: 'layui-layer-molv',
            content: result,
            end: function(index, layero) {
            	console.log(result);
                //getList();
            }
        });
    });*/
})

$(document).on('click','.down_erweima',function(){
    var src = $(this).attr('data-src');
	$.ajax({
		url:'/dolphin/down-img',
		data:{src:src},
		datatype:'text',
		'type':'get',
		success:function(res){
			console.log(res);
		}
	})
})


/**
 * 退票操作
 * auth:tianya
 * 2021-02-26
 */
function DoRed(id,type){
    var user_url = $('.user_url').val();
    layer.confirm('确认要执行退票操作吗？', {
        btn: ['确认','取消'] //按钮
    }, function(){
        var loading = layer.load();
        $.ajax({
            url: user_url+'/invoice/do-red',
            type: "POST",
            data:{id: id,type:type},
            dataType:'json',
            error: function() {
                layer.close(loading);
                layer.msg('网络错误！');
            },
            success: function(json) {
                layer.close(loading);
                if(json.code){
                    layer.msg(json.msg, {icon:1});

                }else{
                    layer.msg(json.msg, {icon:2});
                }
            }
        });
    });
}




