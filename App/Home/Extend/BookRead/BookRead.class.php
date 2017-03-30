<?php
namespace Home\Extend\BookRead;

final class BookRead{
	private $formData=NULL;	//要验证的表单数据源
	private $fieldsMap=NULL;	//要验证的表单数据源
	private $error="";		//错误消息	
	/*
	@param array 要验证的表单数据源
	@param array 表单字段名称映射,用于提示错误消息用
	*/
	function __construct($data,$fieldMap=array())
	{
		$this->formData=$data;
		$this->fieldMap=$fieldMap;
	}
	
	
	/*
	表单验证方法,验证表单数据是否通过相关的规则
	@param array 表单数据源
	@param array 验证规则
	@return bool false时用getError获取错误信息
	*/
	function valid($rules)
	{
		if($rules['rules'])
		{
			foreach($rules['rules'] as $field=>$rule_arr)
			{
				foreach($rule_arr as $rule_type=>$rule_val)
				{
					if(!$this->isValid($this->formData[$field],$rule_type,$rule_val))
					{
						
						$this->error=$rules['messages'][$field][$rule_type]?$rules['messages'][$field][$rule_type]:$this->getDefaultErrMsg($field,$rule_type,$rule_val);
						
						return false;
					}
				}
			}
		}
		return true;
	}

	/*
	判断是否通过验证规则
	@param array 数据值
	@param array 验证类型
	@param array 验证规则值
	@return bool   false为未通过验证
	*/
	private function isValid($val,$rule_type,$rule_val)
	{
		switch($rule_type)
		{
			case "required":
				if($rule_val)
				{
					return !empty($val);
				}
			break;
			case "email":
				if($val && $rule_val) //当值不为空时验证
				{
					return filter_var($val,FILTER_VALIDATE_EMAIL)!==false;
				}
			break;
			case "url":
				if($val && $rule_val) //当值不为空时验证
				{
					return preg_match('/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/',$val)>0;
				}
			break;
			case "date":	//因为格式化数据时自动转换了,所以不验证日期格式
				return true;
			break;
			case "number":
				if($val && $rule_val) //当值不为空时验证
				{
					return is_numeric($val);
				}
			break;
			case "digits":		#必须输入整数
				if($val && $rule_val) //当值不为空时验证
				{
					return is_int($val);
				}
			break;
			case "creditcard":		#信用卡号不验证
				return true;
			break;
			case "equalTo":
				if(substr($rule_val,0,1)=="#")
				{
					return $val==$this->formData[substr($rule_val,1)];
				}
				else
				{
					return $val==$rule_val;
				}
				if($val && $rule_val) //当值不为空时验证
				{
					return filter_var($val,FILTER_VALIDATE_EMAIL)!==false;
				}
			break;
			case "maxlength":
				$val_len=utf8_strlen($val);
				return $val_len<=$rule_val;
			break;
			case "minlength":
				$val_len=utf8_strlen($val);
				return $val_len>=$rule_val;
			break;
			case "rangelength":
				$val_len=utf8_strlen($val);
				return $val_len>=$rule_val[0] && $val_len<=$rule_val[1];
			break;
			case "range":
				return $val>=$rule_val[0] && $val<=$rule_val[1];
			break;
			case "max":
				return $val<=$rule_val;
			break;
			case "min":
				return $val>=$rule_val;
			break;
			default:
				return true;
		}
		return true;
	}
	
	/*
	表单验证方法,验证表单数据是否通过相关的规则
	@param array 表单数据源
	@param array 验证规则
	@return true|array 返回true或者错误的数组
	*/
	function getError()
	{
		return $this->error;
	}
	
	/*
	获取默认错误消息
	@param array 数据值
	@param array 验证类型
	@param array 验证规则值
	@return string   false为未通过验证
	*/
	private function getDefaultErrMsg($field,$rule_type,$rule_val)
	{
		$field_name=$this->getFieldName($field);		
		
		switch($rule_type)
		{
			case "required":
				$err_msg="{$field_name}不能为空";
			break;
			case "email":
				$err_msg="{$field_name}非有效的Email格式";
			break;
			case "url":
				$err_msg="{$field_name}非有效的URL格式";
			break;
			case "date":	
				$err_msg="{$field_name}非有效的日期格式";
			break;
			case "number":
				$err_msg="{$field_name}非有效的数字格式";
			break;
			case "digits":		
				$err_msg="{$field_name}非有效的整数格式";
			break;
			case "creditcard":		
				$err_msg="{$field_name}非有效的信用卡格式";
			break;
			case "equalTo":
				$map_zd=$this->getFieldName(substr($rule_val,1));
				$err_msg="{$field_name}必须等于{$map_zd}";
			break;
			case "maxlength":
				$err_msg="{$field_name}长度不能大于{$rule_val}";
			break;
			case "minlength":
				$err_msg="{$field_name}长度不能小于{$rule_val}";
			break;
			case "rangelength":
				$err_msg="{$field_name}长度范围:".$rule_val[0]."-".$rule_val[1];
			break;
			case "range":
				$err_msg="{$field_name}值范围:".$rule_val[0]."-".$rule_val[1];
			break;
			case "max":
				$err_msg="{$field_name}最大值为:{$rule_val}";
			break;
			case "min":
				$err_msg="{$field_name}最小值为:{$rule_val}";
			break;
			default:
				$err_msg="{$field_name}值验证错误";
		}
		
		return $err_msg;
	}
	
	/*
	或者字段名称,自动翻译
	@param string 字段名称
	@return string 翻译后的字段名称
	*/
	private function getFieldName($field)
	{
		if(isset($this->fieldsMap[$field]))		//如果设置了字段映射
		{
			return $this->fieldsMap[$field];
		}
		elseif(strtoupper(L($field))!=strtoupper($field))		//或者语言项存在对应的项目
		{
			return  L($field);
		}
		else
		{
			return $field;
		}
	}
}