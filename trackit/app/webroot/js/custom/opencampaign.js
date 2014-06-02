function accept_ocr(ocr_id, con_input_field){
$con_id = parseInt(con_input_field.value);ocr_change_status(ocr_id, 1, $con_id);
}
function unaccept_ocr(ocr_id){
ocr_change_status(ocr_id, 0, 0);
}

function ocr_change_status(ocr_id, new_status, con_id){
if (new_status != 1 && new_status != 0){return;}

$.ajax({type:"POST",data:{}, url:$S_N+"oc_responses/ajax_change_status_by_admin/"+ocr_id+"/"+new_status +"/"+con_id,
	success : function(data){var result = IsJsonString(data);				
		if (result.success){
			if (new_status == 1){s_s_m("Promotion accepted.", 0, 0);}
			else if (new_status == 0){s_s_m("Promotion unaccepted.", 0, 0);}
		}else{s_s_m(result.msg, 0, 0);}
	},
	error : function(data) {s_e_m("Looks like, Something didn\'t go right.", 0, 0);}
});
}

function requeue_ocr(ocr_id){
$.ajax({type:"POST",data:{}, url:$S_N+"queues/queueit/"+ocr_id,
	success : function(data){
		if (data){s_s_m("Your promotion is queued for re-evaluation.", 0, 0);}
		else{s_s_m("Looks like, Something didn\'t go right. If the problem persists, then let us know and we\'ll resolve it immediately.", 0, 0);} 
	},error : function(data){s_e_m("Looks like, Something didn\'t go right.", 0, 0);}
});

}

//content_id, newstate
function set_content_state(cid, ns){
$.ajax({type:"POST",data:{},url: $S_N+"contents/set_content_state/"+cid+"/"+ns,success:function(data){ucssr(data, ns);},
	error:function(data){s_e_m("Looks like, Something didn\'t go right.", pageRefresh, 0);}
});
}

// update_content_set_state_response
function ucssr(data, ns) {
if (data == 0){s_e_m("Looks like, Something didn\'t go right.", pageRefresh, 0);}
else if (data == 1 && ns == 1){s_s_m("This content now appears to promoters.", pageRefresh, 0);}
else if (data == 1 && ns == 0){s_s_m("This content is not hidden from promoters.", pageRefresh, 0);}
}

function deactivate_opencampaign(oc_id){edit_campaign_activation(oc_id, 0);}

function activate_opencampaign(oc_id){edit_campaign_activation(oc_id, 1);}

function edit_campaign_activation($oc_id, $on_or_off){
$.ajax({type:"POST",data:{oc_id: $oc_id, new_val : $on_or_off},url: $S_N+"open_campaigns/edit_activation",
	success:function(data){update_opencampaign_activate_response(data, $on_or_off)},
	error:function(data){update_opencampaign_activate_response(data, 0)}
});
}

function update_opencampaign_activate_response(data, $on_or_off){
if((typeof data != 'undefined')&&(true==data)||(1==data)){
 if (1==$on_or_off){msg = "<b>Congratulations</b>, Your campaign has been successfully activated.";}
 else if (0 == $on_or_off){msg = "Your campaign has been de-activated on your request.";}
}else{msg = "Oops, there was some techical problem while updating your campaign. Please email us or phone us and we will resolve it immediately.";} 
s_s_m(msg, pageRefresh, 0);
}

function getdetails_opencampaign(oc_id){s_s_m("just a sec ...");fit_to_inner_content('div.success_msg');r_i_c('div.success_msg');
$.ajax({type:"POST",data:{}, url: $S_N+"open_campaigns/details/"+oc_id,
	success:function(data){cb_clk();$d=IsJsonString(data);$d['share_details']={};$cd = {};$cd['coupon']={};
	for(var $k in $d['oc_data']){if($k.indexOf('default_')==0){$d['share_details'][$k]=$d['oc_data'][$k];}
	if($k.indexOf('coupon')==0){$cd['coupon'][$k]=$d['oc_data'][$k];}}
	for($k in $d['conditions']){if($d['conditions'][$k]['con_name']=='Valid Until'){$cd['coupon']['valid_until_date'] =$d['conditions'][$k]['param1'];delete $d['conditions'][$k];break; }}
	for($k in $d['conditions']){$d['conditions'][$k]['coupon']=$cd['coupon'];}
	//opencampaign_details_clicks_response(JSON.stringify($d));
	show_dynamic_social_coupon.l=1;show_dynamic_social_coupon(JSON.stringify($d));
	},
	error:function(data){cb_clk();}
});
}

