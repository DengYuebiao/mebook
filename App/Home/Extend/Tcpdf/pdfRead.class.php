<?php
namespace Home\Extend\Tcpdf;
require("tcpdf.php");
require("tcpdi.php");
/*pdf阅读器类*/
class pdfRead extends \TCPDI
{
	
	function getOutline()
	{
		$outline_list=$this->current_parser->getOutlines();
		$outline_list=$this->formatOutlines($outline_list);
		return $outline_list;
	}
	
	/*
	格式化书签为父子层级关系
	@param 书签数据
	@return 格式化后的书签数据
	*/
	function formatOutlines($outline_list)
	{
		$outline_re=array();
		foreach($outline_list as $key=>$item)
		{
			$pid=$item["pid"];
			if(!isset($outline_list[$pid]))	  //如果父ID不存在则任认为是根目录
			{
				unset($item['pid']);
				$outline_re[$key]=$item;
				$child=$this->getOutlineChild($outline_list,$key);
				
				if(!empty($child))
				{
					$item["child"]=$child;
				}
				
				$outline_re[$key]=$item;
				unset($outline_list[$key]);
			}
		}
		
		return $outline_re;
	}
	
	/*
	获取某个书签的子书签
	@param array 书签列表数据
	@param int 需要获取子书签的书签ID
	@return 格式化后的书签数据
	*/
	function getOutlineChild(&$outline_list,$oid)
	{
		$outline_re=array();
		foreach($outline_list as $key=>$item)
		{
			$pid=$item["pid"];
			if($pid==$oid)	  //如果是子元素
			{
				unset($item['pid']);
				$child=$this->getOutlineChild($outline_list,$key);
				if(!empty($child))
				{
					$item["child"]=$child;
				}
				
				$outline_re[$key]=$item;
				unset($outline_list[$key]);
			}
			
		}
		
		return $outline_re;
	}
}