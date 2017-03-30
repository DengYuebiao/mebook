/*!
 * jQuery mydialog Plugin 1.0 beta
 *
 * 	Copyright 2014 wuxing qq:252629505 email:wuxing2722@qq.com
 *	Released under the Apache2 license:
 *
*/
//mydialog构造函数
function mydialog(option) {
	this.defaults={
		'html':'',
		'is_submit':true,
		'is_cancel':true,
		'is_close':true,
		'on_submit':null,
		'on_cancel':null,
		'on_close':null,
		'title':'dialog',
		'other_info':'',
		'submit_name':L('confirm'),
		'cancel_name':L('cancel')
	};
	this.dialog_id='mydialog_id_'+$('.m-layer').size();
	this.dialog_obj=null;
	this.settings = $.extend( true, {}, this.defaults, option );
	this.show();
};

mydialog.prototype.show=function(){		//显示tooltip
	var html=this.createHtml();
	if(isIE6())
	{
		$("body").css("height","100%");
	}
	$("body").append(html);
	this.dialog_obj=$("#"+this.dialog_id)
	this.bind();
}

mydialog.prototype.createHtml=function(){	
	var submit_btn=this.settings['is_submit']?'<button type="button" class="submit_btn u-btn">'+this.settings['submit_name']+'</button>':'';
	var cancel_btn=this.settings['is_cancel']?'<button type="button" class="cancel_btn u-btn">'+this.settings['cancel_name']+'</button>':'';
	var close_btn=this.settings['is_close']?'<span class="lyclose">×</span>':'';
	
	var html='<div class="m-layer m-layer-show" id="'+this.dialog_id+'"><div class="lymask"></div> <table class="lytable"><tbody><tr><td class="lytd"><div class="lywrap">	    <div class="lytt"><h2 class="u-tt">'+this.settings['title']+'</h2>'+close_btn+'</div>	<div class="lyct">'+this.settings['html']+'</div><div class="lybt"><div class="lyother">'+this.settings['other_info']+'</div><div class="lybtns">'+submit_btn+cancel_btn+'</div></div></div></td></tr></tbody></table></div>';
	
	return html;
}

mydialog.prototype.__bind=function(fn,me){
	return function(){
		return fn.apply(me,arguments);
		};
};

mydialog.prototype.bind=function(){	
	this.dialog_obj.find(".submit_btn").bind("click",this.__bind(this.onSubmitClick,this));	
	this.dialog_obj.find(".lyclose").bind("click",this.__bind(this.onCloseClick,this));	
	this.dialog_obj.find(".cancel_btn").bind("click",this.__bind(this.onCancelClick,this));		
}

mydialog.prototype.onCancelClick=function(evt){	
	var elem=evt.srcElement||evt.target;
	if(this.settings['on_cancel'])
	{
		var is_success=this.settings['on_cancel'](this);
		if(is_success)
		{
			this.remove();
		}
	}
	else
	{
		this.remove();
	}
}

mydialog.prototype.onCloseClick=function(evt){	
	var elem=evt.srcElement||evt.target;
	if(this.settings['on_close'])
	{
		var is_success=this.settings['on_close'](this);
		if(is_success)
		{
			this.remove();
		}
	}
	else
	{
		this.remove();
	}
}

mydialog.prototype.onSubmitClick=function(evt){	
	var elem=evt.srcElement||evt.target;
	if(this.settings['on_submit'])
	{
		var is_success=this.settings['on_submit'](this);
		if(is_success)
		{
			this.remove();
		}
	}
	else
	{
		this.remove();
	}
}

mydialog.prototype.remove=function(evt){	
	this.dialog_obj.remove();
}