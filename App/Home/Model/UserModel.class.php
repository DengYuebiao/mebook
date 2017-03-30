<?php
namespace Home\Model;
class UserModel extends BaseModel {
	function getAddRule()
	{
		return array(
			"rules"=>array(
				"user_name"=>array(
					"required"=>true,
					"rangelength"=>array(3,20)
				),
				"real_name"=>array(
					"required"=>true,
					"rangelength"=>array(1,50)
				),
				"user_pwd"=>array(
					"required"=>true
				),
				"user_pwd2"=>array(
					"required"=>true,
					"equalTo"=>"#user_pwd"
				),
				"email"=>array(
					"email"=>true
				)
			),
			"messages"=>array(
				"user_name"=>array(
					
				),
				"user_pwd2"=>array(
					"equalTo"=>L('user_pwd_not_eq')
				)
			)
		);
	}
	
	function getEditRule()
	{
		return array(
			"rules"=>array(
				"user_name"=>array(
					"rangelength"=>array(3,20)
				),
				"real_name"=>array(
					"required"=>true,
					"rangelength"=>array(1,50)
				),
				"user_pwd2"=>array(
					"equalTo"=>"#user_pwd"
				),
				"email"=>array(
					"email"=>true
				)
			),
			"messages"=>array(
				"user_name"=>array(
					
				),
				"user_pwd2"=>array(
					"equalTo"=>L('user_pwd_not_eq')
				)
			)
		);
	}
	
	function getLoginRule()
	{
		return array(
			"rules"=>array(
				"user_name"=>array(
					"required"=>true,
					"rangelength"=>array(3,20)
				),
				"user_pwd"=>array(
					"required"=>true
				)
			),
			"messages"=>array(
				"user_name"=>array(
					
				),
				"user_pwd"=>array(
				)
			)
		);
	}
	
	function addUser($data)
	{
		$tmp_info=$this->where("user_name='{$data['user_name']}'")->find();
		if($tmp_info)
		{
			$this->error=L("user_repeat");
			return false;
		}
		$data["add_time"]=time();
		$data["user_pwd"]=md5($data["user_pwd"]);
		$data["portrait"]=$this->getRandPort();
		$this->disFields($data);
		$is_success=$this->add($data);
		return $is_success;
	}
	
	function getPortList()
	{
		$base_dir="Public/image/portrait/";
		$list=array();
		for($i=1;$i<85;$i++)
		{
			$name=str_repeat("0",3-strlen($i)).$i;
			$list[]="{$base_dir}{$name}.jpg";
		}
		return $list;
	}
	
	/*
	上传书目封面图片
	*/
	function upUserPort($file_data)
	{
		$file_dir='Public/upload/portrait/'.date("Ymd")."/";
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
			$file_path=$file_dir.$info['savename'];
			$image = new \Think\Image(\Think\Image::IMAGE_GD,$file_path); 
			$image->thumb(48, 48,\Think\Image::IMAGE_THUMB_FIXED)->save($file_path);
			$ret_arr=array(
				'path'=>$file_path,
				'ext'=>$info['ext']
			);
			return $ret_arr;
		}
	}
	
	function getRandPort()
	{
		$list=$this->getPortList();
		$port=$list[array_rand($list)];
		return $port;
	}
	
	/*
	获取用户相关的统计数据
	@param int 
	@return array
	*/
	function getExtSum($user_id)
	{
		$sum_list=array();
		$mod_bookmark=D("Bookmark");
		$mod_cmt=D("Cmt");
		$mod_rh=D("Rh");
		$mod_search=D("Search");
		
		$sum_list['bookmark_cnt']=$mod_bookmark->where("user_id={$user_id}")->count();
		$sum_list['cmt_cnt']=$mod_cmt->where("user_id={$user_id}")->count();
		$sum_list['rh_cnt']=$mod_rh->where("user_id={$user_id}")->count();
		$sum_list['sh_cnt']=$mod_search->where("user_id={$user_id}")->count();
		return $sum_list;
	}
	
	/*
	获取活跃用户
	@return array
	*/
	function getActiveUser()
	{
		$list=$this->table('eb_user INNER JOIN eb_rh ON eb_user.user_id=eb_rh.user_id')->field("eb_user.user_id,user_name,portrait,count(book_id) as book_cnt")->group("eb_user.user_id")->order("book_cnt desc")->limit("0,12")->select();
		return $list;
		
	}
	
	/*
	获取最近检索的用户
	@return array
	*/
	function getSearchUser()
	{
		$list=$this->table('eb_user INNER JOIN eb_search ON eb_user.user_id=eb_search.user_id')->field("eb_user.user_id,user_name,portrait,count(search_id) as search_cnt")->group("eb_user.user_id")->order("search_cnt desc")->limit("0,12")->select();
		return $list;
		
	}
	
	/*
	获取字符串中的非法词
	@param string
	@return string|false
	*/
	function hasFilterWord($str)
	{
		$sys_filter_word=getOption("sys_filter_word");
		//$filter=explode("|",$str);
		$match=array();
		$cnt=preg_match("/{$sys_filter_word}/",$str,$match);
		$is_filter=$cnt?$match[0]:false;
		return $is_filter;
	}
	
	function filterWord()
	{
		$sys_filter_word=getOption("sys_filter_word");
	}
}
