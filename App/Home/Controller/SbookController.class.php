<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class SbookController extends AdminBaseController {
    public function index(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
		
		$search_data=$table_obj->getDataByMap($_GET,array('s1'=>'v1','s2'=>'v2'),$zd_list);
		
		$where=array();
		if($search_data['title'])
		{
			$where['title']=array("like","{$search_data['title']}%");
		}
		if($search_data['author'])
		{
			$where['author']=array("like","{$search_data['author']}%");
		}
		if($search_data['class'])
		{
			$where['class']=array("like","{$search_data['class']}%");
		}
		
		
		$data_cnt=$mod_table->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->where($where)->limit($limit_beg.','.$page_obj->listRows)->order($mod_table->getPK().' desc')->select();
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("22");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
        $this->display();
    }
	
	public function add(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$valid_rule=$mod_table->getAddRule();
		
		if(!IS_POST)
		{
			$this->_common();
			$_curlocal=AdminMenu::getOne("22");
			$_curlocal[]=array("name"=>L("add_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("valid_rule",$valid_rule);
			$this->display("form");
		}
		else
		{
			$data=formatForm($_POST,array("string"=>"title,author,fileformat,publish,class"));
			unset($valid_rule['rules']['txturl']);
			unset($valid_rule['rules']['mp3url']);
			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}
			
			$mp3_info=$mod_table->uploadMp3($_FILES['mp3url']);

			if($mp3_info===false)
			{
				$this->error($mod_table->getError());
				return;
			}
			else
			{				
				$data['mp3url']=$mp3_info['path'];
			}
			

			$txt_info=$mod_table->uploadTxt($_FILES['txturl']);

			if($txt_info===false)
			{
				$this->error($mod_table->getError());
				return;
			}
			else
			{
				$data['txturl']=$txt_info['path'];
			}

			$data['joindate']=time();
			$data['is_upload']=1;
			$mod_table->disFields($data);	
			$is_success=$mod_table->add($data);
			if($is_success!==false)
			{
				$this->success(L('add_data_ok'),U('Sbook/index'));
				return;
			}
			else
			{
				$this->error(L('add_data_error'));
				return;
			}
			
		}
		
	}
	
	public function edit(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		
		$where=$table_obj->getPKWhere($_GET);	//获取主键条件数组
		$row_info=$mod_table->where($where)->find();
		if(!$row_info)
		{
			$this->error(L('server_not_row'));
			exit();
		}
		if(!IS_POST)
		{
			$this->_common();
			$_curlocal=AdminMenu::getOne("22");
			$_curlocal[]=array("name"=>L("edit_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("row_info",$row_info);
			$this->assign("valid_rule",$mod_table->getEditRule());
			$this->display("form");
		}
		else
		{
			$data=formatForm($_POST,array("string"=>"title,author,fileformat,publish,class"));
			unset($valid_rule['rules']['pdf_addr']);
			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}
			
			//如果重新上传PDF则删除原有文件
			if(!empty($_FILES['mp3url']['tmp_name']))
			{
				@unlink($row_info['mp3url']);
				$mp3_info=$mod_table->uploadMp3($_FILES['mp3url']);

				if($mp3_info===false)
				{
					$this->error($mod_table->getError());
					return;
				}
				else
				{				
					$data['mp3url']=$mp3_info['path'];
				}
			
			}
			
			if(!empty($_FILES['txturl']['tmp_name']))
			{
				@unlink($row_info['txturl']);
				
				$txt_info=$mod_table->uploadTxt($_FILES['txturl']);
	
				if($txt_info===false)
				{
					$this->error($mod_table->getError());
					return;
				}
				else
				{
					$data['txturl']=$txt_info['path'];
				}
			}
			
			$mod_table->disFields($data);	
			$where=$table_obj->getPKWhere($_GET);	//获取主键条件数组
			$is_success=$mod_table->where($where)->save($data);
			if($is_success!==false)
			{
				$this->success(L('edit_data_ok'),U('Sbook/index'));
				return;
			}
			else
			{
				$this->error(L('edit_data_error'));
				return;
			}
		}
	}
	
	public function drop(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		
		$where=$table_obj->getPKWhere($_GET);	//获取主键条件数组
		$row_info=$mod_table->where($where)->find();
		if(!$row_info)
		{
			$this->error(L('server_not_row'));
			exit();
		}
		
		$book_base_path=getOption("sys_book_path");
		if($row_info['is_upload']==0)
		{
			$mp3_path=$book_base_path.DIRECTORY_SEPARATOR.$row_info['mp3url'];
			$txt_path=$book_base_path.DIRECTORY_SEPARATOR.$row_info['txturl'];
		}
		else
		{
			$mp3_path=$row_info['mp3url'];
			$txt_path=$row_info['txturl'];
		}
		@unlink($mp3_path);
		@unlink($txt_path);
		$is_success=$mod_table->where($where)->delete();
		
		if($is_success!==false)
		{
			$this->success(L('drop_data_ok'),U('Sbook/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
	}
	
	public function getTableCnf(){
		$mod_sbook=D("Sbook");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_sbook,
			"isShowBtn"=>true,
			"btnWidth"=>"9%",
			'zdShow'=>array('title.string.30%','author.string.20%','class.string.10%','joindate.date.10%'),
		);
	}
	
	public function getzds(){
		return array(
			"title"=>L('title'),
			"class"=>L('class'),
			"author"=>L('author')
		);
	}
	
	function _common()
	{
		$class_list=getData("admin.sbook_clc");
		ksort($class_list);
		$this->assign('class_list',$class_list);
	}
	
	function clc()
	{
		if(!IS_POST)
		{
			$mod_sbook=D("Sbook");
			$data_list=$mod_sbook->field("class")->group("class")->select();
			$not_clc_list=array();
			foreach($data_list as $item)
			{
				$not_clc_list[]=$item['class'];
			}
			
			$_curlocal=AdminMenu::getOne("25");
			$this->assign("_curlocal",$_curlocal);
			$class_list=getData("admin.sbook_clc");
			ksort($class_list);
			$this->assign('not_clc_list',json_encode($not_clc_list));
			$this->assign('class_list',json_encode($class_list));
			$this->display();
		}
		else
		{
			$clc_list=array();
			foreach($_POST['clc'] as $item)
			{
				$clc=strip_tags(stripslashes($item));
				if($clc)
				{
					$clc_list[]=$clc;
				}
			}
			setData("admin.sbook_clc",$clc_list,true);
			
			$this->success(L('edit_data_ok'),U('Sbook/clc'));
			return;
		}
	}
}