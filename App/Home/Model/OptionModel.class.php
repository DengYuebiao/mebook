<?php
namespace Home\Model;
class OptionModel extends BaseModel {
	
	/*
	上传网站LOGO
	*/
	function uploadLogo($file_data)
	{
		$file_dir='Public/upload/common/';
		$upload = new \Think\Upload();
		$upload->maxSize   =     2097152 ;
		$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
		$upload->autoSub   = 	 false;
		$upload->rootPath  =     $file_dir;
		if(!file_exists($upload->rootPath))
		{
			mkdir($upload->rootPath,0777);
		}
		$upload->savePath  =     '';
		$info   =   $upload->uploadOne($file_data);
		if(!$info)
		{
			$this->error=$upload->getError();
			return false;
		}
		else
		{
			$ret_arr=array(
				'path'=>$file_dir.$info['savename'],
				'ext'=>$info['ext']
			);
			return $ret_arr;
		}
	}
}
