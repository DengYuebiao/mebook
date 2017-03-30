<?php
namespace Home\Controller;
use \Home\Extend\AdminMenu\AdminMenu;
use \Home\Extend\TableTool\TableTool;
use \Home\Extend\FormValid\FormValid;
class MovieController extends AdminBaseController
{
	public function index()
	{
		$table_obj = new \Home\Extend\TableTool\TableTool($this->getTableCnf());
		$mod_table = $table_obj->getDb();
		$zd_list = $this->getzds();
		$search_data = $table_obj->getDataByMap($_GET, array('s1' => 'v1', 's2' => 'v2'), $zd_list);
		$where = array();

		if ($search_data['title']) {
			$where['title'] = array('like', $search_data['title'] . '%');
		}

		if ($search_data['author']) {
			$where['author'] = array('like', $search_data['author'] . '%');
		}

		if ($search_data['keyword']) {
			$where['keyword'] = array('like', $search_data['keyword'] . '%');
		}

		if ($search_data['clc_name']) {
			$where['clc_name'] = array('like', $search_data['clc_name'] . '%');
		}

		$data_cnt = $mod_table->where($where)->count('0');
		$page_obj = new \Home\Extend\Page\Page($data_cnt, 15);
		$page_html = $page_obj->show();
		$this->assign('_page_html', $page_html);
		$limit_beg = ($page_obj->firstRow < $data_cnt ? $page_obj->firstRow : 0);
		$data = $mod_table->where($where)->limit($limit_beg . ',' . $page_obj->listRows)->order($mod_table->getPK() . ' desc')->select();
		$table_html = $table_obj->getHtml($data);
		$this->assign('table_html', $table_html);
		$_curlocal = \Home\Extend\AdminMenu\AdminMenu::getOne('23');
		$this->assign('_curlocal', $_curlocal);
		$this->assign('zd_list', $zd_list);
		$this->display();
	}

	public function add()
	{
		$table_obj = new \Home\Extend\TableTool\TableTool($this->getTableCnf());
		$mod_table = $table_obj->getDb();
		$valid_rule = $mod_table->getAddRule();

		if (!IS_POST) {
			$this->_common();
			$_curlocal = \Home\Extend\AdminMenu\AdminMenu::getOne('23');
			$_curlocal[] = array('name' => l('add_data'));
			$this->assign('_curlocal', $_curlocal);
			$this->assign('valid_rule', $valid_rule);
			$this->display('form');
		}
		else {
			$data = formatform($_POST, array('string' => 'title,author,type,picture,keyword,introduce'));
			$valid_obj = new \Home\Extend\FormValid\FormValid($data);

			if (!$valid_obj->valid($valid_rule)) {
				$this->error($valid_obj->getError());
				return NULL;
			}

			$data['joindate'] = time();
			$data['is_upload'] = 1;

			try {
				$mod_table->startTrans();
				$mod_table->disFields($data);
				$pkey_id = $mod_table->add($data);

				if ($pkey_id === false) {
					$mod_table->rollback();
					$this->error(l('add_data_error'));
					return NULL;
				}

				$is_success = $mod_table->addFile($pkey_id);

				if ($is_success === false) {
					$mod_table->rollback();
					$this->error(l('add_data_error') . ':' . $mod_table->addFile());
					return NULL;
				}

				$mod_table->commit();
				$this->success(l('add_data_ok'), u('Movie/index'));
				return NULL;
			}
			catch (Exception $e) {
				$mod_table->rollback();
				$this->error(l('add_data_exception'));
				return NULL;
			}
		}
	}

