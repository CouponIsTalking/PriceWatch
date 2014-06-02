function aenssfc(){$('._unlockbtn').find('black_button').click();}

function densc(){var $p={};$p['e']=$e1;$p['scn']='uhe';$p['sp'] = {};
$p['sp']['e']=$e2;$p['sp']['scn']='aenssfc';$p['sp']['sp']=0;$p['sp']['ecn']=0;$p['sp']['ep']=0;
$p['ecn']=0;$p['ep'] = 0;uhe($p);
}

function uhe($p){$e = $p['e'];$scn = $p['scn'];$sp = $p['sp'];$ecn = $p['ecn'];$ep = $p['ep'];
cmn_s_m_f_r_f(0, "Please wait while we check your email. "+$e, 0, 0);
$.ajax({method: 'post',url: $S_N+"user_emails/user_has_email",data: {email:$e},
	success: function(data){data=IsJsonString(data); var m=""; var $d=1;if (data){
			if ('no-own' == data.status){m = "You don't have this email ("+$e+") confirmed.<green_button style='float:left;' type='submit' onclick=\"cmn_s_m_f_r_f(0,'Please wait while we confirm your email.',0,0); be_add_eemail($e);\" value='Register'>Confirm email</green_button>";}
			else if ('own-no-confirmed' == data.status){m = "Our records show that we sent you a confirmation email in the past, but it was not confirmed. <green_button style='float:left;' type='submit' onclick=\"cmn_s_m_f_r_f(0, 'Please wait while we resend confirmation email.',0,0); be_add_eemail($e);\" value='Register'>Resend Confirmation Email</green_button>";}
			else if ('own-confirmed' == data.status){ if($scn){$d=0;$sc = window[$scn]; if ($sc){if($sp){$sc($sp);} else{$sc();}}} }
			else {m = data.msg;}
		} else {m = 'An unknown error happened while reading your records.';}
		if($d){cmn_s_m_f_r_f(0, m);}
	},error: function(){cmn_s_m_f_r_f(0, 'Could not connect to the server.');},
});
}

function sadd_eemail($e){var fhtml = "";
if (!$e || !validateEmail($e)){fhtml = "<div class='add_ee' style='height:auto;'><span style='color:white;font-size:14px;'>Please enter an email.</span></div>";} 
else{ fhtml = "<div style='height:auto;'><span style='color:white;font-size:14px;'>Please confirm your email before we sign it up for newsletters.</span>"
	+"<br/><div class='add_ee' style='color:white;font-weight:bold;'>"+$e+"</div><br/><div class='add_ee_m' style='color:white;font-weight:bold;'></div><br/>"
	+"<green_button style='float:left;' type='submit' onclick=\"$(this).parent().find('div.add_ee_m').text('Please wait while we confirm your email.'); be_add_eemail($(this).parent().find('div.add_ee').text());\" value='Register'>Confirm email</green_button>"
	+"<br/></div>";}
cmn_s_m_f_r_f(0, fhtml, 0, 0);
}

function be_add_eemail($e){ $.ajax({method: 'post',url: $S_N+"user_emails/add_email",data: {e:$e,l:window.location.href},
	success: function(data){data=IsJsonString(data); var m="";if (data) {m = data.msg;} else {m = 'An unknown error happened while updating your records.';}
		cmn_s_m_f_r_f(0, m);
	},error: function(){cmn_s_m_f_r_f(0, 'Could not connect to the server.');},
});

}