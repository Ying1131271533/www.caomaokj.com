//十六进制颜色值域RGB格式颜色值之间的相互转换

//-------------------------------------
//十六进制颜色值的正则表达式
var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
/*RGB颜色转换为16进制*/
String.prototype.colorHex = function(){
	var that = this;
	if(/^(rgb|RGB)/.test(that)){
		var aColor = that.replace(/(?:\(|\)|rgb|RGB)*/g,"").split(",");
		var strHex = "#";
		for(var i=0; i<aColor.length; i++){
			var hex = Number(aColor[i]).toString(16);
			if(hex === "0"){
				hex += hex;
			}
			strHex += hex;
		}
		if(strHex.length !== 7){
			strHex = that;
		}
		return strHex;
	}else if(reg.test(that)){
		var aNum = that.replace(/#/,"").split("");
		if(aNum.length === 6){
			return that;
		}else if(aNum.length === 3){
			var numHex = "#";
			for(var i=0; i<aNum.length; i+=1){
				numHex += (aNum[i]+aNum[i]);
			}
			return numHex;
		}
	}else{
		return that;
	}
};

//-------------------------------------------------

/*16进制颜色转为RGB格式*/
String.prototype.colorRgb = function(){
	var sColor = this.toLowerCase();
	if(sColor && reg.test(sColor)){
		if(sColor.length === 4){
			var sColorNew = "#";
			for(var i=1; i<4; i+=1){
				sColorNew += sColor.slice(i,i+1).concat(sColor.slice(i,i+1));
			}
			sColor = sColorNew;
		}
		//处理六位的颜色值
		var sColorChange = [];
		for(var i=1; i<7; i+=2){
			sColorChange.push(parseInt("0x"+sColor.slice(i,i+2)));
		}
		return "rgb(" + sColorChange.join(",") + ")";
	}else{
		return sColor;
	}
};

function convert(){
	var type = $(this).attr("data-type");
	var c = {
		hex2rgb : function(){
			var from = document.getElementById("from").value;
			document.getElementById("to_hex2rgb").value = from.colorRgb();
			$(".colorResult").css("background-color",from);
		},
		rgb2hex : function(){
			var r = document.getElementById("colorR").value;
			var g = document.getElementById("colorG").value;
			var b = document.getElementById("colorB").value;
			if(parseInt(r) > 255){
				r = 255;
			}else if(!parseInt(r)){
				r = 0;
			}
			if(parseInt(g) > 255){
				g = 255;
			}else if(!parseInt(g)){
				g = 0;
			}
			if(parseInt(b) > 255){
				b = 255;
			}else if(!parseInt(b)){
				b = 0;
			}
			document.getElementById("colorR").value = r;
			document.getElementById("colorG").value = g;
			document.getElementById("colorB").value = b;
            var v = "rgb("+r+","+g+","+b+")";
            document.getElementById('to_rgb2hex').value = v.colorHex();
			$(".colorResult").css("background-color",v.colorHex());
		}
	};
	switch(type){
		case 'hex2rgb':
			c.hex2rgb();
			break;
		case 'rgb2hex':
			c.rgb2hex();
			break;
	}
}
$(function(){
	$(".colorConvert").on("click",convert);
})
