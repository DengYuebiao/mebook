<?php
namespace Home\Model;
class NavModel extends BaseModel {
	function getAddRule()
	{
		return array(
			"rules"=>array(
				"nav_name"=>array(
					"required"=>true,
					"rangelength"=>array(1,200)
				)
			)
		);
	}
	
	
	function getEditRule()
	{
		return array(
			"rules"=>array(
				"nav_name"=>array(
					"required"=>true,
					"rangelength"=>array(1,200)
				)
			)
		);
	}

	/*获取导航类型列表
	  @return array
	*/
	function getNavTypeList()
	{
		return array(
			1=>L('nav_type1'),
			2=>L('nav_type2'),
			3=>L('nav_type3'),
			4=>L('nav_type4'),
		);
	}
	
	/*获取系统栏目导航列表
	  @return array
	*/
	function getNavType1List()
	{
		return array(
			U('Index/index')=>L('homepage'),
			U('Index/fullsearch')=>L('index_fullsearch'),
			U('Index/clc')=>L('index_clc'),
			U('Index/sbook')=>L('index_sbook'),
			U('Index/video')=>L('index_video'),
		);
	}
	
	/*获取书单导航列表
	  @return array
	*/
	function getNavType2List()
	{
		$mod_booklist=D("Booklist");
		$list=$mod_booklist->getMap();
		return $list;
	}
	
	function getHtml()
	{
		$li_html='';
		$list=$this->order("order_num,nav_id")->select();
		$nav2_map=$this->getNavType2List();
		
		foreach($list as $item)
		{
			$is_new=$item['is_new']?' target="_blank"':"";
			if($item['nav_type']==1)
			{
				$attr=strpos($_SERVER['REQUEST_URI'],$item['nav_val'])!==false?' class="curr"':"";
				$li_html.='<li><a'.$attr.$is_new.' href="'.$item['nav_val'].'">'.$item['nav_name'].'</a></li>';
			}
			elseif($item['nav_type']==2)
			{
				$url=U('Index/booklist/booklist_id/'.$item['nav_val']);
				$attr=strpos($_SERVER['REQUEST_URI'],$url)!==false?' class="curr"':"";
				$li_html.='<li><a'.$attr.$is_new.' href="'.$url.'">'.$item['nav_name'].'</a></li>';
			}
			elseif($item['nav_type']==3)
			{
				$attr=strpos($_SERVER['REQUEST_URI'],$item['nav_val'])!==false?' class="curr"':"";
				$li_html.='<li><a'.$attr.$is_new.' href="'.$item['nav_val'].'">'.$item['nav_name'].'</a></li>';
			}
			elseif($item['nav_type']==4)
			{
				$li_html.='<li><span>|</span></li>';
			}
		}
		return $li_html;
	}
	
	/*
	查询书单是否被使用
	@param int 书单ID
	@return bool
	*/
	function isUse($booklist_id)
	{
		$tmp_info=$this->where("nav_type=2 AND nav_val={$booklist_id}")->find();
		$is_use=!empty($tmp_info);
		return $is_use;
	}
}
