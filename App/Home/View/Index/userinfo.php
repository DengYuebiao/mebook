<div class="m-userinfo">
    <div style="overflow:auto"><?php echo '<a href="'.U('User/info').'"><img style="margin:20px 0px 20px 20px;display:inline-block;float:left;" src="/'.$user_info['portrait'].'" /></a>
    <span id="user_info" style="margin-left:8%;display:inline-block;margin-bottom:10px;margin-top:30px">'.$user_info['user_name'].'</span>
    <br><span id="real_name" style="margin-left:8%;display:inline-block">('.$user_info['real_name'].')</span>'; ?></div>
    <hr   style="width: 85%;height:1px;border:none;border-top:1px solid #b5c6d8;margin:0 auto" />
    <p style="line-height: 40px"><?php echo '<a style="margin:20px;" href="'.U('User/index').'">'.L('user_home').'</a>&nbsp;&nbsp;<a style="margin-left:20px" href="'.U('User/logout').'">'.L('user_logout').'</a> '; ?></p>
    <p ><?php echo '<span style="margin-left:20px">'.L('bookmark_sum').'</span>'.':<a href="'.U('User/bookmark').'">'.$sum_list['bookmark_cnt'].'</a>&nbsp;&nbsp;'.'<span style="margin-left:30px">'.L('rh_sum') .':<a href="'.U('User/rh').'">'.$sum_list['rh_cnt'].'</a>';?></p>
    <p style="line-height: 40px"><?php echo '<span style="margin-left:20px">'.L('cmt_sum').'</span>'.':<a href="'.U('User/comment').'">'.$sum_list['cmt_cnt'].'</a>&nbsp;&nbsp;' .'<span style="margin-left:30px">'.L('sh_sum').':<a  href="'.U('User/sh').'">'.$sum_list['sh_cnt'].'</a>';?></p>
</div>