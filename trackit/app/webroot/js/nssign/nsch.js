//common handles

function call_enss_for_coupon($this, $uli, $n){
if (!$uli){ask_logintp();return;}

$e = 0;$fe = $('._first_email').val().trim();
if (!validateEmail($fe)){s_s_m('Please enter your email for newsletter signup.'); return;}
$se = 0; if(2==$n){$se = $('._second_email').val().trim();
	if (!validateEmail($se)){s_s_m('Please enter your second email for newsletter signup.'); return;}
	if ($fe.trim() == $se.trim()){s_s_m('Please enter different email addresses for newsletter signup.'); return;}
}

var $sd = $(".brand_promote");$this = $sd.find('div._unlockbtn');$ocid = $sd.find('.hidden_oc_id').text().trim();
$promo_method = $sd.find('._hidden_octype').text().trim();

var prm = [];prm['emails']=[$fe, $se];prm["objid"]=$ocid;prm["objtype"]='oc';prm['promo_method']=$promo_method;
if (1==$n){prm["promo_method"]='single_email_ns_signup';}else if (2==$n){prm["promo_method"]='dual_email_ns_signup';}
prm["scn"]="show_dynamic_coupon";prm["sp"]=[];prm["sp"]["obj"]=[$this];prm["ecn"]=0;prm["ep"]=0;

var $p={};$p['e']=$fe;
if (2==$n){$p['scn']='uhe';$p['sp'] = {};
 $p['sp']['e']=$se;$p['sp']['scn']='snuc';$p['sp']['sp']=prm;$p['sp']['ecn']=0;$p['sp']['ep']=0;
 $p['ecn']=0;$p['ep'] = 0;
}else if (1==$n){$p['scn']='snuc';$p['sp']=prm;$p['ecn']=0;$p['ep']=0;}

uhe($p);
cmn_s_m_f_r_f(0, "Processing... just a sec...", 0, 0);
}

function snuc($p){ $emails = $p['emails'];$objid = $p['objid'];$objtype = $p['objtype'];$promo_method = $p['promo_method'];
scn = $p["scn"];sp = $p["sp"];ecn = $p["ecn"];ep = $p["ep"];

$.ajax({type:"POST",url: $S_N+"user_actions/ns_signup_for_coupon/",
	data:{email1: $emails[0], email2: $emails[1], objid: $objid, objtype: $objtype, promo_method:$promo_method}, 
	success : function(data){ cb_clk(); if (IsJsonString(data)){ data=IsJsonString(data);
			if (data && 1 == data['success']){if(scn){sc = window[scn];if(sc){sc(sp);}}}
			else{if(ecn){ec = window[ecn];if(ec){ ec(ep);}}else{ s_s_m(data['msg']); }}
		} else{s_s_m("An unknown error occured while signing you up. Please try again.", 0, 0);}
	},error : function(data) {s_s_m("An unknown error occured when we were signing you to newsletter. Please try again.", 0, 0);} 
});
	
}