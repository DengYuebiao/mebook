<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/book.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); use \Home\Extend\FormHelper\FormHelper; ?>
    <script type="text/javascript">
        var SITE_URL_FULL="http://<?php echo ($_SERVER['HTTP_HOST']); ?>";
         function getFileName(o){
             var pos=o.lastIndexOf("\\");
              return o.substring(pos+1) ;
         }

         function down_tpl_file()
         {
             var url=SITE_URL_FULL+"/Userman/downtpl";
             location.href=url;
         }
    </script>
<!--搜索表单 beg-->
<div class="m-search">
<form method="get">
<?php echo FormHelper::getSelectHtml($_GET['s1'],$zd_list,array('attr'=>'name="s1"')) ?>
&nbsp;<input type="text" name="v1" value="<?php echo $_GET['v1']?>" />
&nbsp;<?php echo FormHelper::getSelectHtml($_GET['s2']?$_GET['s2']:'phone',$zd_list,array('attr'=>'name="s2"')) ?>
&nbsp;<input type="text" name="v2" value="<?php echo $_GET['v2']?>" />
&nbsp;&nbsp;<input class="u-btn" type="submit" value="<?php echo L('search')?>" />
<a class="u-btn u-btn-c3" type="button" href="<?php echo U('Userman/add')?>"><?php echo L('add')?></a>
&nbsp;&nbsp;<input class="u-btn u-btn-c3" type="button" value="批量导入" onclick="file.click()"/>
    <input name="button" style="width:100px;" id="down_err_btn" class="u-btn u-btn-c3" onclick="down_tpl_file()" type="button" value="下载示范Excel" />
<input class="u-btn" type="button" value="确定" onclick="sub.click()"/>
&nbsp;&nbsp;<input type="text" size="20"  id="upfile" style="border: none;background-color: white;width:110px" disabled="disabled">

</form>
<form method="post" enctype="multipart/form-data" style="display: none">
            <input class="u-btn u-btn-c3"  id="file" type="file" name="file" onchange=" upfile.value = getFileName(this.value)" style="display: none"/>
    <input class="u-btn"  id="sub" name="sub" type="submit" value="确定" style="display: none"/>
</form>
</div>
<!--搜索表单 end-->
<div class="m-data">
<?php echo $table_html;?>
<?php include (MODULE_PATH."View/Public/page.php"); ?>
</div>
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>