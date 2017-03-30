<?php
namespace Home\Controller;

class SysBaseController extends BaseController {
   	
	protected function _initialize()
	{
		parent::_initialize();
		//¼ì²éµÇÂ½ĞÅÏ¢
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