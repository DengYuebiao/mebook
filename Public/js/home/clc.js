
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
	var h=getSite()+"/Index/bookview/book_id/"+pkey_id;
	myWindow=window.open(h,'');
	myWindow.moveTo(0,0);
    myWindow.resizeTo(screen.availWidth,screen.availHeight)
    window.location=bookurl;
	location.href=h;
}

function addToBooklist(pkey_id,elem)
{
	addBoolist(pkey_id,function(ret){
		if(ret.info==L('sys_booklist_id_notset'))
		{
			if(confirm(L('sys_booklist_id_set_note'))==true)
			{
				location.href=getSite()+"/Booklist/index";
				return;
			}
		}
		alert(ret.info);
		
	});
}


function classInit()
{
	if(!class_list)
	{
		class_list=new Array();
	}
	var html=getClcListHtml(class_list);

	$("#clclist").html(html);
}

function getClcListHtml(clc_list)
{
	if(!clc_list)
	{
		clc_list=new Array();
	}
	var li_html='';
	for(var i in clc_list)
	{
		var clc_item=clc_list[i];
		li_html+=getClcItemHtml(clc_item);
		
	}
	var row_html='<ul>'+li_html+'</ul>';
	
	return row_html;
}

function getClcItemHtml(row_data)
{
	if(!row_data)
	{
		row_data=new Array();
	}
	var clc_id=row_data['clc_id']?row_data['clc_id']:'';
	var pid=row_data['pid']?row_data['pid']:'';
	var clc=row_data['clc']?row_data['clc']:'';
	var clc_desc=row_data['clc_desc']?row_data['clc_desc']:'';
	var order_num=row_data['order_num']?row_data['order_num']:'';
	var row_html='<li><div class="clc_info f-cb"><div class="info"><input type="hidden" class="clc_id" value="'+clc_id+'" /><input type="hidden" class="pid" value="'+pid+'" /><input type="hidden" class="order_num" value="'+order_num+'" /><img class="list_ico" onclick="listChild(this)" src="/Public/image/ico1.png"><span class="clc_desc"><span class="clc">'+clc+'</span>&nbsp;'+clc_desc+'</span></div></div></li>';
	
	return row_html;
}

function getClcDialogHtml(row_data)
{
	if(!row_data)
	{
		row_data=new Array();
	}
	var clc_id=row_data['clc_id']?row_data['clc_id']:'';
	var pid=row_data['pid']?row_data['pid']:'';
	var clc=row_data['clc']?row_data['clc']:'';
	var clc_desc=row_data['clc_desc']?row_data['clc_desc']:'';
	var order_num=row_data['order_num']?row_data['order_num']:'';
	var row_html='<div class="m-tableform"><table><tr><td width="100">'+L('clc')+'</td><td><input type="text" class="clc t1" value="'+clc+'" /><input type="hidden" class="clc_id" value="'+clc_id+'" /><input type="hidden" class="pid" value="'+pid+'" /></td></tr><tr><td>'+L('clc_desc')+'</td><td><input type="text" class="clc_desc t1" value="'+clc_desc+'" /></td></tr><tr><td>'+L('order_num')+'</td><td><input type="text" class="order_num t1" value="'+order_num+'" /></td></tr></table></div>';
	
	return row_html;
}

function clcUp(elem)
{
	var data=getClcData($(elem).parent().parent().parent().find(".clc_info"));
	var clc_id=data['clc_id']?data['clc_id']:0;
	var data_prev=getClcData($(elem).parent().parent().parent().prev().find(".clc_info"));
	var dest_clc_id=data_prev['clc_id']?data_prev['clc_id']:0;
	var clc_data={};
	clc_data['seq_type']='up';
	clc_data['clc_id']=clc_id;
	clc_data['dest_clc_id']=dest_clc_id;
	var url=getSite()+"/Book/change_seq";	
	 $.ajax({
		 url:url,
		 dataType:"json",
		 data:clc_data,
		 async:false,
		 type:"POST",
		 success: function(ret){
			  
			  if(ret['status'])
			  {
				  $(elem).parent().parent().parent().prev().before($(elem).parent().parent().parent().clone());
				   $(elem).parent().parent().parent().remove();
			  }
			  else
			  {
				  alert(ret['info']);
			  }
	 }});
	return false;
}

function clcDown(elem)
{
	var data=getClcData($(elem).parent().parent().parent().find(".clc_info"));
	var clc_id=data['clc_id']?data['clc_id']:0;
	var data_prev=getClcData($(elem).parent().parent().parent().next().find(".clc_info"));
	var dest_clc_id=data_prev['clc_id']?data_prev['clc_id']:0;
	var clc_data={};
	clc_data['seq_type']='down';
	clc_data['clc_id']=clc_id;
	clc_data['dest_clc_id']=dest_clc_id;
	var url=getSite()+"/Book/change_seq";	
	 $.ajax({
		 url:url,
		 dataType:"json",
		 data:clc_data,
		 async:false,
		 type:"POST",
		 success: function(ret){
			  
			  if(ret['status'])
			  {
				  $(elem).parent().parent().parent().next().after($(elem).parent().parent().parent().clone());
				   $(elem).parent().parent().parent().remove();
			  }
			  else
			  {
				  alert(ret['info']);
			  }
	 }});
	return false;
}


