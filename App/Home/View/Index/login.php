<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index_login.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.validate.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/tooltip.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/index.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php //include (MODULE_PATH."View/Public/topbar.php");?>
<?php //include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
$(function(){
	initLoginBg($("#login_bg"));
	$("[name='user_name']")[0].focus();
})
</script>
<style type="text/css">
    body{
       width:100%;
       height:100%;
       background:url("/Public/image/login_bg.jpg")  no-repeat ;
       background-size:cover;
    }
</style>
<div class="g-login">
<!--内容 beg-->
<div class="g-bd f-cb">
<!--右侧栏 beg-->
<div class="g-sd">
<?php //include (MODULE_PATH."View/Public/login_form.php"); ?>
    <!--登陆模块 beg-->
    <div class="m-login">
        <div class="inner">
            <form method="post" id="user_login_form" action="<?php echo U('Index/login');?>">
                <div class="title"><h2><?php echo L('user_login');?></h2></div>
                <div class="user_name"><input  onkeydown="return enterToNext(event,'#user_pwd');" type="text" name="user_name" class="ipt" value="" /></div>
                <div class="user_pwd"><input type="password" name="user_pwd" id="user_pwd" class="ipt" value="" /></div>
                <div class="ext">
                    <label for="login_remember"><input title="<?php echo L('login');?>" class="login_remember f-vam" type="checkbox" id="login_remember" name="login_remember" value="1"><?php echo L('login_remember');?></label>
                </div>
                <input class="u-btn u-btn-c3 u-btn-med submit" type="submit" value="<?php echo L('login');?>" /><input class="u-btn u-btn-c4 u-btn-med reg_btn" type="button" onclick="location.href='<?php echo U("Index/reg");?>';" value="<?php echo L('register');?>" />
            </form>
        </div>
    </div>
    <!--登陆模块 end-->
</div>
<!--右侧栏 end-->
</div>
<!--内容 end-->
</div>
<?php include (MODULE_PATH."View/Public/footer.php"); ?>