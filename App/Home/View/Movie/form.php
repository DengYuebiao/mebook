<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/movie.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/tooltip.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/movie.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>

<script type="text/javascript">
var file_list=<?php echo $file_list?$file_list:"''";?>;
$(function(){
	formValid($("#table_form"),<?php echo json_encode($valid_rule)?>,{
		success : function(label){
            label.addClass('form_valid_right').text('OK');
        }
	});	

	subtableInit();
})
</script>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); ?>
<?php use \Home\Extend\FormHelper\FormHelper; ?>
<div class="m-tableform">
<form method="post" name="table_form" id="table_form" enctype="multipart/form-data">
<table>
<thead>
<td align="center" colspan="4"><h3 class="title"><?php if(ACTION_NAME=="add"){echo L('add_data');}else{echo L('edit_data');}?></h3></th>
</thead>
<tbody>
<?php 
		echo(FormHelper::getTrsHtml($row_info,
			array(
				array('zdcnf'=>array(
					array('zd'=>'title','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>'),
					array('zd'=>'author','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'clc_name','type'=>'select','attr'=>'class="text"','param'=>array('list'=>$class_list,'vt'=>'val')),
					array('zd'=>'picture','type'=>'text','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'keyword','type'=>'text','attr'=>'class="text"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'introduce','type'=>'textarea','attr'=>'class="textarea"','tdattr'=>'colspan="3"')
				))
			)
		));
		echo '<tr><th>'.L('mv_addr').'<span class="field_notice u-must">*</span></th><td colspan="3"><div class="m-subtable" id="file_main"></div></td></tr>';

?>
</tbody>
<tfoot>
<td colspan="4" align="center"><input class="u-btn u-btn-c3" type="submit" value="<?php echo L('submit_btn')?>" />&nbsp;&nbsp;<input class="u-btn u-btn-c2" type="reset" value="<?php echo L('reset_btn')?>" />&nbsp;&nbsp;<input class="u-btn u-btn-c5" type="button" onclick="goBack()" value="<?php echo L('return_btn')?>" /></td>
</tfoot>
</table>
</form>
</div>
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>