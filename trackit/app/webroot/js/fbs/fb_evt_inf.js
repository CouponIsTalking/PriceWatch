function call_evtjtgdc($clk_e,$uli){if(!$uli){ask_logintp();return;}
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$foi=$sd.find("div._joinevti").text().trim();$fourl=$sd.find('div._joinevtu').text().trim();
$ocid=$sd.find('.hidden_oc_id').text().trim();
$p=[];$p["params"]=[$foi, $fourl, 0];
$p['scn']="fb_resp_update_and_show_coupon";$p['sp']=[];$p['sp']['share_info']=[$foi, '', $fourl, '', ''];$p['sp']["objid"]=$ocid;
$p['sp']["objtype"]='oc';$p['sp']["promo_method"]='fb_event_join';$p['sp']["resp"]={'otl' : $foi, 'live_link': $fourl};
$p['sp']['scn']="show_dynamic_coupon";$p['sp']['sp']=[];$p['sp']['sp']["obj"]=[$this];
$p['ecn']="s_s_m";$p['ep']="Sorry, we couldn't verify your event join. This may happen if you haven't provided all permissions.";
$p["nlcn"]="ask_addfb";$p["nlcp"]=0;
$p["npcn"]="s_s_m";$p["npp"]="To maintain trust with our users, we request Facebook permissions only when we need it. Right now, we need your permission to access events. Please <a style='color:white; cursor:pointer;' onclick=\"cb_clk(); get_extra_perms('email, read_stream, publish_actions, user_events');\">give these permissions</a> here.";
var $promo_chk_p=[];$promo_chk_p['type']='fb';$promo_chk_p['scn']="jevttgdc_m";$promo_chk_p['sp']=$p;
$promo_chk_p['ecn']='default';$promo_chk_p['ep']=0;
cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
is_fb_promo_allowed($promo_chk_p);
//initiate_fb_post_to_get_coupon(params);
}

function jevttgdc_a($p){$foi=$p['params'][0];$fourl=$p['params'][1];$sotl=$p['params'][2];
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];npcn=$p['npcn'];npp=$p['npp'];
nlcn=$p['nlcn'];nlcp=$p['nlcp'];$user_id=0;
	
FB.api('/me',function(me_resp){if (!me_resp || me_resp.error || !me_resp.id){if (nlcn){nlc=window[nlcn];if(nlc){nlc(nlcp);}}}
else{$user_id=me_resp.id; FB.api('/me/permissions',function(perms_resp){cb_clk();
	if(perms_resp && perms_resp.data && perms_resp.data.length){var perms = perms_resp.data.shift();
		if(((typeof perms.rsvp_events != 'undefined') && perms.rsvp_events))
		{
			FB.api("/"+$foi+"/attending","POST",function (rsvp_resp){
				  if (rsvp_resp && !rsvp_resp.error){if(scn){sc=window[scn];if(sc){sc(sp);}}}
				  else {}
				}
			);
		}else{npc=window[npcn];if(npc){npc(npp);}}
	}}); 
}});
}

function jevttgdc_m($p){$foi=$p['params'][0];$fourl=$p['params'][1];$sotl=$p['params'][2];
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];npcn=$p['npcn'];npp=$p['npp'];
nlcn=$p['nlcn'];nlcp=$p['nlcp'];$user_id=0;
	
FB.api('/me',function(me_resp){if (!me_resp || me_resp.error || !me_resp.id){if (nlcn){nlc=window[nlcn];if(nlc){nlc(nlcp);}}}
else{$user_id=me_resp.id; FB.api('/me/permissions',function(perms_resp){cb_clk();
	if(perms_resp && perms_resp.data && perms_resp.data.length){var perms = perms_resp.data.shift();
		if(true||((typeof perms.user_events != 'undefined') && perms.user_events))
		{FB.api({method: 'fql.query',query: "select rsvp_status from event_member where eid = '"+$foi+"' and uid=me()"},
		  function(fql_resp){if(!fql_resp.error && (1 == fql_resp.length)){rsvp = fql_resp[0].rsvp_status;
			if ('attending' == rsvp){if(scn){sc=window[scn];if(sc){sc(sp);}}}
			else if ('unsure' == rsvp){s_s_m("Ahh.. 'maybe' doesn't count !<br/><a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Change your mind and join ?</a>. Once you join, <a style='color:white;cursor:pointer;' onclick=\"call_evtjtgdc(1,1);\">unlock coupon</a>.");}
			else if ('not_replied' == rsvp){s_s_m("<a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Join facebook event</a>. After you join, <a style='color:white;cursor:pointer;' onclick=\"call_evtjtgdc(1,1);\">unlock coupon</a>. Maybe doesn't count.");}
			else if ('declined' == rsvp){s_s_m("What.. did you decline? Thats sad:( <br/><a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Well, if you change your mind and decide to join</a>, then dont forget to, <a style='color:white;cursor:pointer;' onclick=\"call_evtjtgdc(1,1);\">unlock coupon</a>.");}
			}else{
				s_s_m("<a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Join facebook event</a>. After you join, <a style='color:white;cursor:pointer;' onclick=\"call_evtjtgdc(1,1);\">unlock coupon</a>. Maybe doesn't count.");
			}});
		}else{npc=window[npcn];if(npc){npc(npp);}}
	}}); }});
	
}