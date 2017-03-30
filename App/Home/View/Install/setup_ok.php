<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/home/install.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/install.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/jquery_plug/jquery.form.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/loadbox/loadbox.js"></script>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/js/loadbox/loadbox.css" rel="stylesheet" type="text/css"/>'); ?>
<?php include (MODULE_PATH."View/Public/head.php"); ?>

<script type="text/javascript">
var curr_step=1;
$(function(){
	install_init();
	show_step(4);
})

</script>
<!-- 背景 -->
<div class="g-bd">
<!-- 内容正文 -->
<div class="g-content">
    <div class="m-title"><span><?php echo $_sys['cp_name'];?>安装向导</span></div>
    <hr size="3" color="#C0D9F2"/>
    <!-- 步骤导航 -->
    <div class="m-step_nav">
    <div class="step_item step1"><span>第一步.许可协议</span><p><img src="/Public/image/install_step1.png" /></p></div>
    <div class="step_item step2"><span>第二步.服务器环境检测</span><p><img src="/Public/image/install_step2.png" /></p></div>
    <div class="step_item step3"><span>第三步.填写初始化配置信息</span><p><img src="/Public/image/install_step3.png" /></p></div>
    <div class="step_item step4"><span>第四步.安装完成</span><p><img src="/Public/image/install_step4.png" /></p></div>
    </div>
    <!-- /步骤导航 -->
    <!-- tabs -->
    <div id="tab">
    
     <!-- tab1-第一步 -->
    <div class="tab_item" id="tab-1">
     <!-- 协议内容 -->
    <div class="m-msg">
      <div class="inner">
        <div class="m-licen">
          <h1><?php echo $_sys['cp_name'];?>安装协议</h1>
          <p>感谢您选择<?php echo $_sys['cp_name'];?>。我们努力为用户提供一个强大、高效、安全的数字图书馆系统。本系统是<?php echo $_sys['gs_name'];?>自主开发，独立拥有所有版权。官方网址为 <a href="<?php echo $_sys['gs_site'];?>" target="_blank"><?php echo $_sys['gs_site'];?></a></p>
          <p>用户须知：本协议是您与<?php echo $_sys['gs_name'];?>之间关于您安装使用<?php echo $_sys['gs_name'];?>提供的<?php echo $_sys['cp_name'];?>及服务的法律协议。无论您是个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议。请您审阅并接受或不接受本协议条款。如您不同意本协议条款或<?php echo $_sys['gs_name'];?>随时对其的修改，您应不使用或主动取消<?php echo $_sys['gs_name'];?>提供的<?php echo $_sys['cp_name'];?>。否则，您的任何对<?php echo $_sys['cp_name'];?>使用的行为将被视为您对本服务条款全部的完全接受，包括接受<?php echo $_sys['gs_name'];?>对服务条款随时所做的任何修改。</p>
          <p>本服务条款一旦发生变更，<?php echo $_sys['gs_name'];?>将在网页上公布修改内容。修改后的服务条款一旦在网页上公布即有效代替原来的服务条款。如果您选择接受本条款，即表示您同意接受协议各项条件的约束。如果您不同意本服务条款，则不能获得使用本服务的权利。您若有违反本条款规定，<?php echo $_sys['gs_name'];?>有权随时中止或终止您对<?php echo $_sys['cp_name'];?>的使用资格并保留追究相关法律责任的权利。</p>
          <p>在理解、同意、并遵守本协议的全部条款后，方可开始使用<?php echo $_sys['cp_name'];?>。</p>
          <p><?php echo $_sys['gs_name'];?>拥有本软件的全部知识产权。本软件必须经过<?php echo $_sys['gs_name'];?>注册授权后才可使用。</p>
          <h3>I. 协议许可的权利</h3>
          <ul>
            <li>您拥有使用本软件构建的网站中全部数据及相关信息的所有权，并独立承担与使用本软件构建的网站内容的审核、注意义务，确保其不侵犯任何人的合法权益，独立承担因使用<?php echo $_sys['cp_name'];?>和服务带来的全部责任，若造成<?php echo $_sys['gs_name'];?>或用户损失的，您应予以全部赔偿。</li>
          </ul>
          <p></p>
          <h3>II. 协议规定的约束和限制</h3>
          <ul>
            <li>未获得<?php echo $_sys['cp_name'];?>注册授权之前，不得使用本软件。购买注册授权请联系官方网站<?php echo $_sys['gs_name'];?>,致电<?php echo $_sys['tel_400'];?>了解详情。</li>
            <li>不得对本软件或与之关联的注册授权进行出租、出售、抵押或发放子许可证。</li>
            <li>无论用途如何、是否经过修改或美化、修改程度如何，只要使用<?php echo $_sys['cp_name'];?>的整体或任何部分，未经授权许可，页面页脚处的<?php echo $_sys['cp_name'];?>的版权信息都必须保留，而不能清除或修改。</li>
            <li>禁止在<?php echo $_sys['cp_name'];?>的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。</li>
            <li>如果您未能遵守本协议的条款，您的授权将被终止，所许可的权利将被收回，同时您应承担相应法律责任。</li>
          </ul>
          <p></p>
          <h3>III. 有限担保和免责声明</h3>
          <ul>
            <li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
            <li><?php echo $_sys['gs_name'];?>不对使用本软件构建的平台中的数字图书或用户使用产生的数据和文件承担责任，全部责任由您自行承担。</li>
            <li><?php echo $_sys['gs_name'];?>对提供的软件和服务之及时性、安全性、准确性不作担保，由于不可抗力因素、<?php echo $_sys['gs_name'];?>无法控制的因素（包括黑客攻击、停断电等）等造成软件使用和服务中止或终止，而给您造成损失的，您同意放弃追究<?php echo $_sys['gs_name'];?>责任的全部权利。</li>
          </ul>
          <p></p>
          <p>有关<?php echo $_sys['cp_name'];?>最终用户授权协议、注册授权与技术服务的详细内容，均由<?php echo $_sys['gs_name'];?>独家提供。<?php echo $_sys['gs_name'];?>拥有在不事先通知的情况下，修改授权协议和服务价目表的权利，修改后的协议或价目表对自改变之日起的新授权用户生效。</p>
          <p>一旦您开始安<?php echo $_sys['cp_name'];?>，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权利的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权利。</p>
          <p></p>
          <p align="right"><a href="<?php echo $_sys['gs_site'];?>" target="_blank"><?php echo $_sys['gs_name'];?></a>（2015年04月20日）</p>
        </div>
      </div>
    </div>
    <!-- /协议内容 -->
    <!-- 按钮 -->
    <div class="m-row">
      <input type="button" class="u-btn" value="我不同意" />
    <input type="button" style="width:120px!important;" class="u-btn" onclick="next_step()" value="我同意(继续安装)" />
    </div>
    <!-- /按钮 -->
    </div>
    <!-- /tab1-第一步 -->

     <!-- tab2-第二步 -->
    <div class="tab_item" id="tab-2">
     <!-- 内容 -->
    <div class="m-buff">
     <h2> 服务器环境检查</h2>
     <table width="100%" cellpadding="0" cellspacing="0" class="m-table">
     <thead>
