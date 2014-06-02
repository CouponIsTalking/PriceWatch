function show_successful_login_msg_with_page_refresh(m){if(!m||""==m){pageRefresh();}else{$('#fade').click();s_s_m(m, pageRefresh, 0);}}

function show_user_register_form(){
var $html = "<div class='users' style='height:auto;'>"
+"<span style='color:white;font-size:10px;'>By registering or logging in, you agree to the <a style='color:white;font-style:underline;cursor:pointer;' target='blank' href='"+$S_N+"pure/terms_and_conditions.html'>terms of use.</a></span><br/>"
+"<green_button style='float:left;' type='submit' onclick=\"$('div.user_register_form').find('close_button').click(); show_user_login_form();\" value='Login'>Login</green_button>"
+"<purple_button style='float:right;' type='submit' onclick=\"hide_and_show('.register_form', '.resend_email_confirm_link_form');\" value='Resend Confirmation Link'>Resend Email ConfirmationLink</purple_button>"
+"<div style='clear:both;'></div>"
+"<br/>"
+"<div class='resend_email_confirm_link_form' style='display:none'>"
	+"<div class='input text required'>"
	+ "<label for='UserResendEmailConfirmUsername'>Enter Email</label>"
	+ "<input name='data[User][username]' maxlength='40' type='text' id='UserResendEmailConfirmUsername' required='required'/>"
	+"</div>"
	+"<br/>"
	+"<br/>"
	+"<div class='submit'>"
	+ "<input style='cursor:pointer;' type='submit' onclick='action_resend_email_confirmation_link();' value='Resend Email Confirmation'/>"
	+ "<purple_button style='float:right;' type='submit' onclick=\"hide_and_show('.resend_email_confirm_link_form', '.register_form');\" value='Register'>Register</purple_button>"
	+"</div>"
+"</div>"
+"<br/>"
+"<div style='clear:both'></div>"
+"<div class='register_form'>"
	+"<div class='submit'>"
	+ "<input style='cursor:pointer;' type='submit' onclick='action_fb_login();' value='Register Using Facebook'/>"
	+"</div>"
	+"<div><label>--- OR ---</label></div>"
	+"<div class='input text required'>"
	+ "<label for='UserRegUsername'>Email</label>"
	+ "<input name='data[User][username]' maxlength='40' type='text' id='UserRegUsername' required='required'/>"
	+"</div>"
	+"<div class='input password required'>"
	+ "<label for='UserRegPassword'>Password</label>"
	+ "<input name='data[User][password]' type='password' id='UserRegPassword' required='required'/>"
	+"</div>"
	+"<div class='input password'>"
	+ "<label for='UserRegPassword2'>Re-enter Password</label>"
	+ "<input name='data[User][password2]' type='password' id='UserRegPassword2' required='required'/>"
	+"</div>"
	+"<br/>"
	+"<br/>"
	+"<div class='submit'>"
	+ "<input style='cursor:pointer;' type='submit' onclick='action_user_register();' value='Register'/>"
	+"</div>"
+"</div>"
+"<div class='error_msg1'></div>"
+"<div class='info_msg1'></div>"
+"<div class='success_msg1'></div>"
+"</div>";

append_html_fd_cb("div.user_register_form", $html);

}

function update_fb_user_info(){}

function action_fb_login(){
show_message_in_div('.users .success_msg1', "Logging you in with facebook, just a sec..", 0, 0);
var $p = [];$p['params'] = []; $p['params']["permissions"] = 'email, read_stream, read_friendlists, publish_stream';
$p['params']['get_all_perms'] = true;
$p['scn'] = "show_successful_login_msg_with_page_refresh"; $p['sp'] = "";
$p['ecn'] = "fb_show_error_msg"; $p['ep'] = "We couldn't log you in. This may happen if you did give us proper permissions. In case you had hesitation providing permissions, please be informed that we do not access and use any of your facebook data at all for purposes other than login.  <a style='color:white; cursor:pointer;' onclick='cb_clk(); action_fb_login();'>Grant This Permission</a>;";
fb_login_cb_arg($p);
}

