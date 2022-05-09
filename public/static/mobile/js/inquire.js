// 盘询相关的js
// 模拟国家地区数据
  var earaData = [];
  var filterArea = [];
  var isChinese = false;
  //自定义下拉框弹出事件
  function openValue(obj, e){
    e.stopPropagation(); //阻止冒泡
    //阻止默认浏览器动作(W3C)
    if ( e && e.preventDefault ){
        e.preventDefault();
    } else{
        window.event.returnValue = false;
    }
    if ($(".dropdown-menus").is(":visible")) {
        $('.dropdown-menus').hide();
        $('.ec-input').removeClass('ec-input__open');
    }else{
        $(obj).addClass('ec-input__open');
        $(obj).next().show();
    }
  }
  //点击某个下拉菜单的值之后的事件处理
  function getValue(obj, e){
    e.stopPropagation(); //阻止冒泡
    var selectedDom;
    // 点击的地方可能是li，也可能是li的子节点
    if(e.target && e.target.nodeName == "LI"){
       selectedDom = e.target;
    }else{
        selectedDom = $(e.target).parent();
    }
    var selectedValue = $(selectedDom).data('value');
    var selectedLable = $(selectedDom).data('label');
    var operId = $(obj).data('id');
    $(selectedDom).siblings().removeClass('ec-active')
    $(selectedDom).addClass('ec-active');
    $('#'+operId+'-value').val(selectedLable);
    $('#'+operId+'-label').val(selectedValue);
    $('.ec-input').removeClass('ec-input__open');
    $(obj).parent().hide();
    if(operId==='area'){ // 如果国家地区选择其他
        if(selectedValue==='其他'){
            $("#otherCountry").show();
        }else{
            if($("#otherCountry").is(":visible")){
                $("#otherCountry").val('');
                $("#otherCountry").hide();
            }
        }
    }
  }
   //国家地区搜索框输入事件
  function enterSearch(obj){
    if(!isChinese){
      // var event = window.event || arguments.callee.caller.arguments[0];
      var key = $(obj).val();
      searchArea(key);
    }
  }
  // 下拉搜索框搜索事件
  function searchArea(key){
    filterArea = [];
    for(var j = 0,len=earaData.length;j<len;j++){
      var contryName = earaData[j].country_name;
      if(contryName.match(key)){
        filterArea.push(contryName);
      }
    }
    appendSelectArea(key);
  }
    // 中文输入法
    $('.ec-input__value').on('compositionstart',function(event) {
      isChinese = true;
		})
    //  输入中文之后
    $('.ec-input__value').on('compositionend',function() {
      isChinese = false;
      enterSearch(this)
    })
  // 将搜索到的结果重新渲染到下拉框中
  function appendSelectArea(key){
    var areasHtml = '';
    if(filterArea && filterArea.length>0){
      areasHtml = '<ul class="dropdown-menu__con" onclick="getValue(this, event)" data-id="area">';
      for(var i = 0,len = filterArea.length ;i<len;i++){
        areasHtml+='<li class="menu-item" data-value='+filterArea[i]+' data-label='+filterArea[i]+'><span class="text">'+filterArea[i]+'</span><span class="select-ok"></span></li>'
      }
      areasHtml+='</ul>';
    }else{
      areasHtml = '<div class="dropdown-menu__con no-result" data-id="area"> 很抱歉没有找到“'+key+'”相关结果!</div>';
    }
    $('.area-menus').html(areasHtml);
  }
 // 自定义下拉框：点击其他部分收起下拉事件
  document.addEventListener("click", (e) => {
      if ($(".dropdown-menus").is(":visible")) {
        $('.dropdown-menus').hide();
        $('.ec-input').removeClass('ec-input__open');
      }
    });
// 文本输入框：输入字数监控
function setLength(obj,maxlength){
    var num = obj.value.length;
    if(num>=maxlength){
       num=maxlength;
    }
    document.getElementById('wordsLength').innerHTML=num+"/50";
}

