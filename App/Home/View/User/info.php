<?php HtmlHeadBuff("push",'<link href="/Public/css/home/user_index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/tooltip.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/user.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/movie.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php"); ?>
<?php include (MODULE_PATH."View/Public/nav.php");?>

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
<div class="g-bd f-cb">	
<!--左侧栏 beg-->
<div class="g-sd1">
<?php include (MODULE_PATH."View/Public/usermenu.php"); ?>
</div>
<!--左侧栏 end-->
<!--右侧内容 beg-->
<div class="g-mn1">
<?php use \Home\Extend\FormHelper\FormHelper; ?>
<div class="m-tableform">
<form method="post" name="table_form" id="table_form" enctype="multipart/form-data">
<table>
<thead>
<td align="center" colspan="2"><h3 class="title"><?php echo L('user_info');?></h3></th>
</thead>
<tbody>
<?php 
	echo(FormHelper::getTrsHtml($row_info,
			array(
				array('zdcnf'=>array(
					array('zd'=>'portrait','thattr'=>'width="20%"','tdattr'=>'width="80%"','type'=>'file','attr'=>'class="file"','before'=>'<img src="/'.$row_info['portrait'].'" />&nbsp;','after'=>L('portrait_desc').'<div id="sel_main"><input type="hidden" name="port_type" id="port_type" value="1" /><input type="hidden" name="port_sys" id="port_sys" value="" /><button onclick="return selSysPort(this);" class="u-btn u-btn-c4">'.L('sel_sys_port').'</button></div>')
				)),
				array('zdcnf'=>array(
					array('zd'=>'real_name','type'=>'text','attr'=>'class="text"')
				)),
				array('zdcnf'=>array(
					array('zd'=>'user_pwd','type'=>'password','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
				)),
				array('zdcnf'=>array(
					array('zd'=>'user_pwd2','type'=>'password','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
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
<td colspan="2" align="center"><input class="u-btn u-btn-c3" type="submit" value="<?php echo L('submit_btn')?>" />&nbsp;&nbsp;<input class="u-btn u-btn-c2" type="reset" value="<?php echo L('reset_btn')?>" /></td>
</tfoot>
</table>
</form>
</div>
</div>
<!--右侧内容 end-->
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>