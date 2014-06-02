function save_giveaway_coupon($uli){if(!$uli){show_ulf();return;}
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$ocid=$sd.find('.hidden_oc_id').text().trim();$p={};
$p['objtype']='oc';$p['objid']=$ocid;$p['promo_method']='giveaway';
$p['resp']=JSON.stringify('give-away');$p["pdiv"]=0;
$p['share_info']=['','','','','']; //[$title, $desc, $image_link, $news_title, $news_link];
$p['scn'] = 'change_giveaway_save_btn_txt'; $p['sp']="Saved and Emailed!";
call_post_promo($p, 'give-away','');
}
function change_giveaway_save_btn_txt($ntxt){
var $sd=$(".brand_promote");$this=$sd.find('div._unlockbtn');
$this.find('div').html($ntxt);
}