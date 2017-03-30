<?php $mod_user=D("User");		
	   $valid_rule=$mod_user->getLoginRule();
?>
<script type="text/javascript">
$(function(){
	formValid($("#user_login_form"),<?php echo json_encode($valid_rule)?>,{
		errorPlacement: function(error, element){
			new toolTip(element,error,"top");			
        },
		success : function(label){
            label.addClass('form_valid_right').text('OK');
        }
	});	

})
</script>
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