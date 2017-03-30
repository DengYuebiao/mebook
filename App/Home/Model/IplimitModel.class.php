<?php
namespace Home\Model;
class IplimitModel extends BaseModel {
	function getAddRule()
	{
		return array(
			"rules"=>array(
				"ip_name"=>array(
					"required"=>true,
					"rangelength"=>array(1,60)
				),
				"ip_beg"=>array(
					"required"=>true
				),
				"ip_end"=>array(
					"required"=>true
				)
			)
		);
	}
	
	
	function getEditRule()
	{
		return array(
			"rules"=>array(
				"ip_name"=>array(
					"required"=>true,
					"rangelength"=>array(1,60)
				),
				"ip_beg"=>array(
					"required"=>true
				),
				"ip_end"=>array(
					"required"=>true
				)
			)
		);
	}

	/*转换IP格式
	  @param string
	  @return int
	*/
	function formatIp($ip_str)
	{
		$ip_arr=explode(".",$ip_str);
		$ip_num=0;
		$ip_num+=isset($ip_arr[0])?$ip_arr[0]*16581375:0;
		$ip_num+=isset($ip_arr[1])?$ip_arr[1]*65025:0;
		$ip_num+=isset($ip_arr[2])?$ip_arr[2]*255:0;
		$ip_num+=isset($ip_arr[3])?$ip_arr[3]:0;
		return $ip_num;
	}
	
	/*
	判断用户当前IP是否在IP访问控制列表
	*/
	function isLimit()
	{
		$ip_addr=$_SERVER['REMOTE_ADDR'];
		$ip_num=$this->formatIp($ip_addr);
		$info=$this->where("is_close=0 AND {$ip_num} between ip_beg_val AND ip_end_val")->find();
		return !empty($info);
	}
}
