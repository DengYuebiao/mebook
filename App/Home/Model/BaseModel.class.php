<?php
namespace Home\Model;
use Think\Model;
class BaseModel extends Model {
	
	/*处理数据字段过滤,避免插入不存在的字段*/
	function disFields(&$data)
	{
		foreach ($data as $key=>$val){
			if(!in_array($key,$this->fields))
			{
				unset($data[$key]);
			}
        }
	}
	
	/*获取字段类型数组*/
	function getFieldType()
	{
		$arr=array();
		foreach ($this->fields['_type'] as $key=>$item){
			$arr[$key]=$item;
        }
		return $arr;
	}
}
