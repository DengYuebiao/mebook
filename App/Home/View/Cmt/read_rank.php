<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/iplimit.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script src="/Public/js/jquery.ui/jquery.ui.js" type="text/javascript"></script>'); ?>
<?php HtmlHeadBuff("push",'<script src="/Public/js/jquery.ui/i18n/zh-CN.js" type="text/javascript"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/js/jquery.ui/themes/redmond/jquery.ui.css" rel="stylesheet" type="text/css" media="screen"/>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
    <script type="text/javascript">
        $(function(){
            $('#time_beg').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth:true,
                changeYear:true
            });

            $('#time_end').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth:true,
                changeYear:true
            });
        })
    </script>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); use \Home\Extend\FormHelper\FormHelper; ?>
<!--搜索表单 beg-->
<div class="m-search">
<form method="get">
    日期：
    &nbsp;<input style="width:100px;" type="text" name="time_beg" id="time_beg" value="<?php echo $_GET['time_beg']?>" />-<input style="width:100px;" type="text" name="time_end" id="time_end" value="<?php echo $_GET['time_end']?>"/>
    &nbsp;&nbsp;<input class="u-btn" type="submit" value="<?php echo L('search')?>" />
    &nbsp;&nbsp;<input style="width:100px!important;" type="submit" class="u-btn" name="export_excel" value="导出为Excel" />
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