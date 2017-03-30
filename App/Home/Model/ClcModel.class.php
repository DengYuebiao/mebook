<?php
namespace Home\Model;
class ClcModel extends BaseModel {
	//获取顶层分类号列表
	function getTopClcList()
	{
		return $this->where("lay=0")->order("order_num,clc_id")->select();
	}	
	
	//获取分类的子分类
	function getChild($clc_id)
	{
		$clc_list=$this->where("pid={$clc_id}")->order("order_num,clc_id")->select();
		return $clc_list;
	}
	
	
	function dropClc($clc_id_list)
	{
		foreach($clc_id_list as $clc_id)
		{
			$is_success=$this->where("clc_id={$clc_id}")->delete();
			if($is_success===false)
			{
				$this->error=L('drop_data_error');
				return false;
			}
			$list=$this->where("pid={$clc_id}")->select();
			if($list)
			{
				$list_ids=array();
				foreach($list as $item)
				{
					$list_ids[]=$item['clc_id'];
				}
				$this->dropClc($list_ids);
			}
		}
	}
}
