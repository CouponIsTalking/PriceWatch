// common handles
// like page to get dynamic coupon

function call_vidptgdc($clk_e,$uli){if(!$uli){ask_logintp();return;}
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$fourl=$sd.find('div._postobjurl').text().trim();$fot=$sd.find('div._postobjthumbnail').text().trim();$ocid=$sd.find('.hidden_oc_id').text().trim();
$p=[];$p["params"]=[$fourl, $fot];$p['scn']="fb_resp_update_and_show_coupon";$p['sp']=[];
$p['sp']['share_info']=['', $fot, $fourl, '', $fourl];$p['sp']["objid"]=$ocid;$p['sp']["objtype"]='oc';
$p['sp']["promo_method"]='fb_post_video';
$p['sp']['scn']="show_dynamic_coupon";$p['sp']['sp']=[];$p['sp']['sp']["obj"]=[$this];$p['ecn']=0;$p['ep']=0;
$p["nl_call_name"]="ask_addfb";$p["nl_call_param"]=0;
var $promo_chk_p=[];$promo_chk_p['type']='fb';$promo_chk_p['scn']="vidptgdc";$promo_chk_p['sp']=$p;$promo_chk_p['ecn']='default';$promo_chk_p['ep']=0;
cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
is_fb_promo_allowed($promo_chk_p);
//initiate_fb_post_to_get_coupon(params);
}

function call_vidltgdc($clk_e,$uli){if(!$uli){ask_logintp();return;}
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$foi=$sd.find("div._likeobjid").text().trim();$fourl=$sd.find('div._likeobjurl').text().trim();
if(!$foi){cmn_s_m_f_r_f(0,'Please pick a video to like',0,0);return;}
$ocid=$sd.find('.hidden_oc_id').text().trim();
$p=[];$p["params"]=[$foi, $fourl, 0];$p['scn']="fb_resp_update_and_show_coupon";$p['sp']=[];
$p['sp']['share_info']=[$foi, '', $fourl, '', ''];$p['sp']["objid"]=$ocid;$p['sp']["objtype"]='oc';
$p['sp']["promo_method"]='fb_like_video';$p['sp']["resp"]={'otl' : $foi, 'live_link': $fourl};
$p['sp']['scn']="show_dynamic_coupon";$p['sp']['sp']=[];$p['sp']['sp']["obj"]=[$this];
$p['ecn']="fb_show_error_msg";$p['ep']="Sorry, we couldn't make fb like. This could happen, 1. you haven't provided all permissions, 2. the picture privacy setting is less than public.";
$p["nl_call_name"]="ask_addfb";$p["nl_call_param"]=0;
$p["no_perm_call_name"]="fb_show_error_msg";$p["no_perm_param"]="To build trust with our users, we ask for extra Facebook permissions only when we need it. Right now, we need permission from you to access your likes. Please <a style='color:white; cursor:pointer;' onclick=\"cb_clk(); get_extra_perms('email, read_stream, publish_actions, user_likes');\">give these permissions</a> here. Rest assured, this requested permission does not allow us to post anything.";
$p['al_call_name']='show_success_message';$p['al_call_param']='Ohh. You already like this video. :(';
var $promo_chk_p=[];$promo_chk_p['type']='fb';$promo_chk_p['scn']="lvidtgdc";$promo_chk_p['sp']=$p;
$promo_chk_p['ecn']='default';$promo_chk_p['ep']=0;
cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
is_fb_promo_allowed($promo_chk_p);
//initiate_fb_post_to_get_coupon(params);	
}

