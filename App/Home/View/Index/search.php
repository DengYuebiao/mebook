<?php HtmlHeadBuff("push",'<link href="/Public/css/home/booklist.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<!--文档内容 beg-->
<div class="g-bd f-cb m-search_main">
	<div class="m-box">
        <div class="inner"><div class="toolbar f-cb"><div class="title"><h2><?php echo L("search_title").'"'.strip_tags(stripslashes($_GET["sv"])).'"'.L("search_title1");?></h2></div></div>
        <?php include (MODULE_PATH."View/Index/booktpl.php"); ?></div>
     </div>
</div>
<!--文档内容 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>