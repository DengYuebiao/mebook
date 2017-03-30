<?php

	/*
	HTML头部缓冲函数,压入或者输出全部
	@param string 操作类型 push pop
	@param string | array JS或CSS引用字符串
	@return string
	*/
	function HtmlHeadBuff($type,$val="")
	{
		static $buff_arr;
		if($type=="push" && $val)
		{
			if(is_array($val))
			{
				$buff_arr=array_merge($buff_arr,$val);
			}
			else
			{
				$buff_arr[]=$val;
			}
			return "";
		}
		elseif($type=="pop")
		{
			$ret_str=implode("\r\n",$buff_arr);
			$buff_arr=array();
			return $ret_str;
		}
		else
		{
			return "";
		}
	}
	
	/*
	获取应用的配置项
	@param string 配置项目键值名
	@return string
	*/
	function getOption($option_name)
	{
		$mod_option=D("Option");
		$option_info=$mod_option->where("option_name='{$option_name}'")->find();
		return $option_info?$option_info["option_val"]:"";
	}
	
	/*
	获取应用的配置项多个
	@param string 配置项目键值名
	@return string
	*/
	function getOptions($option_name_list)
	{
		if(!is_array($option_name_list))
		{
			$option_name_list=explode(",",$option_name_list);
		}
		$mod_option=D("Option");
		$option_list=$mod_option->where(array("option_name"=>array("in",$option_name_list)))->select();
		$ret_arr=array();
		foreach($option_list as $item)
		{
			$ret_arr[$item['option_name']]=$item['option_val'];
		}
		return $ret_arr;
	}
	
	/*
	获取应用的配置项
	@param string 配置项目键值名
	@param string 配置项目键值
	@return bool
	*/
	function setOption($option_name,$option_val)
	{
		$mod_option=D("Option");
		$option_info=$mod_option->where("option_name='{$option_name}'")->find();
		if($option_info)
		{
			return $mod_option->where("option_name='{$option_name}'")->save(array("option_val"=>$option_val));
		}
		else
		{
			return $mod_option->add(array(
				"option_name"=>$option_name,
				"option_val"=>$option_val
			));
		}
	}
	
	/*
	根据格式获取格式化表单数据
	@param array 数据源
	@param array 数据格式
	@return array
	*/
	function formatForm($data,$format)
	{	
		$redata=array();
		
		foreach($format as $key=>$item)
		{
			$fields=is_string($item)?explode(",",$item):$item;
			foreach($fields as $field)
			{
				if($key=="string")
				{
					$redata[$field]=isset($data[$field])?trim($data[$field]):"";
				}
				elseif($key=="int")
				{
					$redata[$field]=isset($data[$field])?intval($data[$field]):0;
				}
				elseif($key=="float")
				{
					$redata[$field]=isset($data[$field])?floatval($data[$field]):0;
				}
				elseif($key=="date")
				{
					$redata[$field]=isset($data[$field])?strtotime(intval($data[$field])):0;
				}
				else
				{
					$redata[$field]=isset($data[$field])?trim($data[$field]):"";
				}
			}
		}

		return $redata;
	}
	
	/*
	获取中文字符串的长度
	*/
	function utf8_strlen($string = null) {
	  // 将字符串分解为单元
	  preg_match_all("/./us", $string, $match);
	  // 返回单元个数
	  return count($match[0]);
	}
	
	/*
	获取表单验证的令牌环
	@return array
	*/
	function getToken(){
        $tokenName  = C('TOKEN_NAME',null,'__hash__');
        $tokenType  = C('TOKEN_TYPE',null,'md5');
        if(!isset($_SESSION[$tokenName])) {
            $_SESSION[$tokenName]  = array();
        }
        // 标识当前页面唯一性
        $tokenKey   =  md5($_SERVER['REQUEST_URI']);
        if(isset($_SESSION[$tokenName][$tokenKey])) {// 相同页面不重复生成session
            $tokenValue = $_SESSION[$tokenName][$tokenKey];
        }else{
            $tokenValue = $tokenType(microtime(TRUE));
            $_SESSION[$tokenName][$tokenKey]   =  $tokenValue;
            if(IS_AJAX && C('TOKEN_RESET',null,true))
                header($tokenName.': '.$tokenKey.'_'.$tokenValue); //ajax需要获得这个header并替换页面中meta中的token值
        }
		/*
		echo $_SESSION[$tokenName][$tokenKey];
		echo "<br/>";
		echo $tokenValue;*/

        return array(
			'meta'=>'<meta name="'.$tokenName.'" content="'.$tokenKey.'_'.$tokenValue.'" />',
			'input'=>'<input type="hidden" name="'.$tokenName.'" value="'.$tokenKey.'_'.$tokenValue.'" />'
		); 
    }
	
	//获取数据  数据存在在/app/Home/Data下,参数为文件名,无需扩展名 数据文件为php文件 返回里面的数据
	function getData($file_name)
	{
		$file_name=strtolower($file_name);
		$file_path=MODULE_PATH.DIRECTORY_SEPARATOR."Data".DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR,explode(".",$file_name)).".php";
		if(empty($file_name) || !file_exists($file_path))
		{
			return array();
		}
		
		$data=require($file_path);
		return $data;
	}
	
	//保存数据  数据存在在/app/Home/Data下,参数为文件名,无需扩展名 数据文件为php文件 返回里面的数据
	function setData($file_name,$save_data,$is_over=false)
	{
		$file_name=strtolower($file_name);
		$file_path=MODULE_PATH.DIRECTORY_SEPARATOR."Data".DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR,explode(".",$file_name)).".php";
		if(empty($file_name))
		{
			return false;
		}
		$file_dir=dirname($file_path);
		if(!file_exists($file_dir))
		{
			mkdir($file_dir,0777,true);		//递归创建目录
		}
		
		$data=file_exists($file_path)?require($file_path):array();
		if(is_array($save_data))
		{
			$data=$is_over?$save_data:array_merge($data,$save_data);
		}
		else
		{
			$data[]=$save_data;
		}
		return file_put_contents($file_path, "<?php \nreturn " . var_export($data , true) . ";\n?>");
	}
	
	/*
	根据映射数组获取数据
	@param array
	@param array
	@param array  字段范围限制
	@retutn array
	*/
	function getDataByMap($data,$map,$key_limit=array())
	{
		$ret_arr=array();
		foreach($map as $key=>$item)
		{
			$key_name=isset($data[$key])?trim($data[$key]):"";
			$val=isset($data[$item])?trim($data[$item]):"";
			if(!empty($key_limit) && !in_array($key_name,$key_limit) && !isset($key_limit[$key_name]))
			{
				continue;
			}
			$ret_arr[$key_name]=$val;
		}
		return $ret_arr;
	}
	
	//格式化路径
	function FPATH($path)
	{
		$re_path=str_replace("/",DIRECTORY_SEPARATOR,$path);
		$re_path=str_replace("\\",DIRECTORY_SEPARATOR,$re_path);
		return $re_path;
	}