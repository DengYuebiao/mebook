<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class AnnouController extends AdminBaseController {
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
		if($search_data['add_user'])
		{
			$where['add_user']=array("like","{$search_data['add_user']}%");
		}
		if($search_data['add_time'])
		{
			$search_data['add_time']=strtotime($search_data['add_time']);
			$where['add_time']=array("between",array($search_data['add_time'],$search_data['add_time']+86399));
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
		$_curlocal=AdminMenu::getOne("17");
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
			$_curlocal=AdminMenu::getOne("17");
			$_curlocal[]=array("name"=>L("add_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("valid_rule",$valid_rule);
			$this->display("form");
		}
		else
		{
			$nav_type=isset($_GET['nav_type'])?intval($_GET['nav_type']):0;
			$data=formatForm($_POST,array("string"=>"title,body"));
			
			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}
			
			$data['add_time']=time();
			$data['add_user']=$this->_user_info['user_name'];
			$mod_table->disFields($data);	
			$is_success=$mod_table->add($data);
			if($is_success!==false)
			{
				$this->success(L('add_data_ok'),U('Annou/index'));
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
			$_curlocal=AdminMenu::getOne("17");
			$_curlocal[]=array("name"=>L("edit_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("row_info",$row_info);
			$this->assign("valid_rule",$mod_table->getEditRule());
			$this->display("form");
		}
		else
		{
			
			$data=formatForm($_POST,array("string"=>"title,body"));

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
				$this->success(L('edit_data_ok'),U('Annou/index'));
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
			$this->success(L('drop_data_ok'),U('Annou/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
		
	}
	
	public function getTableCnf(){
		$mod_annou=D("Annou");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_annou,
			"isShowBtn"=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('title.string.20%','add_time.date1.10%','add_user.string.10%'),
			'zdCallBack'=>array('title'=>function($cell_val,$row){
				return '<a class="link" href="'.U('Index/annou/annou_id/'.$row['annou_id']).'" target="_blank">'.$cell_val.'</a>';	
			})
		);
	}
	
	function _common()
	{
		
	}
	
	public function getzds(){
		return array(
			"title"=>L('title'),
			"add_user"=>L('add_user'),
			"add_time"=>L('add_time'),
		);
	}
	
	
	//ue编辑器请求接口
	function uedit()
	{
		$ue_cnf=\Home\Extend\UeUpload\Uploader::getConfig();
		$replace_list=array("imagePathFormat","scrawlPathFormat","snapscreenPathFormat","catcherPathFormat","videoPathFormat","filePathFormat","imageManagerListPath","fileManagerListPath");
		
		foreach($replace_list as $item)
		{
			$ue_cnf[$item]=str_replace("{tsg_code}",$this->_user_info['tsg_code'],$ue_cnf[$item]);
		}
		
		$action=isset($_GET['action'])?trim($_GET['action']):"";

		switch ($action) {
			case 'config':
				$result =  json_encode($ue_cnf);
				
				break;
		
			/* 上传图片 */
			case 'uploadimage':
			/* 上传涂鸦 */
			case 'uploadscrawl':
			/* 上传视频 */
			case 'uploadvideo':
			/* 上传文件 */
			case 'uploadfile':
				$result =$this->_ue_upload($action,$ue_cnf); 
				break;
		
			/* 列出图片 */
			case 'listimage':
				$result =$this->_ue_list($action,$ue_cnf); 
				break;
			/* 列出文件 */
			case 'listfile':
				$result =$this->_ue_list($action,$ue_cnf); 
				break;
		
			/* 抓取远程文件 */
			case 'catchimage':
				$result =$this->_ue_crawler($ue_cnf); 
				break;
		
			default:
				$result = json_encode(array(
					'state'=> '请求地址出错'
				));
				break;
		}
		
		/* 输出结果 */
		if (isset($_GET["callback"])) {
			if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
				echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
			} else {
				echo json_encode(array(
					'state'=> 'callback参数不合法'
				));
			}
		} else {
			echo $result;
		}
	}
	
	private function _ue_upload($type,$ue_cnf)
	{
		/* 上传配置 */
		$base64 = "upload";
		switch ($type) {
			case 'uploadimage':
				$config = array(
					"pathFormat" => $ue_cnf['imagePathFormat'],
					"maxSize" => $ue_cnf['imageMaxSize'],
					"allowFiles" => $ue_cnf['imageAllowFiles']
				);
				$fieldName = $ue_cnf['imageFieldName'];
				break;
			case 'uploadscrawl':
				$config = array(
					"pathFormat" => $ue_cnf['scrawlPathFormat'],
					"maxSize" => $ue_cnf['scrawlMaxSize'],
					"allowFiles" => $ue_cnf['scrawlAllowFiles'],
					"oriName" => "scrawl.png"
				);
				$fieldName = $ue_cnf['scrawlFieldName'];
				$base64 = "base64";
				break;
			case 'uploadvideo':
				$config = array(
					"pathFormat" => $ue_cnf['videoPathFormat'],
					"maxSize" => $ue_cnf['videoMaxSize'],
					"allowFiles" => $ue_cnf['videoAllowFiles']
				);
				$fieldName = $ue_cnf['videoFieldName'];
				break;
			case 'uploadfile':
			default:
				$config = array(
					"pathFormat" => $ue_cnf['filePathFormat'],
					"maxSize" => $ue_cnf['fileMaxSize'],
					"allowFiles" => $ue_cnf['fileAllowFiles']
				);
				$fieldName = $ue_cnf['fileFieldName'];
				break;
		}
		
		/* 生成上传实例对象并完成上传 */
		$up = new \Home\Extend\UeUpload\Uploader($fieldName, $config, $base64);
		
		/**
		 * 得到上传文件所对应的各个参数,数组结构
		 * array(
		 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
		 *     "url" => "",            //返回的地址
		 *     "title" => "",          //新文件名
		 *     "original" => "",       //原始文件名
		 *     "type" => ""            //文件类型
		 *     "size" => "",           //文件大小
		 * )
		 */
		
		/* 返回数据 */
		return json_encode($up->getFileInfo());
	}
	
	
	private function _ue_list($type,$ue_cnf)
	{
		/* 判断类型 */
		switch ($type) {
			/* 列出文件 */
			case 'listfile':
				$allowFiles = $ue_cnf['fileManagerAllowFiles'];
				$listSize = $ue_cnf['fileManagerListSize'];
				$path = $ue_cnf['fileManagerListPath'];
				break;
			/* 列出图片 */
			case 'listimage':
			default:
				$allowFiles = $ue_cnf['imageManagerAllowFiles'];
				$listSize = $ue_cnf['imageManagerListSize'];
				$path = $ue_cnf['imageManagerListPath'];
		}
		$allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);
		
		/* 获取参数 */
		$size = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
		$start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
		$end = $start + $size;
		
		/* 获取文件列表 */
		$path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "":"/") . $path;
		
		$files = $this->_getfiles($path, $allowFiles);
		if (!count($files)) {
			return json_encode(array(
				"state" => "no match file",
				"list" => array(),
				"start" => $start,
				"total" => count($files)
			));
		}
		
		/* 获取指定范围的列表 */
		$len = count($files);
		for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
			$list[] = $files[$i];
		}
		//倒序
		//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
		//    $list[] = $files[$i];
		//}
		
		/* 返回数据 */
		$result = json_encode(array(
			"state" => "SUCCESS",
			"list" => $list,
			"start" => $start,
			"total" => count($files)
		));
		
		return $result;
	}
	
	private function _getfiles($path, $allowFiles, &$files = array())
	{
		if (!is_dir($path)) return null;
		if(substr($path, strlen($path) - 1) != '/') $path .= '/';
		$handle = opendir($path);
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$path2 = $path . $file;
				if (is_dir($path2)) {
					$this->_getfiles($path2, $allowFiles, $files);
				} else {
					if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
						$files[] = array(
							'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
							'mtime'=> filemtime($path2)
						);
					}
				}
			}
		}
		return $files;
	}
	
	private function _ue_crawler($ue_cnf)
	{
		/* 上传配置 */
		$config = array(
			"pathFormat" => $ue_cnf['catcherPathFormat'],
			"maxSize" => $ue_cnf['catcherMaxSize'],
			"allowFiles" => $ue_cnf['catcherAllowFiles'],
			"oriName" => "remote.png"
		);
		$fieldName = $ue_cnf['catcherFieldName'];
		
		/* 抓取远程图片 */
		$list = array();
		if (isset($_POST[$fieldName])) {
			$source = $_POST[$fieldName];
		} else {
			$source = $_GET[$fieldName];
		}
		foreach ($source as $imgUrl) {
			$item = new Uploader($imgUrl, $config, "remote");
			$info = $item->getFileInfo();
			array_push($list, array(
				"state" => $info["state"],
				"url" => $info["url"],
				"size" => $info["size"],
				"title" => htmlspecialchars($info["title"]),
				"original" => htmlspecialchars($info["original"]),
				"source" => htmlspecialchars($imgUrl)
			));
		}
		
		/* 返回抓取数据 */
		return json_encode(array(
			'state'=> count($list) ? 'SUCCESS':'ERROR',
			'list'=> $list
		));
	}
}