<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class NavController extends AdminBaseController {
    public function index(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
		
		$search_data=$table_obj->getDataByMap($_GET,array('s1'=>'v1','s2'=>'v2'),$zd_list);
		
		$where=array();
		if($search_data['nav_name'])
		{
			$where['nav_name']=array("like","{$search_data['nav_name']}%");
		}
		
		$data_cnt=$mod_table->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->where($where)->limit($limit_beg.','.$page_obj->listRows)->order("order_num,".$mod_table->getPK().' asc')->select();
		$nav_type_list=$mod_table->getNavTypeList();
		foreach($data as $key=>$item)
		{
			$data[$key]['is_close']=$item['is_close']==0?L("on"):L("close");
			$data[$key]['is_new']=$item['is_new']==0?L("no"):L("yes");
			$data[$key]['nav_type']=$nav_type_list[$item['nav_type']];
		}
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("12");
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
			$nav_type=isset($_GET['nav_type'])?intval($_GET['nav_type']):0;
			$this->assign("row_info",array('nav_type'=>$nav_type));
			$this->_common();
			$_curlocal=AdminMenu::getOne("12");
			$_curlocal[]=array("name"=>L("add_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("valid_rule",$valid_rule);
			$this->display("form");
		}
		else
		{
			$nav_type=isset($_GET['nav_type'])?intval($_GET['nav_type']):0;
			$data=formatForm($_POST,array("string"=>"nav_val,nav_name",'int'=>'order_num,is_close,is_new'));
			if($nav_type==4)
			{
				$data['nav_name']=L('nav_type4');
			}
			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}
			
			$data['nav_type']=$nav_type;
			$mod_table->disFields($data);	
			$is_success=$mod_table->add($data);
			if($is_success!==false)
			{
				$this->success(L('add_data_ok'),U('Nav/index'));
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
			$_curlocal=AdminMenu::getOne("12");
			$_curlocal[]=array("name"=>L("edit_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("row_info",$row_info);
			$this->assign("valid_rule",$mod_table->getEditRule());
			$this->display("form");
		}
		else
		{
			
			$data=formatForm($_POST,array("string"=>"nav_val,nav_name",'int'=>'order_num,is_close,is_new'));
			if($row_info['nav_type']==4)
			{
				$data['nav_name']=L('nav_type4');
			}
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
				$this->success(L('edit_data_ok'),U('Nav/index'));
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
			$this->success(L('drop_data_ok'),U('Nav/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
		
	}
	
	public function getTableCnf(){
		$mod_nav=D("Nav");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_nav,
			"isShowBtn"=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('nav_name.string.20%','nav_type.string.10%','is_new.string.10%','is_close.string.10%','order_num.string.10%'),
		);
	}
	
	function _common()
	{
		$mod_nav=D("Nav");
		$this->assign('nav_type_list',$mod_nav->getNavTypeList());
		$this->assign('nav_type1_list',$mod_nav->getNavType1List());
		$this->assign('nav_type2_list',$mod_nav->getNavType2List());
	}
	
	public function getzds(){
		return array(
			"nav_name"=>L('nav_name')
			
		);
	}
	
	
}