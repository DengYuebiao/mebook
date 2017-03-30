<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class SearchController extends AdminBaseController {
    public function index(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
		
		$search_data=$table_obj->getDataByMap($_GET,array('s1'=>'v1'),$zd_list);
		
		$where=array();

		if($search_data['search_val'])
		{
			$where['search_val']=array("like","{$search_data['search_val']}%");
		}
		if($search_data['ip_addr'])
		{
			$where['ip_addr']=array("like","{$search_data['ip_addr']}%");
		}
		if($search_data['user_name'])
		{
			$where['user_name']=array("like","{$search_data['user_name']}%");
		}

		$join_str="eb_user ON eb_search.user_id = eb_user.user_id";
		$data_cnt=$mod_table->join($join_str)->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->join($join_str)->where($where)->limit($limit_beg.','.$page_obj->listRows)->order($mod_table->getPK().' desc')->select();
		
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("32");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
		$this->assign("booklist_list",$booklist_list);
        $this->display();
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
			$this->success(L('drop_data_ok'),U('Search/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
		
	}
	
	public function getTableCnf(){
		$mod_search=D("Search");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_search,
			"isShowBtn"=>false,
			'isEditBtn'=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('search_val.string.20%','user_name.string.10%','ip_addr.string.10%','add_time.date1.10%'),
			'zdCallBack'=>array('search_val'=>function($cell_val,$row){
				return '<a class="link" href="'.U('Index/search/sv/'.$row['search_val']).'" target="_blank">'.$cell_val.'</a>';	
			})
		);
	}
	
	function _common()
	{
		
	}
	
	public function getzds(){
		return array(
			"search_val"=>L('search_val'),
			"ip_addr"=>L('ip_addr'),
			"user_name"=>L('user_name')
		);
	}
	
	
}