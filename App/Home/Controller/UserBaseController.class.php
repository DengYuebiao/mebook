<?php
namespace Home\Controller;

class UserBaseController extends BaseController {
   	
	protected function _initialize()
	{
		parent::_initialize();
		
		//处理单点登录
		import("@.Extend.PhpCas.PhpCasTool");
		$cas_obj=\PhpCasTool::getInstance();
		if($cas_obj->isOpen())
		{
			$user_info=$cas_obj->login();
			if($user_info===false)
			{
				$this->error($cas_obj->getError(),U("Index/index"));
			}
			else
			{
				
				session("user_info",$user_info);	
				$this->_user_info=$user_info;				
			}
		}
		
		//检查登陆信息
		if(empty($this->_user_info) || empty($this->_user_info['user_id']))
		{
			$this->error(L("must_login"),U("Index/login"));
			return false;
		}
		
		$menu_pos=\Home\Extend\AdminMenu\AdminMenu::checkMenu();
		$this->assign("_menu_pos",$menu_pos);
		$this->_menu=\Home\Extend\AdminMenu\AdminMenu::getTop();
		$this->_menu_child=\Home\Extend\AdminMenu\AdminMenu::getChild(substr($menu_pos,0,1));
	}
	
}