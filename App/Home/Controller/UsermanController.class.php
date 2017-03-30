<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;

class UsermanController extends AdminBaseController {
	public function index(){
		if(!isset($_POST['sub'])) {
			$table_obj = new TableTool($this->getTableCnf());
			$mod_table = $table_obj->getDb();
			$zd_list = $this->getzds();

			$search_data = $table_obj->getDataByMap($_GET, array('s1' => 'v1', 's2' => 'v2'), $zd_list);

			$where = array();
			if ($search_data['user_name']) {
				$where['user_name'] = array("like", "{$search_data['user_name']}%");
			}
			if ($search_data['email']) {
				$where['email'] = array("like", "{$search_data['email']}%");
			}
			if ($search_data['phone']) {
				$where['phone'] = array("like", "{$search_data['phone']}%");
			}


			$data_cnt = $mod_table->where($where)->count("0");
			$page_obj = new \Home\Extend\Page\Page($data_cnt, 15);
			$page_html = $page_obj->show();
			$this->assign("_page_html", $page_html);

			$limit_beg = $page_obj->firstRow < $data_cnt ? $page_obj->firstRow : 0;
			$data = $mod_table->where($where)->limit($limit_beg . ',' . $page_obj->listRows)->order($mod_table->getPK() . ' desc')->select();
			foreach ($data as $key => $item) {
				$data[$key]['is_admin'] = $item['is_admin'] == 1 ? L("yes") : L("no");
				$data[$key]['is_verify'] = $item['is_verify'] == 1 ? L("yes") : L("no");
			}
			$table_html = $table_obj->getHtml($data);
			$this->assign("table_html", $table_html);
			$_curlocal = AdminMenu::getOne("16");
			$this->assign("_curlocal", $_curlocal);
			$this->assign("zd_list", $zd_list);
			$this->display();
		}else{
			$mod_user = D("User");
			import('@.Extend.UploadFile.UploadFile');
			$upload = new \UploadFile();// 实例化上传类
			$upload->maxSize = 2147483648;// 设置附件上传大小
			$upload->allowExts = array('xls', 'xlsx');// 设置附件上传类型
			$upload->savePath = './Public/Upload/userimport/' .$_SESSION['ebook']['user_info']['user_id'] . "/";// 设置附件上传目录
			if (!file_exists($upload->savePath)) {
				mkdir($upload->savePath, 0770);
			}
			if (!$upload->upload()) {// 上传错误提示错误信息
				$this->error($upload->getErrorMsg());
			} else {// 上传成功 获取上传文件信息
				$info = $upload->getUploadFileInfo();

				$this->import($info[0]);


			}
		}
	}

