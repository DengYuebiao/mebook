<?php HtmlHeadBuff("push",'<link href="/Public/css/home/admin_index.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<link href="/Public/css/home/bootstrap.min.css" rel="stylesheet" type="text/css"/>'); ?>
<script src="/Public/js/jquery-1.8.3.min.js"></script>
<script src="/Public/js/home/modal.js"></script>

<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<script type="text/javascript">
var msglen = 0;
var n=1;
var menu_count = 5;
$(function(){
	//表单提交
   $(".btn-primary").click(function(){
	      var str="";
	      if($("input[name=role_code]").val()==''|| $("input[name=role_name]").val()==''){
			  alert('<?php echo L("no_role_code")?>');
		  }else{
			  $(".modal-body tbody input[type=checkbox]:checked").each(function(){
				   str=str +','+$(this).val();
			  });
			  str = str.substr(1);
              $("input[name=authority]").val(str);
			  $("#modal_form").submit();
		  }

   });

	$("#modal_form tbody td").css("font-weight","bold");
	//全选
	$("#all").bind("change",function(){
		  if($("#all").is(":checked")){
			  $("#modal_form tbody input[type=checkbox]").attr("checked",true);
		  }else{
			  $("#modal_form tbody input[type=checkbox]").attr("checked",false);
		  }
	});
   //列选
	$("#modal_form tfoot input").bind("change",function(){
		checkList($(this).val());
	});

	$.post("role","getMenu=1",function(msg){
		for(var len in msg){
			msglen++;
		}
		if(msg){
			var str = "";
			for(var i=1;i<=msglen;i++){
				str = str+"<tr>";
				for(var j=0;j<menu_count;j++,n++){
					if(msg[i][j].name!==undefined){
						str = str+"<td><input type ='checkbox'id='menu_"+n+"' value='"+n+"'/><label for='menu_"+n+"'>"+(msg[i][j].name)+"</label></td>";
					}else{
						str = str+"<td></td>";
						n = n-1;
					}
				}
				str = str+"</tr>";
			}
			$("#modal_form tbody").append(str);
		}else{

		}
	});
});
function add(){
	var n=1;
	$("#myModalLabel").html("<?php echo L('add_role')?>");
	$("input[name=role_code]").val("");
	$("input[name=role_code]").attr("readonly",false);
	$("input[name=role_name]").val("");
	for(var i=1;i<=msglen;i++){
		for(var j=0;j<menu_count;j++,n++){
				$("#menu_"+n).attr("checked",false);

		}
	}

}
function del(code,thi){
	if(confirm("<?php echo L("drop_confirm")?>")==true){
		if(code=="admin"){
			alert("<?php echo L('ban_admin')?>");
			return;
		}
		var data = {
			del_code:code,
		};

		$.post("role",data,function(msg){
			if(msg){
				alert("<?php echo L('del_suc')?>");
			}else{
				alert("<?php echo L('del_error')?>")
			}
			$(thi).parent().parent().hide();
		});
	}
}

function edit(code){
	var n=1;
	$("#myModalLabel").html("<?php echo L('edit_data')?>");
	$.post("role","edit_code="+code,function(msg){
          $("input[name=role_code]").val(msg.role_code);
          $("input[name=role_code]").attr("readonly","readonly");
          $("input[name=role_name]").val(msg.role_name);
		  var obj=msg.authority.split(",");

		for(var i=1;i<=msglen;i++){
			for(var j=0;j<menu_count;j++,n++){
				 if($.inArray(String(n),obj)>=0){
				 $("#menu_"+n).attr("checked",true);
				 }else{
				 $("#menu_"+n).attr("checked",false);
				 }
			}
		}
	})
}

