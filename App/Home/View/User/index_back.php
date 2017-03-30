<?php HtmlHeadBuff("push",'<link href="/Public/css/home/user_index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php"); ?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<!--内容 beg-->
<div class="g-bd f-cb">	
<!--左侧栏 beg-->
<div class="g-sd1">
<?php include (MODULE_PATH."View/Public/usermenu.php"); ?>
</div>
<!--左侧栏 end-->
<!--右侧内容 beg-->
<div class="g-mn1">
	<!--用户信息 beg-->
   <div class="m-info">
   <p><?php echo '<a href="'.U('User/info').'"><img src="/'.$user_info['portrait'].'" /></a> '; ?></p>
   	<p><?php echo "{$user_info['user_name']}({$user_info['real_name']})";?></p>
    <p><?php echo L('bookmark_sum').':<a href="'.U('User/bookmark').'">'.$sum_list['bookmark_cnt'].'</a>';?></p>
    <p><?php echo L('cmt_sum').':<a href="'.U('User/comment').'">'.$sum_list['cmt_cnt'].'</a>';?></p>
    <p><?php echo L('rh_sum').':<a href="'.U('User/rh').'">'.$sum_list['rh_cnt'].'</a>';?></p>
    <p><?php echo L('sh_sum').':<a href="'.U('User/sh').'">'.$sum_list['sh_cnt'].'</a>';?></p>
   </div>
   <!--用户信息 end-->
   
   
  <!--图书右侧排行榜推荐 beg-->
  <div class="m-indextop">
      <?php 
          $top_html='<div class="btn"><span class="sel">'.L('book_recommend').'</span></div>';
          echo $top_html;
          
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
  <!--图书右侧排行榜推荐 end-->
</div>
<!--右侧内容 end-->
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>