<?php HtmlHeadBuff("push",'<link href="/Public/css/home/booklist.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/tooltip.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
 //var url="http://libnet.cn/api.php/Dz/lend/barcode/00001";
 //var url="http://libnet.cn/Public/islogin";	
 //var url="http://libnet.cn/Public/login";	
 //var url="http://task.me/api/user/login";
 /* var url="http://libnet.cn/Api/dz/lend/barcode/00001";
			 $.ajax({
				 url:url,
				 dataType:"json",
				 data:{'user_name':'admin','user_pwd':'123456'},
				 async:false,
				 type:"POST",
				 success: function(ret){
					  alert(ret.info);
					  
					  if(ret.status)
					  {
						
					  }
					  else
					  {
						  
					  }
					 
				  }});*/

</script>
	<!--文档内容 beg-->
<div class="g-bd f-cb">
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
       <?php include (MODULE_PATH."View/Index/booklist_top.php"); ?>
    </div>
    <!--右侧栏 end-->
</div>
<!--文档内容 end-->
<!-- <embed width="100%" height="800" name="plugin" src="<?php echo U('Index/show');?>" type="application/pdf">-->
<?php include (MODULE_PATH."View/Public/links.php");?>
<?php include (MODULE_PATH."View/Public/footer.php"); ?>