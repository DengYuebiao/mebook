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

