<?php
require('CAS.php');

class PhpCasTool{
	private static $instance=NULL;
	public $cnf=NULL;
	public $error="";
	
	private function __construct()
	{
		$this->init();
	}
	
	static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance=new self();
		}
		return self::$instance;
	}
	
	//加载cas配置
	function getCnf()
	{
		if(!$this->cnf)
		{
			$cnf=getOptions("cas_open,cas_ver,cas_host,cas_port,cas_uri");
			$this->cnf=$cnf;
		}
		
		return $this->cnf;
	}
	
	function getError()
	{
		return $this->error;
	}
	
	//判断cas单点登录是否在系统中开启
	function isOpen()
	{
		$cnf=$this->getCnf();			
		return $cnf['cas_open']==1;
	}
	
	private function init()
	{
		if(!$this->isOpen())
		{
			return false;
		}

		if(APP_DEBUG)
		{
			phpCAS::setDebug();
		}
		
		$cnf=$this->getCnf();
		if(!isset($cnf['cas_open']))
		{
			return false;
		}

		//根据cas参数初始化
		phpCAS::client($cnf['cas_ver'], $cnf['cas_host'], intval($cnf['cas_port']), $cnf['cas_uri'], false);
	}
	
	/*
	调用单点登录
	@return false|array
	*/
	function login()
	{
		phpCAS::setNoCasServerValidation();
		
		phpCAS::forceAuthentication();		
		
		$user_id=phpCAS::getUser(); 
		if($user_id)
		{
			$mod_user=D("User");	
			$mod_user_map=D("User_map");
			
			$user_id=strip_tags(stripslashes($user_id));
			$user_map_info=$mod_user_map->where("map_code='{$user_id}'")->find();
			if(!empty($user_map_info))
			{
				$user_id=$user_map_info['user_name'];
			}
			
			$user_info=$mod_user->where("user_name='{$user_id}'")->find();					
			if(empty($user_info))
			{
				$this->error="未找到该用户信息!";		
				return false;
			}
			
			if($user_info['is_verify']!=1)
			{
				$this->error="用户未通过审核";				
				return false;
			}
			$user_info['is_admin']=$user_info['is_admin']==1;
			return $user_info;	
		}
		else
		{
			$this->error="获取登录服务端用户ID失败";
			return false;
		}
	}

	
	/*
	调用注销
	@return bool
	*/
	function logout()
	{
		phpCAS::logout();
	}
}