<?php HtmlHeadBuff("push",'<link href="/Public/css/home/fullsearch.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/tooltip.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/fullsearch.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
var tab_id="<?php echo $_GET['tab']?>";
tab_id=tab_id?tab_id:"tab1";
</script>
<!--文档内容 beg-->
<div class="g-bd f-cb">
<?php  use \Home\Extend\FormHelper\FormHelper; ?>
<!--搜索表单 beg-->
<div class="m-search" id="clc_search">
<form method="get">
<!--tab选项卡 beg-->
<div class="m-tab f-cb">

<div class="right">
<!--简单检索 beg-->
<div id="tab1">

<?php echo FormHelper::getSelectHtml($_GET['s1'],$zd_list,array('attr'=>'name="s1"')) ?>
&nbsp;<input type="text" name="v1" class="txt" value="<?php echo $_GET['v1']?>" />
&nbsp;<?php echo FormHelper::getSelectHtml($_GET['s2']?$_GET['s2']:'isbn',$zd_list,array('attr'=>'name="s2"')) ?>
&nbsp;<input type="text" name="v2" class="txt" value="<?php echo $_GET['v2']?>" />
&nbsp;<input type="hidden" name="tab" id="tab_val" value="tab1" />
&nbsp;&nbsp;<input class="u-btn" type="submit" value="<?php echo L('search')?>" />

</div>
<!--简单检索 end-->

</div>
</div>
<!--tab选项卡 end-->
</form>
</div>
<!--搜索表单 end-->

	<!--左侧内容 beg-->
    <div class="g-mn">
    	<!--子栏目一 beg-->
        <div class="m-box">
        <div class="inner"><div class="toolbar f-cb"><div class="title"><h2><?php echo $booklist_info['list_name'];?></h2></div></div>
        <?php include (MODULE_PATH."View/Index/booktpl.php"); ?></div>
        </div>
        <!--子栏目一 end-->
    </div>
    <!--左侧内容 end-->
    
    <!--右侧栏 beg-->
    <div class="g-sd">
       <?php include (MODULE_PATH."View/Index/fullsearch_right.php"); ?>
    </div>
    <!--右侧栏 end-->
</div>
<!--文档内容 end-->
<!-- <embed width="100%" height="800" name="plugin" src="<?php echo U('Index/show');?>" type="application/pdf">-->
<?php include (MODULE_PATH."View/Public/links.php");?>
<?php include (MODULE_PATH."View/Public/footer.php"); ?>