	public function edit()
	{
		$table_obj = new \Home\Extend\TableTool\TableTool($this->getTableCnf());
		$mod_table = $table_obj->getDb();
		$where = $table_obj->getPKWhere($_GET);
		$row_info = $mod_table->where($where)->find();
		if (!$row_info) {
			$this->error(l('server_not_row'));
			exit();
		}

		if (!IS_POST) {
			$this->_common();
			$file_list = $mod_table->getFiles($row_info[$mod_table->getPK()]);
			$this->assign('file_list', json_encode($file_list));
			$_curlocal = \Home\Extend\AdminMenu\AdminMenu::getOne('23');
			$_curlocal[] = array('name' => l('edit_data'));
			$this->assign('_curlocal', $_curlocal);
			$this->assign('row_info', $row_info);
			$this->assign('valid_rule', $mod_table->getEditRule());
			$this->display('form');
		}
		else {
			$data = formatform($_POST, array('string' => 'title,author,type,picture,keyword,introduce'));
			$valid_obj = new \Home\Extend\FormValid\FormValid($data);

			if (!$valid_obj->valid($mod_table->getEditRule())) {
				$this->error($valid_obj->getError());
				return NULL;
			}

			try {
				$mod_table->startTrans();
				$mod_table->disFields($data);
				$where = $table_obj->getPKWhere($_GET);
				$is_success = $mod_table->where($where)->save($data);

				if ($is_success === false) {
					$mod_table->rollback();
					$this->error(l('edit_data_error'));
					return NULL;
				}

				$is_success = $mod_table->saveFile($row_info[$mod_table->getPK()]);

				if ($is_success === false) {
					$mod_table->rollback();
					$this->error(l('edit_data_error') . ':' . $mod_table->addFile());
					return NULL;
				}

				$mod_table->commit();
				$this->success(l('edit_data_ok'), u('Movie/index'));
				return NULL;
			}
			catch (Exception $e) {
				$mod_table->rollback();
				$this->error(l('edit_data_exception'));
				return NULL;
			}
		}
	}

	public function drop()
	{
		$table_obj = new \Home\Extend\TableTool\TableTool($this->getTableCnf());
		$mod_table = $table_obj->getDb();
		$where = $table_obj->getPKWhere($_GET);
		$row_info = $mod_table->where($where)->find();

		if (!$row_info) {
			$this->error(l('server_not_row'));
			exit();
		}

		try {
			$mod_table->startTrans();
			$is_success = $mod_table->where($where)->delete();

			if ($is_success === false) {
				$mod_table->rollback();
				$this->error(l('drop_data_error'));
				return NULL;
			}

			$is_success = $mod_table->dropFile($row_info[$mod_table->getPK()]);

			if ($is_success === false) {
				$mod_table->rollback();
				$this->error(l('drop_data_error') . ':' . $mod_table->getError());
				return NULL;
			}

			$mod_table->commit();
			$this->success(l('drop_data_ok'), u('Movie/index'));
			return NULL;
		}
		catch (Exception $e) {
			$mod_table->rollback();
			$this->error(l('drop_data_exception'));
			return NULL;
		}
	}

	public function getTableCnf()
	{
		$mod_movie = d('Movie');
		return array(
	'actionPre' => CONTROLLER_NAME,
	'dbMod'     => $mod_movie,
	'isShowBtn' => false,
	'btnWidth'  => '9%',
	'zdShow'    => array('title.string.20%', 'clc_name.string.10%', 'keyword.string.20%', 'author.string.10%', 'joindate.date.10%')
	);
	}

	public function _common()
	{
		$class_list = getdata('admin.movie_clc');
		ksort($class_list);
		$this->assign('class_list', $class_list);
	}

	public function getzds()
	{
		return array('title' => l('title'), 'clc_name' => l('clc_name'), 'author' => l('author'), 'keyword' => l('keyword'));
	}

	public function clc()
	{
		if (!IS_POST) {
			$mod_movie = d('Movie');
			$data_list = $mod_movie->field('clc_name')->group('clc_name')->select();
			$not_clc_list = array();

			foreach ($data_list as $item) {
				$not_clc_list[] = $item['clc_name'];
			}

			$_curlocal = \Home\Extend\AdminMenu\AdminMenu::getOne('26');
			$this->assign('_curlocal', $_curlocal);
			$class_list = getdata('admin.movie_clc');
			ksort($class_list);
			$this->assign('not_clc_list', json_encode($not_clc_list));
			$this->assign('class_list', json_encode($class_list));
			$this->display();
		}
		else {
			$clc_list = array();

			foreach ($_POST['clc'] as $item) {
				$clc = strip_tags(stripslashes($item));

				if ($clc) {
					$clc_list[] = $clc;
				}
			}

			setdata('admin.movie_clc', $clc_list, true);
			$this->success(l('edit_data_ok'), u('Movie/clc'));
			return NULL;
		}
	}
}

?>
