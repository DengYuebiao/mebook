<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index_reg.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",$_token['meta']); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
$(function(){
	$("#user_name")[0].focus();
	formValid($("#user_reg_form"),<?php echo json_encode($valid_rule)?>);

})
</script>
<!--内容 beg-->
<div class="g-bd">
<div class="m-user_reg">
<div class="head"><h1><?php echo L("welcode_reg");?></h1></div>
<div class="form f-cb"> 
<div class="left"><form method="post" id="user_reg_form">
 <ul>
<li><label class="label"><span class="u-must">*</span><? echo L("user_name")?></label><input type="text" class="input" name="user_name" id="user_name" value="" onkeydown="return enterToNext(event,'#user_pwd');" /></li>
<li><label class="label"><span class="u-must">*</span><? echo L("user_pwd")?></label><input type="password" class="input" name="user_pwd" id="user_pwd" value=""  onkeydown="return enterToNext(event,'#user_pwd2');" /></li>
<li><label class="label"><span class="u-must">*</span><? echo L("user_pwd2")?></label><input type="password" class="input" name="user_pwd2" id="user_pwd2" value="" onkeydown="return enterToNext(event,'#real_name');" /></li>
<li><label class="label"><span class="u-must">*</span><? echo L("real_name")?></label><input type="text" class="input" name="real_name" id="real_name" value="" onkeydown="return enterToNext(event,'#email');" /></li>
<li><label class="label"><? echo L("email")?></label><input type="text" class="input" name="email" id="email" value="" onkeydown="return enterToNext(event,'#phone');" /></li>
<li><label class="label"><? echo L("phone")?></label><input type="text" class="input" name="phone" id="phone" value="" onkeydown="return enterToNext(event,'#vercode');" /></li>
<li><label class="label"><span class="u-must">*</span><? echo L("vercode")?></label><input type="text" class="input" name="vercode" id="vercode" value="" />&nbsp;<img onclick="this.src='<?php echo U("Index/vercode");?>?'+Math.random();" class="vercode" src="<?php echo U("Index/vercode");?>" /></li>
<li class="f-tac"><input class="u-btn u-btn-c3" type="submit" name="btn" value="<? echo L("register")?>" /></li>
</ul>
<? echo $_token['input'];?>     
</form></div>

<div class="right">
<img src="/Public/image/reg_bg.png" />
<ul>
<li><?php echo L("person_book");?></li>
<li><?php echo L("comment_man");?></li>
<li><?php echo L("read_his");?></li>
</ul>
</div>
</div>
 </div>
    
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>