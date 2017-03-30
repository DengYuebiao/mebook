<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index_bookview.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/sbookshow.js"></script>'); ?>
<?php HtmlHeadBuff("push",'	<script type="text/javascript" src="/ckplayer/offlights.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/ckplayer/ckplayer.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<style type="text/css">
	#a1{
		position:relative;
		z-index: 100;
		width:600px;
		height:400px;
		margin: 0 auto;
		margin-top : 30px;
		/*display:inline*/
	}
	/*#choose{
		position:absolute;
		border: 1px red solid;
		width:200px;
		height:50px;
		/!*display:inline*!/

	}*/
</style>
<script type="text/javascript">
$(function(){
	var is_ie11=isIE11();

	if(<?php echo ltrim(strrchr($movie_file_info['path'],"."),'.')=="mp4"?true:0;?>){
		if(browserRedirect()){
			//flash播放
			$(".m-player").remove();
			$(".m-noplayer").remove();
			addflash();
		}else{
			//html5播放
			$(".m-player").remove();
			addhtml5();
	}
	}else{
		if(browserRedirect()){
			if(!isIE() && !is_ie11)
			{
				$(".m-player").remove();
				$(".m-brower").show();
			}else{
				$(".m-player").show();
				$(".m-brower").remove();

			}
		}else{
			//html5播放
			$(".m-player").remove();
			addhtml5();
		}

	}

});

function browserRedirect() {
	var sUserAgent = navigator.userAgent.toLowerCase();
	var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
	var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
	var bIsMidp = sUserAgent.match(/midp/i) == "midp";
	var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
	var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
	var bIsAndroid = sUserAgent.match(/android/i) == "android";
	var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
	var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
	//document.writeln("您的浏览设备为：");
	if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {
		return false;
	} else {
		return true;
	}
}

//browserRedirect();

function player_err()
{
	var is_ie11=isIE11();
	if(isIE() || is_ie11)
	{
		$(function(){
		  $(".m-player").remove();
		  $(".m-noplayer").show();
		});
	}
}

function isIE11()
{
	if(!!window.ActiveXObject || "ActiveXObject" in window)
		return true;
	else
		return false;
}

</script>

<!--内容 beg-->
<div class="g-bd f-cb">	
<div class="m-title"><h3><?php echo $movie_file_info['diversity'];?></h3></div>
<!--播放器 beg-->
<div class="m-player">
<object classid="clsid:02E2D748-67F8-48B4-8AB4-0A085374BB99" width="980" height="560" id="BaiduPlayer" name="BaiduPlayer" onError="player_err()">
<param name='URL' value='<?php echo $movie_file_info['path'];?>'>
<param name='Autoplay' value='1'>
</object>
</div>
<!--播放器 end-->

<div class="m-noplayer">
<h3><?php echo L('noplayer_alert');?></h3>
<span><?php echo L('noplayer_desc');?></span>
<p><a href="<?php echo U('Index/downbd');?>" class="u-btn" target="_blank"><?php echo L('down_bd');?></a></p>
</div>

<div class="m-brower">
<h3><?php echo L('mv_brower_info');?></h3>
<span><?php echo L('mv_brower_desc');?></span>
</div>



