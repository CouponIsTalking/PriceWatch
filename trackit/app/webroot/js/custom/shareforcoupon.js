function call_tw_tweet_to_get_coupon($this, $is_user_logged_in){
var $s_d = $this.closest(".h_100_tile").find("share_item_details");
var $title = $s_d.find("share_title").text();var $desc = $s_d.find("share_desc").text();
var $image_link = $s_d.find("share_image").text();var $news_title = ''; var $news_desc = '';
var $news_link = '';var $is_custom = '';
var $call_tw_login_if_needed = true;
	
var $p = [];
$p["params"] = [$title, $desc, $image_link, $news_title,  $call_tw_login_if_needed, $news_desc, $news_link, $is_custom];
$p['scn'] = "show_coupon";
$p['sp'] = [];$p['sp']["obj"] = [$this];
$p['ecn'] = "fb_show_error_msg";$p['ep'] = "Please tweet to help spread the word and get you the coupon.";
if ($is_user_logged_in){call_tweet_to_get_coupon($this);}else{show_user_register_login_form();}

}

function initiate_tw_share_for_dynamic_coupon($this, $is_user_logged_in){
 if (!$is_user_logged_in){show_user_register_login_form();}else{call_tweet_to_get_dynamic_coupon($this);}
}

function initiate_saveit_share_for_coupon($this, $url_to_save, $is_user_logged_in){
var $s_d = $this.closest(".h_100_tile").find("share_item_details");
$title = $s_d.find("share_title").text();$desc = $s_d.find("share_desc").text();$img_lnk = $s_d.find("share_image").text();
	
var $p = [];$p["params"] = [$title, $desc, $img_lnk, $url_to_save];$p['scn'] = "show_coupon";
$p['sp'] = [];$p['sp']["obj"] = [$this];
$p['ecn'] = "fb_show_error_msg";$p['ep'] = "SaveIt and get the coupon.";
if ($is_user_logged_in){initiate_save_it($p);}else{show_user_register_login_form();}
}


function initiate_save_it($p){$("#loading_image").css('display', 'block');
$url_to_call = $S_N+"products/saveit_for_coupon";
	
$title = $p['params'][0];$desc = $p['params'][1];$image_link = $p['params'][2]; $url_to_save = $p['params'][3];
scn = $p['scn'];sp = $p['sp'];ecn = $p['ecn'];ep = $p['ep'];
	
$.ajax({type:"POST",data:{purl: $url_to_save}, url: $url_to_call,
 success : function(data){$("#loading_image").css('display', 'none');var $d = IsJsonString(data);
  if ($d && $d['success']){m_n_c(window[scn], sp);}else{m_n_c(window[ecn], ep);}},
 error : function(data){$("#loading_image").css('display', 'none');m_n_c(window[ecn], ep);}
});
}

function save_it($url_to_save){}