<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/iplimit.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); use \Home\Extend\FormHelper\FormHelper; ?>
<!--搜索表单 beg-->
<div class="m-search">
<form method="get">
<?php echo FormHelper::getSelectHtml($_GET['s1'],$zd_list,array('attr'=>'name="s1"')) ?>
&nbsp;<input type="text" name="v1" value="<?php echo $_GET['v1']?>" />
&nbsp;&nbsp;<input class="u-btn" type="submit" value="<?php echo L('search')?>" />
<a class="u-btn u-btn-c3" type="button" href="<?php echo U('Nav/add/nav_type/1')?>"><?php echo L('add_nav_type1')?></a>
<a class="u-btn u-btn-c3" type="button" href="<?php echo U('Nav/add/nav_type/2')?>"><?php echo L('add_nav_type2')?></a>
<a class="u-btn u-btn-c3" type="button" href="<?php echo U('Nav/add/nav_type/3')?>"><?php echo L('add_nav_type3')?></a>
<a class="u-btn u-btn-c3" type="button" href="<?php echo U('Nav/add/nav_type/4')?>"><?php echo L('add_nav_type4')?></a>
</form>
</div>
<!--搜索表单 end-->
<div class="m-data">
<?php echo $table_html?>
<?php include (MODULE_PATH."View/Public/page.php"); ?>
</div>
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>