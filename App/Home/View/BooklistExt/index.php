<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/booklist.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<script type="text/javascript">
$(function(){
	$("#booklist_id").change(function(e) {
        var h=getSite()+"/BooklistExt/index/booklist_id/"+$(this).val();
		location.href=h;
    });

})
</script>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); use \Home\Extend\FormHelper\FormHelper; ?>
<!--搜索表单 beg-->
<div class="m-search">
<form method="get">
<span><?php echo L('booklist_sel')?>&nbsp;</span><?php echo FormHelper::getSelectHtml($_GET['booklist_id'],$booklist_list,array('attr'=>'name="booklist_id" id="booklist_id"')) ?>&nbsp;&nbsp;
<?php echo FormHelper::getSelectHtml($_GET['s1'],$zd_list,array('attr'=>'name="s1"')) ?>
&nbsp;<input type="text" name="v1" value="<?php echo $_GET['v1']?>" />
&nbsp;&nbsp;<input class="u-btn" type="submit" value="<?php echo L('search')?>" />
&nbsp;<a class="u-btn" href="<?php echo U('Book/index');?>"><?php echo L('get_book')?></a>
</form>
</div>
<!--搜索表单 end-->
<div class="m-data">
<?php echo $table_html?>
<?php include (MODULE_PATH."View/Public/page.php"); ?>
</div>
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>