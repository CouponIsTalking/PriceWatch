function save_yr_coupon($uli){if(!$uli){show_ulf();return;}
var $yrl=$('textarea._yelp_review_link_textarea').val();if(undefined==$yrl||!$yrl||""==$yrl.trim()){cmn_s_m_f_r_f(0,"Please enter link to your review on yelp.");return;}
if($yrl.toLowerCase().substring("yelp.com") < 0){cmn_s_m_f_r_f(0,"Please enter link a valid review link on yelp.");return;}
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$ocid=$sd.find('.hidden_oc_id').text().trim();$p={};
$p['objtype']='oc';$p['objid']=$ocid;$p['promo_method']='yelp_review';
$p['resp']='';$p["pdiv"]=0;
$p['share_info']=['',$yrl,'','',$yrl]; //[$title, $desc, $image_link, $news_title, $news_link];
$p['scn'] = 'change_yr_save_btn_txt_and_show_msg'; $p['sp']="Sweet! You are in for the chance to win Coupon!";
call_post_promo($p, 'yelp_review',JSON.stringify({'permalink':$yrl}));
}
function change_yr_save_btn_txt_and_show_msg($ntxt){
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$this.find('div').html($ntxt);
s_s_m("Sweet! You are in for the chance to win Coupon!");
}