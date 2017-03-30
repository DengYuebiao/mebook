<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index_bookview.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/js/jplayer/skin/blue/css/jplayer.blue.monday.min.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jplayer/jquery.jplayer.min.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/sbookshow.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
var mp3_path=getSite()+"<?php echo $sbook_info['mp3url'];?>";
$(function(){
	jplayerInit(mp3_path,"<?php echo $sbook_info['title'];?>");	
});
</script>
<!--内容 beg-->
<div class="g-bd f-cb">	
<!--左侧内容 beg-->
<div class="g-mn">
    <!--书目信息 beg-->
<div class="m-bookinfo">
<h3 class="title"><?php echo $sbook_info['title'];?></h3>
<p><span><?php echo L('sbook_author').'：'.$sbook_info['author'];?></span></p>
<p><span><?php echo L('sbook_class').'：'.$sbook_info['class'];?></span></p>
<p class="pubdate"><?php echo L('joindate').'：'.date("Y-m-d",$sbook_info['joindate']);?></p>
</div>
<!--书目信息 end-->

<!--播放器 beg-->
<div class="m-player">
<div id="jquery_jplayer_1"></div>
<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
	<div class="jp-type-single">
		<div class="jp-gui jp-interface">
			<div class="jp-controls">
				<button class="jp-play" role="button" tabindex="0">play</button>
				<button class="jp-stop" role="button" tabindex="0">stop</button>
			</div>
			<div class="jp-progress">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>
				</div>
			</div>
			<div class="jp-volume-controls">
				<button class="jp-mute" role="button" tabindex="0">mute</button>
				<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			</div>
			<div class="jp-time-holder">
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
				<div class="jp-toggles">
					<button class="jp-repeat" role="button" tabindex="0">repeat</button>
				</div>
			</div>
		</div>
		<div class="jp-details">
			<div class="jp-title" aria-label="title">&nbsp;</div>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
</div>
</div>
<!--播放器 end-->

<!--文本 beg-->
<div class="m-booktxt">
<div class="inner"><?php echo $sbook_info['booktxt'];?></div>
</div>
<!--文本 end-->

</div>
<!--左侧内容 end-->
<!--右侧栏 beg-->
<div class="g-sd">
   	<!--图书右侧排行榜推荐 beg-->
<div class="m-indextop">
	<?php 
		$top_html='<div class="btn"><span class="sel">'.L('sbook_top').'</span></div>';
		echo $top_html;
		
		$ul_html='';
		
		$li_html='';
		$i=1;
		if($good_list)
		{
			foreach($good_list as $item)
			{
				$title=\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,16,"utf-8",true);
				$li_html.='<li><span class="ico'.$i.'">'.$i.'</span><a href="'.U("Index/sbookshow/sbook_id/".$item['sbook_id']).'">'.$title.'</a></li>';
				$i++;
			}
		}
		else
		{
			$li_html='<li>'.L("nodata").'</li>';
		}
		$ul_html.='<ul>'.$li_html.'</ul>';
		
		$list_html='<div class="list">'.$ul_html.'</div>';
		
		echo $list_html;
	?>
</div>
<!--图书右侧排行榜推荐 end-->
</div>
<!--右侧栏 end-->
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>