<?php HtmlHeadBuff("push",'<link href="/Public/css/home/admin_index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/bootstrap.min.css" rel="stylesheet" type="text/css"/>'); ?>
<script src="/Public/js/jquery-1.8.3.min.js"></script>
<script src="/Public/js/home/modal.js"></script>

<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<script type="text/javascript">
$(function(){
   $(".btn-primary").click(function(){
          $("#modal_form").submit();
   });
	if(<?php echo  isset($_cookie['search_val']) ? 1:0;?>){
		  $("input[name=search_field]").val('<?php echo cookie("search_field")?>');
		  $("input[name=search_val]").val('<?php echo cookie("search_val")?>')
	}
});
function add(){
	$("#myModalLabel").html("<?php echo L('add_data')?>");
    $("input[name=isbncode]").val("");
    $("input[name=pub_name]").val("");
    $("input[name=pub_place]").val("");
	$("input[name=areacode]").val("");
}
function del(id,thi){
	if(confirm("<?php echo L("drop_confirm")?>")==true){
		var data = {
			del_id:id,
		};
		$.post("publish_man",data,function(msg){
			if(msg){
				alert("<?php echo L('drop_data_ok')?>");
			}else{
				alert("<?php echo L('drop_data_error')?>")
			}
			$(thi).parent().parent().hide();
		});
	}


}

function edit(id){
	$("#myModalLabel").html("<?php echo L('edit_data')?>");
	$.post("publish_man","edit_id="+id,function(msg){
          $("input[name=isbncode]").val(msg.isbncode);
          $("input[name=pub_name]").val(msg.publisher);
          $("input[name=pub_place]").val(msg.pubplace);
          $("input[name=areacode]").val(msg.areacode);
          $("input[name=different]").val(msg.pub_id);
	})
}
</script>
<style type="text/css">
	  body,input,td {font: 12px/1.14 'Microsoft YaHei',simsun,Arial, Helvetica, sans-serif,\5b8b\4f53;}
	  .m-tableform tbody th {font-weight:bold;font-size:15px;text-align:center}
	  td{text-align:center}
	  select{width: 125px;margin-right:10px}
	  .modal-body tr td {
		   text-align:left;
		   padding: 5px;
		   font: 15px/1.14 'Microsoft YaHei',simsun,Arial, Helvetica, sans-serif,\5b8b\4f53;
	  }
	  .modal-body td input,textarea{
		   border:1px solid #dddddd;
		   border-radius:3px;
		   padding: 4px;
	  }
	  .modal-body td select{
		   border:1px solid #dddddd;
		   border-radius:3px;
		   width:148px;
	  }
	  .modal-content{
		  margin-top:150px;
	  }
	  /*.modal-body table tr{text-align:center}*/
	  ul{margin-bottom: 0}
	  body{line-height:1}
	 .g-bd{display:inline;}
	 tfoot td{
		  text-align:right;
		  padding-right:20px;
	 }
</style>
<!--内容 beg-->
<div class="g-bd">
<?php include (MODULE_PATH."View/Public/curlocal.php"); ?>
<?php //use \Home\Extend\FormHelper\FormHelper; ?>
<div class="m-tableform">
<form method="post" name="table_form" id="table_form" enctype="multipart/form-data">
<table class="user_table">
<tbody>
<?php
	/*echo(FormHelper::getTrsHtml($row_info,
			array(
				array('zdcnf'=>array(
					array('zd'=>'sys_reg_name','type'=>'text','thattr'=>'width="15%"','tdattr'=>'width="35%"','attr'=>'class="text"','after'=>'&nbsp;<span class="field_notice u-must">*</span>')
				)),
			)
	));*/
?>
<tr><td  colspan="5"><select name="search_field"><option value="isbncode"><?php echo L("isbncode")?></option><option value="publisher"><?php echo L("pub_name")?></option>
			      <option value="pubplace"><?php echo L("pub_place")?></option><option value="areacode"><?php echo L("areacode")?></option></select>
		<input name="search_val" style="width: 35%"/>&nbsp;&nbsp;<input type="submit" class="u-btn" value="<?php echo L('find')?>" style="width: 50px"/><input class="u-btn u-btn-c3" style="width:46px;margin-left:15px" data-toggle="modal" data-target="#myModal" type="button" onclick="add()" value="<?php echo L('add')?>" /></td></tr>
<tr><th><?php echo L("isbncode")?></th><th><?php echo L("pub_name")?></th><th><?php echo L('pub_place')?></th><th><?php echo L("areacode")?></th><th><?php echo L("op")?></th></tr>
<?php if($pub_info){?>
        <?php foreach($pub_info as $info){?>
		<tr><td><?php echo $info['isbncode']?></td><td><?php echo $info['publisher']?></td><td><?php echo $info['pubplace'];?></td><td><?php echo $info['areacode'];?></td>
			<td><a  data-toggle="modal" data-target="#myModal" onclick="edit('<?php echo $info['pub_id']?>')"><?php echo L('edit')?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="del('<?php echo $info['pub_id']?>',this)"><?php echo L('drop')?></a></td></tr>
        <?php }?>
<?php }else{?>
          <tr><td colspan="5"><?php echo L('no_record')?></td></tr>
<?php }?>
</tbody>
</table>
</form>
</div>
	<div>
		<?php echo $table_html;?>
		<?php include (MODULE_PATH."View/Public/page.php"); ?>
	</div>
</div>
<!--内容 end-->
<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel"><?php echo L('add_data')?></h4>
			</div>
			<div class="modal-body" align="center">
				<form id="modal_form" method="post" >
					<input type="hidden" name="different" />
				<table>
					<tbody>
					<tr><td><?php echo L('isbncode')?>：</td><td><input name="isbncode" type="text" value="" /></td><td><?php echo L('pub_name')?>：</td><td><input  name="pub_name" type="text" value=""/></td></tr>
					<tr><td><?php echo L('pub_place')?>：</td><td><input name="pub_place" type="text" value="" /></td><td><?php echo L('areacode')?>：</td><td><input  name="areacode" type="text" value=""/></td></tr>
					</tbody>
				</table>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">提交</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
