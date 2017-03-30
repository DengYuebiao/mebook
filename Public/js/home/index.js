function hasPdfPlugin() {
    var flag = false;
	if(isPc())
	{
		// 如果是firefox浏览器或者谷歌浏览器
		if (navigator.plugins && navigator.plugins.length) {
			flag = true;
		}
		// 下面代码都是处理IE浏览器的情况 
		else if (window.ActiveXObject) {

			for (x = 2; x < 16; x++) {
				try {
					oAcro = eval("new ActiveXObject('PDF.PdfCtrl." + x + "');");
					if (oAcro) {
						flag = true;
						return flag;
					}
				} catch(e) {
					flag = false;
				}
			}
			try {
				oAcro4 = new ActiveXObject('PDF.PdfCtrl.1');
				if (oAcro4) 
				{
					flag = true;
					return flag;
				}
			} catch(e) {
				flag = false;
			}
			try {
				oAcro7 = new ActiveXObject('AcroPDF.PDF.1');
				if (oAcro7){
					 flag = true;
					 return flag;
				}
			} catch(e) {
				flag = false;
			}
			
			try {
				foxit_pdf = new ActiveXObject('FOXITREADEROCX.FoxitReaderOCXCtrl.1') 
				if (foxit_pdf){
					flag = true;
					return flag;
				}
			} catch(e) {
				flag = false;
			}
			
		}
	
		if (flag) {
			return true;
		} else {
		   return false;
		}
	}
	else
	{
		return true;
	}
}

function showPdf(bookurl)
{
	window.moveTo(0,0);
    window.resizeTo(screen.availWidth,screen.availHeight);
    window.location=bookurl;
}

function initBooklist()
{
	$(".m-box").delegate(".toolbar .page span","click",function(e){
		var pos=$(this).attr("pos");
		$(this).parent().find(".sel").removeClass("sel");
		$(this).addClass("sel");
		$(this).parents(".m-box").find(".content ul:visible").hide();
		$(this).parents(".m-box").find(".content ul[pos='"+pos+"']").show();
	});
}

function initSlide()
{
	$(".m-slide").each(function(index, element) {
		var slide_obj=$(this);
		var slen=this.offsetWidth;
        var time_id=setInterval(function(){
			var sleft=slide_obj.scrollLeft();
			var curr_offset=sleft+1;
			if(sleft>slen)
			{
				curr_offset=0;
			}
			slide_obj.scrollLeft(curr_offset);
		},20);
		this.time_id=time_id;
		slide_obj.mouseover(function(e) {
			if(this.time_id)
			{
				clearInterval(this.time_id);
			}
		});
		
		slide_obj.mouseleave(function(e) {
			if(this.time_id)
			{
				clearInterval(this.time_id);
			}
			
			var time_id=setInterval(function(){
			var sleft=slide_obj.scrollLeft();
			var curr_offset=sleft+1;
			if(sleft>slen)
			{
				curr_offset=0;
			}
			slide_obj.scrollLeft(curr_offset);
			
		},20);
		this.time_id=time_id;
		});
    });
}


function initTextroll()
{
	$(".m-textroll .inner").each(function(index, element) {
		var slide_obj=$(this);
		var slen=this.offsetWidth;
        var time_id=setInterval(function(){
			var sleft=slide_obj.scrollLeft();
			var curr_offset=sleft+1;
			if(sleft>slen)
			{
				curr_offset=0;
			}
			slide_obj.scrollLeft(curr_offset);
		},20);
		this.time_id=time_id;
		slide_obj.mouseover(function(e) {
			if(this.time_id)
			{
				clearInterval(this.time_id);
			}
		});
		
		slide_obj.mouseleave(function(e) {
			if(this.time_id)
			{
				clearInterval(this.time_id);
			}
			
			var time_id=setInterval(function(){
			var sleft=slide_obj.scrollLeft();
			var curr_offset=sleft+1;
			if(sleft>slen)
			{
				curr_offset=0;
			}
			slide_obj.scrollLeft(curr_offset);
			
		},20);
		this.time_id=time_id;
		});
    });
}

function initTop()
{
	$(".m-indextop").delegate(".btn span","click",function(e){
		var index=$(this).index();
		$(this).parent().find("span.sel").removeClass("sel");
		$(this).addClass("sel");
		$(this).parent().parent().find(".list ul:visible").hide();
		$(this).parent().parent().find(".list ul:eq("+index+")").show();
	});
	
	$(".m-indextop").each(function(index, element) {
        
    });
}

function getHeight()
{
	// 获取窗口宽度
	/*if (window.innerWidth)
	{
		winWidth = window.innerWidth;
	}
	else if ((document.body) && (document.body.clientWidth))
	{
		winWidth = document.body.clientWidth;
	}*/
	
	// 获取窗口高度
	if (window.innerHeight)
	{
		winHeight = window.innerHeight;
	}
	else if ((document.body) && (document.body.clientHeight))
	{
		winHeight = document.body.clientHeight;
	}
	// 通过深入 Document 内部对 body 进行检测，获取窗口大小
	if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth)
	{
		winHeight = document.documentElement.clientHeight;
		//winWidth = document.documentElement.clientWidth;
	}
	return winHeight;
}

function initLoginBg(elem_obj)
{
	var height=getHeight()-182;
	elem_obj.height(height);
}


function initTextMove()
{
	$(".m-textroll .inner ul").each(function(index, element) {
        $(this).find("li:first").show();
    });
	
	var curr_ul=0;
	
	setInterval(function(){
		var curr_li=$(".m-textroll .inner ul:eq("+curr_ul+") li:visible");
		$(".m-textroll .inner ul:eq("+curr_ul+") li:visible").hide();
		
		var next_li=curr_li.next();
		if(!next_li.size())
		{
			$(".m-textroll .inner ul:eq("+curr_ul+") li:eq(0)").fadeIn("slow");
		}
		else
		{
			next_li.fadeIn("slow");
		}
		
		if(curr_ul>=2)
		{
			curr_ul=0;
		}
		else
		{
			curr_ul++;
		}
		
	},5000);
	
	/*$(".m-textroll .inner ul:first").show();
	var ul_cnt=$(".m-textroll .inner ul").size();
	var curr_index=0;
	setInterval(function(){
		if(curr_index>=ul_cnt)
		{
			curr_index=0;
		}
		else
		{
			curr_index++;
		}

		$(".m-textroll .inner ul:visible").hide();
		$(".m-textroll .inner ul:eq("+curr_index+")").show();
		
	},1000);*/
	
}

function isPc(){
    var os = new Array("Android","iPhone","Windows Phone","iPod","iPad","BlackBerry","MeeGo","SymbianOS");  // 其他类型的移动操作系统类型，自行添加
    var info = navigator.userAgent;
    var len = os.length;
    for (var i = 0; i < len; i++) {
        if (info.indexOf(os[i]) > 0){
            return false; 
        }
    }
    return true;
}

function showObj(obj)
{
	var html='';
	for(var i in obj)
	{
		var val=obj[i];
		html+='<p>'+i+':'+val+'</p>';
	}
	$("body").append(html);
}