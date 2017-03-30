<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class BookController extends AdminBaseController {
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
		
		$data_cnt=$mod_table->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,15);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		
		$limit_beg=$page_obj->firstRow<$data_cnt?$page_obj->firstRow:0;
		$data=$mod_table->where($where)->limit($limit_beg.','.$page_obj->listRows)->order($mod_table->getPK().' desc')->select();
		$table_html=$table_obj->getHtml($data);
		$this->assign("table_html",$table_html);
		$_curlocal=AdminMenu::getOne("21");
		$this->assign("_curlocal",$_curlocal);
		$this->assign("zd_list",$zd_list);
		$mod_booklist=D("Booklist");
		$this->assign("curr_sys",$mod_booklist->getSys());
        $this->display();
    }
	
	public function publish_man(){
		cookie("search_field",null);
		cookie("search_val",null);
		//cookie(null);
		$mod_pub = D("pubinfo");
		$where=array();
		if(IS_POST){
			$field = isset($_POST['search_field']) ? trim($_POST['search_field']) : "";
			$val = isset($_POST['search_val']) ? trim($_POST['search_val']) : "";
			$del_id = isset($_POST['del_id']) ? trim($_POST['del_id']) : "";
			$edit_id = isset($_POST['edit_id']) ? trim($_POST['edit_id']) : "";
			if(!empty($val)){
				cookie("search_field",$field);
				cookie("search_val",$val);
				$where[$field] = $val;
			}

			//删除
			if(!empty($del_id)){
				$is_success = $mod_pub->where("pub_id='$del_id'")->delete();
				if($is_success === false){
					$this->ajaxReturn(0,"",0);
				}else{
					$this->ajaxReturn(1,"",1);
				}
			}

			//编辑
			if(!empty($edit_id)){
				$pub_info = $mod_pub->where("pub_id='$edit_id'")->find();
				if($pub_info){
					$this->ajaxReturn($pub_info,"",1);
				}else{
					$this->ajaxReturn("","",0);
				}
			}

			if(isset($_POST['different'])){
				$different = $_POST['different'];
				$isbncode = isset($_POST['isbncode']) ? trim($_POST['isbncode']) :"";
				$pub_name = isset($_POST['pub_name']) ? trim($_POST['pub_name']) :"";
				$pub_place = isset($_POST['pub_place']) ? trim($_POST['pub_place']) :"";
				$areacode = isset($_POST['areacode']) ? trim($_POST['areacode']) :"";
				$data = array(
					"isbncode"=>$isbncode,
					"publisher"=>$pub_name,
					"pubplace"=>$pub_place,
					"areacode"=>$areacode,
				);


				if(empty($_POST['different'])){
					$info = $mod_pub->where("isbncode='$isbncode'")->find();
					if(!empty($info)){
						 $this->error(l("exit_isbncode"));
					}
					$is_success = $mod_pub->add($data);
				}else{
					$is_success = $mod_pub->where("pub_id='$different'")->save($data);
				}

				if(!$is_success){
					$this->error(l('op_error'));
				}
			}
		}
		//分页
		$data_cnt=$mod_pub->where($where)->count("0");
		$page_obj= new \Home\Extend\Page\Page($data_cnt,10);
		$page_html= $page_obj->show();
		$this->assign("_page_html",$page_html);
		$limit_beg=$page_obj->firstRow < $data_cnt ? $page_obj->firstRow : 0;
		$pub_info=$mod_pub->where($where)->limit($limit_beg.','.$page_obj->listRows)->select();
		if($pub_info){
			$this->assign("pub_info",$pub_info);
		}
		$_curlocal=AdminMenu::getOne("55");
		$this->assign("_curlocal",$_curlocal);
		$this->display();
	}
	
}