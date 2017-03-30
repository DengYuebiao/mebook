function editData(pkey_id,elem)
{
	location.href=$(elem).attr("url");
}

function drop_bookmark(pkey_id,elem)
{
	if(confirm(L('drop_confirm'))==true)
	{
		location.href=$(elem).attr("url");
	}
	
}

function drop_cmt(pkey_id,elem)
{
	if(confirm(L('drop_confirm'))==true)
	{
		location.href=$(elem).attr("url");
	}
	
}

function showData(pkey_id,elem)
{
	var h=getSite()+"/Index/bookview/book_id/"+pkey_id;
	myWindow=window.open(h,'');
	myWindow.moveTo(0,0);
    myWindow.resizeTo(screen.availWidth,screen.availHeight)
    window.location=bookurl;
	location.href=h;
}


function selSysPort(elem)
{
	if($(elem).parent().find(".m-port").size()>0)
	{
		return false;
	}
	
	var html='';
	var base_dir='/Public/image/portrait/';
	for(var i=1;i<85;i++)
	{
		var name=i<10?'00'+i+'.jpg':'0'+i+'.jpg';
		html+='<li><img src="'+base_dir+name+'" /></li>';
	}
	html='<div class="m-port"><div class="inner"><ul class="f-cb">'+html+'</ul></div></div>';
	$(elem).parent().append(html);
	initUserPort();
	return false;
}

function initUserPort()
{
	$("#portrait").change(function(e) {
        $("#port_type").val("1");
    });
	$(".m-port").delegate("li img","click",function(e){
		$("#port_type").val("2");
		$("#port_sys").val($(this).attr("src").substr(1));
		$(this).parent().parent().find("img.sel").removeClass("sel");	
		$(this).addClass("sel");
	});
}

function drop_sh(pkey_id,elem)
{
	if(confirm(L('drop_confirm'))==true)
	{
		location.href=$(elem).attr("url");
	}
	
}