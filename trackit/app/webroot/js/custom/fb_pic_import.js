function photo_import_from_fb_id($p){$fb_id=$p['fb_id'];
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];
if (undefined == window[scn]){var js_load_p=[];js_load_p['script']='custom/pic_selector';
js_load_p['call_to_load']=scn;js_load_p['scn']='photo_import_from_fb_id';js_load_p['scp']=$p;
js_load_p['ecn']=ecn;js_load_p['ecp']=ep;	
s_s_m("Processing ... ");script_load_and_call(js_load_p);return;
}	

$uri="/"+$fb_id+"/photos/uploaded?" + "limit=100";s_s_m("Processing ...  ");
FB.api($uri, function(photos_resp){
    if (!photos_resp || photos_resp.error){if (104==photos_resp.error.code){action_fb_login();}else{window[ecn];ec(ep);}
	}else{sc=window[scn];sp['photos']=photos_resp;sp['base_uri']=$uri;sc(sp);}
});

}

function get_page_url_and_import_pics($this){$page_url=$this.parent().find('._fb_page_url').val();photo_import_from_fb_page($page_url);}

function ask_fb_page_url_and_import_pics(){
$html="<div><label style='width:500px;' class='_fb_page_url_label'>enter your facebook page url</label>"
+"<input style='width:500px;' class='_fb_page_url' val='enter your facebook page url'></input><br/>"
+"<green_button onclick=\"get_page_url_and_import_pics($(this));\">Import Photos</green_button></div>";
cb_clk();s_s_m($html);fit_to_inner_content('div.success_msg');r_i_c('div.success_msg');
}

function photo_import_from_fb_page($page_url){if (!$page_url){ask_fb_page_url_and_import_pics();return;}
cb_clk();$page_url=get_gapi_purl_from_page_url($page_url);
if (!$page_url){s_s_m("FB page Url does not seem right.");return;}

var $p=[];$p['scn']="show_image_import_picker";$p['sp']=[];$p['ecn']="fb_show_error_msg";$p['ep']="Oops.. import failed!";
if(is_ie()){$.ajax({type:"POST",url: $S_N+'socials/get_page_info',data:{'purl':$page_url},success:function(data){data=IsJsonString(data);
	var $msg = "FB page Url does not seem right.";var fb_id=0;
	if(data){if(data['success']){fb_id = data['page_info']['id'];}else{$msg=data['msg'];}}
	if (fb_id){$p['fb_id']=fb_id;photo_import_from_fb_id($p);}else{s_s_m($msg);}	   
},error:function(data){s_s_m("FB page Url does not seem right.");}
});}else{
$.ajax({type:"GET",url: $page_url,success:function(data){var fb_id=0;try {fb_id=data.id;}catch(e){}
	if (fb_id){$p['fb_id']=fb_id;photo_import_from_fb_id($p);}else{s_s_m("FB page Url does not seem right.");}	   
	},error:function(data){s_s_m("FB page Url does not seem right.");}
});
}

}

function photo_import_from_fb_account(p1){
if (p1){$p=p1;}else{$p=[];$p['scn']="show_image_import_picker";$p['sp']=[];$p['ecn']="fb_show_error_msg";$p['ep']="Oops.. import failed!";}
s_s_m("Processing... ");
	
FB.api('/me/permissions', function(response){var perms_resp=response;var haveperm=false;cb_clk();
 if (perms_resp && perms_resp.data && 0 != perms_resp.data.length){var granted_perms=perms_resp.data.shift();
	if (granted_perms && 0 != granted_perms.user_photos){haveperm=true;}
 }
 if (!haveperm){$alink=$("<a style='cursor:pointer; color:white;'>Grant FB permission to import photos</a>");
  $alink.on('click', function(){FB.login(function(login_resp){if (login_resp && !(login_resp.error)){s_s_m("Processing ...");
   FB.api('/me', function(me_resp){cb_clk();if(me_resp&& !(me_resp.error)){$p['fb_id']=me_resp['id'];photo_import_from_fb_id($p);}}); 
	}},{scope: 'read_stream, user_photos'}); }); cmn_s_m_f_r_f(0, $alink, 0, 0);
 }else{s_s_m("Processing ... ");
   FB.api('/me', function(me_resp){cb_clk();if(me_resp&& !(me_resp.error)){$p['fb_id']=me_resp['id'];photo_import_from_fb_id($p);} });
  }
});

}