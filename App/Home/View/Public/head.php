<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="/Public/Image/ico_tc.ico"> 
<link href="/Public/css/global.css" rel="stylesheet" type="text/css"/>
<link href="/Public/css/module.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="/Public/js/common.js"></script>
<?php echo HtmlHeadBuff("pop");?>
<title><?php echo $_page_title;?></title>
<script type="text/javascript">
var lang_list=<?php echo $lang_list?$lang_list:"''";?>;
$(function(){
	//页面底部版权信息
	$(window).scroll(function(){
		$("#copyright").removeClass("m-copyright-ab");
	});
})
</script>
</head>
<body>