<?php
namespace Home\Extend\Isbn;

class IsbnBase
{
	//isbn转换isbn文献条码
	//@param isbn字符串
	static function convIsbnCode($isbn)
	{
		//如果isbn为空、总长度小于9、去除-符号后长度小于9则返回本身
		if(empty($isbn) || strlen($isbn)<9 )
		{
			return $isbn;
		}
		//去除isbn里的非数字字符
		$isbn=preg_replace("/\D/","",$isbn);

		$len=strlen($isbn);
		$isbn_head=substr($isbn,0,3);
		//如果去除非数字符号后，长度小于10位isbn的最低长度9就返回本身
		if($len<9)
		{
			return $isbn;
		}
		
		//如果长度为12及以上，头三位为978的是13位isbn
		if($isbn_head=="978" && $len>=12)
		{
			$isbn=substr($isbn,0,13);
			$end_is_num=preg_match('/\d/',$isbn,$match);
			
			//如果是13位且结尾为数字则返回isbn
			if(strlen($isbn)==13 && $end_is_num)
			{
				return $isbn;
			}
			$isbn=substr($isbn,0,12);
			
		}
		else
		{
			$isbn="978".substr($isbn,0,9);
		}
		
		$isbn_arr=str_split($isbn);
		
		$sum_a=0;	//奇数和
		$sum_b=0;	//偶数和
		$sum_c=0;	//奇数和x3+偶数和
		foreach($isbn_arr as $key=>$item)
		{
			if((($key+1)%2)==0)
			{
				$sum_b+=(int)$item;
			}
			else
			{
				$sum_a+=(int)$item;
			}
		}
		
		$sum_c=$sum_a+$sum_b*3;
		$sum_c=(string)$sum_c;
		$sum_c=strrev($sum_c);
		$sum_c=$sum_c[0];
		$sum_d=10-$sum_c;
		$sum_d=strrev($sum_d);
		$sum_d=$sum_d[0];
		
		return $isbn.$sum_d;
	}
	
	//isbn文献条码转换isbn
	//@param isbn code字符串
	static function convtoIsbn($isbn_code)
	{
		//如果isbn_code为空、总长度小于12 则返回原字符串
		if(empty($isbn_code) || strlen($isbn_code)<12 )
		{
			return $isbn_code;
		}
		$str_op=substr($isbn_code,3,9);
		$arr=str_split($str_op);
		$sum_int=0;
		for($i=0;$i<9;$i++)
		{
			$a1=intval($arr[$i])*(10-$i);
			$sum_int+=$a1;
		}
		
		$i1=$sum_int%11;

		$i1=11-$i1;
		$i1=$i1==10?"X":$i1;
		$isbn=substr($str_op,0,9).$i1;
		return $isbn;
	}
	
	//isbn文献条码转换isbn isbn13位计算方法
	//@param isbn code字符串
	static function convtoIsbn13($isbn_code)
	{
		//如果isbn_code为空、总长度小于12 则返回原字符串
		if(empty($isbn_code) || strlen($isbn_code)<12 )
		{
			return $isbn_code;
		}
		$str_op=substr($isbn_code,0,12);
		$arr=str_split($str_op);
		$sum_int=0;
		for($i=0;$i<12;$i++)
		{
			$a1=(($i+1)%2)==0?intval($arr[$i])*3:intval($arr[$i])*1;
			$sum_int+=$a1;
		}
		
		$i1=$sum_int%10;

		$i1=10-$i1;
		$i1=$i1==10?"0":$i1;
		$isbn=$str_op.$i1;
		return $isbn;
	}
	
