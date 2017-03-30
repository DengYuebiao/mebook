<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
;
class CmtController extends AdminBaseController {
    public function index(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
		$search_data=$table_obj->getDataByMap($_GET,array('s1'=>'v1'),$zd_list);
		
		$where=array();
		if($search_data['title'])
		{
			$where['title']=array("like","{$search_data['title']}%");
		}
		if($search_data['user_name'])
		{
			$where['user_name']=array("like","{$search_data['user_name']}%");
		}

		$join_str="eb_book ON eb_cmt.book_id = eb_book.book_id";
		$join_str1="eb_user ON eb_cmt.user_id = eb_user.user_id";
		$data_cnt=$mod_table->join($join_str)->join($join_str1)->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->join($join_str)->join($join_str1)->where($where)->limit($limit_beg.','.$page_obj->listRows)->order($mod_table->getPK().' desc')->select();
		
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("31");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
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
			$this->success(L('drop_data_ok'),U('Cmt/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
		
	}

	public function getTableCnf(){
		$mod_cmt=D("Cmt");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_cmt,
			"isShowBtn"=>false,
			'isEditBtn'=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('title.string.20%','user_name.string.8%','cmt_body.string.20%','cmt_time.date1.12%'),
			'zdCallBack'=>array('title'=>function($cell_val,$row){
				return '<a class="link" href="'.U('Index/bookview/book_id/'.$row['book_id']).'" target="_blank">'.$cell_val.'</a>';
			})
		);
	}

	public function getTableCnfB(){
		$mod_book=D("Book");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_book,
			"isShowBtn"=>false,
			'isEditBtn'=>false,
			'isDropBtn'=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('title.string.20%','author.string.20%','isbn.string.10%','readtimes.string.10%'),
			'zdCallBack'=>array('title'=>function($cell_val,$row){
				return '<a class="link" href="'.U('Index/bookview/book_id/'.$row['book_id']).'" target="_blank">'.$cell_val.'</a>';
			})
		);
	}

