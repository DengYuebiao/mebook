<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/tooltip.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/index.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
$(function(){
	initBooklist();
	initSlide();
	initTop();
	initTextMove();
});
</script>
<!--文档内容 beg-->
<div class="g-bd f-cb">
	<?php 
		  if($annou_list)
		  {
			  $annou_list=array_chunk($annou_list,count($annou_list)/3);
			 
			  echo '<div class="m-textroll"><div class="inner f-cb">';
			  
			  foreach($annou_list as $aitem)
			  {
				  echo '<ul class="f-cb">';
				  foreach($aitem as $item)
				  {
					  echo '<li><span class="u-ico1 u-ico1-8"></span><a class="title" href="'.U('Index/annou/annou_id/'.$item['annou_id']).'">'.\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,30).'</a></li>';
				  }
				  echo '</ul>';
			  }
			  
			  echo '</div></div>';
		  }
	?>
    
	<!--左侧内容 beg-->
    <div class="g-mn">
    	 <?php echo $datalist['left1_html'] ?>
         <?php echo $datalist['left2_html'] ?>
         <?php echo $datalist['left3_html'] ?>
    </div>
    <!--左侧内容 end-->
    
    <!--右侧栏 beg-->
    <div class="g-sd">
        <?php 
		if(empty($_user_info) || empty($_user_info['user_id']))
		{
			include (MODULE_PATH."View/Public/login_form.php"); 
		}
		else
		{
			include (MODULE_PATH."View/Index/userinfo.php"); 
		}
		?>
        <?php include (MODULE_PATH."View/Index/booktpl3.php"); ?>
        <div class="m-sysinfo">
        <div class="title"><h3><?php echo L("sysinfo_title"); ?></h3></div>
        <p><span class="u-ico1 u-ico1-5"></span><label><?php echo L("book");?>&nbsp;:&nbsp;<?php echo $datalist["res_cnt"]['book'].L("ce");?></label></p>
        <p><span class="u-ico1 u-ico1-4"></span><label><?php echo L("sbook");?>&nbsp;:&nbsp;<?php echo $datalist["res_cnt"]['sbook'].L("ce");?></label></p>
        <p><span class="u-ico1 u-ico1-6"></span><label><?php echo L("movie");?>&nbsp;:&nbsp;<?php echo $datalist["res_cnt"]['movie'].L("ce");?></label></p>
        <p><span class="u-ico1 u-ico1-7"></span><label><?php echo L("res_all");?>&nbsp;:&nbsp;<?php echo $datalist["res_cnt"]['all'].L("ce");?></label></p>
        </div>
        
        <div class="m-sysinfo">
        <div class="title"><h3><?php echo L("soft_down"); ?></h3></div>
        <p><span class="u-ico2 u-ico2-2"></span><a href="/Public/soft/pdf.exe" target="_blank"><?php echo L("pdf_title");?></a></p>
        <p><span class="u-ico2 u-ico2-1"></span><a href="/Public/soft/bdplay.exe" target="_blank"><?php echo L("bd_title");?></a></p>
        </div>
    </div>
    <!--右侧栏 end-->
</div>
<!--文档内容 end-->
<?php include (MODULE_PATH."View/Public/links.php");?>
<?php include (MODULE_PATH."View/Public/footer.php"); ?>