/*!
 * jQuery tooltip Plugin 1.0 beta
 *
 * 	Copyright 2014 wuxing qq:252629505 email:wuxing2722@qq.com
 *	Released under the Apache2 license:
 *
*/
//toolTipClass构造函数
function toolTip(dest_elem,msg,direct,option) {
	this.defaults={
		left:"50%",			//tooltip在当前显示位置左侧的偏移
		top:null,			//tooltip在当前显示位置上侧的偏移,为null的时候自动获取元素高度
		bottom:null			//tooltip在当前显示位置上侧的偏移,为null的时候自动获取元素高度
	};
	
	this.dest_elem=dest_elem;
	this.msg=msg;

	this.direct=direct;		//tooltip显示的方向 top或者bottom
	this.settings = $.extend( true, {}, this.defaults, option );
	this.show();
};

toolTip.prototype.show=function(){		//显示tooltip
			var html=this.createHtml();
			var tool_obj=$(html).insertAfter($(this.dest_elem));
			
			if(typeof(msg)=="object")
			{
				tool_obj.find(".tooltip_msg").html(this.msg);
				//this.msg=$(msg)[0].outerHTML;
			}
			else
			{
				tool_obj.find(".tooltip_msg").html(this.msg);
				//this.msg=msg;
			}
}

toolTip.prototype.createHtml=function(){		//创建tooltip元素
			var tooltip_css='position: relative;zoom: 1;';
			var position_val="";
			if(this.direct=="bottom")
			{									//如果是显示在下方,通过top定位, 自动加上7像素的指示头高度	
				position_val=7;
				position_val=this.defaults.bottom?this.defaults.bottom:position_val;
				position_val='top:'+position_val.toString()+"px";
			}
			else
			{						//如果是显示在上方,通过bottom定位 ,自动获取目标元素的高度加上9像素的指示头高度
				position_val=$(this.dest_elem).size()>0?$(this.dest_elem)[0].offsetHeight+9:9;
				position_val=this.defaults.top?this.defaults.top:position_val;
				position_val='bottom:'+position_val.toString()+"px";
				
			}
			var warp_css='position: absolute;'+position_val+';left:'+this.settings.left+';border:1px solid #D8D8DA;border-radius: 5px;padding: 5px 5px 5px 5px;background-color: #FFF;z-index:999';
			var msg_css='';
			
			
			if(this.direct=="bottom")
			{
				var dec_css='position: absolute;top: -1px;left: 9px;';
				var dec1_css='color: #CCC;font-family: simsun;font-size: 16px;height: 19px;left: 0;line-height: 19px;position: absolute;text-decoration: none;bottom: -11px;width: 17px;';
				var dec2_css='font-family: simsun;font-size: 16px;height: 19px;left: 0;line-height: 19px;position: absolute;text-decoration: none;width: 17px;color: #FFF;bottom: -12px;';
				var html='<div style="'+tooltip_css+'" class="tooltip_class"><div style="'+warp_css+'" class="tooltip_warp"><span style="'+dec_css+'" class="dec"><s style="'+dec1_css+'" class="dec1">◆</s><s style="'+dec2_css+'" class="dec2">◆</s></span><div style="'+msg_css+'" class="tooltip_msg"></div></div></div>';
			}
			else
			{
				var dec_css='position: absolute;bottom: -1px;left: 9px;';
				var dec1_css='color: #CCC;font-family: simsun;font-size: 16px;height: 19px;left: 0;line-height: 19px;position: absolute;text-decoration: none;top: -9px;width: 17px;';
				var dec2_css='font-family: simsun;font-size: 16px;height: 19px;left: 0;line-height: 19px;position: absolute;text-decoration: none;width: 17px;color: #FFF;top: -10px;';
				var html='<div style="'+tooltip_css+'" class="tooltip_class"><div style="'+warp_css+'" class="tooltip_warp"><div style="'+msg_css+'" class="tooltip_msg"></div><span style="'+dec_css+'" class="dec"><s style="'+dec1_css+'" class="dec1">◆</s><s style="'+dec2_css+'" class="dec2">◆</s></span></div></div>';
			}
			
			return html;
}
		
toolTip.prototype.bind=function(){		//绑定tooltip元素事件
			
}