function opencampaign_details_clicks_response(data){
	if (!data){msg = "Oops, something didn't work. Try again, if the problem persists, please email us or phone us and we will resolve it immediately.";
		node = $("div.error_msg");node.text(msg);node.show();
		node.fadeTo(500, 0.95);node.css("z-index", 10);$fade = $('#fade');$fade.show();
		$fade.on('click', function(){node.empty();node.hide();$fade.hide();});
		$fade.css("z-index", 9);
		return;
	}
	
	data = $.parseJSON(data);	
	oc_id = data['oc_id'];
	start_date = data['start_date'];
	active = parseInt(data['active']);
	type = data['type'];
	is_for_product = data['is_for_product'];
	product_name = data['product_name'];
	company_name = data['company_name'];
	conditions = data['conditions']
	coupon_worth = data['oc_data']['coupon_worth'];
	coupon_worth_cur = data['oc_data']['coupon_worth_cur'];
	coupon_line = data['oc_data']['coupon_line'];
	coupon_code = data['oc_data']['coupon_code'];
	coupon_valid_until_date = data['oc_data']['coupon_valid_until_date'];
	
	promotion_for = "";
	if (is_for_product){promotion_for = product_name;}
	else{promotion_for = company_name;}
	
	var $promotion_for_div = $( "<div class='promotion_for'> Campaign for <div class='promotion_for_name'>" + promotion_for + "</div></div>" );
	
	active_name = ""
	if (active){active_name = "active";var active_div = $( "<div class='oc_active_text'>" + active_name + "</div>" );} 
	else{active_name = "not active";var active_div = $( "<div class='oc_nonactive_text'>" + active_name + "</div>" );} 
	
	if (start_date){var start_date_div = $( "<div class='date'> started on " + start_date + "</div>" );} 
	else{var start_date_div = $( "<div class='date'> not started yet.</div>" );}
    
	node = $("div.opencampaign_details_click_response");
	node.append($promotion_for_div, [active_div, start_date_div] );
	
	for (index in conditions)
	{
		condition = conditions[index];condition_name = condition['con_name'];
		con_param1 = condition['param1'];con_param2 = condition['param2'];
		offer_type = condition['offer_type'];offer_worth = condition['offer_worth'];
		max_count = condition['max_count'];met_so_far = condition['met_so_far'];
		
		/*
		var condition_div = $( "<div class='condition_name'>" + condition_name + "</div>" );
		if (con_param1)
		{
			condition_param1_div = $( "<div class='con_param'>" + con_param1 + "</div>" );
			condition_div.append(condition_param1_div);
		}
		if (con_param2 && con_param2 != -1)
		{
			condition_param2_div = $( "<div class='con_param'>" + con_param2 + "</div>" );
			condition_div.append(condition_param2_div);
		}
		*/
		
		if (con_param1)
		{
			var condition_div = $( "<div class='condition_name'>" + condition_name + " " + con_param1 + "</div>" );
			//condition_param1_div = $( "<div class='con_param'>" + con_param1 + "</div>" );
			//condition_div.append(condition_param1_div);
		}
		else
		{
			var condition_div = $( "<div class='condition_name'>" + condition_name + "</div>" );
			//condition_div.append(condition_param1_div);
		}
		
		if (offer_type == 'coupon')
		{
			//offer_div = $( "<div style='float:left;'><div class='offer_type'> Coupon </div> worth <div class='offer_type'> $" + offer_worth + "</div></div>" );
			offer_div = $( "<div class='offer_type'> Coupon worth " + coupon_worth_cur + " " + offer_worth + "</div>" );
			offer_div = $( "<div class='coupon_code'> Coupon Code - " + coupon_code + "</div>" );
			offer_div = $( "<div class='coupon_code'> Coupon valid until " + coupon_valid_until_date + " (mm/dd/yyyy) </div>" );
			offer_div = $( "<div class='coupon_line'> Coupon Main line - " + coupon_line + "</div>" );
			//offer_div = $( "<div class='coupon_code'> Coupon Code " + coupon_code + "</div>" );
			condition_div.append(offer_div);
		}
		else if (offer_type == 'dollar')
		{
			//offer_div = $( "<div class='offer_type'> Offering </div> <div class='offer_type'> $" + offer_worth + "</div>" );
			offer_div = $( "<div class='offer_type'> Offering $" + offer_worth + "</div>" );
			condition_div.append(offer_div);
		}
		else if (offer_type != "" && offer_type != 'none')
		{
			offer_div = $( "<div class='offer_type'>"+ offer_type +"</div> worth <div class='offer_type'> $" + offer_worth + "</div>" );
			condition_div.append(offer_div);
		}
		
		if (max_count > 0)
		{
			count_div = $( "<div class='offer_type'> Maximum offering " + max_count + "</div>" );
			condition_div.append(count_div);
			count_div = $( "<div class='offer_type'> Successful promos so far " + met_so_far + "</div>" );
			condition_div.append(count_div);
		}
		node.append(condition_div);
	}
	
	fade_and_add_close_button(node);node.draggable();
}


