<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/book.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/mydialog.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/book.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<script type="text/javascript">
var class_list=<?php echo $class_list?$class_list:"''";?>;
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
<td align="center"><h3 class="title"><?php echo L('book_clc');?></h3></th>
</thead>
<tbody>
<tr><td>
<div class="m-clclist" id="clclist">
<?php
if($clc_list){
		foreach($clc_list as $item){
            echo '<div class="m-list2"><div class="m-clc_item"><img class="close" clc="'.$item['clc_id'].'" src="/Public/opac/images/ico1.png" /><a class="open" href="javascript:clc_search(\''.$item['clc'].'\');" title="'.$item['clc'].' '.$item['clc_desc'].'">'.$item['clc'].'&nbsp;'.$item['clc_desc'].'</a></div></div>';
		}
}
?>
</div>
</td></tr>
</tbody>
<tfoot>
<td align="center"><input class="u-btn u-btn-c5" type="button" onclick="goBack()" value="<?php echo L('return_btn')?>" /></td>
</tfoot>
</table>
</form>
</div>
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>