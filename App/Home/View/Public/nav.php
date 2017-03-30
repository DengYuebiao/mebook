<!--头部内容 beg-->
<div class="m-head">
<!--头部banner beg-->
<div class="banner">
	<!--网站logo beg-->
	<div class="logo">
    <?php 
	$site_logo=getOption("sys_site_logo");
	if($site_logo)
	{
		echo '<img src="/'.$site_logo.'" />';
	}
	else
	{
		echo '<img src="/Public/image/logo.png" />';
	}
	?>
    </div>
    <!--网站logo end-->
	<!--搜索表单 beg-->
	<div class="search_form"><form action="<?php echo U('Index/search');?>">
<input type="text" name="sv" class="txt" value="<?php echo strip_tags(stripslashes($_GET["sv"]));?>" /><input type="submit" class="btn" value="<?php echo L('search1')?>" />
</form>
	</div>
    <!--搜索表单 end-->
</div>
<!--头部banner end-->

<!--头部导航菜单 beg-->
<div class="nav_menu">
<div class="inner"><ul class="f-cb">
<?php echo D("Nav")->getHtml()?>
</ul>
</div>
</div>
<!--头部导航菜单 end-->
</div>
<!--头部内容 end-->