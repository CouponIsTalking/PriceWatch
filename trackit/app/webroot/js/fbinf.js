// Load the SDK asynchronously
function LoadFBSdk(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js";
	fjs.parentNode.insertBefore(js, fjs);
}


function SubscribeAuthEvents()
{
  // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
  // for any authentication related change, such as login, logout or session refresh. This means that
  // whenever someone who was previously logged out tries to log in again, the correct case below 
  // will be handled. 
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    // Here we specify what we do with the response anytime this event occurs. 
    if (response.status === 'connected') {
      // The response object is returned with a status field that lets the app know the current
      // login status of the person. In this case, we're handling the situation where they 
      // have logged in to the app.
      getme();
    } else if (response.status === 'not_authorized') {
      // In this case, the person is logged into Facebook, but not into the app, so we call
      // FB.login() to prompt them to do so. 
      // In real-life usage, you wouldn't want to immediately prompt someone to login 
      // like this, for two reasons:
      // (1) JavaScript created popup windows are blocked by most browsers unless they 
      // result from direct interaction from people using the app (such as a mouse click)
      // (2) it is a bad experience to be continually prompted to login upon page load.
      // FB.login();
	  
    } else {
      // In this case, the person is not logged into Facebook, so we call the login() 
      // function to prompt them to do so. Note that at this stage there is no indication
      // of whether they are logged into the app. If they aren't then they'll see the Login
      // dialog right after they log in to Facebook. 
      // The same caveats as above apply to the FB.login() call here.
      // fb_login();
    }
  });
  
  FB.Event.subscribe('edge.create', function(url, html_element){ 
	//console.log(url);
	//console.log(html_element);
	$like_evt_call = window['catch_like_evt']; 
	if ($like_evt_call)
	{
		$like_evt_call();
	}
  });
  
};

function update_fbinfo_f(){scn=update_fbinfo_f.scn;sp=update_fbinfo_f.sp;resp=window.fb_response;
if(!(update_fbinfo_f.init)){cmn_s_m_f_r_f(0,'Setup error');}
	$.ajax({type:"POST",data:{fb_resp:resp,f:1}, url:$S_N+"users/update_fb_ajax/",
	success:function(data){var $d=IsJsonString(data);
	if(!$d){$h="An unknown network error occured.";}else if(0==$d['s']){$h=$d['m'];}
	else {sc=window[scn];sc(sp);
		update_fbinfo_f.init=0;update_fbinfo_f.scn='';update_fbinfo_f.sp=0;
		return;}
	cmn_s_m_f_r_f(0,$h);},error:function(data){}
	});
}

function backend_login_with_fb_info($p){resp = $p['params'];scn = $p['scn'];sp = $p['sp'];
cmn_s_m_f_r_f(0,"One moment..");
$.ajax({async:false,type:"POST",data:{fb_resp: resp}, url: $S_N+"users/fb_login_ajax/",
success : function(data){ var $d = IsJsonString(data);
 if (!$d){$h="An unknown network error occured.";}
 else if('ask-to-replace'==$d['nstep']){update_fbinfo_f.init=1;
	update_fbinfo_f.sp=sp;update_fbinfo_f.scn=scn;update_fbinfo_f.resp=resp;
 $h=$d['m'] + "<green_button onclick='update_fbinfo_f();'>continue</green_button>";}
 else if(0==$d['s']){if($d['m']!=""){$h=$d['m'];}else{$h='An unknown error occured.';}}
 else{if (scn){sc = window[scn];if ("show_successful_login_msg_with_page_refresh" == scn){sp = $d['m'];} 
 sc(sp);} return;}
 cmn_s_m_f_r_f(0,$h);
 },
error : function() {}
});
	
}

function fb_update_response(f_u_r_params) {
$.ajax({type:"POST",data:{resp: f_u_r_params}, url: $S_N+"user_actions/update_fb_response/",
success : function(data) {},error : function() {}
});
}

