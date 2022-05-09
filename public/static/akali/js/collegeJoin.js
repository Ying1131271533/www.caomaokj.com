var _page = 1;

$(function () {
    setTimeout(function() {
    	getList(_page);
    }, 200);

});


function getList(pageNum){
	var loading = layer.load();
	$.ajax({
		url: '/index/user_api/collegeJoin',
	    type: "POST",
	    data:{page: pageNum},
	    dataType:'json',
	    error: function() {
	    	layer.close(loading);
            layer.msg('网络错误！');
	    },
	    success: function(json) {
	    	layer.close(loading);
	    	if (json.code == 200){
	    		var html = '';
	    		var tbody = $('#table-list').find('tbody');
	    		if(json.data.list != ''){
	    			$.each(json.data.list, function(key, val) {
	    				html += '<tr>';
	    				html += '<td>' + (key+1) + '</td>';
	    				html += '<td>';
	    				html += '<a target="_blank" title="'+val.activity_name+'" href="/index/college/detail/id/'+ val.activity_id +'">'+ val.activity_name_short +'</a>';
	    				if(val.at_id == '8') {
	    					html += '<span class="live_activity">live</span>';
	    				}
	    				html += '<br>';
	    				html += '时间：'+ val.activity_start_time + ' ~ '+ val.activity_end_time +'<br>';
	    				html += '活动状态：' + val.status;
	    				html += '</td>';
	    				// html += '<td>' + val.af_title + '('+val.af_desc+')</td>';
	    				html += '<td> -- </td>';
	    				html += '<td>' + val.order_status_name + '</td>';
	    				html += '<td>';
	    				html += '【￥'+ val.pay_amount + '】<br>';
	    				if(val.need_pay == 0){
	    					html += '无需支付';
	    				}else{
	    					if(val.pay_status == 1){
                                html += '【'+val.pay_platform+'】<br/>';
                                html += '【已支付】';
	    					}else{
	    						html += '【未支付】';
	    					}
	    				}
	    				html += '</td>';
	    				html += '<td>' + val.pay_add_date + '</td>';
	    				html += '<td>';
                        if(val.pay_status == '0' && val.order_status == '0'){
                            html += '<a class="layui-btn layui-btn-sm layui-btn-warm pay_a" target="_blank" href="/index/college/confirm/order_id/'+val.ap_id+'">去支付</a>';
                            html += '<a style="" class="layui-btn layui-btn-sm layui-btn-warm" onclick="cancelOrder('+val.ap_id+')">取消订单</a>';

                        }else if(val.order_status == 1){
                            html += '<a  class="layui-btn layui-btn-sm layui-btn-warm" href="/index/user/collegeOrder/order_id/'+val.ap_id+'">查看详情</a>';
                        }else{
                            html += '<a data-code="'+val.pay_code+'" class="layui-btn layui-btn-sm layui-btn-warm pay_a show_erweima">查看二维码</a>';
                            html += '<a  class="layui-btn layui-btn-sm layui-btn-warm" href="/index/user/collegeOrder/order_id/'+val.ap_id+'">查看详情</a>';
                        }

                        /* if(val.pay_status == 1 && val.pay_add_date > '2021-03-15 00:00:00'){
                            if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 1){
                                html += '<a style="margin-top:10px;background: #ddd;color: #fff;" class="layui-btn layui-btn-sm layui-btn-warm" target="_blank" href="'+val.activity_invoice.pdfUrl+'">已开票</a><br/>';
                                //html += '<a style="margin-top:10px;" class="layui-btn layui-btn-sm layui-btn-warm DoRed" onclick="DoRed('+val.ap_id+',1)">退发票</a>';

                            }else if(val.need_pay == 1 && val.activity_invoice && val.activity_invoice.status == 2){
                                //html += '<a herf="javascript:void(0)" style="margin-top:10px;background: #ddd;color: #fff;" class="layui-btn layui-btn-sm layui-btn-warm DoRed">已退发票</a>';

                            }else if(val.pay_platform != '' && val.need_pay == 1 && (val.order_status != '9' && val.order_status != '6')){
                                html += '<a style="margin-top:10px;" class="layui-btn layui-btn-sm layui-btn-warm" href="/college/invoice/'+val.ap_id+'/1">开发票</a><br/>';

                            }
                        }

                        if(val.need_pay == 1 && val.pay_status == 1 && val.order_status==1){
                            html += '<a  class="layui-btn layui-btn-sm layui-btn-warm mt10" onclick="refund_order_akali('+val.ap_id+')">申请退款</a>';
                        } */


                        html += '</td>';
		    			html += '<tr>';
		    		});
	    		}else{
	    			html += '<tr>';
	    			html += '<td colspan="10" class="error">暂无数据</td>';
	    			html += '</tr>';
	    		}
	    		tbody.html(html);
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
            data:{id: id, type: 2},
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

$(document).on('click','.show_erweima',function(){
    var code = $(this).attr('data-code');
    $.ajax({
        url : '/activity/get-activity-sign?code='+code,
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
		url:'/activity/down-img',
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

// 退款操作
function refund_order(id){

    $.get("/index/user_api/refundOrder?id=" + id + "&type=2", function(result) {
        layer.open({
            type: 1,
            title: '确认退款',
            area: ['454px', '350px'],
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

// 退款 阿卡丽
function refund_order_akali(id) {
	var loading = layer.load();
    //通过
    $.ajax({
        url: '/index/user_api/refundOrder',
        type: "POST",
        data: {
            id: id,
            type : 2,
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





