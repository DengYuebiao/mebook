<?php 
namespace Home\Controller;
class InstallController extends BaseController {
	function check_install()
	{
		if(file_exists(APP_PATH."Runtime/Install/install.lock"))  //如果不存在安装锁定文件 跳转到安装模块
		{
			$this->error("系统已经安装,请勿重新安装",'/Index/index');
			return;
		}
	}
	
    public function index(){
	  	  
		$this->assign("PageTitle","{$this->_sys['cp_name']}安装向导");   
	    $this->check_install();
		$check_list=$this->check_env();
		$this->assign("check_list",$check_list);
		$check_access_list=$this->check_access();
		$this->assign("check_access_list",$check_access_list);
		
		$mysql_pwd_path=APP_PATH."Runtime/Install/inst_pwd.php";
		$mysql_pwd=file_exists($mysql_pwd_path)?include($mysql_pwd_path):"123456";
		$this->assign("mysql_pwd",$mysql_pwd);
		
		$this->display();	
    }

	
	function check_env()
	{
		$mod_model=new \Think\Model();
		$check_list=array();
		
		$check_item=array(
			"desc"=>"PHP版本",
			"must"=>">=5.3",
			"is_ok"=>false,
			"curr"=>""
		);
		$ver_info=phpversion();
		$check_item['curr']=$ver_info;
		$check_item['is_ok']=$check_item['curr']>="5.3"?true:false;
		
		$check_list[]=$check_item;
		 
		$check_item=array(
			"desc"=>"PHP扩展-GD",
			"must"=>">=2.0",
			"is_ok"=>false,
			"curr"=>""
		);
		if(function_exists("gd_info"))
		{
			$ver_info=gd_info();
			$check_item['curr']=$ver_info['GD Version'];
			$check_item['is_ok']=$check_item['curr']>="bundled (2"?true:false;
		}
		else
		{
			$check_item['curr']="未安装GD库";
		}

		
		$check_list[]=$check_item;
		
		$check_item=array(
			"desc"=>"PHP扩展-GD-freetype",
			"must"=>"支持",
			"is_ok"=>false,
			"curr"=>""
		);
		if(function_exists("gd_info"))
		{
			$ver_info=gd_info();
			$check_item['curr']=$ver_info['FreeType Linkage'];
			$check_item['is_ok']=$ver_info['FreeType Support']?true:false;
		}
		else
		{
			$check_item['curr']="未安装GD库";
		}

		
		$check_list[]=$check_item;
		
		$check_item=array(
			"desc"=>"PHP扩展-mbstring",
			"must"=>"安装",
			"is_ok"=>false,
			"curr"=>""
		);
		if(function_exists("mb_substr"))
		{
			$check_item['curr']="已安装";
			$check_item['is_ok']=true;
		}
		else
		{
			$check_item['curr']="未安装";
		}
		
		$check_list[]=$check_item;
		
		$check_item=array(
			"desc"=>"PHP扩展-mcrypt",
			"must"=>"安装",
			"is_ok"=>false,
			"curr"=>""
		);
		if(function_exists("mcrypt_create_iv"))
		{
			$check_item['curr']="已安装";
			$check_item['is_ok']=true;
		}
		else
		{
			$check_item['curr']=false;
		}
		
		$check_list[]=$check_item;
		
		return $check_list;
	}
	
	//文件权限检测
	function check_access()
	{
		$check_list=array();
		
		$check_item=array(
			"desc"=>"./App/Runtime",
			"must"=>"可写",
			"is_ok"=>false,
			"curr"=>""
		);

		$file_path=$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."App".DIRECTORY_SEPARATOR."Runtime";
		if($this->check_dir_write($file_path))
		{
			$check_item['curr']="可写";
			$check_item['is_ok']=true;
		}
		else
		{
			$check_item['curr']="不可写";
		}
		$check_list[]=$check_item;
		
		$check_item=array(
			"desc"=>"./Public/upload",
			"must"=>"可写",
			"is_ok"=>false,
			"curr"=>""
		);
		$file_path=$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."Public".DIRECTORY_SEPARATOR."upload";
		if($this->check_dir_write($file_path))
		{
			$check_item['curr']="可写";
			$check_item['is_ok']=true;
		}
		else
		{
			$check_item['curr']="不可写";
		}
		$check_list[]=$check_item;
		
		return $check_list;
	}
	
	//检测dir是否可写
	function check_dir_write($dir_path)
	{
		if(!file_exists($dir_path))
		{
			return false;
		}
		
		return is_writable($dir_path);
	}
	
	//检测db连接,并检测数据库是否存在,存在的话返回失败
	function check_db_conn()
	{
		$db_host=isset($_POST['db_host'])?trim($_POST['db_host']):"";
		$db_port=isset($_POST['db_port'])?trim($_POST['db_port']):"";
		$db_user=isset($_POST['db_user'])?trim($_POST['db_user']):"";
		$db_pass=isset($_POST['db_pass'])?trim($_POST['db_pass']):"";
		$db_name=isset($_POST['db_name'])?trim($_POST['db_name']):"";
		
		/* 连接数据库 */
        $con = @mysql_connect($db_host . ':' . $db_port, $db_user, $db_pass);
		if (!$con)
        {
            $this->error("数据库连接失败:请设置正确的数据库连接信息","",true);
            return false;
        }
		
		$is_success=@mysql_select_db($db_name,$con);
		if ($is_success==true)
        {
            $this->error("当前数据库名称已经存在,请更换数据库名称","",true);
            return false;
        }
		
		$this->success("检测通过","",true);
	}
	
