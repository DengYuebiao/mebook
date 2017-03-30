function addBM(book_id,elem)
{
	addBookmark(book_id,function(ret){
		 alert(ret.info);
		if(ret.status)
		{
			$(elem).remove();
		}
	});
}

function add_cmd(book_id,elem)
{
	var cmt_body=$("#cmt_body").val();
	if(!cmt_body)
	{
		alert(L('cmt_body_empty'));
		return;
	}
	addCmt(book_id,cmt_body,function(ret){
		 alert(ret.info);
		if(ret.status)
		{
			location.reload();
			//$(elem).remove();
		}
	});
}

function sendCmtExt(cmt_id,ctype,elem)
{
	var url=getSite()+"/User/addcmtext";	
	var data={'cmt_id':cmt_id,'ctype':ctype};
	$.ajax({
		 url:url,
		 dataType:"json",
		 data:data,
		 async:false,
		 type:"POST",
		 success: function(ret){
			  if(ret.status)
			  {
				  var cnt=parseInt($(elem).find('.cnt').html());
				  if(isNaN(cnt))
				  {
					 cnt=0;  
				  }
				  $(elem).find('.cnt').html(cnt+1);
			  }
			  else
			  {
				  alert(ret.info);
			  }
			  
	 }});
}