<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index_reg.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",$_token['meta']); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<script type="text/javascript">
$(function(){
	formValid($("#table_form"),<?php echo json_encode($valid_rule)?>,{
		success : function(label){
            label.addClass('form_valid_right').text('OK');
        }
	});	
	
	$("#regname").focus();
})
</script>
<!--内容 beg-->
<div class="g-bd">
<div class="m-sysreg">
	<h2><span class="u-ico u-ico-8"></span><?php echo L("sysreg_title"); ?></h2>
	<div class="form">
    <form method="post" id="table_form">
    <table>
<tr><td width="100"><?php echo L("mcode"); ?>:</td><td width="400"><input type="text" disabled="disabled" name="mcode" class="ipt" value="<?php echo $mcode; ?>" /></td></tr>
    <tr><td><?php echo L("reg_name"); ?>:</td><td><input type="text" name="regname" id="regname" class="ipt" value="" /></td></tr>
    <tr><td><?php echo L("reg_code"); ?>:</td><td><input type="text" name="regcode" class="ipt" value="" /></td></tr>
    <tr><td colspan="2" align="center"><input class="u-btn u-btn-c3" type="submit" value="<?php echo L("reg"); ?>" /></td></tr>
    <tr><td colspan="2"><?php echo sprintf(L("reg_desc"),$_sys['gs_site'],$_sys['gs_name'],$_sys['tel']); ?></td></tr>
</table>
</form>
    </div>
</div>    
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>