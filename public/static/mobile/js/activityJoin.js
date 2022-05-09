$(function () {
    setTimeout(function() {
    	getList();
    }, 200);
});



function getList(pageNum){
	var page = pageNum || 1;
	var loading = layer.load();
	$.ajax({
		url: '/index/user_api/activityJoin',
	    type: "POST",
	    data:{page: page},
	    dataType:'json',
	    error: function() {
	    	layer.close(loading);
            layer.msg('网络错误！');
	    },
	    success: function(json) {
	    	layer.close(loading);
	    	if (json.code == 200){
	    		var html = '';
	    		if(json.data.list != ''){
	    			$.each(json.data.list, function(key, val) {
	    				html += '<li class="orderList">';
    					html += '<a class="personTop" href="/index/user/activityOrder/order_id/'+val.ap_id+'">';
						html += '<div class="name">'+val.add_user+'</div>';
						html += '<div class="state">'+val.order_status_name+'</div>';

						html += '</a>';
						html += '<a class="partyMess" href="/index/user/activityOrder/order_id/'+val.ap_id+'">';
						html += '<div class="img">';
						html += '<img alt="'+ val.activity_name +'" src="'+ val.activity_thumb +'">';
						html += '</div>';
						html += '<div class="messTxt">';
						html += '<div class="title">'+ val.activity_name;
						if(val.at_id == '8') {
	    					html += '<span class="live_activity">live</span>';
	    				}
						html += '</div>';
						html += '<div class="time">'+ val.activity_start_time +' 开始</div>';
						html += '</div>';
						html += '</a>';
						html += '<a class="ticketBox" href="/index/user/activityOrder/order_id/'+val.ap_id+'">';
						html += '<ul>';
						html += '<li><span class="name">'+ val.status +'</span> <span class="price">￥'+val.pay_amount+'</span></li>';
						html += '</ul>';
						html += '</a>';
						html += '<div class="boderBtn">';
						html += '<div class="line"></div>';
						if(val.order_status == '0'){
							html += '<div class="cz">';
							html += '<a class="yellow" href="/index/activity/confirm/order_id/'+val.ap_id+'">马上支付</a>';
							html += '<a class="white" onclick="cancelOrder('+val.ap_id+')">取消订单</a>';
							html += '</div>';
						}
						/* else if(val.order_status == '1'){
                            html += '<div class="cz">';
                            if(val.pay_status == 1){
                                if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 1){
                                    html += '<a style="margin-left:5px;background: #ddd;color: #fff;" class="layui-btn layui-btn-xs layui-btn-warm" target="_blank" href="'+val.activity_invoice.pdfUrl+'">已开发票</a>';
                                    //html += '<a style="" class="layui-btn layui-btn-xs layui-btn-warm DoRed" onclick="DoRed('+val.ap_id+',1)">退发票</a>';

                                }else if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 2){
                                    //html += '<a herf="javascript:void(0)" style="background: #ddd;color: #fff;" class="layui-btn layui-btn-xs layui-btn-warm DoRed">已退发票</a>';

                                }else if(val.pay_platform != '' && val.need_pay == 1 && (val.order_status != '9' && val.order_status != '6')){
                                    html += '<a style="" class="layui-btn layui-btn-xs layui-btn-warm" href="/activity/invoice/'+val.ap_id+'/1">开发票</a>';

                                }

                                if((val.need_pay == 1)){
                                    html += '<a  class="layui-btn layui-btn-sm layui-btn-warm mt10" onclick="refund_order_akali('+val.ap_id+')">申请退款</a>';
                                }
                            }
                            html += '</div>';
						} */


						html += '</div>';
						html += '</li>';
		    		});
	    		}else{
	    			html += '<li class="no_msg">您还没有报名活动</li>';
	    		}
	    		$(".data_ul").html(html);
	    		layerPage(json.data.paginator);
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
            url: '/index/user_api/cancelOrder',
            type: "POST",
            data:{id: id, type: 1},
            dataType:'json',
            error: function() {
                layer.close(loading);
                layer.msg('网络错误！');
            },
            success: function(json) {
                layer.close(loading);
                if(json.code == 200){
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
 * 退票操作
 * auth:tianya
 * 2021-02-26
 */
function DoRed(id,type){
    if(id == '' || type == ''){
        layer.msg('温馨提示：退票失败！',{time:3000});
        return false;
    }
    layer.confirm('确认要执行退票操作吗？', {
        btn: ['确认','取消'] //按钮
    }, function(){
        var loading = layer.load();
        $.ajax({
            url: '/invoice/do-red',
            type: "POST",
            data:{id: id,type:type},
            dataType:'json',
            error: function() {
                layer.close(loading);
                layer.msg('网络错误！');
            },
            success: function(json) {
                layer.close(loading);
                if(json.state){
                    layer.msg(json.msg, {icon:1});

                }else{
                    layer.msg(json.msg, {icon:2});
                }
            }
        });
    });
}

//退款操作
function refund_order(id){

    $.get("/activity/refund-order?id=" + id, function(result) {
        layer.open({
            type: 1,
            title: '确认退款',
            area: ['90%', '300px'],
            closeBtn: 1,
            shadeClose: true,
            skin: 'layui-layer-molv',
            content: result,
            end: function(index, layero) {
                var current_page = localStorage.getItem("current_page");
                getList(current_page);
            }
        });
    });
}

function refund_order_akali(id) {
	var loading = layer.load();
    // 通过
    $.ajax({
        url: '/index/user_api/refundOrder',
        type: "POST",
        data: {
            id: id,
            type : 1,
        },
        dataType:'json',
        error: function() {
            layer.close(loading);
            layer.msg('网络错误！', {icon: 2});
        },
        success: function(json) {
            layer.close(loading);
            if (json.code == 200){
                layer.msg(json.msg, {icon: 1});
                var current_page = localStorage.getItem("current_page");
                getList(current_page);
            }else{
                layer.msg(json.msg, {icon: 2});
            }
            layer.closeAll('page');
        }
    });
}


/**
 * 保存用户退款原因的备注信息
 * auth:tianya
 * 2021-08-10
 */
function saveRefundReason(id) {
    //通过
    var loading = layer.load();
    var remark = $('.remark').val();
    var order_status = $('.order_status').val();
    $.ajax({
        url: '/activity/save-refund',
        type: "POST",
        data: {
            id: id,
            order_status : order_status,
            remark:remark
        },
        dataType:'json',
        error: function() {
            layer.close(loading);
            layer.msg('网络错误！', {icon: 2});
        },
        success: function(json) {
            layer.close(loading);
            if (json.state){
                layer.msg(json.msg, {icon: 1});
                var current_page = localStorage.getItem("current_page");
                getList(current_page);
            }else{
                layer.msg(json.msg, {icon: 2});
            }
            layer.closeAll('page');
        }
    });
}