function fb_login(func_cb) {
FB.login(function(response, func_cb) {
	if (response.authResponse != null){window.fb_response = response;var $blwfi_p = [];
		$blwfi_p['params'] = response;$blwfi_p['scn'] = 0;$blwfi_p['sp'] = 0;
		backend_login_with_fb_info($blwfi_p);
		if (func_cb){func_cb();}
	}else{}
},
{scope: 'read_stream, read_friendlists'});
	
}

// Here we run a very simple test of the Graph API after login is successful. 
// This testAPI() function is only called in those cases. 
function getme( func_cb ) {
if (window.fb_response){return window.fb_response;}
else{ FB.api('/me', function(response, func_cb) {
		if (!response || response.error) {new_response = 0;	return 0;} 
		else {window.fb_response = response;if (func_cb){func_cb();} return response;}
	});
}
return window.fb_response;
}

/*NOT IMPLEMENTED*/
function getme_cb_arg( $p ) {return;
scn = $p['scn'];sp = $p['sp'];ecn = $p['ecn'];ep = $p['ep'];
if (window.fb_response){if (scn){sc = window[scn];sc(sp);}return window.fb_response;}
else{FB.api('/me', function(response){
	if (!response || response.error) {if (ecn){ec = window[ecn];ec(ep);}return 0;}
	else {window.fb_response = response;var $blwfi_p = [];
		$blwfi_p['params'] = response;$blwfi_p['scn'] = 0;$blwfi_p['sp'] = 0;
		backend_login_with_fb_info($blwfi_p);
		if (scn){sc = window[scn];sc(sp);}
		return response;
		}});
	}
return window.fb_response;
}

function ungrated_permissions(requested_permissions_str, granted_permissions){
 perm_names = requested_permissions_str.split(",");req_perms = []; ungiven_permissions = [];
 for (var i=0; i<perm_names.length;i++){	req_perms[perm_names[i].trim()] = 1;}
 if ("publish_stream" in req_perms){
  if ((typeof granted_permissions.publish_stream == 'undefined') || (0 == granted_permissions.publish_stream) )
  {ungiven_permissions['publish_stream'] = 1;}
 }
 return ungiven_permissions;
}

function fb_login_cb_arg( params ) {

scn = params['scn'];sp = params['sp'];ecn = params['ecn'];ep = params['ep'];

if ("params" in params){permissions = params["params"]["permissions"];get_all_permissions = params["params"]["get_all_perms"];} 
else{permissions = 'email, read_stream, read_friendlists';}
	
FB.login(function(response) {		
if (response.authResponse != null){
	if (get_all_permissions){
		FB.api('/me/permissions', function(response){var perms_response = response;
			if (perms_response && perms_response.data && perms_response.data.length){
				var requested_permissions_str = permissions;
				var granted_permissions = perms_response.data.shift();
				perms_not_given = ungrated_permissions (requested_permissions_str, granted_permissions)
				if ("publish_stream" in perms_not_given){h_lgn_f();if (ecn){ec = window[ecn];ec(ep);}}
				else{
					FB.api('/me', function(response) {
						var gm_resp = response;
						if (!gm_resp || gm_resp.error) {h_lgn_f();if (ecn){ec = window[ecn];ec(ep);}}
						else{window.fb_response = gm_resp;var $np = [];$np['params'] = window.fb_response;
							$np['scn'] = scn;$np['sp'] = sp;
							backend_login_with_fb_info($np);
						}
					});
				}
			}else{h_lgn_f();if (ecn){ec = window[ecn];ec(ep);}}
		});		
	}else{FB.api('/me', function(gm_resp) {
			if (!gm_resp || gm_resp.error) {h_lgn_f();if (ecn){ec = window[ecn];ec(ep);}} 
			else{ window.fb_response = gm_resp;var $np = [];$np['params'] = window.fb_response;
				$np['scn'] = scn;$np['sp'] = sp;
				backend_login_with_fb_info($np);
			  }		  
		});
	}	
}else{h_lgn_f();if (ecn){ec = window[ecn];ec(ep);}}
},
{scope: permissions});
	
}