/*---------盘询事件开始-----------*/
function sendAsk(id) {
	var isLogin = $("#isLogin").val();
	var loading = layer.load();
	var html = '';
	$.post("/api/get-warehouse-service", {serviceId : id}, function(json) {
		layer.close(loading);
		if (json.state) {
			html += '<div class="layer_div"><form class="askForm"><input type="hidden" id="form-serviceId" name="serviceId" value="' + id + '"><div class="askFormItem"><span class="askForm-span">姓名</span><input class="form-control" placeholder="请输入您真实的姓名" type="text" name="name" id="name" /></div>'
            +'<div class="askFormItem"><span class="askForm-span">联系方式</span><input class="form-control" placeholder="请输入您真实有效的手机号" type="text" name="phone" id="phone" /></div>';
			if(isLogin != '1') {
				html += '<div class="askFormItem"><span class="askForm-span">验证码</span><div class="form-control phoneCode"><input type="text" class="phoneCode-input" name="code" id="form-code" placeholder="请输入手机验证码" /><span class="phoneCode-btn" id="get-code" onclick="getCode()">获取验证码</span></div></div>';
			}
            // html += '<div class="askFormItem"><span class="askForm-span">业务</span><div class="define-select"><div class="select-container select-left"><div class="ec-input" onclick="openValue(this,event)"><input type="hidden" value="" id="yewu-label"><input type="text" readonly="readonly" class="ec-input__value" placeholder="业务类型" name="service" value="" id="yewu-value"><span class="caret"></span></div><div class="dropdown-menus"><ul class="dropdown-menu__con" onclick="getValue(this, event)" data-id="yewu"><li class="menu-item" data-value="1" data-label="海外仓"><span class="text">海外仓</span><span class="select-ok"></span></li><li class="menu-item" data-value="2" data-label="专线"><span class="text">专线</span><span class="select-ok"></span></li></ul></div></div><div class="select-online"></div>'
            // +'<div class="select-container select-right"><div class="ec-input" onclick="openValue(this,event)"><input type="hidden" value="" id="area-label"><input type="text" class="ec-input__value" placeholder="请选择国家地区" autocomplete="new-password" value="" id="area-value" oninput="enterSearch(this)" name="country"><span class="caret"></span></div><div class="dropdown-menus area-menus"></div></div></div></div>'
            // +'<div class="askFormItem" id="other-country"><span class="askForm-span">国家地区</span><input class="form-control" placeholder="请输入国家或地区" type="text" name="otherCountry" id="otherCountry" /></div>'
             html +='<div class="askFormItem"><span class="askForm-span">公司名称</span><input class="form-control" placeholder="请输入您的公司名称" type="text" name="companyName" id="companyName" /></div>'
             +'<div class="askFormItem"><span class="askForm-span">业务</span><div class="select-con"><div class="define-select"><div class="ec-input" onclick="openValue(this,event)"><input type="hidden" value="" id="yewu-label"><input type="text" readonly="readonly" class="ec-input__value" placeholder="业务类型" name="service" value="" id="yewu-value"><span class="caret"></span></div><div class="dropdown-menus"><ul class="dropdown-menu__con" onclick="getValue(this, event)" data-id="yewu"><li class="menu-item" data-value="1" data-label="海外仓"><span class="text">海外仓</span><span class="select-ok"></span></li><li class="menu-item" data-value="2" data-label="专线"><span class="text">专线</span><span class="select-ok"></span></li></ul></div></div></div></div>'
            +'<div class="askFormItem"><span class="askForm-span">国家地区</span><div class="select-con"><div class="define-select"><div class="ec-input" onclick="openValue(this,event)"><input type="hidden" value="" id="area-label"><input type="text" class="ec-input__value" placeholder="请选择国家地区" autocomplete="new-password" value="" id="area-value" oninput="enterSearch(this)" name="country"><span class="caret"></span></div><div class="dropdown-menus area-menus"></div></div>'
            +'<input class="form-control" style="width:49.9%;display:none;" placeholder="请输入国家或地区" type="text" name="otherCountry" id="otherCountry"/></div></div>'
            +'<div class="askFormItem"><span class="askForm-span">留言详情</span><textarea class="askForm-textarea" placeholder="请输入你要提交的留言，我们会第一时间与您取得联系" rows="6" name="detail" id="form-detail" maxlength="50" onkeyup="setLength(this,50)";></textarea><span id="wordsLength" class="wordsLength">0/50</span></div>'
            +'<div class="askFormItem lastItem"><span class="askForm-span"></span><div class="askForm-checkbox"><input type="checkbox" value="1" id="form-other" checked=true name="is_other"><label for="form-other">一天内无响应，可安排其他服务商与我联系</label></div></div></from></div>';
			layerDiv(html,'盘询', [ '提交询盘', '取消' ]);
            getCountryData(); // 获取国家地区数据
		}
	});
}

/*---------盘询事件弹窗-----------*/
function wechat(wechat_img) {
	var isLogin = $("#isLogin").val();
	var html = '';
	html += '<div class="layer_div">';
	html += '<img src="' + wechat_img + '" width="250" height="250"/>';
	html += '<div class="layui-layer-title" style="padding: 0 5px;font-size:17px;">添加草帽客服微信号，了解详情</div>';
	html += '</div>';
	layerWechat(html);
}

