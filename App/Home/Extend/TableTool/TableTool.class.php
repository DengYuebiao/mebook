<?php
namespace Home\Extend\TableTool;

/*
用于根据配置提供表单表格的数据处理和显示相关
*/
class TableTool{
	
	protected $db=NULL;
	protected $setting=array();
	protected $zdList=NULL;		//数据库所有字段
	protected $zdTypeMap=NULL;		//字段类型映射
	protected $zdMap=NULL;		//数据库字段名称映射
	protected $zdType=NULL;		//显示的字段的类型
	protected $zdWidth=NULL;	//字段列宽度
	
	/*
	@param object 数据模型对象
	@param array 配置数组
	*/
	function __construct($cnf)
	{
		$this->setting=array_merge($this->setting,$this->getDefaultCnf());
		$this->setting=array_merge($this->setting,$cnf);
		$this->init();
	}
	
	private function getDefaultCnf()
	{
		return array(
			"isDbMod"=>true,	//是否对应单个DB模型
			"dbMod"=>NULL,	//单个DB模型的值
			"pkName"=>NULL,	//主键名称
			"zdList"=>NULL,	//数据库全部字段列表，array类型
			"zdTypeMap"=>NULL,	//字段类型映射对应数组，array类型
			"zdMap"=>NULL,	//字段名称对应数组，array类型
			"funList"=>NULL,	//自定义函数列表,用于作为回调处理函数
			"zdShow"=>NULL,	//需要在表格显示的字段，array类型  zd.string.15%(字段名.类型.宽度)
			"zdShowExt"=>NULL,	//显示的字段的相关预处理，array类型
			"zdOrder"=>NULL,	//需要排序的字段，array类型 ,zd=>asc 类似的值
			"actionPre"=>"",	//模块控制器前缀
			"editName"=>"edit",	//控制器编辑数据的接口
			"dropName"=>"drop",	//控制器删除数据的接口
			"showName"=>"show",	//控制器查看数据的接口
			"editJsFunc"=>"editData",	//控制器编辑数据的函数名
			"dropJsFunc"=>"dropData",	//控制器删除数据的函数名
			"showJsFunc"=>"showData",	//控制器查看数据的函数名
			"selType"=>0,	//表格行的选择类型,无选择框0 或者单选1  多选2
			"isEditBtn"=>true,	//是否生成编辑按钮
			"isDropBtn"=>true,	//是否生成删除按钮按钮
			"isShowBtn"=>false,	//是否生成删除按钮按钮
			"btnCallBack"=>NULL, //操作列生成回调函数,返回字符串作为列数据 function($row_data,$btn_arr)
			"tableAttr"=>'class="m-table"',	//表格的属性
			"selWidth"=>"6%",	//表格选择列宽度
			"btnWidth"=>"8%",	//表格操作列宽度
			"zdCallBack"=>array(), //操作列生成回调函数,返回字符串作为列数据 function($row_data,$btn_arr)
		);	
	}
	
	private function init()
	{
		if($this->setting['isDbMod'])	//如果是从db模型里获取字段列表
		{
			$db_mod=$this->setting['dbMod'];
			if(!$db_mod)
			{
				throw new \Exception("数据模型参数不能为空");
				return false;
			}
			
			if(!$this->setting['pkName'])
			{
				$this->setting['pkName']=$db_mod->getPk();
			}
			
			if(!$this->setting['pkName'])
			{
				throw new \Exception("必须指定主键名称");
				return false;
			}
			$this->db=$db_mod;
			$zd_list=array();
			$fields=$db_mod->db()->getFields($db_mod->getTableName());
			$this->zdList=array_keys($fields);
			//获取字段的数据类型
			$this->zdTypeMap=$db_mod->getFieldType();
			
		}
		else
		{
			$this->zdList=$this->setting['zdList'];
			if(!$this->zdList)
			{
				throw new \Exception("字段列表参数不能为空");
				return false;
			}
			
			if(!$this->setting['pkName'])
			{
				throw new \Exception("必须指定主键名称");
				return false;
			}
			
			if(!$this->setting['zdTypeMap'])
			{
				throw new \Exception("必须设置字段数据类型");
				return false;
			}
			
			$this->zdTypeMap=$this->setting['zdTypeMap'];
		}

		$zd_map=$this->setting['zdMap'];
		if($zd_map)
		{
			if(!is_array($zd_map))
			{
				throw new \Exception("字段名称映射参数必须为数组");
				return false;
			}
			foreach($this->zdList as $item)
			{
				if(isset($zd_map[$item]))	//未定义的字段使用语言翻译
				{
					$this->zdMap[$item]=$zd_map[$item];
				}
				else
				{
					$this->zdMap[$item]=$item?L($item):$item;
				}
				
			}
		}
		else	//如果未设置名称对应则从语言包获取
		{
			foreach($this->zdList as $item)
			{
				$this->zdMap[$item]=$item?L($item):$item;
			}
		}			
		
		//处理zdShow参数
		$zd_show_param=$this->setting['zdShow'];
		$zd_show=array();
		foreach($zd_show_param as $item)
		{
			$arr=explode(".",$item);
			$zd_name=$arr[0];
			$zd_show[]=$zd_name;
			$this->zdType[$zd_name]=$arr[1]?$arr[1]:"string";
			$this->zdWidth[$zd_name]=$arr[2]?$arr[2]:"10%";
		}

		foreach($zd_show as $item)
		{
			if(!isset($this->zdMap[$item]))
			{
				if(isset($zd_map[$item]))	//未定义的字段使用语言翻译
				{
					$this->zdMap[$item]=$zd_map[$item];
				}
				else
				{
					$this->zdMap[$item]=$item?L($item):$item;
				}
			}
		}
		$this->setting['zdShow']=$zd_show;
		
		
		
	}
	
