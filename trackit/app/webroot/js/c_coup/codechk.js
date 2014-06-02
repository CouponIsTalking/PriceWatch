function coupcodechk($code){ $ret=false;if(!$code){return $ret;}$code=$code.trim();if(""==$code){return $ret;}
	cmn_s_m_f_r_f(0,'Checking if you have already used this coupon code in past.',0,0);
	$.ajax({url:$S_N+"open_campaigns/company_has_coupon/"+$code,method:'get','async':false,
		success:function(data){cb_clk();data=IsJsonString(data); if(data&&(0==data.is_present)){$ret=true;$('._coupon_code_check').show();}else{$('._coupon_code_check').hide();}},
		error:function(data){$('._coupon_code_check').hide();cmn_s_m_f_r_f(0,"Could not talk to the server.",0,0);}
	});
	return $ret;
}