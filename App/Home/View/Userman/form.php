<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/sbook.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/tooltip.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<script type="text/javascript">
$(function(){
	formValid($("#table_form"),<?php echo json_encode($valid_rule)?>,{
		success : function(label){
            label.addClass('form_valid_right').text('OK');
        }
	});	

})
</script>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); ?>
<?php use \Home\Extend\FormHelper\FormHelper; ?>
<div class="m-tableform">
<form method="post" name="table_form" id="table_form">
<table>
<thead>
<td align="center" colspan="2"><h3 class="title"><?php if(ACTION_NAME=="add"){echo L('add_data');}else{echo L('edit_data');}?></h3></th>
</thead>
<tbody>
<?php 
	echo(FormHelper::getTrsHtml($row_info,
			array(
				array('zdcnf'=>array(
					array('zd'=>'user_name','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
				)),
				array('zdcnf'=>array(
					array('zd'=>'user_pwd','type'=>'password','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
				)),
				array('zdcnf'=>array(
					array('zd'=>'user_pwd2','type'=>'password','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
				)),
				array('zdcnf'=>array(
					array('zd'=>'is_admin','type'=>'radio','attr'=>'class="radio"','param'=>array('list'=>array(0=>L('no'),1=>L('yes'))))
				)),
				array('zdcnf'=>array(
					array('zd'=>'real_name','type'=>'text','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
				)),
				array('zdcnf'=>array(
					array('zd'=>'sex','type'=>'radio','attr'=>'class="radio"','param'=>array('list'=>array(L('man'),L('woman')),'vt'=>"val",'default'=>L('man')))
				)),
				array('zdcnf'=>array(
					array('zd'=>'email','type'=>'text','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'phone','type'=>'text','attr'=>'class="text"')
				)),
				
			)
				
		));
?>
</tbody>
<tfoot>
<td colspan="2" align="center"><input class="u-btn u-btn-c3" type="submit" value="<?php echo L('submit_btn')?>" />&nbsp;&nbsp;<input class="u-btn u-btn-c2" type="reset" value="<?php echo L('reset_btn')?>" />&nbsp;&nbsp;<input class="u-btn u-btn-c5" type="button" onclick="goBack()" value="<?php echo L('return_btn')?>" /></td>
</tfoot>
</table>
</form>
</div>
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>