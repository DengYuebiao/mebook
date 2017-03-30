<?php HtmlHeadBuff("push",'<link href="/Public/css/home/user_index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/user.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php"); ?>
<?php include (MODULE_PATH."View/Public/nav.php");?>

<!--内容 beg-->
<div class="g-bd f-cb">	
<!--左侧栏 beg-->
<div class="g-sd1">
<?php include (MODULE_PATH."View/Public/usermenu.php"); ?>
</div>
<!--左侧栏 end-->
<!--右侧内容 beg-->
<div class="g-mn1">
    <div class="m-data m-bookmark">
	<?php echo $table_html?>
    <?php include (MODULE_PATH."View/Public/page.php"); ?>
    </div>
</div>
<!--右侧内容 end-->
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>