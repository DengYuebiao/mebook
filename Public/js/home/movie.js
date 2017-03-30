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
	var h=getSite()+"/Index/mvshow/movie_file_id/"+pkey_id;
	myWindow=window.open(h,'');
	myWindow.moveTo(0,0);
    myWindow.resizeTo(screen.availWidth,screen.availHeight)
	location.href=h;
}

//初始化子表单
function subtableInit()
{
	var table_html=getTableHtml();
	$('#file_main').html(table_html);
	genMfileName();
}

//获取表格html
function getTableHtml()
{
	var thead_html='<thead><td>'+L('diversity')+'</td><td>'+L('path')+'</td><td>'+L('op')+'</td></thead>';
	var tbody_html='';
	if(file_list)
	{
		var trs=new Array();
		for(var i in file_list)
		{
			var row=file_list[i];
			trs.push(getRowHtml(row['file_type'],row));
		}
		tbody_html='<tbody>'+trs.join('')+'</tbody>';
	}
	else
	{
		tr_html=getRowHtml(1);
		tbody_html='<tbody>'+tr_html+'</tbody>';
	}
	var tfoot_html='<tfoot><td colspan="3" align="center"><input type="button" class="u-btn u-btn-c4" value="'+L('add_file')+'" onclick="addRow(1)" />&nbsp;&nbsp;<input type="button" class="u-btn u-btn-c4" value="'+L('add_url')+'" onclick="addRow(2)" /></td></tfoot>';
	var table_html='<table>'+thead_html+tbody_html+tfoot_html+'</table>';
	return table_html;
}

/*
获取表格html
@param file_type 1为文件  2为url
@param data 行数据
*/
function getRowHtml(file_type,row_data)
{
	var data=row_data?row_data:new Array();
	var diversity=data['diversity']?data['diversity']:'';
	var path=data['path']?data['path']:'';
	var movie_file_id=data['movie_file_id']?data['movie_file_id']:'';
	
	var tr_html='<td width="30%"><input type="hidden" name="mid[]" value="'+movie_file_id+'" /><input type="hidden" name="mfname[]" class="mfname" /><input type="text" class="t" name="diversity[]" value="'+diversity+'" /><input type="hidden" name="file_type[]" value="'+file_type+'" /><span class="field_notice u-must">*</span></td>';
	if(file_type==1)
	{
		var preview=path?'<a href="/'+path+'" target="_blank">'+L('preview')+'</a>&nbsp;':'';
		tr_html+='<td width="50%"><input type="hidden" name="path[]" />'+preview+'<input type="file" class="mfile" /></td>';
	}
	else
	{
		tr_html+='<td width="50%"><input type="text" class="t" name="path[]" value="'+path+'" /></td>';
	}
	tr_html+='<td width="20%"><input type="button" class="u-btn u-btn-c2" value="'+L('drop')+'" onclick="dropRow(this)" />&nbsp;</td>';
	tr_html='<tr>'+tr_html+'</tr>';
	return tr_html;
}

function genMfileName()
{
	var i=0;
	$('#file_main table tbody tr').each(function(index, element) {
        var mfile_name='mfile'+i;
		$(this).find(".mfile").attr("name",mfile_name);
		$(this).find(".mfname").val(mfile_name);
		i++;
    });
	
	
}

function addRow(file_type)
{
	var row_html=getRowHtml(file_type);
	$('#file_main table tbody').append(row_html);
	genMfileName();
}

function dropRow(elem)
{
	$(elem).parent().parent().remove();
}