function action_user_register(){
var un = $("div.user_register_form input[id=UserRegUsername]").val();
var pwd = $("div.user_register_form input[id=UserRegPassword]").val();
var pwd2 = $("div.user_register_form input[id=UserRegPassword2]").val();
var role = $("div.user_register_form select[id=UserRole]").val();

show_message_in_div('.users .success_msg1', "Thanks! just a sec, registering you...", 0, 0);

$.ajax({url: $S_N+"users/add_ajax/",type:"POST",data:{data_username:un, data_password:pwd, data_password2:pwd2, data_role:role,nl:window.location.href}, 
 success : function(data) {update_user_register_result(data);},
 error : function(data) {update_user_register_result(0);}
});
	
}

function update_user_register_result(data){
var $d = IsJsonString(data);
if ($d && $d['errors']>0){show_message_in_div('.users .success_msg1', $d['msg'], 0, 0);}
else{ f_h = $("div.user_register_form");f_h.empty();f_h.hide(); s_s_m($d['msg'], 0, 0); }
}

function hide_and_show(h_slctr, s_slctr){var $h_ele = $(h_slctr);var $s_ele = $(s_slctr);
if ('none'!=$h_ele.css('display')){$h_ele.css('display', 'none');$s_ele.css('opacity','0').css('display', 'block').fadeTo(500, 0.95);} 
}

function show_user_login_form($biz){
cb_clk();
var $html = "<div class='users' style='height:auto;'>"
+"<span style='color:white;font-size:10px;'>By registering or logging in, you agree to the <a style='color:white;font-style:underline;cursor:pointer;' target='blank' href='"+$S_N+"pure/terms_and_conditions.html'>terms of use.</a></span><br/>";
if(undefined==$biz){
$html=$html+"<green_button style='float:left;' type='submit' onclick=\"$('div.user_login_form').find('close_button').click(); show_user_register_form();\" value='Register'>Register</green_button>"
}
$html=$html+"<purple_button style='float:right;' type='submit' onclick=\"hide_and_show('.login_form', '.reset_pass_form');\" value='Reset Password'>Forgot Password</purple_button>"
+"<div style='clear:both;'></div>"
+"<br/>"
+"<div class='reset_pass_form' style='display:none'>"
	+"<div class='input text required'>"
	+	"<label for='UserResetPasswdUsername'>Enter Email</label>"
	+	"<input name='data[User][username]' maxlength='40' type='text' id='UserResetPasswdUsername' required='required'/>"
	+"</div>"
	+"<br/>"
	+"<br/>"
	+"<div class='submit'>"
	+	"<input style='cursor:pointer;' type='submit' onclick='action_reset_pass();' value='Reset Password'/>"
	+	"<purple_button style='float:right;' type='submit' onclick=\"hide_and_show('.reset_pass_form', '.login_form');\" value='Login'>Login</purple_button>"
	+"</div>"
+"</div>"
+"<div class='login_form'>";
if(undefined==$biz){
	$html=$html+"<div class='submit'>"
	+	"<input style='cursor:pointer;' type='submit' onclick='action_fb_login();' value='Login Using Facebook'/>"
	+"</div>"
	+"<div><label>--- OR ---</label></div>";
}
$html=$html+"<div class='input text required'>"
	+	"<label for='UserLoginUsername'>Email</label>"
	+	"<input name='data[User][username]' maxlength='40' type='text' id='UserLoginUsername' required='required'/>"
	+"</div>"
	+"<div class='input password required'>"
	+	"<label for='UserLoginPassword'>Password</label>"
	+	"<input name='data[User][password]' type='password' id='UserLoginPassword' required='required'/>"
	+"</div>"
	+"<br/>"
	+"<br/>"
	+"<div class='submit'>"
	+	"<input style='cursor:pointer;' type='submit' onclick='action_user_login();' value='Login'/>"
	+"</div>";
if(undefined!=$biz&&1==$biz){
$html=$html+"<br/><span style='font-family:Arial;font-size:12px;color:white;'>If you would like to register your business with us instead, <span style='cursor:pointer;border-bottom:2px dotted white;' onclick=\"moveTo('"+$S_N+"companies/add');\">please follow here</span>.</span><br/>"
}
$html=$html+"</div>"
+"<div class='error_msg1'></div>"
+"<div class='info_msg1'></div>"
+"<div class='success_msg1'></div>"
+"</div>";

append_html_fd_cb("div.user_login_form", $html);

}

