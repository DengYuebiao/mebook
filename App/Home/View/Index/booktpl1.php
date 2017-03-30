<!--首页书单 beg-->
<div class="m-box">
<div class="inner">
         <?php if($booklist_info){?>
        <div class="toolbar f-cb"><div class="title"><h2><?php echo $booklist_info['list_name'];?></h2></div><div class="more"><a href="<?php echo U('Index/booklist/booklist_id/'.$booklist_info['booklist_id']);?>"><?php echo L('show_more');?></a></div><div class="page">
        <?php 
			$booklist_cnt=count($booklist_info['booklist']);
			for($i=0;$i<$booklist_cnt;$i++)
			{
				$class=$i==0?' class="sel"':'';
				echo '<span'.$class.' pos="'.($i+1).'"></span>';
			}
		?>
        </div></div>
        <div class="content">
            <?php 	
			if($booklist_info['booklist']){ 
			$i=1;
			foreach($booklist_info['booklist'] as $sublist){
				$ul_class="";
				if($i>1)
				{
					$ul_class=" hide";
				}
				
			?> 
				<ul class="f-cb<?php echo $ul_class;?>" pos="<?php echo $i;?>">
             <?php 	
					foreach($sublist as $item){
			?>         
<li class="f-cb"><div class="img"><a href="<?php echo U('Index/bookview/book_id/'.$item['book_id']);?>"><img title="<?php echo $item['title'];?>" class="u-bookimg" src="<?php echo $item['picture'];?>" /></a></div>
<div class="txt">
<h3 class="title"><a title="<?php echo $item['title'];?>" href="<?php echo U('Index/bookview/book_id/'.$item['book_id']);?>"><?php echo \Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,12);?></a></h3>
<p class="author"><?php echo $item['author']; echo $item['publish']?"&nbsp;/&nbsp;".$item['publish']:"";?></p>
<?php 
	if($item['ztc']){
		echo '<p>'.L('ztc').':'.$item['ztc'].'</p>';
	}
	echo '<p class="disc">'.L('readtimes').':'.$item['readtimes'].'</p>';
	echo '<p>'.L('joindate').':'.date("Y-m-d",$item['joindate']).'</p>';
?>
<p class="btn"><a class="u-btn" href="<?php echo U('Index/bookview/book_id/'.$item['book_id']);?>"><?php echo L("read");?></a></p>
</div>
</li>
	<?php }?>
	</ul>
	<?php
	$i++;
    }	
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