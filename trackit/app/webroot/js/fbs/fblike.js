function getobjlikestatus($otl){// 0 - dont know, 1-already liked, 2 - prev-unliked, 3 - prev-unliked, recently-liked
if (window.prv_liked_ary && window.prv_liked_ary[$otl]){return window.prv_liked_ary[$otl];}return 0;
}

function setobjlikestatus($otl, $ns){if (!window.prv_liked_ary){window.prv_liked_ary={};} window.prv_liked_ary[$otl]=$ns;}

function verifyfblike(){var $user_id=verifyfblike.params['user_id'];var $otl=verifyfblike.params['otl'];
scn=verifyfblike.params['scn'];sp=verifyfblike.params['sp'];ecn=verifyfblike.params['ecn'];ep=verifyfblike.params['ep'];

FB.api({method: 'fql.query',query: "SELECT uid FROM page_fan WHERE uid='"+$user_id+"' and page_id='"+$otl+"'"},
	function(fql_resp){if (fql_resp.length == 1 && fql_resp[0].uid){setobjliked($otl);
		 if(scn){sc=window[scn];if(sc){sp['resp']={'otl': $otl};sp['resp'].post_id=$otl;sc(sp);}}
		}else{if(ecn){ec=window[ecn];if(ec){ec(ep);}}}
	});
}

function lvidtgdc($p){$otl=$p['params'][0];$fourl=$p['params'][1];$sotl=$p['params'][2];$like_uri="/" + $otl + "/likes";
if(!$otl){cmn_s_m_f_r_f(0,'Please pick a video to like',0,0);return;}
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];npcn=$p['no_perm_call_name'];npp=$p['no_perm_param'];
alcn=$p['al_call_name'];alcp=$p['al_call_param'];nlcn=$p['nl_call_name'];nlcp=$p['nl_call_param'];
$user_id=0;
FB.api('/me', function(me_resp){if(!me_resp||me_resp.error||!me_resp.id){if(nlcn){nlc=window[nlcn];if(nlc){nlc(nlcp);}}} 
else{$user_id = me_resp.id;FB.api('/me/permissions', function(perms_resp){cb_clk();
	if (perms_resp && perms_resp.data && perms_resp.data.length){var perms = perms_resp.data.shift();
		if(((typeof perms.publish_actions != 'undefined') && perms.publish_actions)&& ((typeof perms.user_likes != 'undefined') && perms.user_likes)){
			if(1==getobjlikestatus($otl)||3==getobjlikestatus($otl)){if(alcn){alc = window[alcn];if (alc){alc(alcp);}}}
			else{FB.api({method: 'fql.query',query: "SELECT user_id FROM like WHERE user_id='"+$user_id+"' and object_id='"+$otl+"'"}, 
				function(fql_resp){if(fql_resp.length==1 && fql_resp[0].user_id){
					if (0==getobjlikestatus($otl)){ setobjlikestatus($otl,1);}
					if (0==getobjlikestatus($otl)||1==getobjlikestatus($otl)||3==getobjlikestatus($otl)){if(alcn){alc=window[alcn];if(alc){alc(alcp);}}}
					else if (2==getobjlikestatus($otl)){setobjlikestatus($otl,3);if(scn){sc=window[scn];if(sc){sc(sp);}}}
					}else{setobjlikestatus($otl, 2);show_success_message("<a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Like facebook video</a>. After you like the video, <a style='color:white;cursor:pointer;' onclick=\"call_vidltgdc(1,1);\">verify like and unlock coupon</a>")} 
				});}}else{npc=window[npcn];if(npc){npc(npp);}}
		}else{npc=window[npcn];if(npc){npc(npp);}}
});} });	
}