function append_html_fd_cb($fh_slctr, $html){
$fh = $($fh_slctr);form = $($html);$fh.append(form);
$cb = $("<close_button>X</close_button>");
$fh.append($cb);$fh.show();$fh.fadeTo(500, 0.95);$fh.css("z-index", 10);

$fd = $('#fade');$fd.show();
$cb.on('click', function(){$fh.empty();$fh.hide();$fd.hide();});
if(!is_mobile()){$fd.on('click', function(){$cb.trigger('click')});}
$fd.css("z-index", 9);
cnvrt_pval_f_to_a($fh_slctr);
}

function action_tw_email_reg(){
var $email = $.trim($("div.users input[id=UserUsername]").val());
if ($email && $email == "" && validateEmail($email)){	
$.ajax({type:"POST",data:{ data_username:$email }, url: $S_N+"users/post_tw_login_step/",
success : function(data) {update_user_login_result(data);},	error : function(data) {update_user_login_result(0);}
});
}
else{ var $d = [];$d['errors'] = 1;$d['msg'] = "Please provide a valid email.";update_user_login_result($d);}

}

function action_resend_email_confirmation_link(){
var username = $("div.resend_email_confirm_link_form input[id=UserResendEmailConfirmUsername]").val();
if ("" == username.trim()){show_message_in_div('.users .success_msg1', "Please enter you email", 0, 0);return;}
show_message_in_div('.users .success_msg1', 'Please wait ... just a sec ... ', 0, 0);
	
	$.ajax({type:"POST",url: $S_N+"users/resend_confirmation_link/",data:{email:username,nl:window.location.href}, 
		success : function(data) {data = $.parseJSON(data);cb_clk();
			if (0==data['success']){show_message_in_div('.users .success_msg1', data['msg'], 0, 0);}
			else{$("div.user_login_form").empty().hide();s_s_m(data['msg']);r_i_c('div.success_msg');}
			},error : function(data) {
			   show_message_in_div('.users .success_msg1', data['msg'], 0, 0);
			}
		});
	
	return;
}

function action_reset_pass(){
var un = $("div.reset_pass_form input[id=UserResetPasswdUsername]").val();
if ("" == un.trim()){show_message_in_div('.users .success_msg1', "Please enter you email", 0, 0);return;}
$.ajax({type:"POST",data:{ email:un}, url: $S_N+"reset_passwds/get_reset_link/",
success : function(data) {var $d = IsJsonString(data);
if (0 == $d['success']){show_message_in_div('.users .success_msg1', $d['msg'], 0, 0);}
else {$("div.user_login_form").empty().hide();s_s_m($d.msg);r_i_c('div.success_msg');}
},
error : function(data){s_e_m("There was an unknown error in processing your request. If this problem persists, then drop us an email.", 0, 0);} 
});

}

function action_user_login(){
var un = $("div.user_login_form input[id=UserLoginUsername]").val();
var pwd = $("div.user_login_form input[id=UserLoginPassword]").val();

if(un&&!validateEmail(un)){show_message_in_div('.users .success_msg1','Please enter your login email',0,0); return;}
else if(!pwd ||(""==pwd.trim())){show_message_in_div('.users .success_msg1','Please enter your password',0,0); return;}
else{show_message_in_div('.users .success_msg1', "Thanks! just a sec, logging you in...", 0, 0);}
$.ajax({type:"POST",data:{ data_username:un, data_password:pwd},url: $S_N+"users/login_ajax/",
 success : function(data) {update_user_login_result(data);}, error : function(data) {update_user_login_result(0);}
 });
}

