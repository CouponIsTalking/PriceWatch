function cto_gapi_eurl($e){$e=addhttp($e);$gbu = "http://graph.facebook.com";
start_strs = ["http://www.facebook.com", "http://facebook.com", "https://www.facebook.com", "https://facebook.com"];
for(index in start_strs){s=start_strs[index];if(0==$e.indexOf(s)){$e=$gbu + $e.substring(s.length);break;}}
if (0!=$e.indexOf($gbu)||$e.length <= $gbu.len){return false;}return $e;
}

function get_evtpage_props($gp_p){var $url = $gp_p['eurl'];
to_call_name=$gp_p['call_name'];to_call_params=$gp_p['call_params'];
$.ajax({type:"GET",url:$url,success:function(data){var fb_id=0;try{fb_id=data.id;}catch(e){}
 to_call_params['prev_re']=fb_id;if(to_call_name){to_call=window[to_call_name];to_call(to_call_params);}},
 error:function(data){}
});	
}