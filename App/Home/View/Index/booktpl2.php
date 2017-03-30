<!--首页书单 beg-->
<div class="m-box">
<div class="inner">
         <?php if($booklist_info){?>
        <div class="toolbar f-cb"><div class="title"><h2><?php echo $booklist_info['list_name'];?></h2></div><div class="more"><a href="<?php echo U('Index/booklist/booklist_id/'.$booklist_info['booklist_id']);?>"><?php echo L('show_more');?></a></div></div>
        <div class="content m-slide">
            <?php 	
			if($booklist_info['booklist']){ 
			echo '<ul class="f-cb">';
			$i=1;
			foreach($booklist_info['booklist'] as $sublist){
				foreach($sublist as $item){
			?>         
<li class="f-cb"><div class="img"><a href="<?php echo U('Index/bookview/book_id/'.$item['book_id']);?>"><img title="<?php echo $item['title'];?>" class="u-bookimg" src="<?php echo $item['picture'];?>" /></a>
<h4 class="title"><a title="<?php echo $item['title'];?>" href="<?php echo U('Index/bookview/book_id/'.$item['book_id']);?>"><?php echo \Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,8,"utf-8",false);?></a></h4></div>
</li>
	<?php }
	$i++;
    }	
	echo '</ul>';
	 }	else{
		echo '<div class="booklist_nobook"><h3>'.L('booklist_nobook').'</h3></div>';
	}
	?>
</div>
<?php 
}
else
{
	echo '<div class="index_nobooklist"><h3>'.L('index_nobooklist').'</h3></div>';
}
?>
</div>
</div>
<!--首页书单 end-->