function install_init()
{

	
	
}
function back_step()
{
	if(curr_step<=1)
	{
		return;
	}
	curr_step--;
	show_step(curr_step);
}

function next_step()
{
	if(curr_step>=4)
	{
		return;
	}
	
	
	var check_status=step_check(curr_step);
	if(!check_status){return;}
	
	curr_step++;
	show_step(curr_step);
}

function show_step(step_pos)
{
	$("#tab .tab_item:visible").hide();
	$(".m-step_nav .step_item:visible").hide();
	
	$("#tab .tab_item#tab-"+step_pos).show("fast");
	$(".m-step_nav .step_item.step"+step_pos).show();
	
}

function step_check(step_pos)
{
	switch(step_pos)
	{
		case 1:
		return true;
		break;
		case 2:
			if($("#tab-2 .check_fail").size())
			{
				alert($("#tab-2 .check_fail").val()+"未通过检查");
				return false;
			}
		break;
		case 3:
			var is_ok=check_step3();
			if(is_ok)	//如果检测通过则开始安装
			{				
				return install_beg();  
			}
			else
			{
				return false;
			}
		break;
		case 4:
		break;	
		
	}
	return true;
}

function check_step3()
{
	var db_host=$("#db_host").val();
	if(!db_host)
	{
		alert("数据库地址不能为空");
		return false;
	}
	var db_port=$("#db_port").val();
	if(!db_port)
	{
		alert("数据库端口不能为空");
		return false;
	}
	
	var db_name=$("#db_name").val();
	if(!db_name)
	{
		alert("数据库名称不能为空");
		return false;
	}
	
	var db_user=$("#db_user").val();
	if(!db_user)
	{
		alert("数据库用户名不能为空");
		return false;
	}
	
	var init_data=$("#init_data").val();
	if(!init_data)
	{
		alert("请选择初始化数据文件");
		return false;
	}
	
	var sys_reg_name=$("#sys_reg_name").val();
	if(!sys_reg_name)
	{
		alert("单位名称不能为空");
		return false;
	}
	
	var admin_pwd1=$("#admin_pwd1").val();
	if(!admin_pwd1)
	{
		alert("超级管理员密码不能为空");
		return false;
	}
	
	var admin_pwd2=$("#admin_pwd2").val();
	if(!admin_pwd2)
	{
		alert("超级管理员密码确认不能为空");
		return false;
	}
	
	if(admin_pwd1!=admin_pwd2)
	{
		alert("两次输入的超级管理员密码不一致");
		$("#admin_pwd1").focus();
		return false;
	}
	var db_pass=$("#db_pass").val();
	
	
	 var url=getSite()+"/Install/check_db_conn";	
	 var post_data={"db_host":db_host,"db_port":db_port,"db_user":db_user,"db_pass":db_pass,"db_name":db_name};
	 
	 var is_ok=false;
	 $.ajax({
		 url:url,
		 dataType:"json",
		 data:post_data,
		 async:false,
		 type:"POST",
		 success: function(ret){
			  if(ret.status)
			  {
				  is_ok=true;
			  }
			  else
			  {
				  alert(ret.info);
				  is_ok=false;
			  }
			 
		  }});
		  
	 return is_ok;
}

//开始安装的一些操作
function install_beg()
{
	$.LoadBox();

	$("#db_info_form").submit();	 
	 
	return false;
}