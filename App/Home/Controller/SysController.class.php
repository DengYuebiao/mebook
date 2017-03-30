<?php

namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class SysController extends SysBaseController
{
	public function index(){

		if(!IS_POST)
		{
			$_curlocal=AdminMenu::getOne("53");
			$this->assign("_curlocal",$_curlocal);
			$this->display();
		}
		else
		{

		}
	}


	public function tsg_address(){
		$mod_site = D("tsg_site");
		$where=array();
		if(IS_POST){
			$field = isset($_POST['search_field']) ? trim($_POST['search_field']) : "";
			$val = isset($_POST['search_val']) ? trim($_POST['search_val']) : "";
			$del_code = isset($_POST['del_code']) ? trim($_POST['del_code']) : "";
			$edit_code = isset($_POST['edit_code']) ? trim($_POST['edit_code']) : "";
            if(!empty($val)){
				$field=="tsg_site_code"? $val = strtoupper($val):1;
				$where[$field] = $val;
			}

			//删除
			if(!empty($del_code)){
				$is_success = $mod_site->where("tsg_site_code='$del_code'")->delete();
				if($is_success === false){
					$this->ajaxReturn(0,"",0);
				}else{
					$this->ajaxReturn(1,"",1);
				}
			}

			//编辑
			if(!empty($edit_code)){
				$site_info = $mod_site->where("tsg_site_code='$edit_code'")->find();
				if($site_info){
					$this->ajaxReturn($site_info,"",1);
				}else{
					$this->ajaxReturn("","",0);
				}
			}

			if(isset($_POST['different'])){
				$site_code = isset($_POST['site_code']) ? trim($_POST['site_code']) :"";
				$site_name = isset($_POST['site_name']) ? trim($_POST['site_name']) :"";
				$remark = isset($_POST['remark']) ? trim($_POST['remark']) :"";
				$data = array(
					"site_name"=>$site_name,
					"site_remark"=>$remark,
				);


				if($_POST['different']!="edit"){
					$data['tsg_site_code'] = $site_code;
					$info = $mod_site->where("tsg_site_code='$site_code'")->find();
					if($info){
						$this->error(l("exit_site"));
					}else{
						$is_success = $mod_site->add($data);
					}
				}else{
					$is_success = $mod_site->where("tsg_site_code='$site_code'")->save($data);
				}

				if(!$is_success){
					$this->error(l('op_error'));
				}
			}
		}
		//分页
		$data_cnt=$mod_site->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,10);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		$limit_beg=$page_obj->firstRow < $data_cnt ? $page_obj->firstRow : 0;
		$site_info=$mod_site->where($where)->limit($limit_beg.','.$page_obj->listRows)->select();
		if($site_info){
			$this->assign("site_info",$site_info);
		}
		$_curlocal=AdminMenu::getOne("54");
		$this->assign("_curlocal",$_curlocal);
		$this->display();
	}

}