<?php
namespace Home\Model;
class BooklistModel extends BaseModel {
	function getAddRule()
	{
		return array(
			"rules"=>array(
				"list_name"=>array(
					"required"=>true,
					"rangelength"=>array(1,100)
				)
			)
		);
	}
	
	
	function getEditRule()
	{
		return array(
			"rules"=>array(
				"list_name"=>array(
					"required"=>true,
					"rangelength"=>array(1,100)
				)
			)
		);
	}

	function getSys()
	{
		$booklist_id=getOption("sys_booklist_id");
		if(!$booklist_id)
		{
			return array();
		}
		$booklist_info=$this->where("booklist_id={$booklist_id}")->find();
		return $booklist_info;
	}
	
	function getMap()
	{
		$list=$this->order("booklist_id desc")->select();
		$ret=array();
		foreach($list as $item)
		{
			$ret[$item['booklist_id']]=$item['list_name'];
		}
		return $ret;
	}
	
	function getBook($booklist_id,$param)
	{
		$mod_booklist_ext=D("BooklistExt");
		if(!$param['order'])
		{
			$param['order']="booklist_ext_id desc";
		}
		$list=$mod_booklist_ext->join("eb_book ON eb_booklist_ext.book_id=eb_book.book_id")->where("booklist_id={$booklist_id}")->limit($param['limit'])->order($param['order'])->select();
		return $list;
	}
	
	/*获取首页书单数据
	  @param int 书单ID
	  @return array
	*/
	function get_booklist($booklist_id,$param)
	{
		$info=$this->where("booklist_id={$booklist_id}")->find();
		
		$booklist=$this->getBook($booklist_id,$param);
		$mod_book=D("Book");
		foreach($booklist as $key=>$item)
		{
			$booklist[$key]['picture']=$mod_book->fixPic($item['picture']);
		}
		$info['booklist']=array_chunk($booklist,4);
		return $info;
	}
}
