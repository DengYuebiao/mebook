<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class BooklistController extends AdminBaseController {
    public function index(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
		
		$search_data=$table_obj->getDataByMap($_GET,array('s1'=>'v1','s2'=>'v2'),$zd_list);
		
		$where=array();
		if($search_data['list_name'])
		{
			$where['list_name']=array("like","{$search_data['list_name']}%");
		}
		
		$data_cnt=$mod_table->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,2000);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->where($where)->limit($limit_beg.','.$page_obj->listRows)->order($mod_table->getPK().' desc')->select();
	
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("13");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
		$this->assign("curr_sys",$mod_table->getSys());
        $this->display();
    }
	
	public function add(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$valid_rule=$mod_table->getAddRule();
		
		if(!IS_POST)
		{
			$nav_type=isset($_GET['nav_type'])?intval($_GET['nav_type']):0;
			$this->assign("row_info",array('nav_type'=>$nav_type));
			$this->_common();
			$_curlocal=AdminMenu::getOne("13");
			$_curlocal[]=array("name"=>L("add_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("valid_rule",$valid_rule);
			$this->display("form");
		}
		else
		{
			$data=formatForm($_POST,array("string"=>"list_name"));
			
			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}
			
			$data['add_time']=time();
			$mod_table->disFields($data);	
			$is_success=$mod_table->add($data);
			if($is_success!==false)
			{
				$this->success(L('add_data_ok'),U('Booklist/index'));
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
			$_curlocal=AdminMenu::getOne("13");
			$_curlocal[]=array("name"=>L("edit_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("row_info",$row_info);
			$this->assign("valid_rule",$mod_table->getEditRule());
			$this->display("form");
		}
		else
		{
			
			$data=formatForm($_POST,array("string"=>"list_name"));
			
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
				$this->success(L('edit_data_ok'),U('Booklist/index'));
				return;
			}
			else
			{
				$this->error(L('edit_data_error'));
				return;
			}
		}
	}
	
	public function setbid(){
		$booklist_id=isset($_GET['booklist_id'])?intval($_GET['booklist_id']):0;
		if(!$booklist_id)
		{
			$this->error(L('booklist_id_empty'));
			return;
		}
		setOption('sys_booklist_id',$booklist_id);
		if($is_success!==false)
		{
			$this->success(L('edit_data_ok'),U('Booklist/index'));
			return;
		}
		else
		{
			$this->error(L('edit_data_error'));
			return;
		}
	}
	
	public function drop(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		
		$where=$table_obj->getPKWhere($_GET);	//获取主键条件数组
		$booklist_id=current($where);
		//判断是否被导航或者首页使用
		$mod_nav=D("Nav");
		if($mod_nav->isUse($booklist_id))
		{
			$this->error(L('nav_use_booklist'));
			exit();
		}
		$index_booklist=getOptions("sys_index_left1,sys_index_left2,sys_index_left3,sys_index_left4");
		if(in_array($booklist_id,$index_booklist))
		{
			$this->error(L('index_use_booklist'));
			exit();
		}

		$row_info=$mod_table->where($where)->find();
		if(!$row_info)
		{
			$this->error(L('server_not_row'));
			exit();
		}
		
		$is_success=$mod_table->where($where)->delete();
		
		if($is_success!==false)
		{
			$this->success(L('drop_data_ok'),U('Booklist/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
		
	}
	
	public function getTableCnf(){
		$mod_booklist=D("Booklist");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_booklist,
			"isShowBtn"=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('list_name.string.20%','add_time.date.10%'),
			'btnCallBack'=>function($btn_list,$row_data){
				$btn_list[]=array("name"=>L('set_sys_book_list'),"func"=>"setSysBooklist({$row_data['booklist_id']},this);","url"=>U('Booklist/setbid/booklist_id/'.$row_data['booklist_id']));
				$btn_list[]=array("name"=>L('goto_ext'),"func"=>"goExt({$row_data['booklist_id']},this);","url"=>U('BooklistExt/index/booklist_id/'.$row_data['booklist_id']));
				return $btn_list;
			}
		);
	}
	
	function _common()
	{
		
	}
	
	public function getzds(){
		return array(
			"list_name"=>L('list_name')
			
		);
	}
	
	
}