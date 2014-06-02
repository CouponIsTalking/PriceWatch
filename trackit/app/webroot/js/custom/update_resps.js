function is_fb_promo_allowed($p){is_promo_allowed($p);}

function is_promo_allowed($params){$type = $params['type'];
scn = $params['scn'];sp = $params['sp'];ecn = $params['ecn'];ep = $params['ep'];

$.ajax({type:"POST",data:{type: $type}, url: $S_N+ "user_actions/is_new_promo_allowed/"+$type,
 success:function(data){ data = IsJsonString(data);if (data && data.success == 1){sc = window[scn];if (sc){sc(sp);}}
	else{$msg = data.msg;
		if ('default' != ecn){ec = window[ecn];if (ec){ec(ep);return;}}
		else{cb_clk();cmn_s_m_f_r_f(0,data.msg,0,0);}
	}},error:function(data){}
});

}

function send_blog_response( for_oc_id ){
link = $('#blogpost_link'+for_oc_id).val();
$.ajax({type:"POST",data:{blogpost_link: link, oc_id:for_oc_id}, url: $S_N+ "oc_responses/update_blog_response/",
	success : function(data) {fb_show_success_msg (data);},
	error : function(data) {fb_show_error_msg(data)}
});

}

function send_reddit_response(for_oc_id){
var link = $('#redditpost_commentpage_link'+for_oc_id).val();	
$.ajax({type:"POST",data:{redditpost_commentpage_link: link, oc_id:for_oc_id}, url: $S_N+ "oc_responses/update_reddit_response/",
	success : function(data){fb_show_success_msg (data);},error : function(data){fb_show_error_msg(data);}
});

}

function send_imgur_response(for_oc_id){
var link = $('#imgurpost_link'+for_oc_id).val();
$.ajax({type:"POST",data:{imgurpost_link: link, oc_id:for_oc_id},url: $S_N+ "oc_responses/update_imgur_response/",
	success : function(data) {fb_show_success_msg (data);},error : function(data) {fb_show_error_msg(data);}
});
}