<th width="40%" align="left">项目名称</th>
<th width="30%" align="left">所需环境</th>
<th width="30%" align="left">当前环境</th>
</thead>
      <tbody>
<?php foreach($check_list as $item){?>
           <tr>
           <td><?php echo $item['desc'];?></td>
           <td><?php echo $item['must'];?></td>
           <td><?php 
		   echo $item['is_ok']?'<img class="ico" src="/Public/image/success.gif" />':'<input type="hidden" class="check_fail" value="'.$item['desc'].'" /><img class="ico" src="/Public/image/failed.gif" />';
		   ?><?php echo $item['curr'];?></td>
          </tr>
<?php }?>

      </tbody>
    </table>
    <h2>文件和目录权限检查</h2>
    <table width="100%" cellpadding="0" cellspacing="0" class="m-table">
     <thead>
<th width="40%" align="left">文件目录</th>
<th width="30%" align="left">所需权限</th>
<th width="30%" align="left">当前权限</th>
</thead>
      <tbody>

<volist name="check_access_list" id="item">
<?php foreach($check_access_list as $item){?>
           <tr>
           <td><?php echo $item['desc'];?></td>
           <td><?php echo $item['must'];?></td>
           <td>
		   <?php 
		   echo $item['is_ok']?'<img class="ico" src="/Public/image/success.gif" />':'<input type="hidden" class="check_fail" value="'.$item['desc'].'" /><img class="ico" src="/Public/image/failed.gif" />';
		   ?>
		   <?php echo $item['curr'];?></td>
          </tr>
<?php }?>

      </tbody>
    </table>
    </div>
    <!-- /内容 -->
    <!-- 按钮 -->
    <div class="m-row">
    <input type="button"  class="u-btn" onclick="back_step()" value="上一步" />
    <input type="button" class="u-btn" onclick="next_step()"  value="下一步" />
    </div>
    <!-- /按钮 -->
    </div>
    <!-- /tab2-第二步 -->    
     <!-- tab3-第三步 -->
   <form method="post" name="db_info_form" id="db_info_form" enctype="multipart/form-data">
 <div class="tab_item" id="tab-3">
       <!-- 内容 -->
    <div class="m-buff1">
     <h2>设置数据库服务器信息</h2>
     <table width="100%" cellpadding="0" cellspacing="0" class="m-table">
      <tbody>
           <tr>
           <td width="20%">数据库地址</td>
           <td width="40%"><input name="db_host" id="db_host" type="text" class="u-txt"  value="localhost" /><span class="u-must">*</span></td>
           <td width="40%"><span class="u-msg">本机请输入localhost</span></td>
          </tr>
          <tr>
           <td>数据库端口</td>
           <td><input name="db_port" id="db_port" type="text" class="u-txt"  value="6602" /><span class="u-must">*</span></td>
           <td><span class="u-msg">安装包mysql默认端口6602</span></td>
          </tr>
           <tr>
           <td>数据库名称</td>
           <td><input name="db_name" id="db_name" type="text" class="u-txt"  value="ebook" /><span class="u-must">*</span></td>
           <td><span class="u-msg">用于安装的数据库名称,不存在则自动创建</span></td>
          </tr>
           <tr>
           <td>数据库用户名</td>
           <td><input name="db_user" id="db_user" type="text" class="u-txt"  value="root" /><span class="u-must">*</span></td>
           <td><span class="u-msg">用于连接数据库的用户名</span></td>
          </tr>
           <tr>
           <td>数据库密码</td>
           <td><input name="db_pass" id="db_pass" type="text" class="u-txt"  value="<?php echo $mysql_pwd?>" /></td>
           <td><span class="u-msg">数据库密码,windows版本默认为此密码</span></td>
          </tr>
          <tr>
           <td>初始化数据</td>
           <td><input name="init_data" id="init_data" type="file" class="u-txt"  value="" /><span class="u-must">*</span></td>
           <td><span class="u-msg">初始化数据库文件</span></td>
          </tr>
      </tbody>
    </table>
    <h2>设置初始化信息</h2>
    <table width="100%" cellpadding="0" cellspacing="0" class="m-table">
      <tbody>
           <tr>
           <td width="20%">单位名称</td>
           <td width="40%"><input name="sys_reg_name" id="sys_reg_name" type="text" class="u-txt"  value="" /><span class="u-must">*</span></td>
           <td width="40%"><span class="u-msg">请输入单位名称</span></td>
          </tr>
          <tr>
           <td>超级管理员名称</td>
           <td><input name="admin_name" disabled="disabled" id="admin_name" type="text" class="u-txt"  value="admin" /></td>
           <td><span class="u-msg">超级管理员名称固定为admin</span></td>
          </tr>
           <tr>
           <td>超级管理员密码</td>
           <td><input name="admin_pwd1" id="admin_pwd1" type="password" class="u-txt"  value="" /><span class="u-must">*</span></td>
           <td><span class="u-msg">请输入超级管理员密码</span></td>
          </tr>
          <tr>
           <td>超级管理员密码确认</td>
           <td><input name="admin_pwd2" id="admin_pwd2" type="password" class="u-txt"  value="" /><span class="u-must">*</span></td>
           <td><span class="u-msg">请再次输入超级管理员密码以确认</span></td>
          </tr>
      </tbody>
    </table>
    </div>
    <!-- /内容 -->
    <!-- 按钮 -->
    <div class="m-row">
    <input type="button" class="u-btn" onclick="back_step()" value="上一步" />
    <input type="button" class="u-btn" onclick="next_step()"  value="下一步" />
    </div>
    <!-- /按钮 -->
    </div>
</form>
    <!-- /tab3-第三步 -->  
     <!-- tab4-第四步 -->
    <div class="tab_item" id="tab-4">
     <!-- 内容 -->
    <div class="m-buff">
      <!-- 内容 -->
    <div class="m-buff2">
     <h2>安装已经完成</h2>
    <p>访问网站首页:<a href="http://<?php echo $_SERVER['HTTP_HOST'];?>">http://<?php echo $_SERVER['HTTP_HOST'] ?></a></p>
    </div>
    <!-- /内容 -->
    </div>
    <!-- /内容 -->
    </div>
    <!-- /tab4-第四步 -->  
  </div>
  <!-- tabs -->
    
</div>
<!-- /内容正文 -->
</div>
<!-- /背景 -->
<a id="loading_box" href="/Public/image/loading.gif" class="loading_box"><img src="/Public/image/loading.gif" /></a>
<?php include (MODULE_PATH."View/Public/footer.php"); ?>