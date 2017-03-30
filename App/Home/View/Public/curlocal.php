<!--面包屑导航 beg-->
<?php if($_curlocal){?>
<div class="m-curlocal">
	<div class="inner">
	<img width="16" height="16" alt="ebook" src="/Public/image/home_logo.gif">
    <?php 
		foreach($_curlocal as $key=>$item){
			if($item['url'])
			{
				echo '<i>&gt;</i><a onclick="setCookie(\'_menu_pos\',\''.$key.'\',90,\'/\')" href="'.$item['url'].'">'.$item['name'].'</a>';
			}
			else
			{
				echo '<i>&gt;</i>'.$item['name'].'';
			}
		}
		?>
</div>
</div>
<?php }?>
<!--面包屑导航 end-->