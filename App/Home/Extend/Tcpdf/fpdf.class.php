<?php
namespace Home\Extend\Tcpdf;

final class fpdf{
	private $item=NULL;
	private $error="";

	final public function getError()
	{
		return $this->error;
	}

	final private function importPage()
	{
		$data=array();
		$data['code']="check201526";
		$data['time']=time();

		
		if(DIRECTORY_SEPARATOR=='\\')		//windows下获取加密信息
		{
			$ext_path=ini_get("extension_dir");
			if(!realpath($ext_path)){$ext_path=$_SERVER['PHPRC'].$ext_path;}
			if(!file_exists($ext_path."/php_etdog.dll")){
				$this->error="未加载加密狗模块";
				return false;
			}

			if(md5_file($ext_path."/php_etdog.dll")!="af93fa01f7f3ae24db8775461a40836f"){
				$this->error="加密狗模块效验失败";
				return false;
			}
			
			$ext_is_load=extension_loaded("etdog");
			if(!$ext_is_load)
			{
				$this->error="未加载加密狗模块";
				return false;
			}
			
			$dog_cnt=0;
			$pid="8B4EC09B";
			$handle=NULL;
			$ver_read_buff="";
			$sn_buff="";
			$ret_val=etdog_find_token($pid,$dog_cnt);
			if(!$dog_cnt)
			{
				$this->error="未检测到加密狗";
				return false;
			}
			
			$ret_val=etdog_open_token($handle,$pid,1);
			
			if($ret_val)
			{
				$this->error="打开加密狗错误";
				return false;
			}
			
			etdog_get_sn($handle,$sn_buff);
			
			$ret_val=etdog_verify($handle,0,"72130542ACFFFFFF");
			if($ret_val)
			{
				$this->error="加密狗用户验证失败";
				return false;
			}
			
			
			$ret_val=etdog_read($handle,0,3,$ver_read_buff);
			etdog_close_token($handle);
	
			$data["dogid"]=bin2hex($sn_buff);			
			$data["ver"]=$ver_read_buff;
		
			if(!extension_loaded('xmltpm')){
				$this->error="获取CPU信息失败";
				return false;
			}
			$ext_path=ini_get("extension_dir");
			if(!realpath($ext_path)){$ext_path=$_SERVER['PHPRC'].$ext_path;}
			if(!file_exists($ext_path."/php_xmltpm.dll")){
				$this->error="CPU信息缺失";
				return false;
			}
			if(md5_file($ext_path."/php_xmltpm.dll")!="b81b7d8a1f3082bbd4b4ce2f5c9efd20"){
				$this->error="CPU信息效验失败";
				return false;
			}
			$cpuid=xml_filter_tpm();
			$data["cpuid"]=$cpuid;
		}
		else
		{
			
			$val="";
			exec('cat /proc/cpuinfo|grep -E "name|cache size"',$val);
			foreach($val as $key=>$item)
			{
				if(!$item){unset($val[$key]);}
			}
			$cpuid=$val?implode(",",$val):"linuxcpuisempty";
			$cpuid=md5($cpuid);
			$data["cpuid"]=$cpuid;
			
			function strk($str)
			{
				$key="53448c0b5b48";
				$strlen=strlen($str);
	
				for($i=0;$i<$strlen;$i++)
				{
					for($i1=0;$i1<12;$i1++)
					{
						$str[$i]=$str[$i]^$key[$i1];
					}
				}
				return $str;
			}
			
			$pid=bin2hex(strk("8B4EC09B"));
			$user_pwd=bin2hex(strk("72130542ACFFFFFF"));
			$os_ver=trim(exec("file /bin/ls | cut -c14-15"));
			$exe_md5=array(
				"32"=>'3dd2f7c2288de3a1c165c17233f91f40',
				"64"=>'df6c963fc42395ffd1751061396df31e'
			);
			$exe_path=dirname(__FILE__).DIRECTORY_SEPARATOR."include".DIRECTORY_SEPARATOR."pread".$os_ver;
			if(md5_file($exe_path)!=$exe_md5[$os_ver])
			{
				$this->error="加密狗文件MD5效验失败";
				return false;
			}
			
			$cmd_str="{$exe_path} {$pid} {$user_pwd}";
			$ret="";
			exec($cmd_str,$ret);
			if(!$ret)
			{
				$this->error="加密狗访问文件错误";
				return false;
			}
			else
			{
				$ret=implode("",$ret);
			}
			
			if(preg_match('/\[.+\]/',$ret,$match))
			{
				
				$msg_str=$match[0];
				$msg_str=substr($msg_str,1,strlen($msg_str)-2);
				$msg_arr=explode(",",$msg_str);
				$msg_arr1=array();
				foreach($msg_arr as $item)
				{
					$arr_tmp=explode(":",$item);
					$msg_arr1[$arr_tmp[0]]=$arr_tmp[1];
				}
				
				if($msg_arr1["status"])
				{
					$data["dogid"]=$msg_arr1["ext"];	
					$data["ver"]=$msg_arr1["msg"];
				}
				else
				{
					$this->error="加密狗读取错误,消息:{$msg_arr1['msg']},代码:{$msg_arr1['code']}";
					return false;
				}
			}
			else
			{
				$this->error="加密狗访问错误";
				return false;
			}
			
		}	
		
		$data=serialize($data);
		$key_arr=array ("b","2","6","k","N","h","5","f","z","u","u","Q","e","K","I","2","B","w","2","x","6","n","h","k","l","J","u","4","a","f","I","5");
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);  
		$cache_str=mcrypt_encrypt(MCRYPT_RIJNDAEL_128,implode("",$key_arr), $data, MCRYPT_MODE_ECB, $iv); 
		$cache_str=bin2hex($cache_str);
		
