/*!
 * jQuery loadbox Plugin 1.0 beta
 *
 * 	Copyright 2014 wuxing qq:252629505 email:wuxing2722@qq.com
 *
*/

$.LoadBox=function(option){
	if($.LoadBoxClass['currObj'])
	{
		console.log("loadbox Already exists");
		return false;
	}

	var loadbox_obj=new $.LoadBoxClass(option,this);	//初始化类
	$.LoadBoxClass.currObj=loadbox_obj
	return loadbox_obj;
};
		
$.LoadBoxDrop=function(cmd_str){		//只支持单个元素命令
	if(!$.LoadBoxClass['currObj'])
	{
		console.log("loadbox not exists");
		return false;
	}
	$.LoadBoxClass.currObj.drop();
	$.LoadBoxClass.currObj=null;
}


//LoadBoxClass构造函数
$.LoadBoxClass = function( option, select ) {
	this.settings = $.extend( true, {}, $.LoadBoxClass.defaults, option );
	this.elem=null;
	this.show();
};

$.extend($.LoadBoxClass,{
	defaults:{
		"img":"loading.gif"
	},
	prototype:{
		show:function(){		//显示正在加载框
			var html='<div class="m-layer m-layer-show">  <div class="lymask"></div>    <table class="lytable"><tbody><tr><td class="lytd">    <div class="lywrap">	  <div class="lyct"><div class="u-loadbox-ico"></div>  </div>	     </div></td></tr></tbody></table></div>';
			$("body").append(html);
			var elem_obj=$(".m-layer:first");
			this.elem=elem_obj;
		},
		drop:function(){		//显示正在加载框
			this.elem.remove();
		},
		__bind:function(fn,me){
			return function(){
				return fn.apply(me,arguments);
				};
		}


	}
	
	
});