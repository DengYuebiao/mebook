<!--头部工具条 beg-->
<div class="m-topbar">
<div class="inner f-cb">
<ul class="f-fl f-cb">
<li><a href="<?php echo U('Index/index')?>"><?php echo L('home_page')?></a></li>
<?php if($_user_info){?>
<li><span><?php echo $_user_info['user_name']?>,<?php echo L('welcome')?></span></li>
<?php }else{?>
<li><a href="<?php echo U('Index/reg')?>"><?php echo L('register')?></a></li>
<li><a href="<?php echo U('Index/login')?>"><?php echo L('login')?></a></li>
<?php }?>
</ul>
<ul class="top_right f-fr f-cb">
<?php if($_user_info){?>
<!--<li><a href="--><?php //echo U('User/index')?><!--">--><?php //echo L('user_center')?><!--</a></li>-->
    <li><a href="<?php echo U('User/logout')?>"><?php echo L('sign_out')?></a></li>
<?php }else{?>
<?php }?>
<?php /*if($_user_info['is_admin']){*/?><!--
<li><a href="<?php /*echo U('Admin/info')*/?>"><?php /*echo L('backman')*/?></a></li>
<?php /*}else{*/?>
<?php /*}*/?>
<li><a href="javascript:;" onclick="addFav()"><?php /*echo L('addfav')*/?></a></li>-->

</ul>
</div>
</div>
<!--头部工具条 end-->