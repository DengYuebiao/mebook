<?php
namespace Home\Model;
class SearchModel extends BaseModel {
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
}