	//安装的一些操作
	function install_beg()
	{
		try{
			
		$this->check_install();
		
		$db_host=isset($_POST['db_host'])?trim($_POST['db_host']):"";
		$db_port=isset($_POST['db_port'])?trim($_POST['db_port']):"";
		$db_user=isset($_POST['db_user'])?trim($_POST['db_user']):"";
		$db_pass=isset($_POST['db_pass'])?trim($_POST['db_pass']):"";
		$db_name=isset($_POST['db_name'])?trim($_POST['db_name']):"";
		$admin_pwd1=isset($_POST['admin_pwd1'])?trim($_POST['admin_pwd1']):"";
		$sys_reg_name=isset($_POST['sys_reg_name'])?trim($_POST['sys_reg_name']):"";
		
		/* 连接数据库 */
        $con = @mysql_connect($db_host . ':' . $db_port, $db_user, $db_pass);
		if (!$con)
        {
            $this->error("数据库连接失败:请设置正确的数据库连接信息","");
            return false;
        }
		
		$is_success=@mysql_select_db($db_name,$con);
		if ($is_success==true)
        {
            $this->error("当前数据库名称已经存在,请更换数据库名称","");
            return false;
        }
		
		$sql = 'CREATE DATABASE `'.$db_name.'` /*!40100 DEFAULT CHARACTER SET utf8 */;';
		$is_success=mysql_query($sql, $con);
		if (!$is_success) {
			 $this->error("创建数据库失败:". mysql_error(),"");
            return false;
		}
		
		$is_success=@mysql_select_db($db_name,$con);
		if ($is_success==false)
        {
            $this->error("选择目标数据库【{$db_name}】失败".mysql_error(),"");
            return false;
        }
		
		//更新config文件
		$cnf_path=APP_PATH.'Home/Conf/config.php';
		$config_arr=include($cnf_path);
		
		$config_arr['DB_HOST']=$db_host;
		$config_arr['DB_NAME']=$db_name;
		$config_arr['DB_USER']=$db_user;
		$config_arr['DB_PWD']=$db_pass;
		$config_arr['DB_PORT']=$db_port;
		foreach($config_arr as $key=>$item)
		{
			C($key,$item);
		}
		
		file_put_contents($cnf_path, "<?php \nreturn " . var_export($config_arr , true) . ";\n?>");
		
		
		$mod_book=D("Book");
		$init_info=$mod_book->uploadInitData($_FILES['init_data']);

		if($init_info===false)
		{
			$this->error("上传初始化数据库失败".$mod_book->getError(),"");
			return false;
		}
		else
		{
			$init_path=$init_info['path'];
			import("@.Extend.MysqlDump.MysqlDump");
			$mysql_dump=new \Home\Extend\MysqlDump\MysqlDump();
			if(!$mysql_dump->revert($db_name,realpath($init_path)))
			{
				$this->error("初始化数据库失败:".$mysql_dump->getError(),"");
				return false;
			}
		}
		
		//更新管理员密码
		$mod_user=D("User");
		$user_data=array(
			"user_name"=>"admin",
			"real_name"=>"超级管理员",
			"sex"=>"男",
			"user_pwd"=>md5($admin_pwd1),
			"is_admin"=>1,
			"portrait"=>$mod_user->getRandPort(),
			"is_verify"=>1,
			"add_time"=>time()
		);
		$user_id=$mod_user->add($user_data);
		if ($user_id===false)
        {
            $this->error("增加超级管理员用户失败","");
            return false;
        }
		
		setOption("sys_reg_name",$sys_reg_name);
		
		$sys_res_path=APP_PATH."Runtime/Install/inst_respath.php";
		if(file_exists($sys_res_path))
		{
			$sys_book_path=include($sys_res_path);
			setOption("sys_book_path",$sys_book_path);		
		}
		
		$run_file=FPATH("{$_SERVER['DOCUMENT_ROOT']}/App/Runtime/common~runtime.php");
		unlink($run_file);
		
		$dog_cache_file=FPATH("{$_SERVER['DOCUMENT_ROOT']}/App/Home/Data/admin/cache.php");
		unlink($dog_cache_file);
			
		//写入安装锁定文件
		file_put_contents(APP_PATH."Runtime/Install/install.lock",date("Y-m-d H:i:s"));
		$this->success("系统安装成功",U("Install/setup_ok"));
		
		}
		catch(Exception $e)
		{
			$this->error("系统安装出现异常:".$e->getMessage(),"");
            return false;
		}
	}
	
	function setup_ok()
	{
		
		$this->display();	
	}
}	