function layerWechat(html) {
	// 示范一个公告层
	var index = layer.open({
		type : 1,
		title : "询盘", // 不显示标题栏
		area : '300px;',
		shade : 0.8,
		id : 'LAY_layuipro', // 设定一个id，防止重复弹出
		resize : false,
		btn : [ '关闭窗口' ],
		btnAlign : 'c',
		content : html,
		cancel : function() {
			// 右上角关闭回调
			
		}
	});
}

//  autocomplete="off"
// 获取国家地区数据
function getCountryData (){
    $.post("/api/get-warehouse-country", {}, function(json) {
        earaData = json.data;
        earaData.push({'country_name':'其他'})
        if(earaData){
            searchArea(''); //初始化国家地区dom
        }
    });
}
// layer弹出框
function layerDiv(html,title,btns) {
    var isMobile = false;
    // 获取浏览器信息，并转小写
    let userAgent = navigator.userAgent.toLowerCase();
    // 用 test 匹配浏览器信息
    if (/ipad|iphone|midp|rv:1.2.3.4|ucweb|android|windows ce|windows mobile/.test(userAgent)) {
        isMobile = true;
    } else {
        isMobile = false;
    }
	// 示范一个公告层
	var index = layer.open({
		type : 1,
		title : title || "询盘", // 不显示标题栏
		area : isMobile ? '92%' : '480px;',
		shade : 0.8,
		id : 'LAY_layuipro', // 设定一个id，防止重复弹出
		resize : false,
		btn : btns || [ '提交询盘', '取消' ],
		btnAlign : 'c',
		content : html,
		yes : function(index, layero) {
			// 按钮【按钮一】的回调
            if(title == '我的反馈'){
                handelFeedback(index);
            }else{
                handelFormData(index);
            }
		},
		cancel : function() {
			// 右上角关闭回调
		}
	});
}
// 处理表单数据
function handelFormData(index){
    var isLogin = $("#isLogin").val();
    var name = $("#name").val();
    var phone = $("#phone").val();
    var yewuValue = $("#yewu-label").val();
    var areaValue = $('#area-label').val();
    var serviceId = $('#form-serviceId').val();
    var detail = $('#form-detail').val();
    var code = '';
    if(isLogin != '1'){
       code = $('#form-code').val();
    }
    var companyName = $('#companyName').val();
    if (name == "") {
        layer.msg("请输入姓名");
        $("#name").focus();
        return false;
    }
    if (phone == "") {
        layer.msg("请输入手机号码");
        $("#phone").focus();
        return false;
    }
    if (!phone.match(/^(13[0-9]{9})|(14[0-9]{9})|(16[0-9]{9})|(17[0-9]{9})|(18[0-9]{9})|(19[0-9]{9})|(15[0-9]{9})$/)) {
        layer.msg('手机号码填写不正确');
        $("#phone").focus();
        return false;
    }
    if(isLogin != '1' && code == ''){
        layer.msg("请输入验证码");
        $("#form-code").focus();
        return false;
     }
     if (yewuValue == "") {
        layer.msg("请选择业务类型");
        return false;
    }
    var otherCountry = '';
    if (areaValue == "") {
        layer.msg("请选择国家地区");
        return false;
    }else{
        if(areaValue==='其他'){
            otherCountry = $("#otherCountry").val();
            areaValue = otherCountry;
            if(otherCountry == ''){
                layer.msg("国家地区选择“其他”时，请输入国家地区");
                return false;
            }
        }
    }
    if (companyName == "") {
        layer.msg("请输入公司名称");
        $("#companyName").focus();
        return false;
    }

    // var hwAreaValue = $('#area-value').val();
    // if (areaValue == "") {
    //     if(yewuValue == 2){
    //        layer.msg("请选择国家地区");
    //        return false;
    //     }else{
    //         if(hwAreaValue == ''){
    //             layer.msg("请选择或输入国家地区");
    //             return false;
    //         }
    //     }
    // }
    // if(yewuValue == 1 && areaValue==''){
    //     areaValue = hwAreaValue;
    // }
    var paramsObj = {
        serviceId: serviceId,
        name: name,
        phone: phone,
        companyName: companyName,
        service: yewuValue,
        country: areaValue,
        detail: detail,
        is_other: '0',
    }
    if(isLogin != '1'){
        paramsObj.code = code;
    }
    if($('#form-other').is(':checked')) {
        paramsObj.is_other = '1';
    }
    reqSendForm('/api/send-ask',paramsObj,index);
}
//提交表单
function reqSendForm(apiPath,paramsObj,index){
    var loading = layer.load();
    $.ajax({
        url : apiPath,
        type : "POST",
        data : paramsObj,
        dataType : "json",
        success : function(json) {
            layer.close(loading);
            layer.msg(json.msg);
            if (json.state) {
                layer.msg(json.msg, {icon:1});
                layer.close(index);
                var codeImg = layer.open({
                    type : 1,
                    title : '5分钟急速匹配需求', // 不显示标题栏
                    area : '330px',
                    shade : 0.8,
                    id : 'LAY_1', // 设定一个id，防止重复弹出
                    resize : true,
                    btn : [ '关闭' ],
                    offset: 'calc(100%-330px);',
                    content : '<img style="width:100%;height:100%;" src="https://img.kuajingyan.com/home/2021-08-13/948faf4896c0b032.png"/>',
                    yes : function() {
                        layer.close(codeImg);
                    },
                    cancel : function() {
                        // 右上角关闭回调
                    }
                });
            } else {
                layer.msg(json.msg, {icon:2});
            }
        },
        error : function() {
            layer.close(loading);
            layer.msg('网络错误！');
        }
    });
}

