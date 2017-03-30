
//参数一:搜索的表单
//参数二：要排序的表单
//参数三：排序的字段名
//参数四：排序的字段当前的顺序
function TABLE_ORDER(search_form,order_form,order_name,order_name_seq)
{
	this.sform=$(search_form);
	this.oform=$(order_form);
	this.oname=this.sform.find("input[name='"+order_name+"']");
	this.oname_seq=this.sform.find("input[name='"+order_name_seq+"']");
	this.asc_tag="↑";
	this.desc_tag="↓";
	this.curr_field=this.oname.val();
	this.curr_field_seq=this.oname_seq.val();	
	this.oform.find("th[entype='order']").addClass("field_order");		 
	this.curr_field_elem=this.oform.find("th[entype='order'][field='"+this.curr_field+"']");
	if(this.curr_field_elem!=undefined)
	{
		if(this.curr_field_elem.attr['field']==TABLE_ORDER.curr_field)
		{
		var add_tag=this.curr_field_seq=="asc"?this.asc_tag:this.desc_tag;
		this.curr_field_elem.html(this.curr_field_elem.html()+add_tag);
		}
	}
	this.bind();
}

TABLE_ORDER.prototype.__bind=function(fn,me){
	return function(){
		return fn.apply(me,arguments);
		};
};


TABLE_ORDER.prototype.bind=function(){
	this.oform.find("th[entype='order']").bind("click",this.__bind(this.table_head_onclick,this));
	
};

	
TABLE_ORDER.prototype.table_head_onclick=function(evt){
	var element=evt.srcElement||evt.target;
	var field=$(element).attr("field");
	var order=$(element).attr("order");
	if(this.curr_field==field)
	{
		order=this.curr_field_seq=="asc"?"desc":"asc";
	}
	
	this.oname.val(field);
	this.oname_seq.val(order);
	this.sform.submit();
	
};