	/*
		根据数据和配置获取显示的表格html
		@param array
		@return string
	*/
	public function getHtml($data)
	{
		$thead_html=$this->getThead($data);
		$tbody_html=$this->getTbody($data);
		
		$tableAttr=$this->setting['tableAttr']?" ".$this->setting['tableAttr']:"";
		$table_html='<table'.$tableAttr.'>'.$thead_html.$tbody_html.'</table>';
		return $table_html;
	}
	
	/*
		根据数据和配置获取显示的表格thead
		@param array
		@return string
	*/
	public function getThead(&$data)
	{
		$zd_show=$this->setting['zdShow'];
		
		//处理表格头部
		$tds=array();
		
		//当有选择列的时候增加选择列
		if(in_array($this->setting['selType'],array(1,2)))
		{
			$tds[]='<th width="'.$this->setting['selWidth'].'">选择</th>';
		}
		
		foreach($zd_show as $zd_name)
		{
			$cell_val=isset($this->zdMap[$zd_name])?$this->zdMap[$zd_name]:"";
			if($this->setting['zdOrder'])	//处理表格初始化排序
			{
				$order_str=isset($this->setting['zdOrder'][$zd_name])?' zdorder="'.$zd_name.'.'.$this->setting['zdOrder'][$zd_name].'"':'';
			}
			$tds[]='<th'.$order_str.' width="'.$this->zdWidth[$zd_name].'">'.$cell_val.'</th>';
		}
		
		//当有操作按钮的时候增加操作列
		if($this->hasBtns())
		{
			$tds[]='<th class="nh" width="'.$this->setting['btnWidth'].'">操作</th>';
		}
		
		$thead_html='<thead><tr>'.implode("",$tds).'</tr></thead>';
		
		return $thead_html;
	}
	
	/*
		根据数据和配置获取显示的表格tbody
		@param array
		@return string
	*/
	public function getTbody(&$data)
	{
		$zd_show=$this->setting['zdShow'];
		
		if(empty($data))
		{
			$col_cnt=sizeof($zd_show);
			if(in_array($this->setting['selType'],array(1,2)))
			{
				$col_cnt++;
			}
			if($this->hasBtns())
			{
				$col_cnt++;
			}
			$tbody_html='<tbody><tr><td align="center" colspan="'.$col_cnt.'">'.L("nodata").'</td></tr></tbody>';
		
			return $tbody_html;
		}
				
		//处理表格主体
		$trs=array();
		$i=1;
		foreach($data as $row)
		{
			$tds=array();
			//当有选择列的时候增加选择列
			if(in_array($this->setting['selType'],array(1,2)))
			{
				if($this->setting['selType']==1)
				{
					$tds[]='<td><input name="row_sel" type="radio" value=""></td>';
				}
				else
				{
					$tds[]='<td><input name="row_sel" type="checkbox" value=""></td>';
				}
			}
			foreach($zd_show as $zd_name)
			{
				$cell_val=isset($row[$zd_name])?$row[$zd_name]:"";
				$tds[]=$this->getTdVal($zd_name,$cell_val,$row);
			}
			//当有操作按钮的时候增加操作按钮
			if($this->hasBtns())
			{
				$tds[]='<td class="nh">'.$this->getBtns($row).'</td>';
			}
			
			$tr_class=$i%2==0?"even":"odd";
			$i++;
			$trs[]='<tr class="'.$tr_class.'">'.implode("",$tds).'</tr>';
		}
		$tbody_html='<tbody>'.implode("",$trs).'</tbody>';
		
		return $tbody_html;
		
	}
	