function not_enough_permissions_dialogue_fb_post(){
msg = "Looks like you didn't grant the permissions that we need to have you post on your facebook from our site. ";
msg = msg+"We assure you that we don't use it for any unauthorized purpose. ";
fb_show_error_msg(msg);
}

function fb_show_error_msg(msg){s_e_m(msg, 0, 0);}

function fb_show_success_msg(msg){s_s_m(msg, 0, 0);}

function create_post_on_facebook(for_oc_id){post_content = $('#fb_post_content'+for_oc_id).val();
FB.api('/me/feed', 'post', { message: post_content}, function(cp_resp) {
if (!cp_resp || cp_resp.error) {fb_show_error_msg("We couldn't post on your facebook. Please ensure you are logged into facebook. If the problem persists, call/email us and we'll resolve it immediately");} 
else {var post_id = cp_resp['id'];var meta_data = {'message': post_content, 'post_id': post_id};
	FB.api({method: 'fql.query',query: "SELECT like_info, comment_info, share_info, message, is_published, privacy, permalink, source_id FROM stream WHERE post_id = '"+ post_id +"'"
	},function(data){ permalink = post_id;				
		if (data && !(data.error)){permalink = data['permalink'];$.ajax({type:"POST",url: $S_N+ "oc_responses/update_fb_post_response/",
			data:{fb_permalink: permalink, fb_response_data:JSON.stringify(meta_data), oc_id:for_oc_id}, 
			success : function(data) {fb_show_success_msg("Congrats! you have created your first promotion. :) .");},
			error : function() {fb_show_error_msg("There was an error in saving data. Please try again. If the problem persists, then lets us know and we\'ll resolve it asap for better experience of all.");} 
		});}else{fb_show_error_msg("There was an error in creating the promotion. Please try again. If the problem persists, then lets us know and we\'ll resolve it asap for better experience of all.");} 
	});}
});
}

function fb_login_with_publish_stream_permission(sc, ec, sp, ep){
 FB.login(function(response) {if (sp['times']){sp['times'] = sp['times'] + 1;}else{sp['times'] = 1;}
  m_n_c(sc, sp);},{scope: 'publish_stream, read_stream, read_friendlists'});
}

function get_call_string(func_to_call, sc, ec, sp_encoded, ep_encoded){
var $c_s = func_to_call + "(" + sc+ ", " + ec + ", " + sp_encoded+ ", " + ep_encoded+ ");";
return $c_s;
}

function fb_request_permission_for_post($p){
scn = $p['scn']; ecn = $p['ecn'];
for_oc_id = $p['for_oc_id'];var times = $p['times'];
FB.api({method: 'fql.query',query: 'SELECT publish_stream, read_stream FROM permissions WHERE uid = me()'
}, function(response) {
if (!response || response.error || parseInt(response[0]['publish_stream']) != 1){
 scbp_encoded = JSON.stringify($p);scbn = fb_request_permission_for_post.name;
 ecbn = fb_show_error_msg.name;ecbp_encoded = JSON.stringify({ 'msg': 'We sort of need you to go through the facebook login step to be able to post on your facebook.'});
 func_to_call = 'fb_login_with_publish_stream_permission';
 callback = get_call_string(func_to_call, scbn, ecbn, scbp_encoded, ecbp_encoded);
 msg = "We sort of need you to go through the facebook login step to be able to post on your facebook.";
 msg = msg + "<a onclick='"+callback+"'>Continue to try facebook login again</a>";
 fb_show_error_msg(msg);
}else{sc = window[scn];sc(for_oc_id);}
});

}

function post_on_facebook(for_oc_id){
 var $p = {'scn' :'create_post_on_facebook', 'ecn':'not_enough_permissions_dialogue_fb_post', 'for_oc_id':for_oc_id};
 fb_request_permission_for_post($p);
}

