<?php
namespace Home\Extend\MysqlDump;
//mysql备份和还原命令
class MysqlDump
{
	public $isWin=false;
	public $error="";
	
	function __construct()
	{
		$this->isWin = (DIRECTORY_SEPARATOR=='\\')?true:false;
		
	}
	
	/*
	获取mysqldump程序的路径
	@return false|路径信息
	*/
	function getMysqlDumpPath()
	{
		$model=new Model();
		$dir_info=$model->db()->query("show variables like 'basedir'");
		if($dir_info==false)
		{
			$this->error="查询路径失败".$model->db()->getError();
			return false;
		}
		$path_info=$dir_info[0]['Value'].($this->isWin?"":DIRECTORY_SEPARATOR)."bin".DIRECTORY_SEPARATOR.($this->isWin?"mysqldump.exe":"mysqldump");
		
		if(!file_exists($path_info))
		{
			$this->error="mysql程序目录中不存在mysqldump程序";
			return false;
		}
		return $path_info;
	}
	
	/*
	获取mysql程序的路径
	@return false|路径信息
	*/
	function getMysqlPath()
	{
		$model=new \Think\Model();
		$dir_info=$model->db()->query("show variables like 'basedir'");
		if($dir_info==false)
		{
			$this->error="查询路径失败".$model->db()->getError();
			return false;
		}
		$path_info=$dir_info[0]['Value'].($this->isWin?"":DIRECTORY_SEPARATOR)."bin".DIRECTORY_SEPARATOR.($this->isWin?"mysql.exe":"mysql");

		if(!file_exists($path_info))
		{
			$this->error="mysql程序目录中不存在mysql程序";
			return false;
		}
		return $path_info;
	}
	
	
	/*
	*	备份数据库
	*	@param string 备份的数据库名称
	*	@param string 备份的文件路径
	*	@return bool 网站是否备份成功
	*/
	function backup($db_name,$back_path)
	{
		$mysql_path=$this->getMysqlDumpPath();
		if($mysql_path===false)
		{
			$this->error="获取mysqldump路径失败:".$this->error;
			return false;
		}
		
		if(!file_exists(dirname($back_path)))
		{
			$this->error="数据备份目录不存在{$back_path}";
			return false;
		}
		
		$this->linkMysqlInLinux();
		
		$db_host=C("DB_HOST");
		$db_user=C("DB_USER");
		$db_pwd=C("DB_PWD");
		$db_port=C("DB_PORT");
		$pwd=$db_pwd?"-p{$db_pwd}":"";
		$cmd='"'.$mysql_path.'"'." -u{$db_user} {$pwd} -P{$db_port}  --comments=FALSE --triggers=FALSE {$db_name}> \"{$back_path}\"";
		exec($cmd,$cmd_info);
		return true;
	}
	
	/*
	*	备份数据库
	*	@param string 列表需要的索引字段
	*/
	function revert($db_name,$file_path)
	{
		$mysql_path=$this->getMysqlPath();
		if($mysql_path===false)
		{
			$this->error="获取mysql路径失败:".$this->error;
			return false;
		}
		
		if(!file_exists($file_path))
		{
			$this->error="导入文件不存在{$file_path}";
			return false;
		}
		
		$this->linkMysqlInLinux();
		
		$db_host=C("DB_HOST");
		$db_user=C("DB_USER");
		$db_pwd=C("DB_PWD");
		$db_port=C("DB_PORT");
		$pwd=$db_pwd?"-p{$db_pwd}":"";
		$cmd='"'.$mysql_path.'"'." -u{$db_user} {$pwd} -P{$db_port} -D{$db_name}< \"{$file_path}\"";
		exec($cmd,$cmd_info);
		return true;
	}

	function getError()
	{		
		return $this->error;
	}
	
	//在linux中链接mysql
	function linkMysqlInLinux()
	{
		if(!file_exists("/tmp/mysql.sock") && DIRECTORY_SEPARATOR!='\\')
		{
			exec("ln -s /var/lib/mysql/mysql.sock /tmp/mysql.sock");
		}
	}
}