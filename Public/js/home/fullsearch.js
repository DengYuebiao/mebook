$(function(){
	initTab();
	showTab(tab_id);
		
})

function initTab()
{
	$(".m-tab .left span").click(function(e) {
        showTab($(this).attr("for"));
    });
}

function showTab(tab_id)
{
	$(".m-tab .left span.active").removeClass("active");
	$(".m-tab .left span[for='"+tab_id+"']").addClass("active");
	
	$(".m-tab .right [id]:visible").hide();
	$(".m-tab .right #"+tab_id).show();
	$("#tab_val").val(tab_id);
	$(".m-tab .right #"+tab_id+" input[type='text']:first").focus();
}