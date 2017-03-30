<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/index.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>
<?php include (MODULE_PATH."View/Public/topbar.php");?>
<?php include (MODULE_PATH."View/Public/nav.php");?>
<script type="text/javascript">
  
</script>
<!--内容 beg-->
<div class="m-annou">
	<div class="g-bd f-cb">
		<div class="g-sd1">
          <div class="m-list">
          		<?php 
					if($annou_list)
					{
						echo '<ul>';
						foreach($annou_list as $item)
						{
							echo '<li><a class="title" href="'.U('Index/annou/annou_id/'.$item['annou_id']).'">'.\Home\Extend\FormHelper\FormHelper::msubstr($item['title'],0,12).'</a><span class="time">'.date("Y-m-d",$item['add_time']).'</span></li>';
						}
						echo '</ul>';
					}
					else
					{
						echo '<div class="nodata"><h3>'.L('nodata').'</h3></div>';
					}
				?>
                <?php include (MODULE_PATH."View/Public/page.php"); ?>
          </div>
        </div>
		<div class="g-mn1">
        <div class="m-content">
        	<div class="inner">
            
        	<div class="title"><h3><?php echo $annou_info['title'];?></h3></div>
            <div class="author"><span><?php echo L("annout_add_user");?>:admin &nbsp; <?php echo L("annout_add_time");?>:2014-02-03</span></div>
        	<div class="content"><?php echo $annou_info['body'];?></div>
        </div>
        </div>
        
        
        </div>
    </div>
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer.php"); ?>