// 下面是反馈的代码
function feedbackEvent(id){ // 反馈事件开始
    ///feed-back/get-company-detail
  var isLogin = $("#isLogin").val();
  var loading = layer.load();
  var html = '';
  $.post("/feed-back/get-company-detail", {service_id: id}, function(json) {
    layer.close(loading);
    var companyPost = json.companyPost || []; //专线
    var serviceTypes = json.value_added_service || [];//服务类型
    var overseas = json.warehouse_oversea || []; // 海外仓
    var iLen = companyPost.length;
    var jLen = overseas.length;
    var kLen = serviceTypes.length;
    var ywActive = '';
    if(iLen == 0 && jLen>0){
        ywActive = 'yw-active';
    }
    html+=' <form id="feedbackForm" class="feedback-layer"><input type="hidden" name="service_id" value="'+id+'" /><div class="fb-title">您沟通服务商时遇到哪些问题</div><div class="fb-quesions"><ul class="quesion-navs" onclick="changeTypeEvent(event,1)"><li class="quesion-nav nav-active" data-index="1">联系方式不准确</li>';
    if(iLen > 0 || jLen > 0){
        html+='<li class="quesion-nav" data-index="2">业务内容不准确</li>';
    }
    if(kLen > 0){
        html+='<li class="quesion-nav" data-index="3">服务类型不准确</li>';
    }
    html+='</ul><div class="quesions-content">'
    +'<div class="nav-content nav-content_1"><span class="question-item"><input type="checkbox" id="nav-1_1" class="question-chk" name="contact" value="电话" hidden /><label for="nav-1_1" class="question-label">电话</label></span><span class="question-item"><input type="checkbox" id="nav-1_2" name="contact" class="question-chk" value="QQ" hidden /><label for="nav-1_2" class="question-label">QQ</label></span><span class="question-item"><input type="checkbox" name="contact" id="nav-1_3" class="question-chk" value="微信" hidden /><label for="nav-1_3" class="question-label">微信</label></span></div>';
    if(iLen > 0 || jLen > 0){
        html+='<div class="nav-content nav-content_2"><ul class="yw-title" onclick="changeTypeEvent(event,2)">';
        if(iLen > 0){
            html+= '<li class="yw-item yw-active" data-index="1"><input type="checkbox" id="type-1" class="yw-chk" value="专线" hidden/><label for="type-1" class="yw-label">专线</label></li>';
        }
        if(jLen > 0){
            html+= '<li class="yw-item '+ywActive+'" data-index="2"><input type="checkbox" id="type-2" class="yw-chk" value="海外仓" hidden /><label for="type-2" class="yw-label">海外仓</label></li>';
        }
        html+='</ul>';
        html+='<div class="yw-content-main"><div class="yw-content yw-content_1">';
        if(iLen > 0){
            for(var i=0;i<iLen;i++){
              html+='<span class="question-item"><input type="checkbox" id="nav-21_'+i+'" class="question-chk" name="business_detail" value="'+companyPost[i]+'" hidden /><label for="nav-21_'+i+'" class="question-label">'+companyPost[i]+'</label></span>';
            }
         }
         html+='</div>';
         html+= ywActive ? '<div class="yw-content yw-content_2">' : '<div class="yw-content yw-content_2" style="display: none;">';
         if(jLen > 0){
            for(var j=0;j<jLen;j++){
              html+='<span class="question-item"><input type="checkbox" id="nav-22_'+j+'" class="question-chk" name="business_detail1" value="'+overseas[j].warehouse_name+'" hidden /><label for="nav-22_'+j+'" class="question-label">'+overseas[j].warehouse_name+'</label></span>';
            }
         }
        html+='</div></div></div>';
    }
   if(kLen > 0){
       html+='<div class="nav-content nav-content_3">';
       for(var k=0;k<kLen;k++){
        html+='<span class="question-item"><input type="checkbox" id="nav-3_'+k+'" class="question-chk" name="service_type" value="'+serviceTypes[k]+'" hidden /><label for="nav-3_'+k+'" class="question-label">'+serviceTypes[k]+'</label></span>';
       }
       html+='</div>';
    }
    html+='</div></div><div class="form-item"><div class="form-label">请您具体描述(上述问题及其他问题)</div><textarea name="detail" class="form-textarea" placeholder="您的反馈，方便我们为您快速联系服务商、解决需求！" rows="8" maxlength="200" onkeyup="setLength(this,200)"></textarea><span id="wordsLength" class="wordsLength">0/200字</span></div><div class="form-item"><div class="form-label"><span style="color:red">*</span>请您留下联系方式，以便我们及时告知处理结果</div><input class="form-input" placeholder="请输入手机号码" name="phone" id="phone"></input></div>';
    if(isLogin != '1') {
        html+='<div class="form-item form-item_code"><input class="form-input" name="code" placeholder="请输入手机验证码"></input><div class="code-btn" onclick="getCode()" id="get-code">获取验证码</div></div>'
    }
  +'</form>';
  layerDiv(html,'我的反馈', ['提交反馈','取消']);
  })
}
// 切换类型事件
function changeTypeEvent(event, type){
    type = type == 1 ? 'nav':'yw';
	var target = event.target || window.target;
	var curClickDom = $(target);
	if(target.nodeName == 'LI'){  // pointer-events: none; 阻止点击、状态变化和鼠标指针变化
		curClickDom.siblings().removeClass(type+'-active');
		curClickDom.addClass(type+'-active');
		var activeIndex = curClickDom.data('index');
		var curActiveConDom = $('.'+type+'-content_'+activeIndex);
		curActiveConDom.show();
		curActiveConDom.siblings().hide();
	}
}
// 文本输入框：输入字数监控
function setLength(obj,maxlength){
	var num = obj.value.length;
	if(num>=maxlength){
	num=maxlength;
	}
	document.getElementById('wordsLength').innerHTML=num+"/200字";
}