	/*
		根据数据和配置获取js操作函数
		@return string
	*/
	public function getJs()
	{
		
	}
	
	
	/*
		根据数据和配置获取js操作函数
		@return string
	*/
	public function getTdVal($zd_name,$cell_val,$row_data)
	{
		$zd_type=$this->zdType[$zd_name];
		if($zd_type=="string")
		{
			
		}
		elseif($zd_type=="date")
		{
			$cell_val=$cell_val?date("Y-m-d",$cell_val):'-';
		}
		elseif($zd_type=="date1")
		{
			$cell_val=$cell_val?date("Y-m-d H:i:s",$cell_val):'-';
		}
		if(isset($this->setting['zdCallBack'][$zd_name]))
		{
			$cell_val=call_user_func($this->setting['zdCallBack'][$zd_name],$cell_val,$row_data);
		}
		$td_html='<td class="t">'.$cell_val.'</td>';
		return $td_html;
	}
	
	/*
		判断字段名称是否在字段列表里
		@return bool
	*/
	public function inZdList($zd_name)
	{
		return in_array($zd_name,$this->zdList);
	}
	
	/*
		根据行数据获取btn
		@return string
	*/
	public function getBtns($row)
	{
		$btns_arr=array();
		$pk_name=$this->setting['pkName'];
		$pkey_id=$row[$pk_name];
		$pkey_id_parse=is_string($pkey_id)?"'{$pkey_id}'":$pkey_id;
		
		if($this->setting['isEditBtn'])
		{
			$btns_arr[]=array("name"=>"编辑","func"=>$this->setting['editJsFunc']."({$pkey_id_parse},this);","url"=>U($this->setting['actionPre']."/".$this->setting['editName']."/".$pk_name."/".$pkey_id));
		}
		
		if($this->setting['isDropBtn'])
		{
			$btns_arr[]=array("name"=>"删除","func"=>$this->setting['dropJsFunc']."({$pkey_id_parse},this);","url"=>U($this->setting['actionPre']."/".$this->setting['dropName']."/".$pk_name."/".$pkey_id));
		}
		
		if($this->setting['isShowBtn'])
		{
			$btns_arr[]=array("name"=>"查看","func"=>$this->setting['showJsFunc']."({$pkey_id_parse},this);","url"=>U($this->setting['actionPre']."/".$this->setting['showName']."/".$pk_name."/".$pkey_id));
		}
		
		if($this->setting['btnCallBack'])
		{
			$btns_arr=call_user_func($this->setting['btnCallBack'],$btns_arr,$row);
		}
		
		$menu_html="";
		if($btns_arr)
		{
			foreach($btns_arr as $item)
			{
				if(!empty($menu_html))
				{
					$menu_html.='&nbsp;';
				}
				$menu_html.='<a '.$item['attr'].' onclick="'.$item['func'].';" href="javascript:;" url="'.$item['url'].'">'.$item['name'].'</a>';
		}	}
		
		return $menu_html;
	}
	
	/*
		判断是否有按钮
		@return string
	*/
	public function hasBtns()
	{
		$is_btn=$this->setting['isEditBtn'] || $this->setting['isDropBtn'] || $this->setting['isShowBtn'] || $this->setting['btnCallBack'];
		return $is_btn;
	}
	
	/*
		获取Db对象
		@return object
	*/
	public function getDb()
	{
		return $this->db;
	}
	
	/*
		从相关数据源获取主键的条件数组
		@return object
	*/
	public function getPKWhere($data)
	{
		$pkname=$this->setting['pkName'];

		$where=array();
		if(preg_match('/int|tinyint|smallint|mediumint|integer|bigint/i',$this->zdTypeMap[$pkname])!=0)	//如果是整数类型
		{
			$where[$pkname]=isset($data[$pkname])?intval($data[$pkname]):0;
		}
		else
		{
			$where[$pkname]=isset($data[$pkname])?"'".trim($data[$pkname])."'":"";
		}
		return $where;
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
}