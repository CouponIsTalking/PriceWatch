function handle_tweet_error($this_params){
$data_returned = $this_params['data_returned'];
$next_step = $data_returned['next_step'];
$msg = $data_returned['msg'];

$orig_call_name = $this_params['orig_call_name'];
$orig_call_param = $this_params['orig_call_param'];

if ('user_login'==$next_step){cb_clk();show_user_register_login_form();}
else if ('twitter_login'==$next_step){
 var tlp=[];tlp['post_login_params']=[];
 tlp['post_login_params']['scn']=$orig_call_name;
 tlp['post_login_params']['sp']=$orig_call_param;
 tlp['post_login_params']['ecn']='s_s_m';
 tlp['post_login_params']['ep']="There was an error in communicating with twitter. Please try later.";
 twitter_login(tlp);	
}
else if ('nothing_to_share'==$next_step){ s_s_m($msg);}
else if ('user_limit_reached_msg'==$next_step){ s_s_m($msg);}
	
}

function initiate_tweet_to_get_coupon($p){
	
$title = $p['params'][0];
$desc = $p['params'][1];
$image_link = $p['params'][2];
$news_title = $p['params'][3];
$news_link = $p['params'][4];
$tw_is_custom = $p['params'][5];
$tw_content_id = $p['params'][6];
$tw_oc_id = $p['params'][7];

scn = $p['scn'];
sp = $p['sp'];
ecn = $p['ecn'];
ep = $p['ep'];

s_s_m("Just a sec, processing ... ");
	
$.ajax({type:"POST",url: $S_N+"user_actions/tweetit_for_coupon/",
data:{tw_is_custom: $tw_is_custom, tw_content_id: $tw_content_id, tw_oc_id: $tw_oc_id, tw_title: $title, tw_news_title: $news_title, tw_news_link: $news_link, tw_image_link: $image_link, tw_desc: $desc }, 
success : function(data) {cb_clk();	var $d = IsJsonString(data);
 if ($d){if (1 == $d.success){m_n_c(window[scn], sp);}else{ep['data_returned'] = $d;m_n_c(window[ecn], ep);}}
		else{s_s_m("An unknown error occured while contacting twitter. Please try again.", 0, 0);}
	},
error : function(data){s_s_m("An unknown error occured while contacting twitter. Please try again.", 0, 0);} 
});
		
}

function call_tweet_to_get_coupon($this){
var $s_d = $this.closest(".h_100_tile").find("share_item_details");
$title = $s_d.find("share_title").text();
$desc = $s_d.find("share_desc").text();
$image_link = $s_d.find("share_image").text();
$news_title = $title;
$news_link = $image_link;
$content_id = $this.closest('.h_100_tile').find('#user_actionsTwContentId').val();

var $p = [];
$p["params"] = [$title, $desc, $image_link, $news_title, $news_link, 0, $content_id, 0];
$p['scn'] = "show_coupon";$p['sp'] = [];$p['sp']["obj"] = [$this];
$p['ecn'] = "handle_tweet_error";$p['ep'] = [];
$p['ep']["orig_call_name"] = 'call_tweet_to_get_coupon';$p['ep']["orig_call_param"] = $this;

initiate_tweet_to_get_coupon($p);
}

function call_tweet_to_get_dynamic_coupon($this){	
var $s_d = $this.closest(".brand_promote");
$image_link = $s_d.find("div.adv_image_item_drop").find('img').prop('src');
$title = $s_d.find("div.adv_news_item_drop").find('.adv_title_customize').text();
$news_title = $s_d.find("div.adv_news_item_drop").find('.news_title').text();
$news_link = $s_d.find("div.adv_news_item_drop").find('.news_link').text();
$desc = $s_d.find("div.adv_news_item_drop").find('.news_desc').text();
$oc_id = $s_d.find("#user_actionsTwOcId").val();

var $p = [];
$p["params"] = [$title, $desc, $image_link, $news_title, $news_link, 1, 0, $oc_id];
$p['scn'] = "show_dynamic_coupon";$p['sp'] = [];$p['sp']["obj"] = [$this];
$p['ecn'] = "handle_tweet_error";$p['ep'] = [];
$p['ep']["orig_call_name"] = 'call_tweet_to_get_dynamic_coupon';
$p['ep']["orig_call_param"] = $this;

initiate_tweet_to_get_coupon($p);
}