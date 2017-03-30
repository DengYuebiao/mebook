<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class RhController extends AdminBaseController {
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
		if($search_data['author'])
		{
			$where['author']=array("like","{$search_data['author']}%");
		}

		$join_str="eb_book ON eb_rh.book_id = eb_book.book_id";
		$join_str1="eb_user ON eb_rh.user_id = eb_user.user_id";
		$data_cnt=$mod_table->join($join_str)->join($join_str1)->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->join($join_str)->join($join_str1)->where($where)->limit($limit_beg.','.$page_obj->listRows)->order($mod_table->getPK().' desc')->select();
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("33");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
		$this->assign("booklist_list",$booklist_list);
        $this->display();
    }
	
	function tj()
	{
		$curr_year=date("Y");
		$year=isset($_GET['year'])?intval($_GET['year']):$curr_year;
		if(!$year)
		{
			$this->error(L('year_empty'));
			return;
		}
		
		$mod_rh=D("Rh");
		$data_list=$mod_rh->field("FROM_UNIXTIME(add_time,'%m') as mon_name,count(0) as cnt")->where("FROM_UNIXTIME(add_time,'%Y')={$year}")->group("mon_name")->order("mon_name")->select();
		$chart_data="";
		$cnt_map=array();
		$cnt_excel=array();
		foreach($data_list as $item)
		{
			$mon_tmp=intval($item['mon_name']);
			$cnt_map[$mon_tmp]=$item['cnt'];
		}
		
		$color_arr=array("AFD8F8","F6BD0F","8BBA00","FF8E46","008E8E","D64646","8E468E","588526","B3AA00","008ED6","FFFFCC","CCFFFF","CCCCFF","66CCCC","CCFF66","666699","#CCFF00","FF9900","993366","FF6666","009999","CCCC33","CCCC66","CC9933","666633","CC9966");
		for($i=1;$i<13;$i++)
		{
			$arr_index=array_rand($color_arr);
			$color=$color_arr[$arr_index];
			$cnt=isset($cnt_map[$i])?$cnt_map[$i]:0;
			$chart_data.="<set name='{$i}' color='{$color}' value='{$cnt}' />";
		}

		if(isset($_GET['export_excel']))
		{
			//$data=$mod_rh->field("FROM_UNIXTIME(add_time,'%m') as mon_name,count(0) as cnt")->where("FROM_UNIXTIME(add_time,'%Y')={$year}")->group("mon_name")->order("mon_name")->select();
			$fields=array(
				'mon_name'=>L('mon_name'),
				'cnt'=>L('cnt')
			);
			$name="{$_GET['excel']}年阅读量统计.xls";
			$this->export_excel($data_list,$name,$fields);
			exit();
		}


		$this->assign("chart_data",$chart_data);
		$_curlocal=AdminMenu::getOne("34");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("curr_year",$year);
		$this->assign("year_list",array($curr_year-2,$curr_year-1,$curr_year,$curr_year+1,$curr_year+2));
		$this->assign("chart_list",array("FCF_Column2D.swf"=>L('chart_name1'),"FCF_Column3D.swf"=>L('chart_name2'),"FCF_Bar2D.swf"=>L('chart_name3'),"FCF_Pie2D.swf"=>L('chart_name4'),"FCF_Pie3D.swf"=>L('chart_name5'),"FCF_Line.swf"=>L('chart_name6')));
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
			$this->success(L('drop_data_ok'),U('Rh/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
		
	}
	
	public function getTableCnf(){
		$mod_rh=D("Rh");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_rh,
			"isShowBtn"=>false,
			'isEditBtn'=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('title.string.20%','user_name.string.10%','author.string.10%','add_time.date.8%'),
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
			"user_name"=>L('user_name'),
			"author"=>L('author')
		);
	}


	private function export_excel($datalist,$name,$fields)
	{
		import("@.Extend.phpExport.phpExport");
		$exporter = new \ExportDataExcel('browser', $name);
		$exporter->initialize(); 			// starts streaming data to web browser
        $data=array();
		$head_list=array();
		foreach($fields as $item)				//写入列名
		{
			$head_list[]=$item;
		}
		$exporter->addRow($head_list);
        for($i=0;$i<12;$i++){
			 $data[$i] = array("mon_name"=>$i+1,"cnt"=>"0");
		}
		foreach($datalist as $item){
			$mon_name = intval($item['mon_name']);
             $data[$mon_name-1] = $item;
		}

		foreach($data as $item)				//写入数据
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