<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class LinkController extends AdminBaseController {
    public function index(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
		
		$search_data=$table_obj->getDataByMap($_GET,array('s1'=>'v1','s2'=>'v2'),$zd_list);
		
		$where=array();
		if($search_data['link_name'])
		{
			$where['link_name']=array("like","{$search_data['link_name']}%");
		}
		
		$data_cnt=$mod_table->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->where($where)->limit($limit_beg.','.$page_obj->listRows)->order("order_num,".$mod_table->getPK().' asc')->select();
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("18");
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
			$order_num=$mod_table->max("order_num")+1;
			$this->assign("row_info",array('order_num'=>$order_num));
			$this->_common();
			$_curlocal=AdminMenu::getOne("18");
			$_curlocal[]=array("name"=>L("add_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("valid_rule",$valid_rule);
			$this->display("form");
		}
		else
		{
			$data=formatForm($_POST,array("string"=>"link_name,link_val",'int'=>'order_num'));

			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}
			
			$mod_table->disFields($data);	
			$is_success=$mod_table->add($data);
			if($is_success!==false)
			{
				$this->success(L('add_data_ok'),U('Link/index'));
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
			$_curlocal=AdminMenu::getOne("18");
			$_curlocal[]=array("name"=>L("edit_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("row_info",$row_info);
			$this->assign("valid_rule",$mod_table->getEditRule());
			$this->display("form");
		}
		else
		{
			
			$data=formatForm($_POST,array("string"=>"link_name,link_val",'int'=>'order_num'));

			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}

			$mod_table->disFields($data);	
			$where=$table_obj->getPKWhere($_GET);	//获取主键条件数组
			$is_success=$mod_table->where($where)->save($data);
			if($is_success!==false)
			{
				$this->success(L('edit_data_ok'),U('Link/index'));
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
		
		$is_success=$mod_table->where($where)->delete();
		
		if($is_success!==false)
		{
			$this->success(L('drop_data_ok'),U('Link/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
		
	}
	
	public function getTableCnf(){
		$mod_link=D("Link");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_link,
			"isShowBtn"=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('link_name.string.20%','link_val.string.50%','order_num.string.10%'),
			'zdCallBack'=>array('link_name'=>function($cell_val,$row){
				return '<a class="link" href="'.$row['link_val'].'" target="_blank">'.$cell_val.'</a>';	
			})
		);
	}
	
	function _common()
	{
		
	}
	
	public function getzds(){
		return array(
			"link_name"=>L('link_name')
			
		);
	}
	
	
}