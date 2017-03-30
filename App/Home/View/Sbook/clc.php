<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/sbook.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/sbook.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<script type="text/javascript">
var class_list=<?php echo $class_list?$class_list:"''";?>;
var not_clc_list=<?php echo $not_clc_list?$not_clc_list:"''";?>;
$(function(){
	classInit();
})
</script>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); use \Home\Extend\FormHelper\FormHelper; ?>

<div class="m-tableform" id="clc_main">
<form method="post">
<table>
<thead>
<td align="center" colspan="4"><h3 class="title"><?php echo L('sbook_clc');?></h3></th>
</thead>
<tbody>
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