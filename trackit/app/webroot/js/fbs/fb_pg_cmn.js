function get_gapi_purl_from_page_url($page_url){$page_url = addhttp($page_url);
$graph_base_url = "http://graph.facebook.com";
start_strs = ["http://www.facebook.com", "http://facebook.com", "https://www.facebook.com", "https://facebook.com"];
for(index in start_strs){s = start_strs[index];
 if(0==$page_url.indexOf(s)){$page_url = $graph_base_url + $page_url.substring(s.length);break;}
}
if (0!=$page_url.indexOf($graph_base_url)||$page_url.length <= $graph_base_url.len){return false;}
return $page_url;
}

function get_fbpage_props($gp_p){var $url = $gp_p['purl'];
to_call_name=$gp_p['call_name'];to_call_params=$gp_p['call_params'];
$.ajax({type:"GET",url:$url,success:function(data){var fb_id=0;try{fb_id=data.id;}catch(e){}
 to_call_params['prev_re']=fb_id;if(to_call_name){to_call=window[to_call_name];to_call(to_call_params);}},
 error:function(data){}
});	
}