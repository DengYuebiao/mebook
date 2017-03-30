<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/index.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<script type="text/javascript">
   var pdfurl="<?php echo U("Index/showpdf/book_id/".$book_id)?>";
   var filetype="<?php echo $book_info['fileformat']?>";
   if(filetype=="pdf")
   {
	   var has_plugin=hasPdfPlugin();
	   if(has_plugin)
	   {
		   showPdf(pdfurl);
	   }
	   else
	   {
		   $("#pdfalert").show();
	   }
   }
   else
   {
	   showPdf(pdfurl);
   }
</script>
<!--内容 beg-->
<div class="m-pdfalert" id="pdfalert">
	<div class="g-bd">
	<h2><?php echo L('not_pdf_reader');?></h2>
	<div class="btns">
    <a href="<?php echo U('Index/downadpdf');?>" target="_blank" class="u-btn u-btn-c3"><?php echo L('setup_pdf');?></a>&nbsp;&nbsp;<a href="<?php echo U("Index/showpdf/book_id/".$book_id)?>" target="_blank" class="u-btn u-btn-c2"><?php echo L('open_pdf');?></a>
    </div>
    </div>
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>