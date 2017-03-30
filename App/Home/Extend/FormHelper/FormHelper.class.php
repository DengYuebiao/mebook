<?php
namespace Home\Extend\FormHelper;

/*
表单助手,用于生成各种表单元素
*/
class FormHelper{
	
	/*获取input的text元素
	  @param array 字段生成配置
	  @return string
	*/
	static function getText($cnf)
	{
		
		$zd=$cnf['zd'];
		$zd_val=$cnf['val'];
		$attr=$cnf['attr']?" {$cnf['attr']}":"";
		$elem_html='<input'.$attr.' type="text" name="'.$zd.'" id="'.$zd.'" value="'.$zd_val.'" />';
		
		return $elem_html;
	}
	
	/*获取input的text元素
	  @param array 字段生成配置
	  @return string
	*/
	static function getPassword($cnf)
	{
		
		$zd=$cnf['zd'];
		$zd_val=$cnf['val'];
		$attr=$cnf['attr']?" {$cnf['attr']}":"";
		$elem_html='<input'.$attr.' type="password" name="'.$zd.'" id="'.$zd.'" value="" />';
		
		return $elem_html;
	}
	
	/*获取input的radio元素
	  @param array 字段生成配置
	  @return string
	*/
	static function getRadio($cnf)
	{
		
		$zd=$cnf['zd'];
		$zd_val=$cnf['val'];
		$attr=$cnf['attr']?" {$cnf['attr']}":"";
		$param=$cnf['param'];
		$vt=$param['vt'];
		if(!$zd_val && $param['default'])
		{
			$zd_val=$param['default'];
		}
		$elem_html='';
		$i=0;
		foreach($param['list'] as $key=>$item)
		{
			if($vt=="val")
			{
				$key=$item;
			}
			$sel_attr=$key==$zd_val?' checked="checked"':'';
			$id_tmp=$zd.$i;
			$elem_html.='<input'.$sel_attr.$attr.' type="radio" name="'.$zd.'" id="'.$id_tmp.'" value="'.$key.'" /><label class="radio_label" for="'.$id_tmp.'">'.$item.'</label>';
			$i++;
		}
		
		return $elem_html;
	}
	
	/*获取input的text元素
	  @param array 字段生成配置
	  @return string
	*/
	static function getFile($cnf)
	{
		
		$zd=$cnf['zd'];
		$zd_val=$cnf['val'];
		$attr=$cnf['attr']?" {$cnf['attr']}":"";
		$elem_html='<input'.$attr.' type="file" name="'.$zd.'" id="'.$zd.'" value="" />';
		
		return $elem_html;
	}
	
	/*获取span元素
	  @param array 字段生成配置
	  @return string
	*/
	static function getSpan($cnf)
	{
		$zd=$cnf['zd'];
		$attr=$cnf['attr']?" {$cnf['attr']}":"";
		$param=$cnf['param'];
		
		$elem_html='<span'.$attr.'>'.$param['val'].'</span>';
		
		return $elem_html;
	}
	
	/*获取textarea
	  @param array 字段生成配置
	  @return string
	*/
	static function getTextarea($cnf)
	{
		
		$zd=$cnf['zd'];
		$zd_val=$cnf['val'];
		$attr=$cnf['attr']?" {$cnf['attr']}":"";
		$elem_html='<textarea'.$attr.' type="text" name="'.$zd.'" id="'.$zd.'">'.$zd_val.'</textarea>';
		
		return $elem_html;
	}
	
	/*获取select的列表元素
	  @param array 字段生成配置
	  @return string
	*/
	static function getSelect($cnf)
	{
		
		$zd=$cnf['zd'];
		$zd_val=$cnf['val'];
		$param=$cnf['param'];
		
		$attr='name="'.$zd.'" id="'.$zd.'"';
		$attr.=$cnf['attr']?" {$cnf['attr']}":"";
		$param['attr']=$attr;
		$elem_html=self::getSelectHtml($zd_val,$param['list'],$param);
		return $elem_html;
	}
	
