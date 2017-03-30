<!--数据列表 beg-->
<div class="m-booklist">
<?php if($booklist_list){?>
<ul class="f-cb">
<?php 
foreach($booklist_list as $item){?>
<li class="f-cb"><div class="img"><a href="<?php echo U('Index/bookview/book_id/'.$item['book_id']);?>"><img class="u-bookimg" src="<?php echo $item['picture'];?>" /></a></div>
<div class="txt">
<h3 class="title"><a href="<?php echo U('Index/bookview/book_id/'.$item['book_id']);?>"><?php echo $item['title'];?></a></h3>
<p class="author"><?php echo $item['author']; echo $item['publish']?"&nbsp;/&nbsp;".$item['publish']:"";  echo $item['isbn']?"&nbsp;/&nbsp;".$item['isbn']:"";?></p>
<?php 
	if($item['ztc']){
		echo '<p>'.L('ztc').':'.$item['ztc'].'</p>';
	}
	echo '<p class="disc">'.L('readtimes').':'.$item['readtimes'].'</p>';
	echo '<p>'.L('fileformat').':'.$item['fileformat'].'</p>';
	echo '<p>'.L('joindate').':'.date("Y-m-d",$item['joindate']).'</p>';
?>
<p class="btn"><a class="u-btn" href="<?php echo U('Index/bookview/book_id/'.$item['book_id']);?>"><?php echo L("read");?></a></p>
</div>
</li>
<?php }?>
</ul>
<?php 
}
else
{
	echo '<div class="nodata_search"><h3>'.L('nodata_search').'</h3></div>';
}
?>
</div>
<!--数据列表 end-->
<?php include (MODULE_PATH."View/Public/page.php"); ?>