<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index_bookview.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/bookview.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
  <script type="text/javascript">
         $(function(){
             $('#mincode').mouseover(function(){
                   $('#qrcode').css("display","");
                   $('#mincode').css("bgcolor","white");
             });

             $("#mincode").mouseout(function(){
                  $("#qrcode").css("display","none");

             });

         });
  </script>
<!--内容 beg-->
<div class="g-bd f-cb">	
<!--左侧内容 beg-->
<div class="g-mn">
    <!--书目信息 beg-->
    <div class="m-bookinfo">
    <div class="inner f-cb">
        <div class="img"><img class="u-bookimg" src="<?php echo $book_info['picture'];?>" /></div>
        <div class="txt">
    <h3 class="title" ><?php echo $book_info['title'];?></h3>
    <p class="author"><?php echo $book_info['author']; echo $book_info['publish']?"&nbsp;/&nbsp;".$book_info['publish']:"";  echo $book_info['isbn']?"&nbsp;/&nbsp;".$book_info['isbn']:"";?></p>
    <?php 
	echo '<p><b>'.L('clc').':</b>&nbsp;'.$book_info['clc'].'</p>';
	echo '<p><b>'.L('ztc').':</b>&nbsp;'.$book_info['ztc'].'</p>';
	echo '<p><b>'.L('keyword').':</b>&nbsp;'.$book_info['keyword'].'</p>';
	echo '<p><b>'.L('content').':</b>&nbsp;'.$book_info['content'].'</p>';
	echo '<p><b>'.L('readtimes').':</b>&nbsp;'.$book_info['readtimes'].'</p>';
	echo '<p><b>'.L('fileformat').':</b>&nbsp;'.$book_info['fileformat'].'</p>';
	echo '<p><b>'.L('joindate').':</b>&nbsp;'.date("Y-m-d",$book_info['joindate']).'</p>';
	?>
    <div class="btns"><a class="u-btn" target="_blank" href="<?php echo U('Index/show/book_id/'.$book_id);?>">&nbsp;阅&nbsp;读&nbsp;</a>
    <?php if(!$book_info['in_bookmark']){?>&nbsp;<button onclick="addBM(<?php echo $book_info['book_id'];?>,this)" class="u-btn u-btn-c4">&nbsp;放入书架&nbsp;</button><?php }?>
    </div>

    </div>
       <img width="160" height="160" id="qrcode" src="<?php echo U('/Index/get_qrcode/qr_code_text/'.$qr_code_text);?>" style="position:absolute;top:45px;right:10px;display:none" />
       <img width="40" height="40" id="mincode"src="<?php echo U('Public/image/harfcode.png')?>" style="position:absolute;top:0;right:0;"/>
    </div>

    </div>
    <!--书目信息 end-->
    <!--评论列表 beg-->
    <div class="m-cmtlist">
    <h3><?php echo L('book_comment');?></h3>
    <?php 
	if($cmt_list){
	foreach($cmt_list as $item){
		echo '<div class="ditem f-cb"><div class="left"><img class="port" src="/'.$item['portrait'].'" /><p class="txt">'.$item['user_name'].'</p></div><div class="right"><p class="info">'.$item['cmt_body'].'</p><p class="ext"><label onclick="sendCmtExt('.$item['cmt_id'].',1,this)" class="cmtico"><img src="/Public/image/good_ico.png" class="ico" />(<span class="cnt">'.($item['good_cnt']?$item['good_cnt']:0).'</span>)<span></label>&nbsp;<label onclick="sendCmtExt('.$item['cmt_id'].',2,this)" class="cmtico"><img src="/Public/image/bad_ico.png" class="ico" />(<span class="cnt">'.($item['bad_cnt']?$item['bad_cnt']:0).'</span>)<span></label>&nbsp;&nbsp;</span>&nbsp;&nbsp;<span>'.$item['cmt_ip'].'</span>&nbsp;&nbsp;'.date("Y-m-d H:i:s",$item['cmt_time']).'</p></div></div>';
	}
	}else{
		echo '<div class="ditem"><p class="info"><span>'.L("nodata").'</span></p></div>';
	}
	?>
    <?php include (MODULE_PATH."View/Public/page.php"); ?>
    </div>
    <!--评论列表 end-->
    <!--评论框 beg-->
    <div class="m-cmt">
    <div class="txt"><textarea name="cmt_body" id="cmt_body"></textarea></div>
    <div class="btn"><button onclick="add_cmd(<?php echo $book_info['book_id'];?>,this)" class="u-btn u-btn-c5">&nbsp;<?php echo L('send_cmt')?>&nbsp;</button>
	</div>
    </div>
    <!--评论框 end-->
    
</div>
<!--左侧内容 end-->
<!--右侧栏 beg-->
<div class="g-sd">
   <?php include (MODULE_PATH."View/Index/booktop.php"); ?>
</div>
<!--右侧栏 end-->
</div>
<!--内容 end-->

<!--随机推荐 beg-->
<div class="g-bd">
<div class="m-randtop">
 <?php 
          $ul_html='';
          $li_html='';
          $i=1;
          if($book_list)
          {
              foreach($book_list as $item)
              {
                  $title=\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,16,"utf-8",true);
                  $li_html.='<li><a href="'.U('Index/bookview/book_id/'.$item['book_id']).'"><img title="'. $item['title'].'" class="u-bookimg" src="'. $item['picture'].'" /></a><h4 class="title"><a title="'. $item['title'].'" href="'. U('Index/bookview/book_id/'.$item['book_id']).'">'.\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,8,"utf-8",false).'</a></h4></li>';
                  $i++;
              }
          }
          else
          {
              $li_html='<li>'.L("nodata").'</li>';
          }
          $ul_html.='<ul class="f-cb">'.$li_html.'</ul>';
          
          $list_html='<div class="list">'.$ul_html.'</div>';
          
          echo $list_html;
      ?>
</div>
</div>
</div>
<!--随机推荐 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>