<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/iplimit.js"></script>'); ?>
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
		if($row_info['nav_type']==1)
		{
			echo(FormHelper::getTrsHtml($row_info,
				array(
					array('zdcnf'=>array(
						array('zd'=>'nav_name','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>'),
					)),
					array('zdcnf'=>array(
						array('zd'=>'nav_type','type'=>'span','attr'=>'class="text"','param'=>array('val'=>L('nav_type1')))
					)),
					array('zdcnf'=>array(
						array('zd'=>'nav_val','type'=>'select','attr'=>'class="text"','param'=>array('list'=>$nav_type1_list))
					)),
					array('zdcnf'=>array(
						array('zd'=>'is_new','type'=>'radio','attr'=>'class="radio"','param'=>array('list'=>array(0=>L('no'),1=>L('yes'))))
					)),
					array('zdcnf'=>array(
						array('zd'=>'order_num','type'=>'text','attr'=>'class="text"')
					))
				)
			));
		}
		elseif($row_info['nav_type']==2)
		{
			echo(FormHelper::getTrsHtml($row_info,
				array(
					array('zdcnf'=>array(
						array('zd'=>'nav_name','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>'),
					)),
					array('zdcnf'=>array(
						array('zd'=>'nav_type','type'=>'span','attr'=>'class="text"','param'=>array('val'=>L('nav_type2')))
					)),
					array('zdcnf'=>array(
						array('zd'=>'nav_val','type'=>'select','attr'=>'class="text"','param'=>array('list'=>$nav_type2_list))
					)),
					array('zdcnf'=>array(
						array('zd'=>'is_new','type'=>'radio','attr'=>'class="radio"','param'=>array('list'=>array(0=>L('no'),1=>L('yes'))))
					)),
					array('zdcnf'=>array(
						array('zd'=>'order_num','type'=>'text','attr'=>'class="text"')
					))
				)
			));
		}
		elseif($row_info['nav_type']==3)
		{
			echo(FormHelper::getTrsHtml($row_info,
				array(
					array('zdcnf'=>array(
						array('zd'=>'nav_name','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>'),
					)),
					array('zdcnf'=>array(
						array('zd'=>'nav_type','type'=>'span','attr'=>'class="text"','param'=>array('val'=>L('nav_type3')))
					)),
					array('zdcnf'=>array(
						array('zd'=>'nav_val','type'=>'text','attr'=>'class="text"')
					)),
					array('zdcnf'=>array(
						array('zd'=>'is_new','type'=>'radio','attr'=>'class="radio"','param'=>array('list'=>array(0=>L('no'),1=>L('yes'))))
					)),
					array('zdcnf'=>array(
						array('zd'=>'order_num','type'=>'text','attr'=>'class="text"')
					))
				)
			));
		}
		elseif($row_info['nav_type']==4)
		{
			echo(FormHelper::getTrsHtml($row_info,
				array(
					array('zdcnf'=>array(
						array('zd'=>'nav_type','type'=>'span','attr'=>'class="text"','param'=>array('val'=>L('nav_type4')))
					)),
					array('zdcnf'=>array(
						array('zd'=>'order_num','type'=>'text','attr'=>'class="text"')
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