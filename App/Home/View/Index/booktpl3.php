<!--首页右侧排行榜 beg-->
<div class="m-indextop">
	<?php 
		$top_html='<div class="btn"><span class="sel">'.L('top_title1').'</span><span>'.L('top_title2').'</span><span>'.L('top_title3').'</span></div>';
		echo $top_html;
		
		$ul_html='';
		
		$li_html='';
		$i=1;
		foreach($datalist['good'] as $item)
		{
			$title=\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,16,"utf-8",true);
			$li_html.='<li><span class="ico'.$i.'">'.$i.'</span><a href="'.U("Index/bookview/book_id/".$item['book_id']).'">'.$title.'</a></li>';
			$i++;
		}
		$ul_html.='<ul>'.$li_html.'</ul>';
		
		$li_html='';
		$i=1;
		foreach($datalist['read'] as $item)
		{
			$title=\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,12,"utf-8",true);
			$li_html.='<li><span class="ico'.$i.'">'.$i.'</span><a href="'.U("Index/bookview/book_id/".$item['book_id']).'">'.$title.'</a></li>';
			$i++;
		}
		$ul_html.='<ul class="hide">'.$li_html.'</ul>';
		
		$li_html='';
		$i=1;
		foreach($datalist['sc'] as $item)
		{
			$title=\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,12,"utf-8",true);
			$li_html.='<li><span class="ico'.$i.'">'.$i.'</span><a href="'.U("Index/bookview/book_id/".$item['book_id']).'">'.$title.'</a></li>';
			$i++;
		}
		$ul_html.='<ul class="hide">'.$li_html.'</ul>';
		
		$list_html='<div class="list">'.$ul_html.'</div>';
		
		echo $list_html;
	?>
</div>
<!--首页书单 end-->