	public function getTableCnfR(){
		$mod_book=D("User");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_book,
			"isShowBtn"=>false,
			'isEditBtn'=>false,
			'isDropBtn'=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('real_name.string.10%','user_name.string.10%','sex.string.10%','email.string.20%','phone.string.10%','cnt.string.10%'),

		);
	}
	public function getTableCnfC(){
		$mod_book=D("Book");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_book,
			"isShowBtn"=>false,
			'isEditBtn'=>false,
			'isDropBtn'=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('title.string.17%','author.string.22%','isbn.string.15%','clc.string.10%','col.string.10%'),
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
			"user_name"=>L('user_name'),
			"title"=>L('title'),
		);
	}

	public function read_rank(){
		$table_obj=new TableTool($this->getTableCnfR());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();

		$where=array();
		$begtime = isset($_GET['time_beg']) && !empty($_GET['time_beg'])? strtotime($_GET['time_beg']):'26537718';
		$endtime = isset($_GET['time_end']) && !empty($_GET['time_end'])? strtotime($_GET['time_end']):time();

		$field = "eb_user.real_name,user_name,sex,email,phone,a.cnt";

		$join_str="(select count(book_id) as cnt,user_id  from eb_rh where add_time between '{$begtime}' and '{$endtime}'group by user_id) as a ON eb_user.user_id=a.user_id";
		$data_cnt=$mod_table->join($join_str)->field($field)->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);

		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data = $mod_table->join($join_str)->field($field)->where($where)->limit($limit_beg.','.$page_obj->listRows)->order("cnt".' desc')->select();

		if(isset($_GET['export_excel']))
		{
			$data = $mod_table->join($join_str)->field($field)->where($where)->order("cnt".' desc')->select();
			$fields=array(
				'user_name'=>L('user_name'),
				'real_name'=>L('real_name'),
				'sex'=>L('sex'),
				'email'=>L('email'),
				'phone'=>L('phone')
			);
			$name="读者阅读排行.xls";
			$this->export_excel($data,$name,$fields);
			exit();
		}


		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("35");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
		$this->display();
	}

	public function book_rank(){
		$table_obj=new TableTool($this->getTableCnfB());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
        $mod_book = D('Book');
		$search_data=$table_obj->getDataByMap($_GET,array('s1'=>'v1'),$zd_list);

		$where=array();

		$begtime = isset($_GET['time_beg']) && !empty($_GET['time_beg'])? strtotime($_GET['time_beg']):'1121131980';
		$endtime = isset($_GET['time_end']) && !empty($_GET['time_end'])? strtotime($_GET['time_end']):time();

        $field = "eb_book.book_id,title,isbn,author,clc,a.readtimes";
		$join_str="(select count(user_id) as readtimes,book_id  from eb_rh where add_time between '{$begtime}' and '{$endtime}' group by book_id) as a ON eb_book.book_id=a.book_id";

		$data_cnt=$mod_book->join($join_str)->field($field)->where("a.readtimes>0")->count('0');
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);

		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_book->join($join_str)->field($field)->where("a.readtimes>0")->limit($limit_beg.','.$page_obj->listRows)->order("a.readtimes".' desc')->select();

		if(isset($_GET['export_excel']))
		{
			$data=$mod_book->join($join_str)->field($field)->where("a.readtimes>0")->order("a.readtimes".' desc')->select();
			$fields=array(
				'title'=>L('title'),
				'author'=>L('author'),
				'isbn'=>L('isbn'),
				'readtimes'=>L('readtimes')
			);
			$name="热门图书排行.xls";
			$this->export_excel($data,$name,$fields);
			exit();
		}

		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("36");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
		//$this->assign("booklist_list",$booklist_list);
		$this->display();
	}

	public function col_rank(){
		$table_obj=new TableTool($this->getTableCnfC());
		$mod_table=$table_obj->getDb();
		$zd_list=$this->getzds();
        $mod_book = D('Book');

		$where=array();

		$begtime = isset($_GET['time_beg']) && !empty($_GET['time_beg'])? strtotime($_GET['time_beg']):'1121131980';
		$endtime = isset($_GET['time_end']) && !empty($_GET['time_end'])? strtotime($_GET['time_end']):time();

        $field = "eb_book.book_id,title,isbn,author,clc,a.col";
		$join_str="(select count(book_id) as col,book_id  from eb_bookmark where add_time between '{$begtime}' and '{$endtime}' group by book_id) as a ON eb_book.book_id=a.book_id";

		$data_cnt=$mod_book->join($join_str)->field($field)->where("a.col>0")->count('0');
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);

		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_book->join($join_str)->field($field)->where("a.col>0")->limit($limit_beg.','.$page_obj->listRows)->order("a.col".' desc')->select();



		if(isset($_GET['export_excel']))
		{
			$data=$mod_book->join($join_str)->field($field)->where("a.col>0")->order("a.col".' desc')->select();
			$fields=array(
				'title'=>L('title'),
				'author'=>L('author'),
				'isbn'=>L('isbn'),
				'col'=>L('col')
			);
			$name="热门收藏排行.xls";
			$this->export_excel($data,$name,$fields);
			exit();
		}

		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("37");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
		//$this->assign("booklist_list",$booklist_list);
		$this->display();
	}

	private function export_excel($datalist,$name,$fields)
	{
		import("@.Extend.phpExport.phpExport");
		$exporter = new \ExportDataExcel('browser', $name);
		$exporter->initialize(); 			// starts streaming data to web browser

		$head_list=array();
		foreach($fields as $item)				//写入列名
		{
			$head_list[]=$item;
		}
		$exporter->addRow($head_list);


		foreach($datalist as $item)				//写入数据
		{
			$data_row=array();
			foreach($fields as $key1=>$item1)
			{
				$val_tmp=isset($item[$key1])?$item[$key1]:"";
				$data_row[]=$val_tmp;

			}
			$exporter->addRow($data_row);
			$data_row=array();
		}

		$exporter->finalize(); // writes the footer, flushes remaining data to browser.
	}

}