function update_user_login_result(data){
var $d = IsJsonString(data);
if ($d && $d['errors']>0){show_message_in_div('.users .success_msg1', $d['msg'], 0, 0);}
else{var f_h = $("div.user_login_form");f_h.empty();f_h.hide();
	if (window.location.toString().indexOf('reset_passwds') > 0){s_s_m($d['msg'], moveToHomePage, 0);}
	else{s_s_m($d['msg'], pageRefresh, 0);}
 }	
}

function logout_user(){
$.ajax({type:"POST",data:{},url: $S_N+"users/logout_ajax/",success:function(data){moveToHomePage();},
 error : function(data) {location.reload();}});
}


function show_user_register_login_form($biz){show_ulf($biz);}
function show_ulf($biz){if(in_iframe()){OpenInNewTab($S_N+"join/us");return;}
show_user_login_form($biz);}

function show_reddit_login_form(myparams){window.show_reddit_login_form.temp = myparams;
var $html = "<div class='users'>"
+"<div class='input text required'>"
+	"<label for='RedditUsername'>Reddit Username</label>"
+	"<input name='data[User][username]' maxlength='40' type='text' id='RedditUsername' required='required'/>"
+"</div>"
+"<div class='input password required'>"
+	"<label for='RedditPassword'>Reddit Password</label>"
+	"<input name='data[User][password]' type='password' id='RedditPassword' required='required'/>"
+"</div>"
+"<br/>"
+"<br/>"
+"<div class='submit'>"
+	"<input type='submit' onclick=\"action_reddit_login(window.show_reddit_login_form.temp);\" value='Login'/>"
+"</div>"
+"<div class='error_msg1'></div>"
+"<div class='info_msg1'></div>"
+"<div class='success_msg1'></div>"
+"</div>";

append_html_fd_cb("div.user_login_form", $html);
}

function post_tw_login_popup_close(successful_login){
params = post_tw_login_popup_close.params;
params_valid = post_tw_login_popup_close.params_valid;

if (params_valid){
 if (successful_login){s_s_m("Twitter login successful, now processing request ...");
  scn = params['scn'];sp = params['sp'];m_n_c(window[scn], sp);
 }
else{ecn = params['ecn'];ep = params['ep'];m_n_c(window[ecn], ep); } 
}
post_tw_login_popup_close.params_valid = false;
}

function twitter_login(params){	
post_tw_login_popup_close = window['post_tw_login_popup_close'];
post_tw_login_popup_close.params_valid = true;
post_tw_login_popup_close.params = params['post_login_params'];
s_s_m("Just a sec, contacting twitter ... ");
// call twitter login
$.ajax({type:"POST",data:{}, url: $S_N+"twitters/init_tw_login/",
 success : function(data){ var $d = IsJsonString(data);
  if ($d){ if (1==$d['success']){var $url = $d['pop_up_url'];var $opened = OpenInPopUpWindow("", $url);
    if ($opened){s_s_m("We need your permissions to do twitter related things. Rest assured, we will always ask you before doing any twitter activity. We neither spam nor sale any of your private data. Thank you!");}
	else{s_s_m("<div>Please unblock pop-up in your browser.</div>");}
   }
  else{s_s_m($d.msg, 0, 0);}
  }
  else{s_s_m("An unknown error occured while contacting twitter. Please try again.", 0, 0);}
 },
 error : function(data) {s_s_m("An unknown error occured while contacting twitter. Please try again.", 0, 0);}
});
// if url params returned for next call
//     open pop up
	
}
function h_lgn_f(){$("div.user_register_form").hide();$("div.user_login_form").hide();}