//列选
function checkList(m){
	var bool = $("#list_"+m).is(":checked");
	$.post("role","getList="+m,function(msg){
		$(msg).each(function(i,item){
			$("#modal_form tbody input[type=checkbox]").each(function(j,tem){
				if($(this).next("label").html()==item){
					if(bool){
						$(this).attr("checked",true);
					}else{
						$(this).attr("checked",false);
					}
				}

			})
		})
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
		   border:1px solid #B5C6D8 ;
	  }
	  .modal-body tr label{
		   font-weight: normal;
		   font-size: 11px;
		   margin-bottom: 5px;
	  }
	  .modal-body td input{
		   border:1px solid #dddddd;
		   border-radius:3px;
		   padding: 4px;
	  }
	  .modal-body td select{
		   border:1px solid #dddddd;
		   border-radius:3px;
	  }
	  .modal-content{
		  margin-top:150px;
	  }

	  ul{margin-bottom: 0}
	  body{line-height:1}
	 .g-bd{display:inline;}

</style>
<!--内容 beg-->
<div class="g-bd">
<?php include (MODULE_PATH."View/Public/curlocal.php"); ?>
<?php use \Home\Extend\FormHelper\FormHelper; ?>
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
	));*///
?>
<tr><td  colspan="6"><select name="search_field"><option value="role_code"><?php echo L("role_code")?></option><option value="role_name"><?php echo L("role_name")?></option>
		</select><input name="search_val" style="width: 35%"/>&nbsp;&nbsp;<input type="submit" class="u-btn" value="<?php echo L('find')?>" style="width: 50px"/><input class="u-btn u-btn-c3" style="width:46px;margin-left:15px" data-toggle="modal" data-target="#myModal" type="button" onclick="add()" value="<?php echo L('add')?>" /></td></tr>
<tr><th><?php echo L("role_code")?></th><th><?php echo L('role_name')?></th><th><?php echo L("op")?></th></tr>
<?php if($role_info){?>
        <?php foreach($role_info as $info){?>
		<tr><td><?php echo $info['role_code']?></td><td><?php echo $info['role_name']?></td>
			<td><a  data-toggle="modal" data-target="#myModal" onclick="edit('<?php echo $info['role_code']?>')"><?php echo L('edit')?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="del('<?php echo $info['role_code']?>',this)"><?php echo L('drop')?></a></td></tr>
        <?php }?>
<?php }else{?>
          <tr><td colspan="5"><?php echo L('no_record')?></td></tr>
<?php }?>
</tbody>
</table>
</form>
</div>
	<div>
		<?php echo $table_html?>
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
					<input type="hidden" name="authority" />
				<table>
					<tbody>
					<tr><td colspan="5"><?php echo L('role_code')?>：<input name="role_code" type="text" value=""/>&nbsp;&nbsp;&nbsp;<?php echo L('role_name')?>：<input name="role_name" type="text" value=""/></td></tr>
					<tr><td colspan="5"><?php echo L('role_authority')?>：&nbsp;<input type="checkbox" id="all"/><label for="all" style="font-size: 13px"><?php echo L("all_or_not")?></label></td></tr>
					<tr><td style="width: 20%;"><?php echo L('menu_bm')?></td><td style="width: 20%"><?php echo L('menu_dc')?></td><td style="width: 20%"><?php echo L('menu_lt')?></td><td style="width: 20%"><?php echo L('menu_report')?></td><td style="width: 20%"><?php echo L('menu_system')?></td></tr>
					</tbody>
					<tfoot><tr><td><input type="checkbox" id="list_1" value="1"/><label for="list_1"><?php echo L("is_list")?></label></td><td><input type="checkbox" id="list_2" value="2"/><label for="list_2"><?php echo L("is_list")?></label></td>
						<td><input type="checkbox" id="list_3" value="3"/><label for="list_3"><?php echo L("is_list")?></label></td><td><input type="checkbox" id="list_4" value="4"/><label for="list_4"><?php echo L("is_list")?></label></td><td><input type="checkbox" id="list_5" value="5"/><label for="list_5"><?php echo L("is_list")?></label></td></tr></tfoot>
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