</div>
<!--内容 end-->

	<!--播放设置 beg-->
	<div id="a1"></div><div id="choose"></div>
	<script type="text/javascript">

		var flashvars={
			f:'<?php echo $movie_file_info['path'];?>',//视频地址
			a:'',//调用时的参数，只有当s>0的时候有效
			s:'0',//调用方式，0=普通方法（f=视频地址），1=网址形式,2=xml形式，3=swf形式(s>0时f=网址，配合a来完成对地址的组装)
			c:'0',//是否读取文本配置,0不是，1是
			x:'',//调用配置文件路径，只有在c=1时使用。默认为空调用的是ckplayer.xml
			i:'',//初始图片地址
			e:'8',//视频结束后的动作，0是调用js函数，1是循环播放，2是暂停播放并且不调用广告，3是调用视频推荐列表的插件，4是清除视频流并调用js功能和1差不多，5是暂停播放并且调用暂停广告
			v:'80',//默认音量，0-100之间
			p:'0',//视频默认0是暂停，1是播放，2是不加载视频
			h:'0',//播放http视频流时采用何种拖动方法，=0不使用任意拖动，=1是使用按关键帧，=2是按时间点，=3是自动判断按什么(如果视频格式是.mp4就按关键帧，.flv就按关键时间)，=4也是自动判断(只要包含字符mp4就按mp4来，只要包含字符flv就按flv来)
			q:'',//视频流拖动时参考函数，默认是start
			m:'',//让该参数为一个链接地址时，单击播放器将跳转到该地址
			o:'',//当p=2时，可以设置视频的时间，单位，秒
			w:'',//当p=2时，可以设置视频的总字节数
			g:'',//视频直接g秒开始播放
			j:'',//跳过片尾功能，j>0则从播放多少时间后跳到结束，<0则总总时间-该值的绝对值时跳到结束
			wh:'',//宽高比，可以自己定义视频的宽高或宽高比如：wh:'4:3',或wh:'1080:720'
			lv:'0',//是否是直播流，=1则锁定进度栏
			loaded:'',//当播放器加载完成后发送该js函数loaded
			//调用播放器的所有参数列表结束
			//以下为自定义的播放器参数用来在插件里引用的
			my_title:'演示视频标题文字',
			my_url:encodeURIComponent(window.location.href)//本页面地址
			//调用自定义播放器参数结束
		};
		var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always'};//这里定义播放器的其它参数如背景色（跟flashvars中的b不同），是否支持全屏，是否支持交互
		var video=['<?php echo $movie_file_info['path'];?>->video/mp4'];
		CKobject.embed('ckplayer/ckplayer.swf','a1','ckplayer_a1','100%','100%',false,flashvars,video,params);
		/*
		 以上代码演示的兼容flash和html5环境的。如果只调用flash播放器或只调用html5请看其它示例
		 */
		function videoLoadJs(s){
			alert("执行了播放");
		}
		function playerstop(){
			//只有当调用视频播放器时设置e=0或4时会有效果
			alert('播放完成');
		}

		function getstart(){
			var a=CKobject.getObjectById('ckplayer_a1').getStatus();
			var ss='';
			for (var k in a){
				ss+=k+":"+a[k]+'\n';
			}
			alert(ss);
		}

		//开关灯
		var box = new LightBox();
		 function closelights(){//关灯
		 box.Show();
		 CKobject._K_('a1').style.width='940px';
		 CKobject._K_('a1').style.height='550px';
		 CKobject.getObjectById('ckplayer_a1').width=940;
		 CKobject.getObjectById('ckplayer_a1').height=550;
		 }
		 function openlights(){//开灯
		 box.Close();
		 CKobject._K_('a1').style.width='600px';
		 CKobject._K_('a1').style.height='400px';
		 CKobject.getObjectById('ckplayer_a1').width=600;
		 CKobject.getObjectById('ckplayer_a1').height=400;
		 }

		function addflash(){
			if(CKobject.Flash()['f']){
				CKobject._K_('a1').innerHTML='';
				CKobject.embedSWF('/ckplayer/ckplayer.swf','a1','ckplayer_a1','600','400',flashvars,params);
			}
			else{
				alert('该环境中没有安装flash插件，无法播放');
			}
		}
		function addhtml5(){
			if(CKobject.isHTML5()){
				support=['all'];
				CKobject._K_('a1').innerHTML='';
				CKobject.embedHTML5('a1','ckplayer_a1',600,400,video,flashvars,support);
			}
			else{
				alert('该环境不支持html5，无法播放');
			}
		}

		function textBoxShow(){//增加提示文字
			var o = {
				name: 'prompt', //该文本的名称，主要作用是关闭时需要用到
				coor: '0,2,-100,-100', //坐标
				text: '{a href="http://www.ckplayer.com" target="_blank"}{font color="#FFFFFF" size="12" face="Microsoft YaHei,微软雅黑"}这里是一个提示文字演示，6.8新增功能{/font}{/a}', //文字
				bgColor: '0x000000', //背景颜色
				borderColor: '0x000000', //边框颜色
				radius: 3, //圆角弧度
				alpha:0,//总体透明度
				bgAlpha: 50, //背景透明度
				xWidth: 20, //宽度修正
				xHeight: 5, //高度修正
				pic: ['temp/face.png'], //附加图片地址数组，可以增加多个图片
				pwh:[[30,30]],//图片绽放宽高
				pEvent:[['url','http://www.ckplayer.com']],//图片事件数组
				pCoor: ['0,0,-22,-3'], //图片坐标数组
				pRadius: [30], //附加图片的弧度
				tween:[['x',1,50,0.3],['alpha',1,100,0.3]]//缓动效果
			};
			var boxtemp=CKobject.getObjectById('ckplayer_a1').textBoxShow(o);
		}
		function textBoxClose(){
			var is=CKobject.getObjectById('ckplayer_a1').textBoxClose('prompt');
		}
		function textBoxTween(){
			CKobject.getObjectById('ckplayer_a1').textBoxTween('prompt',[['y',0,-30,0.3]]);
		}
	</script>
	<!--播放设置 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>