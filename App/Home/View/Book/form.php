<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/book.js"></script>'); ?>
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
<form method="post" name="table_form" id="table_form" enctype="multipart/form-data">
<table>
<thead>
<td align="center" colspan="4"><h3 class="title"><?php if(ACTION_NAME=="add"){echo L('add_data');}else{echo L('edit_data');}?></h3></th>
</thead>
<tbody>
<?php 
	if(ACTION_NAME=="add")
	{
		echo(FormHelper::getTrsHtml($row_info,
			array(
				array('zdcnf'=>array(
					array('zd'=>'title','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>'),
					array('zd'=>'author','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'isbn','type'=>'text','attr'=>'class="text"'),
					array('zd'=>'fileformat','type'=>'text','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'publish','type'=>'text','attr'=>'class="text"'),
					array('zd'=>'clc','type'=>'text','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'ztc','type'=>'text','attr'=>'class="text"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'keyword','type'=>'text','attr'=>'class="text"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'dir','type'=>'textarea','attr'=>'class="textarea"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'content','type'=>'textarea','attr'=>'class="textarea"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'fm_addr','type'=>'file','attr'=>'class="file"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'pdf_addr','type'=>'file','attr'=>'class="file"','tdattr'=>'colspan="3"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
				))
			)
				
		));
	}
	else
	{
		echo(FormHelper::getTrsHtml($row_info,
			array(
				array('zdcnf'=>array(
					array('zd'=>'title','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'<span class="field_notice u-must">*</span>'),
					array('zd'=>'author','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'isbn','type'=>'text','attr'=>'class="text"'),
					array('zd'=>'fileformat','type'=>'text','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'publish','type'=>'text','attr'=>'class="text"'),
					array('zd'=>'clc','type'=>'text','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'ztc','type'=>'text','attr'=>'class="text"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'keyword','type'=>'text','attr'=>'class="text"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'dir','type'=>'textarea','attr'=>'class="textarea"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'content','type'=>'textarea','attr'=>'class="textarea"','tdattr'=>'colspan="3"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'fm_addr','type'=>'file','attr'=>'class="text"','tdattr'=>'colspan="3"','before'=>'<a href="'.U('Index/showpic/book_id/'.$row_info['book_id']).'" target="_blank" class="u-btn u-btn-c5">'.L('view').'</a>&nbsp;')
				)),
				array('zdcnf'=>array(
					array('zd'=>'pdf_addr','type'=>'file','attr'=>'class="text"','tdattr'=>'colspan="3"','before'=>'<a href="'.U('Index/show/book_id/'.$row_info['book_id']).'" target="_blank" class="u-btn u-btn-c5">'.L('view').'</a>&nbsp;')
				))
			)
				
		));
	}
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