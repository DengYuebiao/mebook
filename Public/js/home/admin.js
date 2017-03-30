/*后台管理所有页面初始化函数*/
function admin_top_init()
{
	if(isIE6())
	{
		DD_belatedPNG.fix('.u-ico');
	}
	
	//页面底部版权信息
	$(window).scroll(function(){
		$("#copyright").removeClass("m-copyright-ab");
	});
	
	if($(".m-table").size()>0)
	{
		$(".m-table th,.m-table td").each(function(index, element) {
            if($(this).find("*").size()<=0)
			{
				$(this).attr("title",$(this).html());
			}
        });
	}
}


//生成全文索引
function genFullSearch()
{
	if(confirm(L("genfs_confirm"))==true)
	{
		var h=getSite()+"/Admin/genfs";
		location.href=h;
	}
}