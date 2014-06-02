function call_linkstgdc($clk_e,$uli){if(!$uli){ask_logintp();return;}
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$foi=$sd.find("div._joinevti").text().trim();$fourl=$sd.find('div._joinevtu').text().trim();
$ocid=$sd.find('.hidden_oc_id').text().trim();
$p=[];$p["params"]=[$foi, $fourl, 0];
$p['scn']="fb_resp_update_and_show_coupon";$p['sp']=[];$p['sp']['share_info']=[$foi, '', $fourl, '', ''];$p['sp']["objid"]=$ocid;
$p['sp']["objtype"]='oc';$p['sp']["promo_method"]='fb_event_share';//$p['sp']["resp"]={'otl' : $foi, 'live_link': $fourl};
$p['sp']['scn']="show_dynamic_coupon";$p['sp']['sp']=[];$p['sp']['sp']["obj"]=[$this];
$p['ecn']="s_s_m";$p['ep']="Sorry, we couldn't verify your event share. This may happen if you haven't provided all permissions.";
$p["nlcn"]="ask_addfb";$p["nlcp"]=0;
$p["npcn"]="s_s_m";$p["npp"]="We need events related permission from you. Please <a style='color:white; cursor:pointer;' onclick=\"cb_clk(); get_extra_perms('email, read_stream, publish_stream');\">give these permissions</a> here.";
var $promo_chk_p=[];$promo_chk_p['type']='fb';$promo_chk_p['scn']="linkstgdc_m";$promo_chk_p['sp']=$p;
$promo_chk_p['ecn']='default';$promo_chk_p['ep']=0;
cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
is_fb_promo_allowed($promo_chk_p);
//initiate_fb_post_to_get_coupon(params);
}

function linkstgdc_a($p){$foi=$p['params'][0];$fourl=$p['params'][1];$sotl=$p['params'][2];
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];npcn=$p['npcn'];npp=$p['npp'];
nlcn=$p['nlcn'];nlcp=$p['nlcp'];$user_id=0;

FB.api('/me/permissions', function(perms_resp){
if (perms_resp && perms_resp.data && perms_resp.data.length){var permissions = perms_resp.data.shift();
	if ((typeof permissions.publish_stream != 'undefined') && permissions.publish_stream) {
	  FB.ui({method: 'feed',link: $fourl},function(cp_resp){
			if (!cp_resp || cp_resp.error) {}
			else{if (scn){sc = window[scn];sp['resp'] = cp_resp;sc(sp);}}
		});
	}else{npc=window[npcn];if(npc){npc(npp);}}
}});

}

function linkstgdc_m($p){$foi=$p['params'][0];$fourl=$p['params'][1];$sotl=$p['params'][2];
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];npcn=$p['npcn'];npp=$p['npp'];
nlcn=$p['nlcn'];nlcp=$p['nlcp'];$user_id=0;
	
FB.api('/me',function(me_resp){if (!me_resp || me_resp.error || !me_resp.id){if (nlcn){nlc=window[nlcn];if(nlc){nlc(nlcp);}}}
else{$user_id=me_resp.id; FB.api('/me/permissions',function(perms_resp){cb_clk();
	if(perms_resp && perms_resp.data && perms_resp.data.length){var perms = perms_resp.data.shift();
		if(((typeof perms.read_stream != 'undefined') && perms.read_stream)){linkstgdc_m_r($p,0);}
		else{npc=window[npcn];if(npc){npc(npp);}}
	}}); }});
	
}

function linkstgdc_m_r($p, $ver_m){if(!$p && linkstgdc_m_r.ump){$p=linkstgdc_m_r.p;}else{linkstgdc_m_r.p=$p;linkstgdc_m_r.ump=1;}
$foi=$p['params'][0];$fourl=$p['params'][1];$sotl=$p['params'][2];
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];npcn=$p['npcn'];npp=$p['npp'];
nlcn=$p['nlcn'];nlcp=$p['nlcp'];$user_id=0;

if(!$ver_m){$html="<span class='__js_shr_update_msg'></span><br/><br/>Go to <a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Event page</a> and share the event <br/>OR<br/> Copy the event link below and post it on your facebook.<textarea readonly>"+$fourl+"</textarea>. Once event is shared or posted, <a style='color:white;cursor:pointer;' onclick=\"$('span.__js_shr_update_msg').html('Nice, checking few things...');linkstgdc_m_r(0,1);\">unlock the coupon</a>.";
s_s_m($html,0,0);
}else if(1==$ver_m ||'1'==$ver_m){FB.api("/me/links",function (f_resp){
	  if(!f_resp || f_resp.error || !f_resp.data){$html = "<span class='__js_shr_update_msg'>Ahhh.. We couldn't verify your event share. Did you share the facebook event?</span><br/><br/>Go to <a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Event page</a> and share the event <br/>OR<br/> Copy the event link below and post it on your facebook.<textarea readonly>"+$fourl+"</textarea>. Once event is shared or posted, <a style='color:white;cursor:pointer;' onclick=\"$('div.__js_shr_update_msg').html('Nice, checking few things...');linkstgdc_m_r(0,1);\">unlock the coupon</a>.";
			s_s_m($html,0,0);}
	  else{$l="/events/"+$foi+"/";$call_s=false;
		for (var i=0;i<f_resp.data.length;i++){if($l==f_resp.data[i].link){$call_s=true;break;}}
		if($call_s&&scn){linkstgdc_m_r.ump=0;sp['resp']={'ots': $foi};sp['resp'].post_id=f_resp.data[i].id;sp['resp']['fbobj']=f_resp.data[i];sc=window[scn];if(sc){sc(sp);}}
		else{$html = "<span class='__js_shr_update_msg'>Ahhh.. We couldn't verify your event share. Did you share the facebook event?</span><br/><br/>Go to <a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Event page</a> and share the event <br/>OR<br/> Copy the event link below and post it on your facebook.<textarea readonly>"+$fourl+"</textarea>. Once event is shared or posted, <a style='color:white;cursor:pointer;' onclick=\"$('span.__js_shr_update_msg').html('Nice, checking few things...');linkstgdc_m_r(0,1);\">unlock the coupon</a>.";
			s_s_m($html,0,0);
		}}
	});
}

}