<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
   	
	protected function _initialize()
	{
				
		$this->_sys=array(
			"cp_name"=>"图畅数字图书馆",
			"gs_name"=>"广州图畅计算机科技有限公司",
			"gs_site"=>"http://www.tcmarc.net",
			"tel"=>"4006-591-191"
		);
		
		if(!file_exists(APP_PATH."Runtime/Install/install.lock"))  //如果不存在安装锁定文件 跳转到安装模块
		{
			 if(CONTROLLER_NAME!="Install")
			 {
				 header("Location: http://{$_SERVER['HTTP_HOST']}/Install/index");
				 exit();
			 }
			 else
			 {
				 return false;
			 }
			 
		}
		
		$this->autologin();
		  
		$act_name=CONTROLLER_NAME.'_'.ACTION_NAME ;
		
		$reg_arr=getData("reg");
		if($act_name!="Index_sysreg")
		{
			if(!$reg_arr || !$reg_arr['name'] || !$reg_arr['code'])
			{
				$this->error(L("sys_not_reg"),U("Index/sysreg"));
				exit();
			}
		}
		
		$lang_list=L();
		$this->assign('lang_list',json_encode($lang_list));
		$sys_reg_name=getOption("sys_reg_name");
		$this->_page_title=$sys_reg_name?$sys_reg_name:$this->_sys['cp_name'];
		$this->_user_info=session("user_info");
		$mod_iplimit=D("Iplimit");
		$sys_limit_info=getOptions("sys_ip_fail_type,sys_ip_type");
		$sys_limit_info['is_limit']=$mod_iplimit->isLimit();
		$this->_sys_limit_info=$sys_limit_info;
		
		
		if(!in_array($act_name,array("Admin_info","Index_login")) && $sys_limit_info['sys_ip_fail_type']==2) //当限制是禁止访问网站且不是admin控制器时检测
		{
			header('Content-Type:text/html;charset=utf-8');
			if($sys_limit_info['sys_ip_type']==1 && $sys_limit_info['is_limit'])
			{
				echo sprintf(L('system_limit_ip'),$_SERVER['REMOTE_ADDR']);
				exit();
			}
			elseif($sys_limit_info['sys_ip_type']==2 && !$sys_limit_info['is_limit'])
			{
				echo sprintf(L('system_limit_ip'),$_SERVER['REMOTE_ADDR']);
				exit();
			}
			
		}

	}
	
	//检测自动登陆
	private function autologin()
	{
		$user_info=session("user_info");	
		if(empty($user_info) || empty($user_info['user_id']))
		{
			$save_user=cookie("save_user");
			if($save_user)
			{
				$save_user=pack("H*",$save_user);
				$param=explode(":",$save_user);
				$user_name=$param[0];
				$user_name=strip_tags(stripslashes($user_name));
				$save_time=$param[1];
				$token=$param[2];
				$time_limit=time()+864000;
				if($user_name && $save_time<$time_limit && $token)
				{
					$mod_user=D("User");
					$user_info=$mod_user->where("user_name='{$user_name}'")->find();
					if($user_info)
					{
						$token_check=md5($user_name.$save_time.$user_info['user_pwd']);
						if($token==$token_check)
						{
							$user_info['is_admin']=$user_info['is_admin']==1;
							session("user_info",$user_info);
						}
					}
					else
					{
						cookie("save_user",NULL);
					}
				}
				else
				{
					cookie("save_user",NULL);
				}
				
			}
			
		}
	}
	
	
}