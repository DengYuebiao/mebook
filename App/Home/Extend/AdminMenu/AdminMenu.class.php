<?php
namespace Home\Extend\AdminMenu;

class AdminMenu{
	/*
	获取admin菜单的原始数据
	*/
	static function getData()
	{
		$data=getData("admin.menu");
		return $data;
	}
	
	/*
	获取admin顶层菜单
	@return array 菜单
	*/
	static function getTop()
	{
		$menu_data=self::getData();
		$menu_list=array();
		foreach($menu_data as $key=>$item)
		{
			if(strlen($key)==1)
			{
				$menu_list[$key]=$item;
			}
		}
		
		//处理顶层菜单的默认链接,当url为空时有效
		foreach($menu_list as $key=>$item)
		{
			if(empty($item['url']) && isset($item['default']) && isset($menu_data[$item['default']]))
			{
				$menu_list[$key]['url']=$menu_data[$item['default']]['url'];
			}
		}

		return $menu_list;
	}

	
	/*
	获取admin顶层子菜单
	@param string 顶层菜单ID
	@return array 菜单
	*/
	static function getChild($pid)
	{//echo "<pre>";var_dump($pid);
		//$pid=5;
		$menu_data=self::getData();
		$menu_list=array();
		foreach($menu_data as $key=>$item)
		{
			if(strlen($key)==2)
			{
				if(substr($key,0,1)==$pid)
				{
				  $menu_list[$key]=$item;
				}
			}
		}

		return $menu_list;
	}
	
	/*
	获取单个菜单的数据，子菜单将在第一个数组处放置父菜单数据
	@param string 菜单ID
	@return array 菜单
	*/
	static function getOne($menu_id)
	{
		$menu_data=self::getData();
		$menu_list=array();
		if(isset($menu_data[$menu_id]))
		{
			$pid=substr($menu_id,0,1);
			if(isset($menu_data[$pid]))
			{
				$pmenu=$menu_data[$pid];
				if(empty($pmenu['url']) && isset($pmenu['default']) && isset($menu_data[$pmenu['default']]))
				{
					$pmenu['url']=$menu_data[$pmenu['default']]['url'];
				}
				$menu_list[$pid]=$pmenu;
			}			
			
			if(strlen($menu_id)==2)
			{
				$menu_list[$menu_id]=$menu_data[$menu_id];
			}

		}

		return $menu_list;
	}
	
	/*
	根据当前url检测在哪个菜单
	@param string 顶层菜单ID
	@return array 菜单
	*/
	static function checkMenu()
	{
		$url=!in_array(ACTION_NAME,array("add","edit"))?"/".CONTROLLER_NAME."/".ACTION_NAME:"/".CONTROLLER_NAME."/"."index" ;
		$url=strtolower($url);
		$curr_menu="11";
		$menu_data=self::getData();
		foreach($menu_data as $key=>$item)
		{
			$curr_url=strtolower($item['url']);
			if($item['url'] && strpos($url,$curr_url)!==false)
			{
				$curr_menu=$key;
				break;
			}
		}
		
		return $curr_menu;
	}
}