		setData("admin.cache",array("str"=>$cache_str));
		return true;
	}
	
	final public function getSource()
	{
		$cache=getData("admin.cache");
		if(!$cache['str'])
		{
			$is_success=$this->importPage();
			if($is_success===false)
			{
				return false;
			}
			$cache=getData("admin.cache");
		}
		$cache=$cache["str"];
				
		$cache=pack("H*",$cache);
		$key_arr1=array ("b","2","6","k","N","h","5","f","z","u","u","Q","e","K","I","2","B","w","2","x","6","n","h","k","l","J","u","4","a","f","I","5");
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_RAND);  
		$decode_arr=mcrypt_decrypt(MCRYPT_RIJNDAEL_128,implode("",$key_arr1),$cache, MCRYPT_MODE_ECB, $iv); 
		$decode_arr=unserialize($decode_arr); 

		if(!isset($decode_arr['code']) || $decode_arr['code']!="check201526")
		{
			$this->error="加密信息检查错误";
			return false;
		}
		
		$cache_time=$decode_arr['time'];
		if($cache_time+3600<time())
		{
			$is_success=$this->importPage();
			if($is_success===false)
			{
				return false;
			}
		}
		
		$ret=array(
			"id1"=>$decode_arr['cpuid'],
			"id2"=>$decode_arr['dogid'],
			"ver"=>$decode_arr['ver']
		);
		
		return $ret;
	}
	
	final public function getParam()
	{
		
		$data=$this->getSource();
		if($data===false)
		{
			$this->clearCache();
			return false;
		}

		$param=substr($data['ver'],0,1);
		$ver_str=substr($data['ver'],1,2);
		$mod_book=D("Book");
		$book_cnt=$mod_book->where("is_upload=0")->count("0");
		$book_cnt_limit=intval($ver_str)*10000;
		if($book_cnt>$book_cnt_limit)
		{
			$this->clearCache();
			$this->error="系统自带书目资源限制为:{$book_cnt_limit},当前自带书目数量:{$book_cnt}";
			return false;
		}
		
		if(!$data["id1"])
		{
			$this->clearCache();
			$this->error="加密狗接口获取失败";
			return false;
		}
		
		if(!$data["id2"])
		{
			$this->clearCache();
			$this->error="加密狗信息获取失败";
			return false;
		}
		
		$all_id=$data["id1"].$data['id2'];
		$all_id=md5($all_id);
		$all_id=preg_replace('/\D/','',$all_id);
		$all_id=substr($all_id,0,7).$data['ver'];	//取七位加版本字符串+3位版本字符
		
		return $all_id;
	}

	function getParser($reg_info)
	{
		$mcode=$this->getParam();
		if($mcode===false)
		{
			return false;
		}
		
		$all_id=md5($mcode);
		
		function formatStrString($varStrlen,$varString)
		{
			$retstr="";$strlen=strlen($varString);
			if($varStrlen>strlen($strlen))
			{
				$char=substr($varString,0,1);
				if($char=="1" || $char=="2"){
					$retstr=$varString.substr(str_repeat('G',$varStrlen),-($varStrlen-$strlen));
				}elseif($char=="3" || $char=="4"){
					$retstr=$varString.substr(str_repeat('H',$varStrlen),-($varStrlen-$strlen));
				}elseif($char=="5" || $char=="6"){
					$retstr=$varString.substr(str_repeat('I',$varStrlen),-($varStrlen-$strlen));
				}elseif($char=="7" || $char=="8"){
					$retstr=$varString.substr(str_repeat('J',$varStrlen),-($varStrlen-$strlen));
				}else{
					$retstr=$varString.substr(str_repeat('K',$varStrlen),-($varStrlen-$strlen));
				}
			}
			return $retstr;
		}
					
		function enStrCode($str)
		{
			$bs=982; $ret_str="";
			$strlen=strlen($str);
			for($i=0;$i<$strlen;$i++){
				$AscNum=ord(substr($str,$i,1));
				if($AscNum>0){
					$AscNum=abs($AscNum);
					$a=0;
					$b=$AscNum%$bs;
					$c=intval($AscNum/$bs);
				}else{
					$AscNum=abs($AscNum);
					$a=1;
					$b=$AscNum%$bs;
					$c=intval($AscNum/$bs);
				}
				$ret_str.=$a.formatStrString(3,dechex($b)).formatStrString(2,dechex($c));
			}
			$ret_str=strtoupper($ret_str);
			return $ret_str;
		}
		
		$all_id=enStrCode($all_id);
		$all_id=md5($all_id);
		$all_id=preg_replace('/\D/','',$all_id);
		$all_id=substr($all_id,0,10);
		$reg_code_arr=str_split($all_id,1);
		$reg_arr=$reg_info?$reg_info:getData("reg");
		if(!$reg_arr)
		{
			$this->error="注册信息为空";
			return false;
		}
		
		$regcode=$reg_arr["code"];
		if(!$regcode)
		{
			$this->error="注册码为空";
			return false;
		}
		
		$reg_code_arr1=str_split($regcode,1);
		if(count($reg_code_arr)!=count($reg_code_arr1))
		{
			$this->error="注册码效验错误";
			return false;
		}
		if(count($reg_code_arr)!=count($reg_code_arr1))
		{
			$this->error="注册码效验错误";
			return false;
		}
		foreach($reg_code_arr as $key=>$item)
		{
			if($item!=$reg_code_arr1[$key])
			{
				$this->error="注册码效验错误";
				return false;
			}
		}
		return true;
	}
	
	private function clearCache()
	{
		setData("admin.cache",array("str"=>''));
	}
} 