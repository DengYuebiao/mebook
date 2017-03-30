<?php
namespace Home\Model;
class BookModel extends BaseModel {
	function getAddRule()
	{
		return array(
			"rules"=>array(
				"title"=>array(
					"required"=>true,
					"rangelength"=>array(1,200)
				),
				"pdf_addr"=>array(
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
	上传书目封面图片
	*/
	function uploadBookimg($file_data)
	{
		$file_dir='Public/upload/bookimg/'.date("Ymd")."/";
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
	
	/*
	上传书目PDF
	*/
	function uploadBookpdf($file_data)
	{
		$file_dir='Public/upload/bookpdf/'.date("Ymd")."/";
		$upload = new \Think\Upload();
		$upload->maxSize   =     2147483648 ;
		$upload->exts      =     array('jpg','gif','pdf','djvu','txt','iso','rar','ceb','chm','jeb','pdg','zip','htm','html','doc','docx','jar','epub','caj');
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
	上传数据库初始化XML
	*/
	function uploadInitData($file_data)
	{
		$file_dir='App/Runtime/Install/'.date("Ymd")."/";
		$upload = new \Think\Upload();
		$upload->maxSize   =     2147483648 ;
		$upload->exts      =     array('sql');
		$upload->autoSub   = 	 false;
		$upload->replace   = 	 true;
		$upload->rootPath  =     $file_dir;
		$upload->saveName  =	 "ebook_setup";
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
		
	function fixPic($pic_path)
	{
		$book_nopic='Public/image/book_nopic.gif';
		if(!$pic_path || $pic_path=="images/nopic.gif")
		{
			$pic_path=$book_nopic;
		}
		if(substr($pic_path,0,1)!="/")
		{
			$pic_path="/".$pic_path;
		}
		
		return $pic_path;
	}
	
	function getTopGood()
	{
		$data=$this->join("INNER JOIN (SELECT count(0) as cmt_cnt,book_id as book_id1 FROM eb_cmt INNER JOIN eb_cmt_ext ON eb_cmt.cmt_id=eb_cmt_ext.cmt_id where ctype=1 GROUP BY book_id)t1 on book_id=book_id1")->order("cmt_cnt desc")->limit("0,10")->select();  //获取好评榜
		$data=$this->getBuque($data,10);
		return $data;
	}
	
	function getTopRead()
	{
		$data=$this->order("readtimes desc")->limit("0,10")->select();  //获取阅读榜
		return $data;
	}
	
	function getTopSc()
	{
		
		$data=$this->join("INNER JOIN (SELECT count(0) as read_cnt,book_id as book_id1 FROM eb_rh GROUP BY book_id)t1 on book_id=book_id1")->order("read_cnt desc")->limit("0,10")->select();  //获取收藏榜
		$data=$this->getBuque($data,10);
		return $data;
	}
	
	function getBuque($data,$cnt)
	{
		$book_ids=array();
		foreach($data as $item)
		{
			$book_ids[]=$item['book_id'];
		}
		$where=$book_ids?array("book_id"=>array("not in",$book_ids)):array();
		$bu_cnt=$cnt-count($data);
		
		if(!$bu_cnt)
		{
			return $data;
		}
		$bu_data=$this->where($where)->order("readtimes")->limit("0,{$bu_cnt}")->select();
		$bu_data=$data?array_merge($data,$bu_data):$bu_data;
		return $bu_data;
	}
	
	//获取图书相关的数据
	function getBookAbout($book_info)
	{
		$data_list=array();
		
		$mod_user=D("User");
		$data_list['user']=$mod_user->join("eb_rh ON eb_user.user_id=eb_rh.user_id")->where("book_id={$book_info['book_id']}")->group("eb_rh.user_id")->limit("0,12")->select();  //获取谁阅读了这本书
		
		$mod_rh=D("Rh");
		$data_list['read']=$this->table("eb_book INNER JOIN (SELECT book_id,count(rh_id) rh_cnt,max(add_time) add_time from eb_rh GROUP BY book_id ORDER BY add_time DESC,rh_cnt DESC limit 0,200)eb_rh ON eb_book.book_id=eb_rh.book_id")->field("eb_book.*")->group("eb_book.book_id")->order("eb_rh.add_time desc,rh_cnt desc")->limit("0,10")->select();  //获取阅读榜 

		return $data_list;
	}
	
	function getRecommend()
	{
		$data_list=$this->where("picture!='images/nopic.gif' AND picture!=''")->limit("0,1000")->select();
		$list=array();
		for($i=0;$i<12;$i++)
		{
			$index=array_rand($data_list);
			$data_list[$index]['picture']=$this->fixPic($data_list[$index]['picture']);
			$list[]=$data_list[$index];
			unset($data_list[$index]);
		}
		
		return $list;
	}
	
	function checkDayLimit()
	{
		$is_not_out=true;
		
		$ip_addr=$_SERVER['REMOTE_ADDR'];	
		$cache = \Think\Cache::getInstance('File',array("expire"=>86400));
		$key=$ip_addr.date("Ymd");
		
		$cnt=$cache->get($key);
		if($cnt>=100)
		{
			$is_not_out=false;
		}
		else
		{
			$cnt=$cnt+1;
			$cache->set($key,$cnt);
		}
		
		return $is_not_out;
	}
	
	function getListBySubject($ztc)
	{
		$ztc_list=preg_split('/-|\040|一|－/',$ztc);
		$ztc=$ztc_list?$ztc_list[0]:$ztc;

		$data_list=$this->where("picture!='images/nopic.gif' AND picture!='' AND ztc like '{$ztc}%'")->limit("0,200")->select();
		
		$list=array();
		for($i=0;$i<8;$i++)
		{
			$index=array_rand($data_list);
			$data_list[$index]['picture']=$this->fixPic($data_list[$index]['picture']);
			$list[]=$data_list[$index];
			unset($data_list[$index]);
		}
		
		if(count($list)!=8)
		{
			$data_list=$this->where("picture!='images/nopic.gif' AND picture!=''")->limit("0,1000")->select();
			
		}
		return $list;
	}
}