function show_dynamic_social_coupon(data){
	if (!data){msg = "Oops, something didn't work. Try again, if the problem persists, please email us or phone us and we will resolve it immediately.";
		node = $("div.error_msg");node.text(msg);node.show();		
		node.fadeTo(500, 0.95);node.css("z-index", 10);$fade = $('#fade');$fade.show();
		$fade.on('click', function(){node.empty();node.hide();$fade.hide();});
		$fade.css("z-index", 9);		
		return false;
	}
	
	data = $.parseJSON(data);	
	is_for_product = data['is_for_product'];
	product_name = data['product_name'];
	company_name = data['company_name'];
	promotion_for = data['promotion_for'];
	start_date = data['start_date'];
	active = parseInt(data['active']);
	type = data['type'];
	conditions = data['conditions'];
	
	default_title = data['share_details']['default_title'];
	default_link = data['share_details']['default_link'];
	default_desc = data['share_details']['default_desc'];
	default_fbpageurl = data['share_details']['default_fbpageurl'];
	default_linkforpost = data['share_details']['default_linkforpost'];
	
	if (typeof promotion_for == 'undefined'){promotion_for = "";
		if (is_for_product){promotion_for = product_name;}
		else{promotion_for = company_name;}
	}
	
	var $promotion_for_div = $( "<div class='promotion_for'> Coupon to <div class='promotion_for_name'>" + promotion_for + "</div></div>" );
	
	if (start_date){active_name = ""
		if (active){active_name = "active";
			var active_div = $( "<div class='oc_active_text'> Offer is " + active_name + "</div>" );
		}else{active_name = "not active";
			var active_div = $( "<div class='oc_nonactive_text'> Offer is " + active_name + "</div>" );
		}
		var start_date_div = $( "<div class='date'> Offer started on " + start_date + "</div>" );
	}else{var start_date_div = $( "<div class='date'> Offer not started yet (start it now).</div>" );} 
    
	node = $("div.opencampaign_details_click_response");
	node.append($promotion_for_div, [active_div, start_date_div] );
	
	for (index in conditions)
	{
		condition = conditions[index]; if(!condition){continue;}
		condition_name = condition['con_name'];
		con_param1 = condition['param1'];
		con_param2 = condition['param2'];
		//offer_type = condition['offer_type'];
		//offer_worth = condition['offer_worth'];
		
		max_count = condition['max_count'];
		met_so_far = condition['met_so_far'];
		
		if (typeof condition['coupon'] != 'undefined' && condition['coupon'] != false)
		{
			coupon = condition['coupon'];
			offer_type = 'coupon';
			coupon_code = coupon['coupon_code'];
			offer_worth = coupon['coupon_worth'];
			cur_code = coupon['coupon_worth_cur'];
			coupon_line = coupon['coupon_line'];
			coupon_desc = coupon['coupon_details'];
			coupon_valid_until_date = coupon['valid_until_date']
		}
		else
		{
			offer_type = condition['offer_type'];
			offer_worth = condition['offer_worth'];
			cur_code = "";
		}
		
		if (con_param1)
		{
			var condition_div = $( "<div class='condition_name'> Offer Condition - " + condition_name + " " + con_param1 + "</div>" );
			//condition_param1_div = $( "<div class='con_param'>" + con_param1 + "</div>" );
			//condition_div.append(condition_param1_div);
		}
		else
		{
			var condition_div = $( "<div class='condition_name'> Offer Condition - " + condition_name + "</div>" );
			//condition_div.append(condition_param1_div);
		}
		
		if (offer_type == 'coupon')
		{
			//offer_div = $( "<div style='float:left;'><div class='offer_type'> Coupon </div> worth <div class='offer_type'> $" + offer_worth + "</div></div>" );
			offer_div = $( "<div class='offer_type'> Coupon worth - "+ cur_code + " " + offer_worth + "</div>" );
			condition_div.append(offer_div);
			coupon_details_div = $("<div class='coupon_summary'>" +"<div>Coupon valid until - " + coupon_valid_until_date + " (mm/dd/yyyy)</div>"+"<div>Coupon Code - " + coupon_code + "</div>"+"<div>Coupon Main Line - " + coupon_line + "</div>" +"<div>Coupon details - " + coupon_desc + "</div>"+"</div>")
			condition_div.append(coupon_details_div);
		}
		else if (offer_type == 'dollar')
		{
			//offer_div = $( "<div class='offer_type'> Offering </div> <div class='offer_type'> $" + offer_worth + "</div>" );
			if (cur_code != "")
			{
				offer_div = $( "<div class='offer_type'> Offering " +cur_code + " "  + offer_worth + "</div>" );
			}
			else
			{
				offer_div = $( "<div class='offer_type'> Offering $" + offer_worth + "</div>" );
			}
			condition_div.append(offer_div);
		}
		else if (offer_type != "" && offer_type != 'none')
		{
			offer_div = $( "<div class='offer_type'>"+ offer_type +"</div> worth <div class='offer_type'> $" + offer_worth + "</div>" );
			condition_div.append(offer_div);
		}
		
		if (max_count > 0)
		{
			count_div = $( "<div class='offer_type'> Maximum offering " + max_count + "</div>" );
			condition_div.append(count_div);
			count_div = $( "<div class='offer_type'> Successful promos so far " + met_so_far + "</div>" );
			condition_div.append(count_div);
		}
		
		node.append(condition_div);
	}
	
	$con1 = data['con_code'];
	if (20 == $con1){
		node.append("<div>It is a give-away type coupon and doesn't require any social interaction.</div>");
	}else if (21 == $con1){
		node.append("<div>It is a yelp-review coupon. People will give link to their reviews, which you can check and send coupons for.</div>");
	}else if (14 == $con1){
		node.append("<div>Facebook page to like - <a style='color:white;cursor:pointer;' onclick=\"OpenInNewTab('"+default_fbpageurl+"');\">"+default_fbpageurl+"</a></div>");
	}else if (16 == $con1){
		node.append("<div>Video to post on Facebook - <a style='color:white;cursor:pointer;' onclick=\"OpenInNewTab('"+default_linkforpost+"');\">"+default_linkforpost+"</a></div>");
	}else if (17 == $con1){
		node.append("<div>Email Signup method - <span style='font-weight:bold;color:white;'>"+default_desc+"</span>");
	}else if (18 == $con1){
		node.append("<div>Facebook event to share - <a style='color:white;cursor:pointer;' onclick=\"OpenInNewTab('"+default_link+"');\">"+default_link+"</a></div>");
	}else if (19 == $con1){
		node.append("<div>Facebook event to join - <a style='color:white;cursor:pointer;' onclick=\"OpenInNewTab('"+default_link+"');\">"+default_link+"</a></div>");
	}else{
		if (!show_dynamic_social_coupon.l){node.append("<div>Default Tweet or FB Post - "+default_title+"</div><br/><div>Default Link - "+default_link+"</div><br/><div>Default FB-Share description - "+default_desc+"</div>");}
	}
		
	fade_and_add_close_button(node);node.draggable();
	
	return node;
}
