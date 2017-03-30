<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class BooklistExtController extends AdminBaseController {
    public function index(){
		$mod_booklist=D("Booklist");
		$booklist_list=$mod_booklist->getMap();
		
		$booklist_id=0;
		if(!isset($_GET['booklist_id']))
		{
			$booklist_id=key($booklist_list);
		}
		else
		{
			$booklist_id=intval($_GET['booklist_id']);
		}
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
		
		$search_data=$table_obj->getDataByMap($_GET,array('s1'=>'v1'),$zd_list);
		
		$where=array();
		$where['booklist_id']=$booklist_id;
		if($search_data['title'])
		{
			$where['title']=array("like","{$search_data['title']}%");
		}
		if($search_data['isbn'])
		{
			$where['isbn']=array("like","{$search_data['isbn']}%");
		}
		if($search_data['author'])
		{
			$where['author']=array("like","{$search_data['author']}%");
		}
		if($search_data['clc'])
		{
			$where['clc']=array("like","{$search_data['clc']}%");
		}
		$join_str="eb_book ON eb_booklist_ext.book_id = eb_book.book_id";
		$data_cnt=$mod_table->join($join_str)->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->join($join_str)->where($where)->limit($limit_beg.','.$page_obj->listRows)->order($mod_table->getPK().' desc')->select();
		
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("14");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
		$this->assign("booklist_list",$booklist_list);
        $this->display();
    }
	
	public function add(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		
		$book_id=isset($_GET['book_id'])?intval($_GET['book_id']):0;
		if(!$book_id)
		{
			$this->error(L('book_id_empty'));
			return;
		}
		
		$mod_book=D("Book");
		$book_info=$mod_book->field('book_id')->find();
		if(!$book_info)
		{
			$this->error(L('book_info_empty'));
			return;
		}
		

		
		$booklist_id=getOption("sys_booklist_id");
		if(!$booklist_id)
		{
			$this->error(L('sys_booklist_id_notset'));
			return;
		}
		
		$booklist_info=$mod_table->where("book_id={$book_id} AND booklist_id={$booklist_id}")->find();
		if($booklist_info)
		{
			$this->success(L('book_in_booklist'));
			return;
		}
		
		$data['booklist_id']=$booklist_id;
		$data['book_id']=$book_id;
		$mod_table->disFields($data);	
		$is_success=$mod_table->add($data);
		if($is_success!==false)
		{
			$this->success(L('add_booklist_ok'));
			return;
		}
		else
		{
			$this->error(L('add_booklist_error'));
			return;
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
			$this->success(L('drop_data_ok'),U('BooklistExt/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
		
	}
	
	public function getTableCnf(){
		$mod_booklist_ext=D("BooklistExt");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_booklist_ext,
			"isShowBtn"=>false,
			'isEditBtn'=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('title.string.30%','author.string.20%','clc.string.10%','isbn.string.10%'),
			'zdCallBack'=>array('title'=>function($cell_val,$row){
				return '<a class="link" href="'.U('Index/bookview/book_id/'.$row['book_id']).'" target="_blank">'.$cell_val.'</a>';	
			})
		);
	}
	
	function _common()
	{
		
	}
	
	public function getzds(){
		return array(
			"title"=>L('title'),
			"isbn"=>L('isbn'),
			"author"=>L('author'),
			"clc"=>L('clc')
		);
	}
	
	
}