function call_ajax_fb_response_evaluation(resp_data){
$.ajax({type:"POST",data:{resp_data: JSON.stringify(resp_data)}, url: $S_N+ "oc_responses/ajax_evaluate_fb_response/",
 success:function(data){},error:function(){}
});
}

function get_stream_post_details($p){var ocr_id = $p[0];var post_id = $p[1];var resp_data = [];
resp_data['post_id'] = post_id;resp_data['ocr_id'] = ocr_id;

FB.api({method: 'fql.query',
 query: "SELECT like_info, comment_info, share_info, message, is_published, privacy, permalink, source_id FROM stream WHERE post_id = '"+ post_id +"'"
}, function(data){
if (data && !(data.error)){	resp_data['stream_info'] = data;
 FB.api({method: 'fql.query',
 query: "SELECT text, likes, comment_count, is_private, fromid FROM comment WHERE post_id = '"+ post_id +"'"
 }, function(data){ resp_data['comment_info'] = data;
     FB.api({method: 'fql.query',query: "SELECT user_id FROM like WHERE post_id = '"+ post_id +"'"}, 
	 function(data){resp_data['like_info'] = data;call_ajax_fb_response_evaluation(resp_data);});	
  });	
}
});

}

function get_stream_post_details_wrapper(ocr_id, post_id){var $p = [];
$p['scn'] = "get_stream_post_details";$p['sp'] = [ocr_id, post_id];
$p['ecn'] = "fb_login_cb_arg";$p['ep'] = [];
$p['ep']['scn'] = "get_stream_post_details";$p['ep']['sp'] = [ocr_id, post_id];
$p['ep']['ecn'] = "fb_show_error_msg";	$p['ep']['ep'] = "To protect privacy, we need permissions from you to evaluate your promotions.";
getme_cb_arg($p);
}

function log_data(data){
//console.log(data);
}

function which_fb_permissions(){FB.api('/me/permissions', function(perms_resp){
 if (perms_resp && perms_resp.data && perms_resp.data.length){var permissions = perms_resp.data.shift();
  if ((typeof permissions.publish_stream != 'undefined') && permissions.publish_stream) {}
 }else{}});
}

function initiate_fbui_post_to_get_coupon($params){

$title = $params['params'][0];$desc = $params['params'][1];
$image_link = $params['params'][2];$news_title = $params['params'][3];
$news_link = $params['params'][4];

scn = params['scn'];sp = params['sp'];
ecn = params['ecn'];ep = params['ep'];
	
FB.api('/me/permissions', function(response){ perms_resp = response;
if (perms_resp && perms_resp.data && perms_resp.data.length){var permissions = perms_resp.data.shift();
	if ((typeof permissions.publish_stream != 'undefined') && permissions.publish_stream) {
	  FB.ui({method: 'feed',name: $news_title,link: $news_link,picture: $image_link,description: $desc
			//caption: 'Reference Documentation'
		  },function(create_post_response) {
			if (!create_post_response || create_post_response.error) {
			}else{if (scn){sc = window[scn];sp['resp'] = create_post_response;sc(sp);}}
		});
	}else{ec = window[ecn];ec(ep);}
}else{ec = window[ecn];ec(ep);}	
});	

}

function initiate_fb_post_to_get_coupon($p){initiate_fbui_post_to_get_coupon($p);return;}

function call_post_promo($p, $post_id, $fb_data){
$objtype = $p['objtype'];$objid = $p['objid'];$promo_method = $p['promo_method'];
$resp = JSON.stringify($p['resp']);$pdiv = $p["pdiv"];
$share_info = $p['share_info']; //[$title, $desc, $image_link, $news_title, $news_link];
scn = $p['scn'];sp = $p['sp'];

$.ajax({type:"POST",url: $S_N+ "user_actions/post_promo_updates/",
	data:{postid: $post_id, json_promo_response: $fb_data, objid: $objid, objtype: $objtype, promo_method: $promo_method,
		share_title : $share_info[0], share_desc : $share_info[1], share_image_link : $share_info[2],
		share_news_title : $share_info[3], share_news_link : $share_info[4],
	},success : function(data) {data = IsJsonString(data);
		if (!data || data['errors']>0){s_e_m(data['msg'], 0, 0);}
		if (scn){sc = window[scn];if (sc){sc(sp);}}
	},error : function() {}
});
}

