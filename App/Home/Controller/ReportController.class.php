<?php

namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class ReportController extends BaseController
{
	public function index(){
		$row_info=getOptions("sys_reg_name,sys_is_open,sys_is_verify,sys_is_reg,sys_ip_type,sys_ip_fail_type,sys_index_left1,sys_index_left2,sys_index_left3,sys_site_logo,sys_filter_word,sys_book_path,cas_open,cas_ver,cas_host,cas_port,cas_uri");
		//检查登陆信息
		if(empty($this->_user_info) || empty($this->_user_info['user_id']))
		{
			$this->error(L("must_login"),U("Index/login"));
			return false;
		}
		if(!IS_POST)
		{
			/*$mod_booklist=D("Booklist");
			$booklist_map=$mod_booklist->getMap();
			$this->assign("booklist_map",$booklist_map);
			$this->assign("ip_type_list",array(1=>L('ip_type1'),2=>L('ip_type2')));
			$this->assign("ip_fail_type_list",array(1=>L('ip_fail_type1'),2=>L('ip_fail_type2')));*/
			$_curlocal=AdminMenu::getOne("11");
			$this->assign("_curlocal",$_curlocal);
			/*$row_info['sys_site_logo']=$row_info['sys_site_logo']?$row_info['sys_site_logo']:'Public/image/logo.png';
			$this->assign("row_info",$row_info);
			$mod_book=D("Book");
			$mod_book_ft=D("Book_ft");
			$book_num=$mod_book->count("0");
			$book_fs_num=$mod_book_ft->count("0");
			$this->assign("book_num",$book_num);
			$this->assign("book_fs_num",$book_fs_num);*/
			$this->display();
		}
		else
		{
			$data=formatForm($_POST,array("string"=>"sys_reg_name,sys_filter_word,sys_book_path,cas_open,cas_ver,cas_host,cas_port,cas_uri",'int'=>'sys_is_open,sys_is_verify,sys_is_reg,sys_ip_type,sys_ip_fail_type,sys_index_left1,sys_index_left2,sys_index_left3'));
			$old_logo=getOption("sys_site_logo");
			$mod_option=D("Option");
			if(!empty($_FILES['sys_site_logo']['tmp_name']))
			{
				if($old_logo && file_exists($old_logo))
				{
					@unlink($old_logo);
				}
				$logo_info=$mod_option->uploadLogo($_FILES['sys_site_logo']);

				if($logo_info===false)
				{
					$this->error($mod_option->getError());
					return;
				}
				else
				{
					$is_success=setOption("sys_site_logo",$logo_info['path']);
					if($is_success===false)
					{
						$this->error(L('edit_data_error'));
						return;
					}
				}
			}

			foreach($data as $key=>$item)
			{
				$is_success=setOption($key,$item);
				if($is_success===false)
				{
					$this->error(L('edit_data_error'));
					return;
				}
			}

			$this->success(L('edit_data_ok'),U('Admin/info'));
			return;

		}
	}


	public function login()
	{
		import('@.Extend.PhpCas.PhpCasTool');
		$cas_obj = \PhpCasTool::getInstance();
		if ($cas_obj->isOpen()) {
			$user_info = $cas_obj->login();
			if ($user_info === false) {
				$this->error($cas_obj->getError(), u('Index/index'));
			} else {
				session('user_info', $user_info);
				$this->_user_info = $user_info;
			}
		}
		if (!empty($this->_user_info['user_id'])) {
			$this->error(l('already_login'), u('Index/index'));
			return false;
		}
		$mod_user = d('User');
		$valid_rule = $mod_user->getLoginRule();
		$this->assign('valid_rule', $valid_rule);
		if (isset($_POST['user_name'])) {
			$data = formatform($_POST, array('string' => 'user_name,user_pwd', 'int' => 'login_remember'));
			$valid_obj = new \Home\Extend\FormValid\FormValid($data);
			if (!$valid_obj->valid($valid_rule)) {
				$this->error($valid_obj->getError());
				return NULL;
			}
			$user_name = strip_tags(stripslashes($data['user_name']));
			$user_pwd = strip_tags(stripslashes($data['user_pwd']));
			if (!$user_name) {
				$this->error(l('must_user_name'));
				return NULL;
			}
			if (!$user_pwd) {
				$this->error(l('must_user_pwd'));
				return NULL;
			}
			$user_info = $mod_user->where('user_name=\'' . $user_name . '\'')->find();
			if (!$user_info) {
				$this->error(l('user_not_exist'));
				return NULL;
			}
			if ($user_info['user_pwd'] != md5($user_pwd)) {
				$this->error(l('user_pwd_err'));
				return NULL;
			}
			/*if ($user_info['is_verify'] != 1) {
				$this->error(l('user_not_verify'));
				return NULL;
			}*/
			if ($data['login_remember'] == 1) {
				$save_time = time();
				$save_user = $user_info['user_name'] . ':' . $save_time . ':' . md5($user_info['user_name'] . $save_time . $user_info['user_pwd']);
				$save_user = bin2hex($save_user);
				cookie('save_user', $save_user, 864000);
			}

			session('user_info', $user_info);
			$this->success(l('user_login_ok'), u('Index/index'));
			return NULL;
		}
		$this->display();
	}
	public function reg()
	{
		$sys_is_open = getoption('sys_is_open');
		if (!$sys_is_open) {
			$this->error(l('system_close_reg'), u('Index/index'));
			return false;
		}
		if (!empty($this->_user_info['user_id'])) {
			$this->error(l('already_login'), u('User/index'));
			return false;
		}
		$mod_user = d('User');
		$valid_rule = $mod_user->getAddRule();
		$valid_rule['rules']['vercode'] = array('required' => true);
		$this->assign('valid_rule', $valid_rule);
		if (isset($_POST['user_name'])) {
			if (!$mod_user->autoCheckToken($_POST)) {
				$this->error(l('submit_repeat'));
				return NULL;
			}
			$data = formatform($_POST, array('string' => 'user_name,user_pwd,user_pwd2,email,phone,real_name,vercode'));
			$mod_user = d('User');
			$filter_word = $mod_user->hasFilterWord($data['user_name']);
			if ($filter_word) {
				$this->error(sprintf(l('user_has_filter_word'), $filter_word));
				return NULL;
			}
			$valid_obj = new \Home\Extend\FormValid\FormValid($data);
			if (!$valid_obj->valid($valid_rule)) {
				$this->error($valid_obj->getError());
				return NULL;
			}
			$verify = new \Think\Verify();
			if (!$verify->check($data['vercode'], $id)) {
				$this->error(l('vercode_fail'));
				return NULL;
			}
			unset($data['vercode']);
			$sys_is_verify = getoption('sys_is_verify');
			$data['is_verify'] = $sys_is_verify ? 0 : 1;
			$user_id = $mod_user->addUser($data);
			if ($user_id !== false) {
				if ($sys_is_verify) {
					$this->success(l('add_user_ok_verify'), u('Index/index'));
					return NULL;
				} else {
					$user_info = $mod_user->where('user_id=\'' . $user_id . '\'')->find();
					if ($user_info) {
						$user_info['is_admin'] = $user_info['is_admin'] == 1;
						session('user_info', $user_info);
					}
					$this->success(l('add_user_ok'), u('User/index'));
					return NULL;
				}
			} else {
				$this->error(l('add_user_err') . $mod_user->getError());
				return NULL;
			}
		}
		$this->assign('_token', gettoken());
		$this->display();
	}


	public function search()
	{
		$search_val = isset($_GET['sv']) ? trim($_GET['sv']) : '';
		$search_val = strip_tags(stripslashes($search_val));
		if ($this->_user_info['user_id'] && $search_val) {
			$mod_user = d('User');
			$filter_word = $mod_user->hasFilterWord($search_val);
			if (!$filter_word) {
				$mod_search = d('Search');
				$mod_search->add(array('user_id' => $this->_user_info['user_id'], 'search_val' => $search_val, 'ip_addr' => $_SERVER['REMOTE_ADDR'], 'add_time' => time()));
			}
		}
		$mod_book = d('Book');
		$where = array();
		$where['title'] = array('like', $search_val . '%');
		$data_cnt = $mod_book->where($where)->count('0');
		$page_obj = new \Home\Extend\Page\Page($data_cnt, 10);
		$page_html = $page_obj->show();
		$this->assign('_page_html', $page_html);
		$limit_beg = $page_obj->firstRow < $data_cnt ? $page_obj->firstRow : 0;
		$booklist = $mod_book->where($where)->limit($limit_beg . ',' . $page_obj->listRows)->order('readtimes desc')->select();
		foreach ($booklist as $key => $item) {
			$booklist[$key]['picture'] = $mod_book->fixPic($item['picture']);
		}
		$this->assign('booklist_list', $booklist);
		$this->display();
	}
	public function fullsearch()
	{
		$auth_obj = new \Home\Extend\Tcpdf\fpdf();
		$class_obj = new \ReflectionClass($auth_obj);
		$file_md5 = md5_file($class_obj->getFileName());

		$info = $auth_obj->getSource();
		if (!$info) {
			header('Content-Type:text/html;charset=utf-8');
			echo $auth_obj->getError();
			die;
		}
		$ver = substr($info['ver'], 0, 1);
		$is_full = in_array($ver, array(1, 3)) ? true : false;
		$this->assign('is_full', $is_full);
		$mod_book = d('Book');
		$zd_list = array('title' => l('title'), 'isbn' => l('isbn'), 'author' => l('author'), 'clc' => l('clc'));
		$search_data = getdatabymap($_GET, array('s1' => 'v1', 's2' => 'v2'), $zd_list);
		$tab = isset($_GET['tab']) ? trim($_GET['tab']) : 'tab1';
		$stxt = isset($_GET['stxt']) ? trim($_GET['stxt']) : '';
		if ($stxt) {
			$stxt = strip_tags(stripslashes($stxt));
			$search_data['all_content'] = $stxt;
		}
		$where = array();
		if ($tab == 'tab2') {
			if ($search_data['all_content']) {
				$where[] = 'MATCH(all_content) AGAINST(\'' . mysql_escape_string($search_data['all_content']) . '\')';
			}
		} else {
			if ($search_data['title']) {
				$where['title'] = array('like', $search_data['title'] . '%');
			}
			if ($search_data['isbn']) {
				$where['isbn'] = array('like', $search_data['isbn'] . '%');
			}
			if ($search_data['author']) {
				$where['author'] = array('like', $search_data['author'] . '%');
			}
			if ($search_data['clc']) {
				$where['clc'] = array('like', $search_data['clc'] . '%');
			}
		}
		$data_cnt = $mod_book->where($where)->count('0');
		$page_obj = new \Home\Extend\Page\Page($data_cnt, 10);
		$page_html = $page_obj->show();
		$this->assign('_page_html', $page_html);
		$limit_beg = $page_obj->firstRow < $data_cnt ? $page_obj->firstRow : 0;
		$booklist = $mod_book->where($where)->limit($limit_beg . ',' . $page_obj->listRows)->order('joindate desc')->select();
		foreach ($booklist as $key => $item) {
			$booklist[$key]['picture'] = $mod_book->fixPic($item['picture']);
		}
		$this->assign('booklist_list', $booklist);
		$this->assign('zd_list', $zd_list);
		$mod_user = d('User');
		$search_user = $mod_user->getSearchUser();
		$this->assign('search_user', $search_user);
		$good_list = $mod_book->getTopGood();
		$this->assign('good_list', $good_list);
		$this->display();
	}

	public function get_clc_child()
	{
		$clc_id = isset($_GET['clc_id']) ? intval($_GET['clc_id']) : 0;
		$mod_clc = d('Clc');
		$data_list = $mod_clc->where('pid=' . $clc_id)->order('order_num,clc_id')->select();
		$this->ajaxReturn($data_list, '', 1);
	}
	public function sysreg()
	{
		$reg_info = getdata('reg');
		if ($reg_info && $reg_info['name'] && $reg_info['code']) {
			$this->error(l('sys_reg_too'), u('Index/index'));
			die;
		}
		$valid_rule = array('rules' => array('regname' => array('required' => true), 'regcode' => array('required' => true)));
		if (!IS_POST) {
			$this->assign('valid_rule', $valid_rule);
			$auth_obj = new \Home\Extend\Tcpdf\fpdf();
			$mcode = $auth_obj->getParam();
			if ($mcode === false) {
				$mcode = $auth_obj->getError();
			}
			$this->assign('mcode', $mcode);
			$this->_page_title = l('sysreg_title') . '-' . $this->_page_title;
			$this->display();
		} else {
			$data = formatform($_POST, array('string' => 'regname,regcode'));
			$valid_obj = new \Home\Extend\FormValid\FormValid($data);
			if (!$valid_obj->valid($valid_rule)) {
				$this->error($valid_obj->getError());
				return NULL;
			}
			$reg_info = array('name' => $data['regname'], 'code' => $data['regcode']);
			$auth_obj = new \Home\Extend\Tcpdf\fpdf();
			$is_auth = $auth_obj->getParser($reg_info);
			if ($is_auth === false) {
				$this->error($auth_obj->getError());
				die;
			} else {
				setdata('reg', $reg_info);
				$this->success(l('sysreg_ok'), u('Index/index'));
			}
		}
	}


}