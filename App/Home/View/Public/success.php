<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/DD_belatedPNG.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<script type="text/javascript">
$(function(){
	if(isIE6())
	{
		DD_belatedPNG.fix('.img');
	}
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
});
</script> 
<style type="text/css">
html,body{height:100%;}
.msg_info{font-size:16px;}
.main{width:500px;position:relative;top:20%;margin:0 auto;	border:1px solid #D6E9C6;border-radius:10px; background-color:#DFF0D8; box-shadow: inset 0 2px 6px #C1E2B3;}
.main,.main a{color:#3C763D;}

table.error{margin:0 auto;}

.msg_title{ font-size:14px;
margin-left:-96px;}

.clear{clear:both;}
.f-fl{ float:left;}
#msgblock{width:100%; padding: 35px 20px;}
#msgblock .logo{margin-top:-8px;}
#msgblock .info{margin-top:15px; margin-left:10px;}
#msgblock .info p{margin-bottom:10px;}
#msgblock .title{font-weight:bold; font-size:16px;}
</style>

<div class="main">

<div id="msgblock">
<div class="logo f-fl"><IMG SRC='/Public/image/success1.png' class='img' align='absmiddle' width="96" height="96" BORDER='0' /></div>
<div class="info f-fl"><p><label class="title"><?php echo nl2br(htmlspecialchars($message)); ?></label></p>
<p><label class="label_2">页面将在<b id="wait"><?php echo($waitSecond); ?></b>秒后<present name="closeWin" >自动关闭<else/>自动跳转</present></label></p>
<p><label class="label_3">如果不想等待,直接点击<a id="href" href="<?php echo($jumpUrl); ?>">【跳转】</a></label></p></div>
<div class="clear"></div>
</div>
</div>
<?php include (MODULE_PATH."View/Public/footer.php"); ?>

