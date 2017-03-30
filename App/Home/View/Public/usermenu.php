<!--用户菜单 beg-->
<?php $menu_list=getData("user.menu");
	echo '<div class="m-user_nav">';
	$i=1;
	foreach($menu_list as $item)
	{
		$dt_attr=$i==1?' class="first"':'';
		echo '<dl><dt'.$dt_attr.'><span class="'.$item['ico'].'"></span>'.$item['title'].'</dt><dd><ul>';
		$menu_cnt=count($item['list']);
		foreach($item['list'] as $key1=>$item1)
		{
			$attr_tmp=strpos($_SERVER['REQUEST_URI'],$item1['url'])!==false?' class="active"':'';
			echo '<li'.$attr_tmp.'><a href="'.$item1['url'].'">'.$item1['name'].'</a></li>';
			
		}
		$i++;
		echo '</ul></dd></dl>';
	}
	echo '</div>';
?>
<!--用户菜单 end-->