	public function add(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();
		$valid_rule=$mod_table->getAddRule();

		if(!IS_POST)
		{
			$this->_common();
			$_curlocal=AdminMenu::getOne("16");
			$_curlocal[]=array("name"=>L("add_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("valid_rule",$valid_rule);
			$this->display("form");
		}
		else
		{
			$data=formatForm($_POST,array("string"=>"user_name,user_pwd,user_pwd2,email,phone,real_name,sex"));

			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}

			$is_success=$mod_table->addUser($data);
			if($is_success!==false)
			{
				$this->success(L('add_data_ok'),U('Userman/index'));
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
		$valid_rule=$mod_table->getEditRule();
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
			$_curlocal=AdminMenu::getOne("16");
			$_curlocal[]=array("name"=>L("edit_data"));
			$this->assign("_curlocal",$_curlocal);
			$this->assign("row_info",$row_info);
			$this->assign("valid_rule",$mod_table->getEditRule());
			$this->display("form");
		}
		else
		{
			$data=formatForm($_POST,array("string"=>"user_name,user_pwd,user_pwd2,email,phone,real_name,sex"));

			$valid_obj=new FormValid($data);
			if(!$valid_obj->valid($valid_rule))	 //如果表单数据验证失败
			{
				$this->error($valid_obj->getError());
				return;
			}

			if($data['user_pwd'] && $data['user_pwd']==$data['user_pwd2'])
			{
				$data['user_pwd']=md5($data['user_pwd']);
			}
			else
			{
				unset($data['user_pwd']);
			}

			$mod_table->disFields($data);
			$where=$table_obj->getPKWhere($_GET);	//获取主键条件数组
			$is_success=$mod_table->where($where)->save($data);
			if($is_success!==false)
			{
				$this->success(L('edit_data_ok'),U('Userman/index'));
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
			$this->success(L('drop_data_ok'),U('Userman/index'));
			return;
		}
		else
		{
			$this->error(L('drop_data_error'));
			return;
		}
	}

	public function verify(){
		$table_obj=new TableTool($this->getTableCnf());
		$mod_table=$table_obj->getDb();

		$where=$table_obj->getPKWhere($_GET);	//获取主键条件数组
		$row_info=$mod_table->where($where)->find();
		if(!$row_info)
		{
			$this->error(L('server_not_row'));
			exit();
		}

		if($row_info['user_name']=="admin")
		{
			$this->error(L('not_verify_admin'));
			exit();
		}
		$is_verify=isset($_GET['is_verify'])?intval($_GET['is_verify']):0;
		$is_success=$mod_table->where($where)->save(array("is_verify"=>$is_verify));

		if($is_success!==false)
		{
			$this->success(L('verify_data_ok'),U('Userman/index'));
			return;
		}
		else
		{
			$this->error(L('verify_data_error'));
			return;
		}
	}

	public function getTableCnf(){
		$mod_user=D("User");
		return array(
			"actionPre"=>CONTROLLER_NAME,
			"dbMod"=>$mod_user,
			"isShowBtn"=>false,
			"btnWidth"=>"9%",
			'zdShow'=>array('user_name.string.10%','real_name.string.10%','is_verify.string.8%','is_admin.string.10%','email.string.10%','add_time.date.10%'),
			'btnCallBack'=>function($btn_list,$row_data){
				if($row_data['user_name']!="admin")
				{
					if($row_data['is_verify']==L("no"))
					{
						$btn_list[]=array("name"=>L('verify'),"func"=>"verifyUser({$row_data['user_id']},1,this);","url"=>'');
					}
					else
					{
						$btn_list[]=array("name"=>L('re_verify'),"func"=>"verifyUser({$row_data['user_id']},0,this);","url"=>'');
					}
				}
				return $btn_list;
			},
		);
	}

	public function getzds(){
		return array(
			"user_name"=>L('user_name'),
			"email"=>L('email'),
			"phone"=>L('phone'),
		);
	}

	function _common()
	{
		$this->assign('is_admin_list',array(
			0=>L('no'),
			1=>L('yes'),
		));
	}


	//读者数据导入
	function import($file_info,$sheet_name="")
	{//var_dump($file_info);die();

		$mod_user = D("User");
		$sheet_name=isset($sheet_name)?trim($sheet_name):"";

		if(empty($file_info))
		{
			$this->error("在数据库未找到该文件信息！");
			return false;
		}
		if(!file_exists($file_info['savepath'].$file_info['savename']))
		{

			$this->error("在服务器上未找到该文件！",U("Userman/index"));
			return false;
		}


		import("@.Extend.PHPExcel.IOFactory");
		$file_path=$file_info['savepath'].$file_info['savename'];
		$objPHPExcel = \PHPExcel_IOFactory::load($file_path);
		$sheet_list=$objPHPExcel->getSheetNames();
		$data_list=array();
		if(!empty($sheet_name))		//如果指定工作表的名称
		{
			if(!in_array($sheet_name,$sheet_list))
			{
				$data_list = $objPHPExcel->getSheet(0)->toArray(null, true, true, true);
			}
			else
			{
				$data_list = $objPHPExcel->getSheetByName($sheet_name)->toArray(null, true, true, true);

			}
		}
		else
		{
			$data_list = $objPHPExcel->getSheet(0)->toArray(null, true, true, true);
		}

		$data_head=array_shift($data_list);	//获取表头


		$data_ok_cnt = 0;
		$data_err_cnt = 0;
		$err_data_list = array();
		$data_msg = array();

		foreach ($data_list as $key => $data_item) {
			$add_data = array();
			try {
				$mod_user->startTrans();                            //开始事务

				//$data=formatForm($_POST,array("string"=>"user_name,user_pwd,user_pwd2,email,phone,real_name,sex"));

				$valid_obj=new FormValid($data_item);
				$add_data['user_name'] = $data_item['A'];
				$add_data['user_pwd'] = $data_item['B'];
				$add_data['real_name'] = $data_item['C'];
				$add_data['is_verify'] = trim($data_item['D'])=="是"?1:0;
				$add_data['is_admin'] = trim($data_item['E'])=="是"?1:0;
				$add_data['email'] = $data_item['F'];

				$is_success=$mod_user->addUser($add_data);

				if ($is_success !== false) {
					$mod_user->commit();                                //事务提交
					$data_ok_cnt++;
					$data_msg[] = array(
						"user_name" => $add_data["user_name"],
						"real_name" => $add_data["real_name"],
						"status" => "成功",
						"msg" => "读者导入成功"
					);
				} else {
					$mod_user->rollback();                                //事务回滚
					$data_err_cnt++;
					$err_data_list[] = $data_item;
					$data_msg[] = array(
						"user_name" => $add_data["user_name"],
						"real_name" => $add_data["real_name"],
						"status" => "失败",
						"msg" => $mod_user->getError()
					);
				}


			} catch (\Exception $e) {
				$mod_user->rollback();                                //事务回滚
			}


		}

		$re_arr = array(
			"data_ok_cnt" => $data_ok_cnt,
			"data_err_cnt" => $data_err_cnt,
			"data_errmsg" => $data_msg
		);

		if ($data_err_cnt > 0) {
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("ebook")
				->setLastModifiedBy("ebook")
				->setTitle("ebook")
				->setSubject("ebook")
				->setDescription("ebook")
				->setKeywords("ebook")
				->setCategory("ebook");

			$worksheet_obj = $objPHPExcel->setActiveSheetIndex(0);
			$data_write_list = array_merge($data_head, $err_data_list);

			$r = 4;                                                            //写入数据
			foreach ($data_write_list as $row_item) {
				$i = 0;
				foreach ($row_item as $key => $item) {
					$worksheet_obj->setCellValueExplicitByColumnAndRow($key, $i, $item, \PHPExcel_Cell_DataType::TYPE_STRING);
				}
				$r++;
			}


			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle("读者导入错误数据");

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);

			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
			$file_name = preg_replace('/.xls.$/', ".xls", basename($file_path));
			//将错误信息写入文件
			$err_file_path = dirname($file_path) . "/err_" . $file_name;
			$objWriter->save($err_file_path);
			$upload_data = array();
			$upload_data["err_file"] = $err_file_path;
			$upload_data["is_add"] = 1;
			$upload_data["disuse_time"] = strtotime(date("Y-m-d"));


		}


		$this->success("读者导入完成,成功导入：".$re_arr['data_ok_cnt'].";失败导入：".$re_arr['data_err_cnt'],U("Userman/index"),5);

	}

	public function downtpl(){
		//import('Org.Net.Http');
		import("@.Extend.Http.Http");
		$file_name="example.xls";
		$file_path=$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."Public".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR."userimport".DIRECTORY_SEPARATOR."example".DIRECTORY_SEPARATOR.$file_name;
		\Http::download($file_path,urlencode("example.xls"),"",1800);
	}

}