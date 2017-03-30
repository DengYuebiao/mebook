<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="/Public/Image/ico_tc.ico"> 
<link href="/Public/css/global.css" rel="stylesheet" type="text/css"/>
<link href="/Public/css/module.css" rel="stylesheet" type="text/css"/>
<link href="/Public/css/home/admin_index.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/Public/js/common.js"></script>
<script type="text/javascript" src="/Public/js/jquery_plug/DD_belatedPNG.js"></script>
<script type="text/javascript" src="/Public/js/home/admin.js"></script>
<?php echo HtmlHeadBuff("pop");?>
<title><?php echo $_page_title;?></title>
<style type="text/css">
	a:hover{text-decoration:none}
	a:active{text-decoration:none}
	div .f-cb{display:inline}
</style>
<script type="text/javascript">
var lang_list=<?php echo $lang_list?$lang_list:"''";?>;
$(function(){
	admin_top_init();
});
</script>
</head>
<body>
<?php include (MODULE_PATH."View/Public/topbar.php"); ?>
<!--后台管理导航菜单 beg-->
<div class="m-admin_nav" id="admin_menu" >
<div class="inner f-cb" >
<div class="menu" >
<ul class="f-cb">
<?php 
$top_pos=substr($_menu_pos,0,1);
$i=0;
foreach($_menu as $key=>$item)
{
	$class_arr=array();
	if($i==0){$class_arr[]="first";}
	$i++;
	if($top_pos==$key){$class_arr[]="active";}
?>
<li><a class="<?php echo implode(" ",$class_arr)?>" <?php echo $item['attr']?>  href="<?php echo $item['url']?>"><span class="<?php echo $item['ico_class']?>"></span><br/><?php echo $item['name']?></a></li>
<?php }?>
<li><a target="_blank"  href="<?php echo $_sys['gs_site'];?>"><span class="about"></span><br/><?php echo L("contact_us");?></a></li>
<!--<li><a target="_blank"  href="--><?php //echo U("Index/index");?><!--"><span class="u-ico u-ico-6"></span><br/>--><?php //echo L("re_index");?><!--</a></li>-->
</ul>
</div>
<div class="submenu">
<ul class="f-cb">
<?php 
$i=0;
$menu_cnt=sizeof($_menu_child);
foreach($_menu_child as $key=>$item)
{
	$class_arr=array();
	$i++;
	if($_menu_pos==$key){$class_arr[]="active";}
?>
<li><a class="<?php echo implode(" ",$class_arr)?>" <?php echo $item['attr']?> href="<?php echo $item['url']?>"><?php echo $item['name']?></a></li>
<?php if($i>0 && $i<$menu_cnt){?>
<li><span>|</span></li>
<?php }?>
<?php }?>
</ul>
</div>
</div>
</div>
<!--后台管理导航菜单 end-->
