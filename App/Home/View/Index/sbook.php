<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/sbook.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
$(function(){
	if(isIE())
	{
		$(".m-clclist").addClass("m-clclist-ie");
	}
})

function showSbook(pkey_id,elem)
{
	var h=getSite()+"/Index/sbookshow/sbook_id/"+pkey_id;
	location.href=h;
}

function clc_search(elem)
{
	var clc_val=$(elem).html();
	$("#clc_search #clc").val(clc_val);
	$("#clc_search form").submit();
}
</script>
<!--内容 beg-->
<div class="g-bd f-cb">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); use \Home\Extend\FormHelper\FormHelper; ?>

<!--搜索表单 beg-->
<div class="m-search" id="clc_search">
<form method="get">
<?php echo FormHelper::getSelectHtml($_GET['s1'],$zd_list,array('attr'=>'name="s1"')) ?>
&nbsp;<input type="text" name="v1" value="<?php echo $_GET['v1']?>" />
&nbsp;&nbsp;<input class="u-btn" type="submit" value="<?php echo L('search')?>" />
&nbsp;<input type="hidden" name="clc" id="clc" value="<?php echo $_GET['clc']?>" />
</form>
</div>
<!--搜索表单 end-->

<!--左侧 beg-->
<div class="g-sd1">
<div class="m-clctitle"><h3><?php echo L("sbook_all");?>(<?php echo $data_cnt_all;?>)</h3></div>
<div class="m-clclist" id="clclist">
<?php
if($class_list){
		echo '<ul>';
		foreach($class_list as $item){
			$attr=$_GET['clc']==$item?' curr':'';
            echo '<li><a class="clc_item'.$attr.'" href="javascript:;" onclick="clc_search(this);return false;">'.$item.'</a></li>';
		}
		echo '</ul>';
}
?>
</div>
</div>
<!--左侧 end-->

<!--右侧 beg-->
<div class="g-mn1" id="booklist">
<!--数据列表 beg-->
<div class="m-data">
<?php echo $table_html?>
<?php include (MODULE_PATH."View/Public/page.php"); ?>
</div>
<!--数据列表 end-->
</div>
<!--右侧 end-->
</div>
<!--内容 end-->
<?php include (MODULE_PATH."View/Public/links.php");?>
<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>