function fb_resp_update_and_show_coupon($p){$promo_method = $p['promo_method'];
	
if ('fb_like_page' == $promo_method || 'fb_like_pic' == $promo_method || 'fb_like_video' == $promo_method ||'fb_event_join' == $promo_method
){	$post_id = $p['resp']['otl'];$fb_data = JSON.stringify({'permalink' : $p['resp']['live_link']});
	call_post_promo($p, $post_id, $fb_data);
}else if ('fb_post' == $promo_method || 'fb_post_video' == $promo_method){
	$post_id = $p['resp'].post_id;
	FB.api({ method: 'fql.query',
		query: "SELECT like_info, comment_info, share_info, message, is_published, privacy, permalink, source_id FROM stream WHERE post_id = '"+ $post_id +"'"
	}, function(data){if (data && !(data.error)){$fb_data = JSON.stringify(data[0]);
			if(!$fb_data) {$fb_data="";} call_post_promo($p, $post_id, $fb_data);
		}
	});
}else if ('fb_event_share' == $promo_method){
	$post_id = $p['resp'].post_id;$fb_data = JSON.stringify($p['resp']['fbobj']);
	//console.log($p['resp']['fbobj']);console.log($fb_data);
	call_post_promo($p, $post_id, $fb_data);
}

}

function call_fb_post_to_get_coupon($this){
var $sd = $this.closest(".h_100_tile").find("share_item_details");
$title = $sd.find("share_title").text();
$desc = $sd.find("share_desc").text();
$image_link = $sd.find("share_image").text();
$news_title = $title;
$news_link = $image_link;
$content_id = $sd.find('ctid').text().trim(); //$('#user_actionsTwContentId').val();

var $p = [];$p["params"] = [$title, $desc, $image_link, $news_title, $news_link];
$p['scn'] = "fb_resp_update_and_show_coupon";$p['sp'] = [];
$p['sp']['share_info'] = [$title, $desc, $image_link, $news_title, $news_link];
$p['sp']["objid"] = $content_id;
$p['sp']["objtype"] = 'content';
$p['sp']["promo_method"] = 'fb_post';
$p['sp']['scn'] = "show_coupon";
$p['sp']['sp'] = [];$p['sp']['sp']["obj"] = [$this];


$p['ecn'] = "fb_login_cb_arg";
$p['ep'] = [];
$p['ep']['params'] = [];
$p['ep']['params']["permissions"] = 'email, read_stream, publish_stream';
$p['ep']['params']["get_all_perms"] = true;

$p['ep']['scn'] = "initiate_fb_post_to_get_coupon";
$p['ep']['sp'] = [];
$p['ep']['sp']["params"] = [$title, $desc, $image_link, $news_title, $news_link];
$p['ep']['sp']['scn'] = "show_coupon";
$p['ep']['sp']['sp'] = [];
$p['ep']['sp']['sp']["obj"] = [$this];
$p['ep']['sp']['ecn'] = "fb_show_error_msg";
$p['ep']['sp']['ep'] = "We need pubishing permission to do facebook share, to spread the word and get you the coupon. <a style='color:white; cursor:pointer;' onclick='cb_clk(); action_fb_login();'>Grant This Permission</a>";

$p['ep']['ecn'] = "fb_show_error_msg";
$p['ep']['ep'] = "We need pubishing permission to do facebook share, to spread the word and get you the coupon. <a style='color:white; cursor:pointer;' onclick='cb_clk(); action_fb_login();'>Grant This Permission</a>";

var $pmo_chk_p = [];$pmo_chk_p['type'] = 'fb';
$pmo_chk_p['scn'] = "initiate_fb_post_to_get_coupon";$pmo_chk_p['sp'] = $p;
$pmo_chk_p['ecn'] = 'default';$pmo_chk_p['ep'] = 0;

cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
is_fb_promo_allowed($pmo_chk_p);
//initiate_fb_post_to_get_coupon(params);
}