function lpagetgdc($p){$otl=$p['params'][0];$fourl=$p['params'][1];$sotl=$p['params'][2];$like_uri="/" + $otl + "/likes";
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];npcn=$p['no_perm_call_name'];npp=$p['no_perm_param'];
alcn=$p['al_call_name'];alcp=$p['al_call_param'];nlcn=$p['nl_call_name'];nlcp=$p['nl_call_param'];$user_id=0;
	
FB.api('/me',function(me_resp){if (!me_resp || me_resp.error || !me_resp.id){if (nlcn){nlc=window[nlcn];if(nlc){nlc(nlcp);}}}
else{$user_id=me_resp.id; FB.api('/me/permissions',function(perms_resp){cb_clk();
	if(perms_resp && perms_resp.data && perms_resp.data.length){var perms = perms_resp.data.shift();
		if(((typeof perms.publish_actions != 'undefined') && perms.publish_actions)&& ((typeof perms.user_likes != 'undefined') && perms.user_likes))
		{if (1==getobjlikestatus($otl) || 3==getobjlikestatus($otl)){if(alcn){alc=window[alcn];if(alc){alc(alcp);}}}
		 else{FB.api({method: 'fql.query',query: "SELECT uid FROM page_fan WHERE uid='"+$user_id+"' and page_id='"+$otl+"'"},
		  function(fql_resp){if(fql_resp.length==1 && fql_resp[0].uid){if(0==getobjlikestatus($otl)){setobjlikestatus($otl,1);}
			if(0==getobjlikestatus($otl)||1==getobjlikestatus($otl)||3==getobjlikestatus($otl)){if(alcn){alc=window[alcn];if(alc){alc(alcp);}}} 
			else if (2 == getobjlikestatus($otl)){setobjlikestatus($otl, 3);if(scn){sc=window[scn];if(sc){sc(sp);}}}
			}else{setobjlikestatus($otl, 2);show_success_message("<a target='_blank' href=\""+ $fourl +"\" style='color:white; cursor:pointer;'>Like facebook page</a>. After you like the page, <a style='color:white;cursor:pointer;' onclick=\"call_pageltgdc(1,1);\">verify like and unlock coupon</a>")} 
		});	}}else{npc=window[npcn];if(npc){npc(npp);}}}else{npc=window[npcn];if(npc){npc(npp);}}
 });}});
	
}

function lpictgdc($p){$otl=$p['params'][0];$sotl=$p['params'][1];$like_uri="/"+$otl+"/likes";
if(!$otl){cmn_s_m_f_r_f(0,'Please pick an image to like',0,0);return;}
scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];
npcn=$p['no_perm_call_name'];npp=$p['no_perm_param'];alcn=$p['al_call_name'];alcp=$p['al_call_param'];
nlcn=$p['nl_call_name'];nlcp=$p['nl_call_param'];$user_id=0;
	
FB.api('/me', function(me_resp){if (!me_resp || me_resp.error || !me_resp.id){if (nlcn){nlc=window[nlcn];if(nlc){nlc(nlcp);}}}
else{$user_id = me_resp.id;FB.api('/me/permissions', function(perms_resp){cb_clk();
	if(perms_resp && perms_resp.data && perms_resp.data.length){var perms = perms_resp.data.shift();
		if(((typeof perms.publish_actions != 'undefined') && perms.publish_actions)&& ((typeof perms.user_likes != 'undefined') && perms.user_likes))
		{FB.api({method: 'fql.query',query: "SELECT user_id FROM like WHERE user_id='"+$user_id+"' and object_id='"+$otl+"'"}, 
			function(fql_resp){if(fql_resp.length==1 && fql_resp[0].user_id){alc=window[alcn];if(alc){alc(alcp);}}
				else{FB.api($like_uri, 'post', function(like_resp){
					if (!like_resp||like_resp.error){ec=window[ecn];ec(ep);}else{sc=window[scn];sc(sp);}}); 
				}
			});	}else{npc=window[npcn];if(npc){npc(npp);}}}else{npc=window[npcn];if(npc){npc(npp);}}
});}
});
	
}