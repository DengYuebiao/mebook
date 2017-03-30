$(function(){
	book_init();
})

function book_init()
{
	
}

function editData(pkey_id,elem)
{
	location.href=$(elem).attr("url");
}

function dropData(pkey_id,elem)
{
	if(confirm(L('drop_confirm'))==true)
	{
		location.href=$(elem).attr("url");
	}
	
}

function showData(pkey_id,elem)
{
	var h=getSite()+"/Index/sbookshow/sbook_id/"+pkey_id;
	myWindow=window.open(h,'');
	myWindow.moveTo(0,0);
    myWindow.resizeTo(screen.availWidth,screen.availHeight)
	location.href=h;
}

function classInit()
{
	if(!class_list)
	{
		class_list=new Array();
	}
	var html='';
	for(var i in class_list)
	{
		html+=getClcRow(class_list[i]);
	}
	$("#clc_main table tbody").html(html);
}

function getClcRow(row_data)
{
	if(!row_data)
	{
		row_data=new Array();
	}
	var row_html='<tr><td><input type="text" name="clc[]" class="clc t" value="'+row_data+'" />&nbsp;<button class="u-btn u-btn-c4" onclick="return clcUp(this)">'+L('up')+'</button>&nbsp;<button class="u-btn u-btn-c4" onclick="return clcDown(this)">'+L('down')+'</button>&nbsp;<button class="u-btn u-btn-c4" onclick="return clcDrop(this)">'+L('drop')+'</button>&nbsp;<button class="u-btn u-btn-c4" onclick="return clcAdd(this)">'+L('add')+'</button></td></tr>';
	
	return row_html;
}

function clcUp(elem)
{
	if($(elem).parent().parent().prev().size()>0)
	{
		$(elem).parent().parent().prev().before($(elem).parent().parent().clone());
		$(elem).parent().parent().remove();
	}
	return false;
}

function clcDown(elem)
{
	if($(elem).parent().parent().next().size()>0)
	{
		$(elem).parent().parent().next().after($(elem).parent().parent().clone());
		$(elem).parent().parent().remove();
	}
	return false;
}

function clcDrop(elem)
{
	var val=$(elem).parent().parent().find(".clc").val();
	if($.inArray(val,not_clc_list)!=-1)
	{
		alert(L('clc_not_drop'));
		return false;
	}
	$(elem).parent().parent().remove();
	return false;
}

function clcAdd(elem)
{
	var html=getClcRow();
	$(elem).parent().parent().after(html);
	$(elem).parent().parent().next().find("input.t").focus();
	return false;
}