function clcAdd(elem)
{
	var data=getClcData($(elem).parent().parent().parent().find(".clc_info"));
	var after_clc_id=data['clc_id']?data['clc_id']:0;
	var html_str=getClcDialogHtml({'pid':data['pid']});
	new mydialog({html:html_str,title:L('add_clc'),on_submit:function(dialog){
		var clc_data=getClcData(dialog.dialog_obj);
		clc_data['after_clc_id']=after_clc_id;
		var url=getSite()+"/Book/add_clc";	
		 $.ajax({
			 url:url,
			 dataType:"json",
			 data:clc_data,
			 async:false,
			 type:"POST",
			 success: function(ret){
				  alert(ret['info']);
				  if(ret['status'])
				  {
				  		location.reload();
				  }
		 }});
	}});
	return false;
}

function clcEdit(elem)
{
	var data=getClcData($(elem).parent().parent().parent().find(".clc_info"));
	var html_str=getClcDialogHtml(data);
	new mydialog({html:html_str,title:L('edit_clc'),on_submit:function(dialog){
		var clc_data=getClcData(dialog.dialog_obj);
		var url=getSite()+"/Book/save_clc";	
		 $.ajax({
			 url:url,
			 dataType:"json",
			 data:clc_data,
			 async:false,
			 type:"POST",
			 success: function(ret){
				  alert(ret['info']);
				  if(ret['status'])
				  {
				  		location.reload();
				  }
		 }});
	}});
	return false;
}

function clcDrop(elem)
{
	if(confirm(L('drop_confirm'))==true)
	{
		var data=getClcData($(elem).parent().parent().parent().find(".clc_info"));
		var url=getSite()+"/Book/drop_clc";	
		$.ajax({
				 url:url,
				 dataType:"json",
				 data:{'clc_id':data['clc_id']},
				 async:false,
				 type:"POST",
				 success: function(ret){
					  alert(ret['info']);
					  if(ret['status'])
					  {
							location.reload();
					  }
			 }});
	}
	return false;
}

function clcAddChlid(elem)
{
	var data=getClcData($(elem).parent().parent().parent().find(".clc_info"));
	var pid=data['clc_id']?data['clc_id']:0;
	var html_str=getClcDialogHtml();
	new mydialog({html:html_str,title:L('add_child'),on_submit:function(dialog){
		var clc_data=getClcData(dialog.dialog_obj);
		clc_data['pid']=pid;
		var url=getSite()+"/Book/add_clc";	
		 $.ajax({
			 url:url,
			 dataType:"json",
			 data:clc_data,
			 async:false,
			 type:"POST",
			 success: function(ret){
				  alert(ret['info']);
				  if(ret['status'])
				  {
				  		location.reload();
				  }
		 }});
	}});
	return false;
}
function clcChangePid(elem)
{
	new mydialog({html:'',title:L('add_clc'),on_submit:function(dialog){alert('test');return true;}});
	var html=getClcRow();
	$(elem).parent().parent().after(html);
	$(elem).parent().parent().next().find("input.t").focus();
	return false;
}

function listChild(elem)
{
	
	var img_src=$(elem).attr("src");
	if(img_src.substr(img_src.length-8)=="ico1.png")
	{
		
		$(elem).attr("src",img_src.replace('ico1.png','ico2.png'));
		var clc_id=$(elem).parent().find(".clc_id").val();
		getClcChildHtml(clc_id,function(html){
			$(elem).parent().parent().parent().append(html);
		})
	}
	else
	{
		$(elem).attr("src",img_src.replace('ico2.png','ico1.png'));
		$(elem).parent().parent().parent().find("ul").remove();
	}
	
}

function getClcChildHtml(clc_id,re_func)
{
	 var url=getSite()+"/Index/get_clc_child/clc_id/"+clc_id;	
	 $.ajax({
		 url:url,
		 dataType:"json",
		 async:false,
		 type:"POST",
		 success: function(ret){
			  if(re_func)
			  {
				  var html=getClcListHtml(ret);
				  re_func(html);
			  }
	 }});
}

function getClcData(elem_obj)
{
	var data={};
	data['clc_id']=getElemVal($(elem_obj).find(".clc_id"));
	data['pid']=getElemVal($(elem_obj).find(".pid"));
	data['clc']=getElemVal($(elem_obj).find(".clc"));
	data['clc_desc']=getElemVal($(elem_obj).find(".clc_desc"));
	data['order_num']=getElemVal($(elem_obj).find(".order_num"));
	return data;
}

function getElemVal(obj)
{
	if(obj.is("input[type='text'],input[type='hidden']"))
	{
		return obj.val();
	}
	else
	{
		return obj.html();
	}
}

function clcSearch(elem)
{
	 var clc=$(elem).parent().find(".clc").html();
	 var data=$("#clc_search form").serialize();
	 var url=getSite()+"/Index/clc/clc/"+clc+"?"+data;	
	 var post_data={'gtype':'json'};
	 $.ajax({
		 url:url,
		 dataType:"json",
		 data:post_data,
		 async:false,
		 type:"POST",
		 success: function(ret){
			  $("#booklist").html(ret);
	 }});
}