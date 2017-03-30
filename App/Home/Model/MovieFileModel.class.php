<?php
namespace Home\Model;
class MovieFileModel extends BaseModel {
	const FILE_TYPE_FILE=1;	  //文件类型-文件	
	const FILE_TYPE_URL=2;	  //文件类型-url	
	
		
	/*
	上传视频
	*/
	function uploadVideo($file_data)
	{
		$file_dir='Public/upload/video/'.date("Ymd")."/";
		$upload = new \Think\Upload();
		$upload->maxSize   =     2147483648 ;
		$upload->exts      =     array('rm', 'rmvb', 'ram', 'rmx', 'mp4', 'm4a', 'mpg', 'wmv', 'avi', 'mpeg', 'aac', 'asf', 'dat', 'mkv', 'mov', '3gp', 'flv', 'swf');
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
	从post获取并增加数据
	@return bool
	*/
	function addData($movie_id)
	{
		$file_data=array();
		foreach($_POST['file_type'] as $key=>$item)
		{
			$file=array(
				'movie_id'=>$movie_id,
				'is_upload'=>1,
				'file_type'=>intval($item),
				'diversity'=>isset($_POST['diversity'][$key])?trim($_POST['diversity'][$key]):"",
				'path'=>isset($_POST['path'][$key])?trim($_POST['path'][$key]):""
			);
			
			//当类型为上传文件时
			if($file['file_type']==self::FILE_TYPE_FILE)
			{
				$mvfile_name=isset($_POST['mfname'][$key])?trim($_POST['mfname'][$key]):"";
				if(!empty($_FILES[$mvfile_name]['tmp_name']))
				{
					$ret_info=$this->uploadVideo($_FILES[$mvfile_name]);	//如果上传文件失败则返回
					if($ret_info===false)
					{
						return false;
					}
					$file['path']=$ret_info['path'];
					$file_data[]=$file;
				}
			}
			else
			{
				if($file['path'])
				{
					$file_data[]=$file;	
				}
			}
		}
		$is_success=$this->addall($file_data);
		if($is_success===false)
		{
			$this->error=L('add_mvfile_fail');
		}
		return $is_success;
	}
	
	/*
	从post获取并保存数据
	@return bool
	*/
	function saveData($movie_id)
	{
		$mid_list=array();
		$file_data=array();
		foreach($_POST['file_type'] as $key=>$item)
		{
			$file=array(
				'file_type'=>intval($item),
				'movie_id'=>$movie_id,
				'diversity'=>isset($_POST['diversity'][$key])?trim($_POST['diversity'][$key]):"",
				'path'=>isset($_POST['path'][$key])?trim($_POST['path'][$key]):""
			);
			
			$mid=isset($_POST['mid'][$key])?trim($_POST['mid'][$key]):0;
			$mid_list[]=$mid;
			//当类型为上传文件时
			if($file['file_type']==self::FILE_TYPE_FILE)
			{
				$mvfile_name=isset($_POST['mfname'][$key])?trim($_POST['mfname'][$key]):"";
				if(!empty($_FILES[$mvfile_name]['tmp_name']))
				{
					$ret_info=$this->uploadVideo($_FILES[$mvfile_name]);	//如果上传文件失败则返回
					if($ret_info===false)
					{
						return false;
					}
					$file['path']=$ret_info['path'];
				}
				else
				{
					unset($file['path']);
				}
			}
			
			//如果存在主键id则保存 ，否则放入新增列表
			if($mid)
			{
				$is_success=$this->where("movie_file_id='{$mid}'")->save($file);
				if($is_success===false)
				{
					$this->error=L('save_mvfile_fail');
					return false;
				}
			}
			else
			{
				if($file['path'])
				{
					$file_data[]=$file;
				}
			}

		}

		if($file_data)
		{
			$is_success=$this->addall($file_data);
			if($is_success===false)
			{
				$this->error=L('save_mvfile_fail');
				return false;
			}
		}

		//$drop_list=$this->where(array('movie_id'=>$movie_id,'movie_file_id'=>array('not in',$mid_list)))->select();
		/*foreach($drop_list as $item)
		{
			if($item['file_type']==self::FILE_TYPE_FILE)
			{
				@unlink($item['path']);
			}
			$is_success=$this->where("movie_file_id='{$item['movie_file_id']}'")->delete();
			if($is_success===false)
			{
				$this->error=L('del_mvfile_fail');
				return false;
			}
		}*/

		return true;
	}
	
	/*
	删除数据
	@return bool
	*/
	function dropData($movie_id)
	{
		$file_list=$this->where("movie_id='{$movie_id}'")->select();
		$book_base_path=getOption("sys_book_path");
		
		foreach($file_list as $item)
		{
			if($item['file_type']==self::FILE_TYPE_FILE)
			{
				if($item['is_upload']==0)
				{
					@unlink($book_base_path.DIRECTORY_SEPARATOR.$item['path']);
				}
				else
				{
					@unlink($item['path']);
				}
			}
			
		}
		$is_success=$this->where("movie_id='{$movie_id}'")->delete();
		if($is_success===false)
		{
			$this->error=L('drop_file_data_fail');
		}
		return $is_success;
	}
}