	//格式化ISBN
	//@param string
	//@return string
	function formatIsbn($isbn)
	{
		if(empty($isbn))
		{
			return $isbn;
		}
		$isbn_old=$isbn;
		$isbn=strlen($isbn)>=13?substr($isbn,3,10):$isbn;
		$format_str="";
		$l3_code=substr($isbn,0,3);		//头三位代号
		switch($l3_code)
		{	
			case "957":				//台湾isbn
				$isbn_str2=substr($isbn,3,2);
				$isbn_str3=substr($isbn,3,3);
				if( ($isbn_str3>="000" && $isbn_str3<="199") || ($isbn_str2>="21" && $isbn_str2<="27") || ($isbn_str3>="310" && $isbn_str3<="430") )
				{
					$isbn="957-".substr($isbn,3,2)."-".substr($isbn,5,4)."-".substr($isbn,9,1);
				}
				elseif($isbn_str2=="20" || ($isbn_str2>="82" && $isbn_str2<="96")  )
				{
					$isbn="957-".substr($isbn,3,4)."-".substr($isbn,7,2)."-".substr($isbn,9,1);
				}
				elseif( ($isbn_str2>="97" && $isbn_str2<="99") || ($isbn_str2>="28" && $isbn_str2<="30"))
				{
					$isbn="957-".substr($isbn,3,5)."-".substr($isbn,8,1)."-".substr($isbn,9,1);
				}
				elseif($isbn_str2>="44" && $isbn_str2<="81" )
				{
					$isbn="957-".substr($isbn,3,3)."-".substr($isbn,6,3)."-".substr($isbn,9,1);
				}	
			break;
			case "986":				//台湾isbn
				$isbn_str3=substr($isbn,3,3);
				if( $isbn_str3>="000" && $isbn_str3<="120")
				{
					$isbn="986-".substr($isbn,3,2)."-".substr($isbn,5,4)."-".substr($isbn,9,1);
				}
				elseif( $isbn_str3>="121" && $isbn_str3<="599")
				{
					$isbn="986-".substr($isbn,3,3)."-".substr($isbn,6,3)."-".substr($isbn,9,1);
				}
				elseif( $isbn_str3>="600" && $isbn_str3<="799")
				{
					$isbn="986-".substr($isbn,3,4)."-".substr($isbn,7,2)."-".substr($isbn,9,1);
				}
				elseif( $isbn_str3>="800" && $isbn_str3<="999")
				{
					$isbn="986-".substr($isbn,3,5)."-".substr($isbn,8,1)."-".substr($isbn,9,1);
				}
			break;
			case "962":				//香港ISBN
				$isbn_str3=substr($isbn,3,3);
				if( ($isbn_str3>="000" && $isbn_str3<="200") || $isbn_str3=="220")
				{
					$isbn="962-".substr($isbn,3,2)."-".substr($isbn,5,4)."-".substr($isbn,9,1);
				}
				elseif(( $isbn_str3>="201" && $isbn_str3<="219" ) || ( $isbn_str3>="221" && $isbn_str3<="699") || ( $isbn_str3>="900" && $isbn_str3<="999"))
				{
					$isbn="962-".substr($isbn,3,3)."-".substr($isbn,6,3)."-".substr($isbn,9,1);
				}
				elseif( ($isbn_str3>="700" && $isbn_str3<="849" ) ||($isbn_str3>="870" && $isbn_str3<="899" ) )
				{
					$isbn="962-".substr($isbn,3,4)."-".substr($isbn,7,2)."-".substr($isbn,9,1);
				}
				elseif( $isbn_str3>="850" && $isbn_str3<="869")
				{
					$isbn="962-".substr($isbn,3,5)."-".substr($isbn,8,1)."-".substr($isbn,9,1);
				}
				
			break;
			case "988":				//香港ISBN
				$isbn_str3=substr($isbn,3,3);
				if( ( $isbn_str3>="000" && $isbn_str3<="199" ) ||( $isbn_str3>="600" && $isbn_str3<="999" )  )
				{
					$isbn="988-".substr($isbn,3,5)."-".substr($isbn,8,1)."-".substr($isbn,9,1);
				}
				elseif( $isbn_str3>="200" && $isbn_str3<="599")
				{
					$isbn="988-".substr($isbn,3,4)."-".substr($isbn,7,2)."-".substr($isbn,9,1);
				}
			break;
			default:						//中国isbn
				$isbn=$l3_code=="978"?substr($isbn,3):$isbn;		//如果有978则去掉
				$isbn_l2=substr($isbn,0,2);
				if($isbn_l2=="70")
				{
					$isbn="7-".substr($isbn,1,2)."-".substr($isbn,3,6)."-".substr($isbn,9,1);
				}
				elseif(in_array($isbn_l2,array("71","72","73")))
				{
					$isbn="7-".substr($isbn,1,3)."-".substr($isbn,4,5)."-".substr($isbn,9,1);
				}
				elseif(in_array($isbn_l2,array("74","75","76","77")))
				{
					$isbn="7-".substr($isbn,1,4)."-".substr($isbn,5,4)."-".substr($isbn,9,1);
				}
				elseif(in_array($isbn_l2,array("78")))
				{
					$isbn="7-".substr($isbn,1,5)."-".substr($isbn,6,3)."-".substr($isbn,9,1);
				}
				elseif(in_array($isbn_l2,array("79")))
				{
					$isbn="7-".substr($isbn,1,6)."-".substr($isbn,7,2)."-".substr($isbn,9,1);
				}
			break;
		}
		$isbn=strlen($isbn_old)>=13?substr($isbn_old,0,3)."-".$isbn:$isbn;
		return $isbn;
	}
	
