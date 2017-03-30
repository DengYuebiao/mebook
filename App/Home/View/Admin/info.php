<?php HtmlHeadBuff("push",'<link href="/Public/css/home/admin_index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/bootstrap.min.css" rel="stylesheet" type="text/css"/>'); ?>
<script src="/Public/js/jquery-1.8.3.min.js"></script>
<script src="/Public/js/home/modal.js"></script>
<?php //HtmlHeadBuff("push",'<link href="/Public/css/modals.less" rel="stylesheet/less" />'); ?>
<!--<script src="/Public/js/less.min.js"></script>-->
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<script type="text/javascript">
$(function(){

})
</script>
<style type="text/css">
	  body,input,td {font: 12px/1.14 'Microsoft YaHei',simsun,Arial, Helvetica, sans-serif,\5b8b\4f53;}
	  td{text-align:center}
	  select{width: 125px;margin-right:10px}
	  .modal-body tr td {
		   text-align:left;
		   padding: 5px;
		   font: 12px/1.14 'Microsoft YaHei',simsun,Arial, Helvetica, sans-serif,\5b8b\4f53;
	  }
	  /*.modal-body table tr{text-align:center}*/
	  ul{margin-bottom: 0}
	  body{line-height:1}
</style>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); ?>
<?php use \Home\Extend\FormHelper\FormHelper; ?>
<div class="m-tableform">
<form method="post" name="table_form" id="table_form" enctype="multipart/form-data">
<table>
<!--<thead>
<td  colspan="5"><h3 class="title"><?php /*echo L('user_manage');*/?></h3></td>
</thead>-->
<tbody>
<?php
	/*echo(FormHelper::getTrsHtml($row_info,
			array(
				array('zdcnf'=>array(
					array('zd'=>'sys_reg_name','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
				)),
			)
	));*/
?>
<tr><td  colspan="5"><select><option><?php echo L("user_bm")?></option><option><?php echo L("user_name")?></option><option><?php echo L("user_real")?></option><option><?php echo L("user_status")?></option></select><input name="user_name" style="width: 35%"/>&nbsp;&nbsp;<input type="button" class="u-btn" value="<?php echo L('find')?>" style="width: 50px"/></td></tr>
<tr><td><?php echo L("user_bm")?></td><td><?php echo L("user_name")?></td><td><?php echo L("user_real")?></td><td><?php echo L('user_status')?></td></tr>
<tr><td colspan="5"></td></tr>
</tbody>
<tfoot>
<td colspan="5" align="center"><input class="u-btn u-btn-c3"  data-toggle="modal" data-target="#myModal" type="button" value="<?php echo L('add')?>" />&nbsp;&nbsp;<input class="u-btn u-btn-c3" data-toggle="modal" data-target="#myModal"  type="button" value="<?php echo L('edit')?>" />&nbsp;&nbsp;<input class="u-btn u-btn-c2" type="button" value="<?php echo L('drop')?>" />&nbsp;&nbsp;<input class="u-btn u-btn-c5" type="button" onclick="goBack()" value="<?php echo L('return_btn')?>" /></td>
</tfoot>
</table>
</form>
</div>
</div>
<!--内容 end-->
<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo L('add_data')?></h4>
			</div>
			<div class="modal-body" align="center">
				<table>
					<tbody>
					<tr><td><?php echo L('user_real')?>：<input type="text"/></td><td><?php echo L('user_name')?>：<input type="text"/></td></tr>
					<tr><td><?php echo L('user_pwd')?>：<input type="password"/></td><td><?php echo L('user_phone')?>：<input type="text"/></td></tr>
					<tr><td><?php echo L('user_bm')?>：<select></select></td><td><?php echo L('user_group')?>：<select></select></td></tr>
					<tr><td><?php echo L('user_status')?>：<select></select></td></tr>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">提交</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
