<?php HtmlHeadBuff("push",'<link href="/Public/css/home/rh.css" rel="stylesheet" type="text/css"/>'); ?>
<?php HtmlHeadBuff("push",'<script type="text/javascript" src="/Public/js/fusionchart/FusionCharts.js"></script>'); ?>
<?php include (MODULE_PATH."View/Public/head_admin.php"); ?>
<script type="text/javascript">
var base_path=getSite()+"/Public/js/fusionchart";
var chart_data="<?php echo $chart_data;?>";
var chart_width=1000;
var chart_height=500;
$(function(){
	updateChart();
	
	$("#tj_year").change(function(e) {
		var year_name=$("#tj_year").val();
        var h=getSite()+"/Rh/tj/year/"+year_name;
		location.href=h;
    });
	
	$("#tj_year,#curr_chart").change(function(e) {
        updateChart();
    });

	$("#export_excel").click(function(e){
	 var year_name=$("#tj_year").val();
		 $("#excel").val(year_name);
	  /*		var url=getSite()+"/Rh/tj/year/"+year_name+"/export_excel/1";
		 $.ajax({
		 url:url,
		 cache:false,
		 type:"get",
		 success: function(ret){
		    //alert(ret);
		 }});*/
	});

});
function updateChart(){
	var year_name=$("#tj_year").val();
	var swf_name=$("#curr_chart").val();
	var xml_data=getXml(year_name);
	$("#rel_data").width(chart_width);
	var chart1 = new FusionCharts(base_path+"/"+swf_name, "chart1Id", chart_width,chart_height, "0", "0");
	chart1.setDataXML(xml_data);
	chart1.render("chart1div");	
}

function getXml(year)
{
	var xml="<graph baseFontSize='12' baseFont='宋体'  chartLeftMargin='5' chartRightMargin='40' chartTopMargin='20'  yAxisMinValue='0' yAxisMaxValue='10'  chartBottomMargin='0' animation='1' pieFillAlpha='60' showLegend='1'  formatNumber='0' formatNumberScale='0' caption='"+year+"<?php echo L('year_desc');?>'  xAxisName='<?php echo L('mon_desc');?>' yAxisName='<?php echo L('readnum');?>' showNames='1' >"+chart_data+"</graph>";
	return xml;
}
</script>
<!--内容 beg-->
<div class="g-bd">	
<?php include (MODULE_PATH."View/Public/curlocal.php"); use \Home\Extend\FormHelper\FormHelper; ?>
<!--统计 end-->
<div class="m-rhtj">
<div id="chart1div">Chart</div>
<div class="ext"><?php echo L('sel_year');?><?php echo FormHelper::getSelectHtml($curr_year,$year_list,array('attr'=>'name="tj_year" id="tj_year"','vt'=>'val')) ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo L('sel_chart');?><?php echo FormHelper::getSelectHtml($curr_year,$chart_list,array('attr'=>'name="curr_chart" id="curr_chart"')) ?>&nbsp;&nbsp;&nbsp;<form method="get" style="display: inline;"><input style="width:100px!important;" type="submit" id="export_excel" name="export_excel" value="导出为Excel" /><input type="hidden" id="excel" name="excel" value=""/></form></div>
</div>
<!--统计 end-->
</div>
<!--内容 end-->

<?php include (MODULE_PATH."View/Public/footer_admin.php"); ?>