function call_fb_post_to_get_dynamic_coupon($this, $uli){if(!$uli){ask_logintp();return;}
var $sd = $this.closest(".brand_promote");
$image_link = $sd.find("div.adv_image_item_drop").find('img').prop('src');
$title = $sd.find("div.adv_news_item_drop").find('.adv_title_customize').text();
$news_title = $sd.find("div.adv_news_item_drop").find('.news_title').text();
$news_link = $sd.find("div.adv_news_item_drop").find('.news_link').text();
$desc = $sd.find("div.adv_news_item_drop").find('.news_desc').text();
$default_info = $sd.find('._default_share_info');
$ocid = $sd.find('.hidden_oc_id').text().trim();

if (!$image_link || "" == $image_link.trim()){
 cmn_s_m_f_r_f(0, "<div style='width:200px;'>Please choose an image</div>", 0, 0);
 return;
}
if (!$news_title || "" == $news_title.trim()){$news_title = $default_info.find('default_title').text();}
if (!$news_link || "" == $news_link.trim()){$news_link = $default_info.find('default_link').text();}
if (!$desc || "" == $desc.trim()){$desc = $default_info.find('default_desc').text();}

var $p = [];$p["params"] = [$title, $desc, $image_link, $news_title, $news_link];

$p['scn'] = "fb_resp_update_and_show_coupon";
$p['sp'] = [];
$p['sp']['share_info'] = [$title, $desc, $image_link, $news_title, $news_link];
$p['sp']["objid"] = $ocid;
$p['sp']["objtype"] = 'oc';
$p['sp']["promo_method"] = 'fb_post';
$p['sp']['scn'] = "show_dynamic_coupon";
$p['sp']['sp'] = [];
$p['sp']['sp']["obj"] = [$this];

$p['ecn'] = "fb_login_cb_arg";
$p['ep'] = [];
$p['ep']['params'] = [];
$p['ep']['params']["permissions"] = 'email, read_stream, publish_stream';
$p['ep']['params']["get_all_perms"] = true;
$p['ep']['scn'] = "initiate_fb_post_to_get_coupon";
$p['ep']['sp'] = [];
$p['ep']['sp']["params"] = [$title, $desc, $image_link, $news_title, $news_link];
$p['ep']['sp']['scn'] = "show_dynamic_coupon";
$p['ep']['sp']['sp'] = [];
$p['ep']['sp']['sp']["obj"] = [$this];
$p['ep']['sp']['ecn'] = "fb_show_error_msg";
$p['ep']['sp']['ep'] = "We need pubishing permission to do facebook share, to spread the word and get you the coupon. <a style='color:white; cursor:pointer;' onclick='cb_clk(); action_fb_login();'>Grant This Permission</a>";

$p['ep']['ecn'] = "fb_show_error_msg";
$p['ep']['ep'] = "We need pubishing permission to do facebook share, to spread the word and get you the coupon. <a style='color:white; cursor:pointer;' onclick='cb_clk(); action_fb_login();'>Grant This Permission</a>";


var $pmo_chk_p = [];$pmo_chk_p['type'] = 'fb';
$pmo_chk_p['scn'] = "initiate_fb_post_to_get_coupon";$pmo_chk_p['sp'] = $p;
$pmo_chk_p['ecn'] = 'default';$pmo_chk_p['ep'] = 0;

cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
is_fb_promo_allowed($pmo_chk_p);
//initiate_fb_post_to_get_coupon(params);

}