function handelFeedback(index){
   var formData = $('#feedbackForm').serializeArray();
   var map = new Map();
   var formObj = {type: ''};
   var types = [];
   var business = [];
   for(var i=0;i<formData.length;i++){
      var key = formData[i].name;
      var value = formData[i].value;
     if(map.has(key)){
        var valStr = map.get(key);
        valStr += ',' + value;
        map.set(key, valStr);
        formObj[key] = valStr;
     }else{
        if(value){
          if(key == 'contact') types.push('联系方式不准确');
          if(key == 'business_detail' || key == 'business_detail1'){
              types.push('业务内容不准确');
              if(key == 'business_detail') business.push('专线');
              if(key == 'business_detail1') business.push('海外仓');
          }
          if(key == 'service_type') types.push('服务类型不准确');
        }
        formObj[key] = value;
        map.set(key, value);
     }
   }
   if(types.length > 0){
     formObj.type = types.join(',');
   }else{
    layer.msg('请选择您遇到的问题', {icon:2});
    return false;
   }
   if(!formObj.phone){
    layer.msg('请输入您的手机号', {icon:2});
    return false;
   }
   if(!/(^1[3|4|5|6|7|8|9][0-9]{9}$)/.test(formObj.phone)){
    layer.msg('手机号格式不正确', {icon:2});
    return false;
   }
   var isLogin = $("#isLogin").val();
   if(isLogin!=1){
    if(!formObj.code){
        layer.msg('请输入验证码', {icon:2});
        return false;
    }
    if(!/^\d{6}$/.test(formObj.code)){
        layer.msg('验证码为6位数字', {icon:2});
        return false;
    }
   }
   if(types.length > 0){
    formObj.business = business.join(',');
   }
   if(formObj.hasOwnProperty('business_detail1') && formObj.business_detail1){
       if(formObj.hasOwnProperty('business_detail') && formObj.business_detail){
        formObj.business_detail +=","+formObj.business_detail1;
       }else{
        formObj.business_detail = formObj.business_detail1;
       }
       delete formObj.business_detail1;
   }
  reqSendForm('/feed-back/save',formObj,index);
}