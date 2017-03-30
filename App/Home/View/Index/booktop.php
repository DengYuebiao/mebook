<!--谁看过这本书 beg-->
<div class="m-readuser">
	<?php 
		$top_html='<div class="btn"><span class="sel">'.L('book_read_title').'</span></div>';
		echo $top_html;
		
		$ul_html='';
		
		$li_html='';
		$i=1;
		if($booktop['user'])
		{
			foreach($booktop['user'] as $item)
			{
				$user_name=\Home\Extend\FormHelper\FormHelper::msubstr($item['user_name'],0,16,"utf-8",true);
				$li_html.='<li><div class="img"><img class="port" src="/'.$item['portrait'].'" /></div><div class="title">'.$user_name.'</div></li>';
				$i++;
			}
		}
		else
		{
			$li_html='<li>'.L("noreader").'</li>';
		}
		$ul_html.='<ul class="f-cb">'.$li_html.'</ul>';
		
		$list_html='<div class="list">'.$ul_html.'</div>';
		
		echo $list_html;
	?>
</div>
<!--谁看过这本书 end-->

<!--图书右侧排行榜推荐 beg-->
<div class="m-indextop">
	<?php 
		$top_html='<div class="btn"><span class="sel">'.L('time_read_24').'</span></div>';
		echo $top_html;
		
		$ul_html='';
		
		$li_html='';
		$i=1;
		if($booktop['read'])
		{
			foreach($booktop['read'] as $item)
			{
				$title=\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,16,"utf-8",true);
				$li_html.='<li><span class="ico'.$i.'">'.$i.'</span><a href="'.U("Index/bookview/book_id/".$item['book_id']).'">'.$title.'</a></li>';
				$i++;
			}
		}
		else
		{
			$li_html='<li>'.L("nodata").'</li>';
		}
		$ul_html.='<ul>'.$li_html.'</ul>';
		
		$list_html='<div class="list">'.$ul_html.'</div>';
		
		echo $list_html;
	?>
</div>
<!--图书右侧排行榜推荐 end-->