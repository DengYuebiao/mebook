<?php 
	  if($link_list)
	  {
		  echo '<div class="m-links"><ul class="f-cb"><li><span>'.L('friend_link').':</span></li>';
		  foreach($link_list as $item)
		  {
			  echo '<li><a target="_blank" href="'.$item['link_val'].'">'.$item['link_name'].'</a></li>';
		  }
		  echo '</ul></div>';
	  }
?>