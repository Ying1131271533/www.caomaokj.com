

// token登录状态
function isToken(){
	var jwt = storage('token');
	if(jwt)
	{
		$.ajax({
			type: "GET",
			url: "https://www.caomaokj.com/index/login_api/isToken",
			beforeSend: function(xhr) {
			  xhr.setRequestHeader("Authorization", jwt);
			},
			success: function(json) {
				if(json.data.code == 0)
				{
					// token验证通过
					// layer.msg(json.data.msg, {icon: 1});
					return true
				}else{
					// token验证未通过
					// layer.msg(json.data.msg, {icon: 2});
					return false;
				}
			}
		});
	}
}

// 退出登录
function logout(){
	storage('token', null);
	window.location.href = "/index/login/logout";
}
 
// 本地储存
function storage(key, value){
	if(!key)
	{
		return false;
	}
	
	if(typeof value != "undefined"){
		window.localStorage.setItem(key, value);
	}else{
		var jwt = window.localStorage.getItem(key);
		return jwt;
	}
}