function call_pageltgdc($clk_e,$uli){if(!$uli){ask_logintp();return;}
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$foi=$sd.find("div._likeobjid").text().trim();$fourl=$sd.find('div._likeobjurl').text().trim();
$ocid=$sd.find('.hidden_oc_id').text().trim();
$p=[];$p["params"]=[$foi, $fourl, 0];
$p['scn']="fb_resp_update_and_show_coupon";$p['sp']=[];$p['sp']['share_info']=[$foi, '', $fourl, '', ''];$p['sp']["objid"]=$ocid;
$p['sp']["objtype"]='oc';$p['sp']["promo_method"]='fb_like_page';$p['sp']["resp"]={'otl' : $foi, 'live_link': $fourl};
$p['sp']['scn']="show_dynamic_coupon";$p['sp']['sp']=[];$p['sp']['sp']["obj"]=[$this];
$p['ecn']="fb_show_error_msg";$p['ep']="Sorry, we couldn't make fb like. This could happen, 1. you haven't provided all permissions, 2. the picture privacy setting is less than public.";
$p["nl_call_name"]="ask_addfb";$p["nl_call_param"]=0;
$p["no_perm_call_name"]="fb_show_error_msg";$p["no_perm_param"]="To build trust with our users, we ask for extra Facebook permissions only when we need it. Right now, we need permission from you to access your likes. Please <a style='color:white; cursor:pointer;' onclick=\"cb_clk(); get_extra_perms('email, read_stream, publish_actions, user_likes');\">give these permissions</a> here. Rest assured, this requested permission does not allow us to post anything.";
$p['al_call_name']='show_success_message';$p['al_call_param']='Ohh. You already like this page. :(';
var $promo_chk_p=[];$promo_chk_p['type']='fb';$promo_chk_p['scn']="lpagetgdc";$promo_chk_p['sp']=$p;
$promo_chk_p['ecn']='default';$promo_chk_p['ep']=0;
cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
is_fb_promo_allowed($promo_chk_p);
//initiate_fb_post_to_get_coupon(params);
}

function call_picltgdc($this,$uli){if(!$uli){ask_logintp();return;}
var $sd=$this.closest(".brand_promote");
$image_link=$sd.find("div.adv_image_item_drop").find('img').prop('src');
$foi=$sd.find("div.adv_foinfo").find("div._likeobjid").text().trim();
if(!$foi){cmn_s_m_f_r_f(0,'Please pick an image to like',0,0);return;}
$fourl=$sd.find("div.adv_foinfo").find('div._likeobjurl').text().trim();
$ocid=$sd.find('.hidden_oc_id').text().trim();
if(!$image_link||""==$image_link.trim()){cmn_s_m_f_r_f(0, "<div style='width:200px;'>Please choose an image</div>", 0, 0);return;} 
$p=[];$p["params"]=[$foi, 0];$p['scn']="fb_resp_update_and_show_coupon";$p['sp']=[];
$p['sp']['share_info']=[$foi, $image_link, $fourl, '', ''];$p['sp']["objid"]=$ocid;$p['sp']["objtype"]='oc';
$p['sp']["promo_method"]='fb_like_pic';$p['sp']["resp"]={'otl' : $foi, 'live_link': $fourl};
$p['sp']['scn']="show_dynamic_coupon";$p['sp']['sp']=[];$p['sp']['sp']["obj"]=[$this];
$p['ecn']="fb_show_error_msg";$p['ep']="Sorry, we couldn't make fb like. This could happen, 1. you haven't provided all permissions, 2. the picture privacy setting is less than public.";
$p["nl_call_name"]="ask_addfb";$p["nl_call_param"]=0;
$p["no_perm_call_name"]="fb_show_error_msg";$p["no_perm_param"]="To build trust with our users, we ask for extra Facebook permissions only when we need it. Right now, we need permission from you to access your likes. Please <a style='color:white; cursor:pointer;' onclick=\"cb_clk(); get_extra_perms('email, read_stream, publish_actions, user_likes');\">give these permissions</a> here. Rest assured, this requested permission does not allow us to post anything.";
$p['al_call_name']='show_success_message';$p['al_call_param']='Ohh. You already like this photo. :(';
var $promo_chk_p=[];$promo_chk_p['type']='fb';$promo_chk_p['scn']="lpictgdc";$promo_chk_p['sp']=$p;
$promo_chk_p['ecn']='default';$promo_chk_p['ep']=0;
cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
is_fb_promo_allowed($promo_chk_p);
//initiate_fb_post_to_get_coupon(params);
}