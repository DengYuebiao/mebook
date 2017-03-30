/*表单验证函数
@param object 表单对象
@param object 服务器验证规则
@param object 表单对象配置类
*/
function formValid(formObj,rules,setting)
{
	if(!formObj || !rules)
	{
		return false;
	}
	var rules_setting=rules.rules?rules.rules:{};
	var messages_setting=rules.messages?rules.messages:{};
	var valid_cnf={
		errorPlacement: function(error, element){			
            $(element).next('.field_notice').hide();
            $(element).after(error);
        },
		success : function(label){
            label.addClass('form_valid_right').text('OK');
        },
        onkeyup : false,
		errorClass:"form_valid_error",
		validClass:"form_valid_right",
        rules : rules_setting,
        messages :messages_setting
	};
	if(setting)		//如果设置类验证类配置则覆盖
	{
		$.extend(valid_cnf,setting);
	}
	formObj.validate(valid_cnf);
}

/*回车跳转到下一个元素*/
function enterToNext(e,dest)
{
	var keynum = e.which||e.keyCode;
	if(keynum==13)
	{
		var dest_obj=$(dest);
		if(dest_obj.size())
		{
			dest_obj[0].focus();
		}
		return false;
	}
	else
	{
		return true;
	}
}

function isIE()
{
	var is_ie=!!window.ActiveXObject;
	return is_ie;
}

function isIE6()
{
	var is_ie=!!window.ActiveXObject;
	var is_ie6=is_ie&&!window.XMLHttpRequest;
	return is_ie6;
}

function isIE7()
{
	var is_ie=!!window.ActiveXObject;
	var is_ie6=is_ie&&!window.XMLHttpRequest;
	var is_ie8=is_ie&&!!document.documentMode;
	var is_ie7=is_ie&&!is_ie6&&!is_ie8;
	return is_ie7;
}

function isIE8()
{
	var is_ie=!!window.ActiveXObject;
	var is_ie6=is_ie&&!window.XMLHttpRequest;
	var is_ie8=is_ie&&!!document.documentMode;
	return is_ie8;
}

function addFav()
{
	var ctrl = (navigator.userAgent.toLowerCase()).indexOf('mac') != -1 ? 'Command/Cmd': 'CTRL';
	var site="http://"+document.location.host;
	if (document.all) {
		window.external.addFavorite(site, document.title)
	} else if (window.sidebar) {
		window.sidebar.addPanel(document.document.title,site, "")
	} else {
		alert("您可以尝试CTRL+D快捷键加入收藏夹")
	}
}

function getCookie(sName) {
    var aCookie = document.cookie.split("; ");
    for (var i=0; i < aCookie.length; i++){
      var aCrumb = aCookie[i].split("=");
      if (sName == aCrumb[0]) return decodeURIComponent(aCrumb[1]);
    }
    return '';
}

function setCookie(sName, sValue, sExpires,path) {
    var sCookie = sName + "=" + encodeURIComponent(sValue);
    if (sExpires != null)
	{ 
		var date=new Date();
		date.setTime(date.getTime()+sExpires*24*3600*1000); 
		sCookie += "; expires=" + date.toGMTString();
	}
	if (path != null) sCookie += "; path=" + path;
    document.cookie = sCookie;
}

function removeCookie(sName) {
    document.cookie = sName + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
}

function getSite(){
    return "http://"+location.host;
}

//是否存在指定函数 
function hasFunc(funcName) {
    try {
        if (typeof(eval(funcName)) == "function") {
            return true;
        }
    } catch(e) {}
    return false;
}


function goBack()
{
	history.back();
}

/*访问语言变量*/
function L(key)
{
	var lang_arr=lang_list?lang_list:new Array();
	key=key.toUpperCase();
	var val=lang_arr[key]?lang_arr[key]:key.toLowerCase();
	return val;
}

//增加书单数据
function addBoolist(book_id,ret_func)
{
	var url=getSite()+"/BooklistExt/add/book_id/"+book_id;	
	$.ajax({
		 url:url,
		 dataType:"json",
		 async:false,
		 type:"POST",
		 success: function(ret){
			  if(ret_func)
			  {
				  ret_func(ret);
			  }
			  else
			  {
				  alert(ret.info);
			  }
			  
	 }});
}

function addBookmark(book_id,ret_func)
{
	var url=getSite()+"/User/addbookmark/book_id/"+book_id;	
	$.ajax({
		 url:url,
		 dataType:"json",
		 async:false,
		 type:"POST",
		 success: function(ret){
			  if(ret_func)
			  {
				  ret_func(ret);
			  }
			  else
			  {
				  alert(ret.info);
			  }
			  
	 }});
}


function addCmt(book_id,cmt_body,ret_func)
{
	var url=getSite()+"/User/addcmt/book_id/"+book_id;	
	var data={'cmt_body':cmt_body};
	$.ajax({
		 url:url,
		 dataType:"json",
		 data:data,
		 async:false,
		 type:"POST",
		 success: function(ret){
			  if(ret_func)
			  {
				  ret_func(ret);
			  }
			  else
			  {
				  alert(ret.info);
			  }
			  
	 }});
}