	/*
		根据表格行数据和配置参数获取表格某个字段的标头和内容
		@param array
		@param array
		@return string
	*/
	static public function getZdHtml(&$row_data,$cnf)
	{
		$zd=$cnf['zd'];
		$type=$cnf['type'];
		$tdattr=$cnf['tdattr']?" {$cnf['tdattr']}":"";
		$thattr=$cnf['thattr']?" {$cnf['thattr']}":"";
		$zd_str=L($zd).":";
		
		$zd_val=isset($cnf['val'])?$cnf['val']:$row_data[$zd];
		$elem_html="";
		switch($type)
		{
			case "text":
				$elem_html=self::getText(array("zd"=>$zd,"val"=>$zd_val,"attr"=>$cnf['attr']));
			break;
			case "textarea":
				$elem_html=self::getTextarea(array("zd"=>$zd,"val"=>$zd_val,"attr"=>$cnf['attr']));
			break;
			case "file":
				$elem_html=self::getFile(array("zd"=>$zd,"val"=>$zd_val,"attr"=>$cnf['attr']));
			break;
			case "select":
				$elem_html=self::getSelect(array("zd"=>$zd,"val"=>$zd_val,"attr"=>$cnf['attr'],"param"=>$cnf['param']));
			break;
			case "radio":
				$elem_html=self::getRadio(array("zd"=>$zd,"val"=>$zd_val,"attr"=>$cnf['attr'],"param"=>$cnf['param']));
			break;
			case "password":
				$elem_html=self::getPassword(array("zd"=>$zd,"val"=>$zd_val,"attr"=>$cnf['attr'],"param"=>$cnf['param']));
			break;
			case "span":
				$elem_html=self::getSpan(array("zd"=>$zd,"val"=>$zd_val,"attr"=>$cnf['attr'],"param"=>$cnf['param']));
			break;
			case "custom":
				$elem_html=$zd_val;
			break;
		}
		if(isset($cnf['before']))	
		{
			$elem_html=$cnf['before'].$elem_html;
		}	
		if(isset($cnf['after']))	
		{
			$elem_html.=$cnf['after'];
		}
		$html='<th'.$thattr.'>'.$zd_str.'</th><td'.$tdattr.'>'.$elem_html.'</td>';
		
		return $html;
	}
	
	/*
		根据表格行数据和配置参数获取表格某行的html
		@param array
		@param array 
		@return string
	*/
	static public function getTrHtml(&$row_data,$cnf)
	{
		$attr=$cnf['attr']?" {$cnf['attr']}":"";
		$zdcnf=$cnf['zdcnf'];
		
		$zd_html='';
		
		foreach($cnf['zdcnf'] as $item)
		{
			$zd_html.=self::getZdHtml($row_data,$item);
		}
		
		$html='<tr'.$attr.'>'.$zd_html.'</tr>';
		
		return $html;
	}
	
	/*
		根据表格行数据和配置参数获取表格多行的html
		@param array
		@param array 
		@return string
	*/
	static public function getTrsHtml(&$row_data,$cnf)
	{		
		$tr_html='';
		
		foreach($cnf as $item)
		{
			$tr_html.=self::getTrHtml($row_data,$item);
		}
		
		return $tr_html;
	}
	
	/*
		根据参数获取select的html内容
		@param mixed 列表当前值
		@param array 列表数组
		@param array 相关配置
		@return string
	*/
	static public function getSelectHtml($curr_val,$data_list,$cnf)
	{	
		$type=isset($cnf['type'])?$cnf['type']:"key";
		$attr=isset($cnf['attr'])?" ".$cnf['attr']:"";
		$vt=isset($cnf['vt'])?$cnf['vt']:"";
		$option_html="";
		foreach($data_list as $key=>$item)
		{
			if($vt=="val")
			{
				$key=$item;
			}
			if($type=="key")
			{
				$sel_attr=$key==$curr_val?' selected="selected"':"";
			}
			else
			{
				$sel_attr=$item==$curr_val?' selected="selected"':"";
			}
			$option_html.='<option'.$sel_attr.' value="'.$key.'">'.$item.'</option>';
		}
		
		$select_html='<select '.$attr.'>'.$option_html.'</select>';
		return $select_html;
	}
	
	/**
     * 字符串截取，支持中文和其他编码
     * @static
     * @access public
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * @return string
     */
    static public function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }
		if( $suffix && strlen($slice)<strlen($str))
		{
			$slice=$slice.'...';
		}
        return $slice;
    }
}