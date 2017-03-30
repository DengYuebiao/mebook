<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class AdminController extends AdminBaseController {
    public function index(){
        $this->display();
    }
	

	public function info(){
		$row_info=getOptions("sys_reg_name,sys_is_open,sys_is_verify,sys_is_reg,sys_ip_type,sys_ip_fail_type,sys_index_left1,sys_index_left2,sys_index_left3,sys_site_logo,sys_filter_word,sys_book_path,cas_open,cas_ver,cas_host,cas_port,cas_uri");
		
		if(!IS_POST)
		{
			/*$auth_obj=new \Home\Extend\Tcpdf\fpdf();
			$class_obj=new \ReflectionClass($auth_obj);
			$file_md5=md5_file($class_obj->getFileName());
	
			$info=$auth_obj->getSource();
			if(!$info)
			{
				header('Content-Type:text/html;charset=utf-8');
				echo($auth_obj->getError());
				exit();
			}
			
			$ver=substr($info["ver"],0,1);
			$is_full=in_array($ver,array(1,2))?true:false;
			$this->assign("is_full",$is_full);*/
			
			$mod_booklist=D("Booklist");
			$booklist_map=$mod_booklist->getMap();
			$this->assign("booklist_map",$booklist_map);
			$this->assign("ip_type_list",array(1=>L('ip_type1'),2=>L('ip_type2')));
			$this->assign("ip_fail_type_list",array(1=>L('ip_fail_type1'),2=>L('ip_fail_type2')));
			$_curlocal=AdminMenu::getOne("11");
			$this->assign("_curlocal",$_curlocal);
			$row_info['sys_site_logo']=$row_info['sys_site_logo']?$row_info['sys_site_logo']:'Public/image/logo.png';
			$this->assign("row_info",$row_info);
			$mod_book=D("Book");
			$mod_book_ft=D("Book_ft");
			$book_num=$mod_book->count("0");
			$book_fs_num=$mod_book_ft->count("0");
			$this->assign("book_num",$book_num);
			$this->assign("book_fs_num",$book_fs_num);
			$this->display();
		}
		else
		{
			$data=formatForm($_POST,array("string"=>"sys_reg_name,sys_filter_word,sys_book_path,cas_open,cas_ver,cas_host,cas_port,cas_uri",'int'=>'sys_is_open,sys_is_verify,sys_is_reg,sys_ip_type,sys_ip_fail_type,sys_index_left1,sys_index_left2,sys_index_left3'));
			$old_logo=getOption("sys_site_logo");
			$mod_option=D("Option");
			if(!empty($_FILES['sys_site_logo']['tmp_name']))
			{
				if($old_logo && file_exists($old_logo))
				{
					@unlink($old_logo);
				}
				$logo_info=$mod_option->uploadLogo($_FILES['sys_site_logo']);
	
				if($logo_info===false)
				{
					$this->error($mod_option->getError());
					return;
				}
				else
				{
					$is_success=setOption("sys_site_logo",$logo_info['path']);
					if($is_success===false)
					{
						$this->error(L('edit_data_error'));
						return;
					}
				}
			}

			foreach($data as $key=>$item)
			{
				$is_success=setOption($key,$item);
				if($is_success===false)
				{
					$this->error(L('edit_data_error'));
					return;
				}
			}

			$this->success(L('edit_data_ok'),U('Admin/info'));
			return;
			
		}
    }
	
/*	public function drop_logo(){
		$sys_site_logo=getOption("sys_site_logo");
		if($sys_site_logo && file_exists($sys_site_logo))
		{
			@unlink($sys_site_logo);
		}
		setOption("sys_site_logo","");
		$this->success(L('drop_logo_ok'),U('Admin/info'));
	}
	*/
}