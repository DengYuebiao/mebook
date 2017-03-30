<?php
namespace Home\Controller;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
use \Home\Extend\AdminMenu\AdminMenu;
class UserController extends UserBaseController {
    public function index(){
		$_curlocal=AdminMenu::getOne("51");
		$mod_user = D("User");
		$mod_role = D('Role');
		if(isset($this->_user_info['role_code'])){
			$code = $mod_role->field("authority")->where("role_code='".$this->_user_info['role_code']."'")->find();
			if(strpos($code['authority'],'31')===false){
				$this->error(L("no_authority"),U("Index/index"));
				return false;
			}
		}
		if(!isset($_POST['user_pwd'])){
			$where=array();
			$field = isset($_POST['search_field']) ? trim($_POST['search_field']) : "";
			$val = isset($_POST['search_val']) ? trim($_POST['search_val']) : "";
			$del_id = isset($_POST['del_id']) ? trim($_POST['del_id']) : "";
			$edit_id = isset($_POST['edit_id']) ? trim($_POST['edit_id']) : "";
			//删除
			if(!empty($del_id)){
				$is_success = $mod_user->delete($del_id);
				if($is_success === false){
					$this->ajaxReturn(0,"",0);
				}else{
					$this->ajaxReturn(1,"",1);
				}
			}
			//编辑
			if($edit_id){
				$user_info = $mod_user->find($edit_id);
				if($user_info){
					$this->ajaxReturn($user_info,"",1);
				}else{
					$this->ajaxReturn("","获取数据出错",0);
				}
			}

			if($val){
				if($field=="is_useful"){
					$where[$field] = $val=="有效"?1:0;
				}elseif($field=="last_login"){
					$time = strtotime($val);
					$where[$field] = empty($time) ? $val:$time;
				}else{
					$where[$field]=$val;
				}
			}

		}else{
			$user_id = isset($_POST['different']) ? trim($_POST['different']) :"";
			$real_name = isset($_POST['real_name']) ? trim($_POST['real_name']) : "";
			$user_name = isset($_POST['user_name']) ? trim($_POST['user_name']) : "";
			$user_pwd = isset($_POST['user_pwd']) ? md5(trim($_POST['user_pwd'])) : "";
			$user_phone = isset($_POST['user_phone']) ? trim($_POST['user_phone']) : "";
			$user_group = isset($_POST['group']) ? trim($_POST['group']) : "";
			$is_useful = isset($_POST['is_useful']) ? trim($_POST['is_useful'])-10 : "";

			/*if (!$user_name) {
				$this->error(l('must_user_name'));
				return NULL;
			}
			if (!$real_name) {
				$this->error(l('must_real_name'));
				return NULL;
			}
			if (!$user_pwd) {
				$this->error(l('must_user_pwd'));
				return NULL;
			}*/
			$data = array(
				 'real_name'=>"$real_name",
				 'user_name'=>"$user_name",
				 'user_pwd'=>"$user_pwd",
				 'user_phone'=>"$user_phone",
				 'role_code'=>"$user_group",
				 'is_useful'=>"$is_useful",
			);
			if($user_id){
                //编辑
				$is_success = $mod_user->where("user_id=$user_id")->save($data);
				if(!$is_success){
					$this->error(l("edit_error"));
				}
			}else{
				//增加
				$is_success = $mod_user->add($data);
				if(!$is_success){
					$this->error(l("add_error"));
				}
			}

		}
        //分页
		$data_cnt=$mod_user->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,10);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$user_info=$mod_user->where($where)->limit($limit_beg.','.$page_obj->listRows)->select();
		if($user_info){
			$this->assign("user_info",$user_info);
		}
		$group_list=$mod_role->select();
		$this->assign("group_list",$group_list);
		$this->assign("_curlocal",$_curlocal);
		$this->display();

    }
	function role(){
		$mod_role = D('Role');
		$where=array();
		$del_code = isset($_POST['del_code']) ? $_POST['del_code'] :"";
		$edit_code = isset($_POST['edit_code']) ? $_POST['edit_code'] :"";

		//权限检测
		if(isset($this->_user_info['role_code'])){
			$code = $mod_role->field("authority")->where("role_code='".$this->_user_info['role_code']."'")->find();
            if(strpos($code['authority'],'32')===false){
				$this->error(L("no_authority"),U("Index/index"));
				return false;
			}
		}

		if(IS_POST){
			$where=array();
			$field = isset($_POST['search_field']) ? trim($_POST['search_field']) : "";
			$val = isset($_POST['search_val']) ? trim($_POST['search_val']) : "";
			if(!empty($val)){
					$where[$field]=$val;
			}

			//删除
			if(!empty($del_code)){
				if($del_code=="admin"){
					 $this->ajaxReturn("",l("ban_admin"),0);
				}
				$is_success = $mod_role->where("role_code='$del_code'")->delete();
				if($is_success === false){
					$this->ajaxReturn(0,"",0);
				}else{
					$this->ajaxReturn(1,"",1);
				}
			}

			//编辑
			if(!empty($edit_code)){
				$user_info = $mod_role->where("role_code='$edit_code'")->find();
				if($user_info){
					$this->ajaxReturn($user_info,"",1);
				}else{
					$this->ajaxReturn("","",0);
				}
			}

			if(isset($_POST['authority'])){
				$role_code = isset($_POST['role_code']) ? trim($_POST['role_code']) :"";
				$role_name = isset($_POST['role_name']) ? trim($_POST['role_name']) :"";
				$authority = isset($_POST['authority']) ? trim($_POST['authority']) :"";
				$data = array(
					 "role_name"=>$role_name,
					 "authority"=>$authority,
				);

                $bool = $mod_role->where("role_code='$role_code'")->find();
                if(!$bool){
					$data['role_code'] = $role_code;
					$is_success = $mod_role->add($data);
				}else{
					$is_success = $mod_role->where("role_code='$role_code'")->save($data);
				}

				if(!$is_success){
					$this->error(l('op_error'));
				}
			}
			if(isset($_POST['getList'])){
				$pid = isset($_POST['getList'])?trim($_POST['getList']):1;
				$list_info=\Home\Extend\AdminMenu\AdminMenu::getChild($pid);
				foreach($list_info as $val){
					 $list[]=$val['name'];
				}
				$this->ajaxReturn($list,'',1);
			}



            if(isset($_POST['getMenu'])){
				//获取权限菜单
				$menu_pos=\Home\Extend\AdminMenu\AdminMenu::getData();
				$this->_menu=\Home\Extend\AdminMenu\AdminMenu::getTop();
				$menu = array();
				for($i=1;$i<=9;$i++){
					for($j=1;$j<=count($this->_menu);$j++){
						$menu[$i][] = isset($menu_pos[$j.$i])? $menu_pos[$j.$i]:"";
					}
				}
				//echo"<pre>";var_dump($menu);die();
				$this->ajaxReturn($menu,'',1);
			}
		}


		$data_cnt=$mod_role->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,10);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		$limit_beg = $page_obj->firstRow < $data_cnt ? $page_obj->firstRow : 0;
		$role_info = $mod_role->where($where)->limit($limit_beg.','.$page_obj->listRows)->select();
		$_curlocal=AdminMenu::getOne("52");
		$this->assign('_curlocal',$_curlocal);
		$this->assign('role_info',$role_info);
        $this->display();
	}

}