	//格式化clc分类号 将符号转换为 _便于检索
	//@param string
	//@return string
	static function formatClc($clc_str)
	{
		$reg_str='/\.|\。|\-|\－|\—|\=|\＝|\：|\:|\（|\）|\(|\)|\[|\]|\【|\】/';
		return preg_replace($reg_str,"_",$clc_str);
	}
	
	//批量格式化clc分类号 将符号转换为 _便于检索
	//@param array
	//@param string
	//@return bool
	static function formatClcArray(&$clc_arr,$key_name)
	{
		$reg_str='/\.|\。|\-|\－|\—|\=|\＝|\：|\:|\（|\）|\(|\)|\[|\]|\【|\】/';
		foreach($clc_arr as $key=>$item)
		{
			$clc_arr[$key][$key_name]=preg_replace($reg_str,"_",$item[$key_name]);
		}
		return true;
	}
	
	//转换ISSN到ISSNCODE
	static function convISSN($issn_code_val)
	{
		//去除isbn里的非数字字符
		$issn_code=preg_replace("/\D/","",$issn_code_val);
	
		if(empty($issn_code) || strlen($issn_code)!=13 || substr($issn_code,0,3)!="977")	//issn为空、不等于13位 或 开头不为977返回原值
		{
			return $issn_code_val;
		}
		$issn_code=substr($issn_code,3,7);
		
		//  以ISSN 0317—8471为例，其校验码计算方法见下表。
		//取ISSN的前7位数字(校验位是第8位，即最后1位)      0    3    1    7    8    4    7
		//取各位数字所对应的加权值(8—2)  8    7    6    5    4    3    2
		//将各位数字与其相应的加权值依次相乘 0    21   6    35   32   12   14
		//将乘积相加，得出和数    0   +21   +6   +35  +32  +12  +14 =120
		//用和数模除11，得出余数    120%11 
		//用模数ll减余数，所得差数即为校验码的值   11—10=1
		//将所得校验码数值放在构成ISSN的基本数字的最右边     0317—8471   
		//    如果差数为10，校验码则以大写英文字母“X”表示；如果余数是“0”，则校验码为“0” 
		
		$sum=0;
		for($i=0;$i<7;$i++)
		{
			$sum+=$issn_code[$i]*(8-$i);
		}
	
		$sum=intval($sum%11);

		$sum=11-$sum;
		$sum==10 && $sum="X";
		
		$issn=substr($issn_code,0,4)."-".substr($issn_code,4,3).$sum;
		
		return $issn;
	}
	
	//issn文献条码转换issn
	//@param isbn code字符串
	static function convToIssn($issn_code)
	{
		//如果issn_code为空、长度不等于13 则返回原字符串
		if(empty($issn_code) || strlen($issn_code)!=13 )
		{
			return $issn_code;
		}
		
		$issn_str=substr($issn_code,3,7);
		
		$total=0;
		for($i=0;$i<7;$i++)
		{
			$total+=substr($issn_str,$i,1)*(8-$i);
		}
		
		$check_num=11-($total%11);
		if($check_num=="10")
		{
			$check_num="X";
		}
		else if($check_num=="11")
		{
			$check_num="0";
		}
		
		$issn_str=substr($issn_str,0,4)."-".substr($issn_str,4,3).$check_num;
		return $issn_str;
	}
}