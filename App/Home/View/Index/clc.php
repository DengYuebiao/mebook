<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/tooltip.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/clc.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/clc.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
var class_list=<?php echo $class_list?$class_list:"''";?>;
$(function(){
	classInit({'nobtn':1});
	$("#clclist").delegate("ul li .clc_info .clc_desc","click",function(e){
		clcSearch(this);
	});
})
</script>
<!--内容 beg-->
<div class="g-bd f-cb">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); use \Home\Extend\FormHelper\FormHelper; ?>

<!--搜索表单 beg-->
<div class="m-search" id="clc_search">
<form method="get">
<?php echo FormHelper::getSelectHtml($_GET['s1'],$zd_list,array('attr'=>'name="s1"')) ?>
&nbsp;<input type="text" name="v1" value="<?php echo $_GET['v1']?>" />
&nbsp;<?php echo FormHelper::getSelectHtml($_GET['s2']?$_GET['s2']:'isbn',$zd_list,array('attr'=>'name="s2"')) ?>
&nbsp;<input type="text" name="v2" value="<?php echo $_GET['v2']?>" />
&nbsp;&nbsp;<input class="u-btn" type="submit" value="<?php echo L('search')?>" />
</form>
</div>
<!--搜索表单 end-->

<!--左侧 beg-->
<div class="g-sd1">
<div class="m-clclist" id="clclist">
<?php
if($clc_list){
		foreach($clc_list as $item){
            echo '<div class="m-list2"><div class="m-clc_item"><img class="close" clc="'.$item['clc_id'].'" src="/Public/opac/images/ico1.png" /><a class="open" href="javascript:clc_search(\''.$item['clc'].'\');" title="'.$item['clc'].' '.$item['clc_desc'].'">'.$item['clc'].'&nbsp;'.$item['clc_desc'].'</a></div></div>';
		}
}
?>
</div>
</div>
<!--左侧 end-->

<!--右侧 beg-->
<div class="g-mn1" id="booklist">
<?php include (MODULE_PATH."View/Index/booktpl.php"); ?>
</div>
<!--右侧 end-->
</div>
<!--内容 end-->
<?php include (MODULE_PATH."View/Public/links.php");?>
<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>