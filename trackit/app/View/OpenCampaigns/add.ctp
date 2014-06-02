<?php 
echo $this->Html->script('custom/opencampaign'); 
echo $this->Html->script('custom/select_op'); 
echo $this->Html->script('custom/content_picker'); 
echo $this->Html->script('c_coup/codechk'); 
?>

<style type='text/css'>
.conditions_in_oc_add_form
{
left-margin:20%
}

* div._ccp_suggestion
{
margin: 0;
padding: 5px 5px 5px 5px;
width: 260px;
clear: both;
font-style: normal;
font-weight: normal;
font-family: sans-serif;
font-size: 14px;
background: #F3F2F2;
color: black;
text-align: justify;
}

* ._x_fbstatuses_for_update{
padding:10px;margin:10px;
box-shadow: 0 1px 3px 0 rgba(0,0,0,0.33);
-webkit-box-shadow: 0 1px 3px 0 rgba(0,0,0,0.33);
-moz-box-shadow: 0 1px 3px 0 rgba(0,0,0,0.33);
cursor:pointer;
transition-duration: 2s;
-moz-transition-duration: 2s;
-webkit-transition-duration: 2s;

}

*._x_fbstatuses_for_update :hover{
	font-weight:bold;
	background-color: #333;
}

* ._add_fbstatuses_for_update{
padding:10px;margin:10px;
box-shadow: 0 1px 3px 0 rgba(0,0,0,0.33);
-webkit-box-shadow: 0 1px 3px 0 rgba(0,0,0,0.33);
-moz-box-shadow: 0 1px 3px 0 rgba(0,0,0,0.33);
cursor:pointer;
transition-duration: 2s;
-moz-transition-duration: 2s;
-webkit-transition-duration: 2s;
}

*._add_fbstatuses_for_update :hover{
	font-weight:bold;
	background-color:#333;
}

*._fbstatusesupdate textarea{
padding:15px;
width:500px;
}

</style>

<script type="text/javascript">
function RunOnLoad()
{
	ResetConditions();
}

function _display_next_fb_status_update_field(){
	var n = $("._fbstatusesupdate_next").text();
	if (parseInt(n)==6){return;}
	$("._fbstatusesupdate"+n).fadeTo("fast", 1);
	$("._fbstatusesupdate_next").text("" + (parseInt(n)+1));
}

function _x_fb_status_update_field(){
	var n = $("._fbstatusesupdate_next").text();
	n=(parseInt(n)-1);
	if (1==n){return;}
	$("._fbstatusesupdate"+n).fadeTo("fast", 0, function(){$(this).css('display','none');});
	$("._fbstatusesupdate_next").text("" + n);	
}

function _has_items($this, $show_import_msg){
	$ccp_parent = $this.closest('div._selector');
	$ccp_div_html = $ccp_parent.find('div.ccp').html();
	$empty_items = $("<temp>"+$ccp_div_html+"</temp>").find('div.__empty_items');
	//console.log($empty_items);
	//console.log($empty_items.length);
	if (undefined != $empty_items && $empty_items.length > 0){
		s_s_m(_has_items.error,0,0);r_i_c('div.success_msg');fit_to_inner_content('div.success_msg');
		return false;
	}else{return true;}
}

function _condition_change()
{
	$con = $("#OpenCampaignCondition1").val();
	if ('11' == $con)
	{
		$('._dcsm').text('What should people share to get coupon.');
	}
	else if ('12' == $con)
	{
		$('._dcsm').text('What should people tweet to get coupon.');
	}
	else if ('13' == $con)
	{
		$('._dcsm').text('What should people like to get coupon.');
	}
	else if ('14' == $con)
	{
		$('._dcsm').text('Give the url of facebook page that people should like.');
	}
	else if ('15' == $con)
	{
		$('._dcsm').text('Which videos people can like to get coupon.');
	}
	
	_prepare_share_box();
}

function _prepare_share_box()
{
	$con = $("#OpenCampaignCondition1").val();
	_clear_content_selection();
	_clear_preview_images();
	$('div._fbimageselector').find('div.ccp').html("");
	
	$('div._fblinkposturl').hide();
	$('div._fbshareandtweet').hide();
	$('div._fbimageselector').hide();
	$('div._tweetline').hide();
	$('div._fblikepageurl').hide();
	$('div._emailsignup_options').hide();
	$('div._fbeventurl').hide();
	$('div._yelp_review_tip').hide();
	//$('div._fbimageselector').hide();
	
	$image_error = "<div>Please import images to attach to your promotion. <br/><div onclick=\"_header_ctp_fb_img_import_for_ad();\" class='header_tabs' style='border-bottom:2px solid orange;color:white;'>Import Images from Facebook</div></div>";
	$video_error = "<div>Please import videos to attach to your promotion. <br/><div onclick=\"_header_ctp_fb_vdo_import_for_ad();\" class='header_tabs' style='border-bottom:2px solid orange;color:white;'>Import Videos from Facebook</div></div>";
	_has_items.error = "";
	
	if ('11' == $con)
	{
		$('._dcsm').text('What should people share to get coupon.');
		//$('div._fblikepageurl').hide();
		$('div._fbshareandtweet').show();
		
		var $iccp = $('div._iccp').html();
		$('div._fbimageselector').find('div.ccp').html($iccp);
		_has_items.error = $image_error;
		
		$('div._fbimageselector').find('black_button._selector_btn').text('Choose 1 or more pics');
		$('div._fbimageselector').find('div._ccp_suggestion').text("Pick one or more images that people should add to their Facebok post for this coupon. If you choose more than one, people will choose one out of those to add to their FB post.");
		$('div._fbimageselector').show();
	}
	else if ('12' == $con)
	{
		$('._dcsm').text('What should people tweet to get coupon.');
		//$('div._fblikepageurl').hide();
		//$('div._fbshareandtweet').show();
		$('div._tweetline').show();
		
		var $iccp = $('div._iccp').html();
		$('div._fbimageselector').find('div.ccp').html($iccp);
		_has_items.error = $image_error;
		
		$('div._fbimageselector').find('black_button._selector_btn').text('Choose 1 or more pics');
		$('div._fbimageselector').find('div._ccp_suggestion').text("Pick one or more images that people should add to their tweet for this coupon. If you choose more than one, people will add only one out of those to their tweet for the coupon.");
		$('div._fbimageselector').show();
	}
	else if ('13' == $con)
	{
		$('._dcsm').text('Which photo people should like to get coupon.');
		//$('div._fblikepageurl').hide();
		//$('div._fbshareandtweet').hide();
		
		var $iccp = $('div._iccp').html();
		$('div._fbimageselector').find('div.ccp').html($iccp);
		_has_items.error = $image_error;
		
		$('div._fbimageselector').find('black_button._selector_btn').text('Choose 1 or more pics');
		$('div._fbimageselector').find('div._ccp_suggestion').text("Pick one or more images that people should promote for this coupon. If you choose more than one, people will 'facebook-like' only one of those for the coupon.");
		$('div._fbimageselector').show();
	}
	else if ('14' == $con)
	{
		$('._dcsm').text('Give the url of facebook page that people should like.');
		$('div._fblikepageurl').show();
		//$('div._fbshareandtweet').hide();
		//$('div._fbimageselector').hide();
	}
	else if ('15' == $con)
	{
		$('._dcsm').text('Which video people should like to get coupon.');
		//$('div._fblikepageurl').hide();
		//$('div._fbshareandtweet').hide();
		
		var $vccp = $('div._vccp').html();
		$('div._fbimageselector').find('div.ccp').html($vccp);
		_has_items.error = $video_error;
		
		$('div._fbimageselector').find('black_button._selector_btn').text('Choose 1 or more videos');
		$('div._fbimageselector').find('div._ccp_suggestion').text("Pick one or more videos that people should promote for this coupon. If you choose more than one, people will 'facebook-like' only one of those for the coupon.");
		$('div._fbimageselector').show();
	}
	else if ('16' == $con)
	{
		$('._dcsm').text('Give the url of Youtube/Vimeo video that people should post on Facebook.');
		$('div._fblinkposturl').show();
		//$('div._fbshareandtweet').hide();
		//$('div._fbimageselector').hide();
	}
	else if ('17' == $con)
	{
		$('._dcsm').text('Choose a signup option.');
		$('div._emailsignup_options').show();
	}
	else if ('18' == $con)
	{
		$('._dcsm').text('Give the url of the Facebook event that people should share.');
		$('div._fbeventurl').show();
	}
	else if ('19' == $con)
	{
		$('._dcsm').text('Give the url of the Facebook event that you want people to join.');
		$('div._fbeventurl').show();
	}
	else if ('20' == $con)
	{
		$('._dcsm').text("Give-away coupon doesn't require any social interaction.");
	}
	else if ('21' == $con)
	{
		$('._dcsm').text("");
		$('div._yelp_review_tip').show();
	}
	else if ('22' == $con)
	{
		$('._dcsm').text("What facebook status people should put to unlock coupon (type upto 5)");
		$('div._fbstatusesupdate').show();
	}
}

function ResetConditions()
{
	
	$con1 = $("#OpenCampaignCondition1");
	$con2 = $("#OpenCampaignCondition2");
	$oc_type = $("#OpenCampaignPlat").val();
	
	$con1.val(0);
	$con2.val(0);
	
	set_select_options_display ($("#OpenCampaignCondition1").find("option"), 'show');
	set_select_options_display ($("#OpenCampaignCondition2").find("option"), 'show');
	
	$con1.html($('#OpenCampaignCondition1Repo').html());
		
	if ($oc_type == 'giveaway')
	{
		to_disable = ['0', '1', '2','3','4', '5', '6','7', '8', '9', '10', '11', '12', '13', '14','15','16','17','18','19','21','22'];
		for (x in to_disable)
		{
			hide_select_option(get_select_option_by_value($("#OpenCampaignCondition1"), to_disable[x]));
		}
		$("#OpenCampaignCondition1").find("option[value=" + '20' + "]").prop('selected', true);
	}
	else if ($oc_type == 'review')
	{
		to_disable = ['0', '1', '2','3','4', '5', '6','7', '8', '9', '10', '11', '12', '13', '14','15','16','17','18','19','20','22'];
		for (x in to_disable)
		{
			hide_select_option(get_select_option_by_value($("#OpenCampaignCondition1"), to_disable[x]));
		}
		$("#OpenCampaignCondition1").find("option[value=" + '21' + "]").prop('selected', true);
	}
	else if ($oc_type == 'signup')
	{
		to_disable = ['0', '1', '2','3','4', '5', '6','7', '8', '9', '10', '11', '12', '13', '14', '15', '16','18','19','20','21','22'];
		for (x in to_disable)
		{
			hide_select_option(get_select_option_by_value($("#OpenCampaignCondition1"), to_disable[x]));
		}
		$("#OpenCampaignCondition1").find("option[value=" + '17' + "]").prop('selected', true);
	}
	else if ($oc_type == 'fb_post')
	{
		//to_disable = ['1','8','9', '10', '12'];
		to_disable = ['0', '1', '2','3','4', '5', '6','7', '8', '9', '10', '12', '17','20','21'];
		for (x in to_disable)
		{
			hide_select_option(get_select_option_by_value($("#OpenCampaignCondition1"), to_disable[x]));			
		}
		$("#OpenCampaignCondition1").find("option[value=" + '11' + "]").prop('selected', true);
	}
	else if ($oc_type == 'tweet')
	{
		to_disable = ['0', '1', '2','3','4', '5', '6','7', '8', '9', '10', '11', '13', '14', '15', '16', '17','18','19','20','21','22'];
		for (x in to_disable)
		{
			hide_select_option($("#OpenCampaignCondition1").find("option[value='" + to_disable[x] + "']"));			
		}
		$("#OpenCampaignCondition1").find("option[value=" + '12' + "]").prop('selected', true);
	}
	else if ($oc_type == 'blog')
	{
		to_disable = ['2','3','4','6','7','9', '10', '11', '12', '13', '14', '15', '16', '17','18','19','20','21','22'];
		for (x in to_disable)
		{
			hide_select_option($("#OpenCampaignCondition1").find("option[value='" + to_disable[x] + "']"));
		}
	}
	else if ($oc_type == 'reddit')
	{
		to_disable = ['1', '2','3','4','6','7', '8', '10', '11', '12', '13', '14', '15', '16', '17','18','19','20','21','22'];
		for (x in to_disable)
		{
			hide_select_option($("#OpenCampaignCondition1").find("option[value='" + to_disable[x] + "']"));
		}
	}
	else if ($oc_type == 'imgur')
	{
		to_disable = ['1', '2','3','4','6','7', '8', '9', '11', '12', '13', '14', '15', '16', '17','18','19','20','21','22'];
		for (x in to_disable)
		{
			hide_select_option($("#OpenCampaignCondition1").find("option[value='" + to_disable[x] + "']"));	
		}
	}
	
	_condition_change();
	
	return;
}

function validate_form($form)
{
	//$form = $("."+form_container_class).find('form');
	
	var $default_title = $form.find("#OpenCampaignDefaultTitle").val();
	var $default_link = $form.find("#OpenCampaignDefaultShareLink").val();
	var $default_desc = $form.find("#OpenCampaignDefaultShareDesc").val();
	var $default_tweet = $form.find("#OpenCampaignDefaultTweet").val();
	var $default_fbpageurl = $form.find("#OpenCampaignDefaultFbpageurl").val();
	var $default_linkforpost = $form.find("#OpenCampaignDefaultLinkforpost").val();
	var $default_fbeventurl = $form.find("#OpenCampaignDefaultFbeventurl").val();
	var $con1 = parseInt($form.find('#OpenCampaignCondition1').find(':selected').val());
	
	var $cids = $form.find("#OpenCampaignApprovedContentIds").val();
	var $coupon_worth = $form.find("#OpenCampaignCouponWorth").val().replace(/ /g, "").replace(/\$/g, "");
	var $coupon_line = $form.find("#OpenCampaignCouponLine").val();
	var $coupon_code = $form.find("#OpenCampaignCouponCode").val();
	
	var $coupon_valid_until_day = $form.find("#OpenCampaignCondition3Param1Day").find(":selected").val();
	var $coupon_valid_until_month = $form.find("#OpenCampaignCondition3Param1Month").find(":selected").val();
	var $coupon_valid_until_year = $form.find("#OpenCampaignCondition3Param1Year").find(":selected").val();
	
	var $error_msg = "";
	var $error_ele = 0;
	
	//alert($cids + "+" + $form.find("#OpenCampaignApprovedContentIds").html());
	if ( (11 == $con1) && (!($cids) || "" == $cids.trim()))
	{
		$error_msg = "Please pick an image, that people should add to their Facebok post for this coupon. If you choose more than one, people will choose one out of those to add to their FB post.";
		$error_ele = $form.find("#OpenCampaignApprovedContentIds").parent().first();
	}
	else if ( (12 == $con1) && (!($cids) || "" == $cids.trim()))
	{
		$error_msg = "Please pick an image, that people should add to their tweet for this coupon. If you choose more than one, people will choose one out of those for the coupon.";
		$error_ele = $form.find("#OpenCampaignApprovedContentIds").parent().first();
	}
	else if ( (13 == $con1) && (!($cids) || "" == $cids.trim()))
	{
		$error_msg = "Please pick an image, that people should promote for this coupon. If you choose more than one, people will like only one of those for the coupon.";
		$error_ele = $form.find("#OpenCampaignApprovedContentIds").parent().first();
	}
	else if ( (15 == $con1) && (!($cids) || "" == $cids.trim()))
	{
		$error_msg = "Please pick a video, that people should promote for this coupon. If you choose more than one, people will like only one of those for the coupon.";
		$error_ele = $form.find("#OpenCampaignApprovedContentIds").parent().first();
	}
	else if ( (14 == $con1) && (typeof $default_fbpageurl =='undefined' || $default_fbpageurl == ''))
	{
		$error_msg = "Please enter Facebook page url.";
		$error_ele = $form.find("#OpenCampaignDefaultFbpageurl").parent().first();
		
	}
	else if ( (16 == $con1) && (typeof $default_linkforpost =='undefined' || $default_linkforpost == ''))
	{
		$error_msg = "Please enter Youtube or Vimeo link of Video that people should post on facebook.";
		$error_ele = $form.find("#OpenCampaignDefaultFbpageurl").parent().first();		
	}
	else if ( (18 == $con1) && (typeof $default_fbeventurl =='undefined' || $default_fbeventurl == ''))
	{
		$error_msg = "Please enter URL of your facebook event page";
		$error_ele = $form.find("#OpenCampaignDefaultFbeventurl").parent().first();		
	}
	else if ( (19 == $con1) && (typeof $default_fbeventurl =='undefined' || $default_fbeventurl == ''))
	{
		$error_msg = "Please enter URL of your facebook event page";
		$error_ele = $form.find("#OpenCampaignDefaultFbeventurl").parent().first();		
	}
	else if ( (11 == $con1) && (typeof $default_title =='undefined' || $default_title.trim() == ''))
	{
		$error_msg = "Please enter a default Tweet or FB post line.";
		$error_ele = $form.find("#OpenCampaignDefaultTitle").parent().first();
	}
	else if ( (11 == $con1) && (typeof $default_link =='undefined' || $default_link.trim() == ''))
	{
		$error_msg = "Please enter a default link to be part of Tweet or FB Share.";
		$error_ele = $form.find("#OpenCampaignDefaultShareLink").parent().first();
	}
	else if ((11 == $con1) && (typeof $default_desc =='undefined' || $default_desc.trim() == ''))
	{
		$error_msg = "Please enter a default description to be part of FB Share.";
		$error_ele = $form.find("#OpenCampaignDefaultShareDesc").parent().first();
	}
	else if ( (12 == $con1) && (typeof $default_tweet == 'undefined' || $default_tweet.trim() == ''))
	{
		$error_msg = "Please enter the tweet that you want people to share.";
		$error_ele = $form.find("#OpenCampaignDefaultTweet").parent().first();
	}
	else if (typeof $coupon_worth =='undefined' || $coupon_worth == '')
	{
		$error_msg = "Please enter total value of this coupon(Coupon worth).";
		$error_ele = $form.find("#OpenCampaignCouponWorth").parent().first();
	}
	else if (true != isNumber($coupon_worth))
	{
		$error_msg = "Please only enter numbers for total value of this coupon(Coupon worth).";
		$error_ele = $form.find("#OpenCampaignCouponWorth").parent().first();
	}
	else if (NaN == parseFloat($coupon_worth) || parseFloat($coupon_worth) <= 0)
	{
		$error_msg = "Please check the Coupon worth again.";
		$error_ele = $form.find("#OpenCampaignCouponWorth").parent().first();
	}
	else if  (typeof $coupon_line == 'undefined' || $coupon_line == '')
	{
		$error_msg = "Please enter one line for the coupon, that shortly explains the coupon offer (Coupon line).";
		$error_ele = $form.find("#OpenCampaignCouponLine").parent().first();
	}
	else if  (typeof $coupon_code == 'undefined' || $coupon_code == '')
	{
		$error_msg = "Please enter coupon code.";
		$error_ele = $form.find("#OpenCampaignCouponCode").parent().first();
	}
	else if  (
		(typeof $coupon_valid_until_day == 'undefined' || $coupon_valid_until_day == '')
		||(typeof $coupon_valid_until_month == 'undefined' || $coupon_valid_until_month == '')
		||(typeof $coupon_valid_until_year == 'undefined' || $coupon_valid_until_year == '')
		)
	{
		$error_msg = "Please enter coupon valid until date(last date of coupon validity).";
		$error_ele = $form.find("#OpenCampaignCondition3Param1Day").parent().first();
	}
	else 
	{
		var today = get_today_mm_dd_yyyy().split("/");
		var mm = today[0];
		var dd = today[1];
		var yyyy = today[2];
		
		var today = yyyy.toString() + mm.toString() + dd.toString();
		var $valid_until_date = $coupon_valid_until_year + $coupon_valid_until_month + $coupon_valid_until_day;
		
		if ($valid_until_date < today)
		{
			$error_msg = "'Coupon valid until date' should not be a past date.";
			$error_ele = $form.find("#OpenCampaignCondition3Param1Day").parent().first();
		}
	}
	
	if ("" == $error_msg)
	{
		$is_available = coupcodechk($coupon_code);
		if ($is_available) { return true; }
		else
		{
			$error_msg = "This coupon code ("+$coupon_code+") has already been used by you. Use the same code again? If you use the same code again, it won't be locked for the people who have already unlocked it before."
			$error_ele = $form.find("#OpenCampaignCouponCode").parent().first();
		}
	}
	
	s_s_m($error_msg, slide_to_view, $error_ele);
	fit_to_inner_content('div.success_msg');
	r_i_c('div.success_msg');
	return false;
	
}

function verify_dynamic_social_coupon_and_add($form)
{
	var $cm_str = _build_comma_separated_content_str();
	//alert($cm_str);
	$form.find("#OpenCampaignApprovedContentIds").val($cm_str);
	$form.find("#OpenCampaignApprovedContentIds").text($cm_str);
	
	var $form_is_correct = validate_form($form);
	if (!$form_is_correct)
	{
		return false;
	}
	//$form = $("."+form_container_class).find('form');
	
	$coupon_worth = $form.find("#OpenCampaignCouponWorth").val().replace(/ /g, "").replace(/\$/g, "");
	$coupon_worth_cur = $form.find("#OpenCampaignCouponWorthCur").find(":selected").text();
	$coupon_line = $form.find("#OpenCampaignCouponLine").val();
	$coupon_details = $form.find("#OpenCampaignCouponDetails").val();
	$coupon_code = $form.find("#OpenCampaignCouponCode").val();
	$product_id = $form.find("#OpenCampaignProductId").val();
	$condition_name_code = $form.find("#OpenCampaignCondition1").find(":selected").val();
	$condition_name = $form.find("#OpenCampaignCondition1").find(":selected").text();
	
	if ($condition_name.indexOf("----None----")>=0){
		$end_con_name = $condition_name.indexOf("----None----");
		$condition_name = $condition_name.substring(0,$end_con_name);
	}
	
	var $default_title = $form.find("#OpenCampaignDefaultTitle").val();
	var $default_link = $form.find("#OpenCampaignDefaultShareLink").val();
	var $default_desc = $form.find("#OpenCampaignDefaultShareDesc").val();
	var $default_tweet = $form.find("#OpenCampaignDefaultTweet").val();
	var $default_fbpageurl = $form.find("#OpenCampaignDefaultFbpageurl").val();
	var $default_linkforpost = $form.find("#OpenCampaignDefaultLinkforpost").val();
	var $default_fbeventurl = $form.find("#OpenCampaignDefaultFbeventurl").val();
	var $email_signup_option_val = $form.find("#OpenCampaignEmailSignupWays").find(":selected").val();
	var $email_signup_option_txt = $form.find("#OpenCampaignEmailSignupWays").find(":selected").text();
	
	data = {};
	data['con_code'] = $condition_name_code;
	if ($product_id == '0' || $product_id == 0)
	{
		data['is_for_product'] = 0;
		data['product_name'] = "";
		data['promotion_for'] = $form.find("#OpenCampaignProductId").find(":selected").text();
	}
	else
	{
		data['is_for_product'] = 1;
		data['product_name'] = $form.find("#OpenCampaignProductId").find(":selected").text();
		data['promotion_for'] = $form.find("#OpenCampaignProductId").find(":selected").text();
	}
	
	data['start_date'] = "";
	data['active'] = "";
	data['plat'] = $form.find("#OpenCampaignPlat").val();
	
	if (11 == $condition_name_code)
	{
		data['type'] = 'fb_post';
	}
	else if (12 == $condition_name_code)
	{
		data['type'] = 'tweet';
	}
	else if (13 == $condition_name_code)
	{
		data['type'] = 'fb_like_pic';
	}
	else if (14 == $condition_name_code)
	{
		data['type'] = 'fb_like_page';
	}
	else if (15 == $condition_name_code)
	{
		data['type'] = 'fb_like_vdo';
	}
	else if (16 == $condition_name_code)
	{
		data['type'] = 'fb_post_video';
	}
	else if (18 == $condition_name_code)
	{
		data['type'] = 'fb_event_share';
	}
	else if (19 == $condition_name_code)
	{
		data['type'] = 'fb_event_join';
	}
	
	// add conditions
	condition = {};
	condition['con_name'] = $condition_name;
	condition['param1'] = 0;
	condition['param2'] = 0;
	condition['offer_type'] = 'coupon';
	condition['coupon'] = {};
	condition['coupon']['coupon_code'] = $coupon_code;
	condition['coupon']['coupon_worth'] = $coupon_worth;
	condition['coupon']['coupon_worth_cur'] = $coupon_worth_cur;
	condition['coupon']['coupon_line'] = $coupon_line;
	condition['coupon']['coupon_details'] = $coupon_details;
	data['share_details'] = {};	
	data['share_details']['default_title'] = $default_title;
	data['share_details']['default_link'] = $default_link;
	
	
	if (12 == $condition_name_code)
	{
		data['share_details']['default_title'] = $default_tweet;
	}
	else if (17 == $condition_name_code)
	{
		data['share_details']['default_title'] = $email_signup_option_val;
		data['share_details']['default_desc'] = $email_signup_option_txt;
	}
	else if(18==$condition_name_code || 19==$condition_name_code)
	{
		data['share_details']['default_title'] = $default_fbeventurl;
		data['share_details']['default_link'] = $default_fbeventurl;
	}
	else
	{
		data['share_details']['default_desc'] = $default_desc;
	}
	data['share_details']['default_fbpageurl'] = $default_fbpageurl;
	data['share_details']['default_linkforpost'] = $default_linkforpost;
	
	$coupon_valid_until_day = $form.find("#OpenCampaignCondition3Param1Day").find(":selected").val();
	$coupon_valid_until_month = $form.find("#OpenCampaignCondition3Param1Month").find(":selected").val();
	$coupon_valid_until_year = $form.find("#OpenCampaignCondition3Param1Year").find(":selected").val();

	condition['coupon']['valid_until_date'] = 	$coupon_valid_until_month + "/" + $coupon_valid_until_day + "/" + $coupon_valid_until_year
	
	condition['max_count'] = -1;
	condition['met_so_far'] = -1;
	
	data['conditions'] = [condition];
	//alert(JSON.stringify(data));
	node = show_dynamic_social_coupon(JSON.stringify(data));
	
	node.append("<div style='margin-top:2px; margin-bottom:2px; margin-color'><a style='color:white; cursor:pointer;' onclick=\"$('close_button').click(); $('#OpenCampaignStartNowAswell').val(1); $('#OpenCampaignAddForm').find('.submit').find('input').click(); show_loading_image(); \">Add and Start it now.<a></div>")
	node.append("<div style='margin-top:2px; margin-bottom:2px; margin-color'><a style='color:white; cursor:pointer;' onclick=\"$('close_button').click(); $('#OpenCampaignStartNowAswell').val(0); $('#OpenCampaignAddForm').find('.submit').find('input').click(); show_loading_image(); \">Save it and Start later.<a></div>")
	//$('#OpenCampaignAddForm').submit();
}

function _set_preview_image($this, $cid, $val)
{
	var $isrc = 0;
	var $ci = $this.closest('div.content_news_box_sidebar').find('content_image');
	if ($ci && 1== $ci.length){	var $isrc = $ci.find('img').prop('src');}
	if (!$isrc){
		var $ci = $this.closest('div.content_news_box_sidebar').find('content_video');
		if ($ci && 1== $ci.length){var $bg = $ci.find('img').css('background-image');$bg = $bg.replace('url(','').replace(')','');var $isrc = $bg;}
	}
	
	if ($isrc){
		if (1 == $val){$('div._promo_imgs').append("<div class='_cid"+$cid+"' style='width:102px; height:102px;border:silver;border-style:solid;border-size: 2px;'><img style=\"max-width:100px; max-height:100px;\" src=\""+$isrc+"\"></img></span>"); }
		else{ $('div._promo_imgs').find("div._cid"+$cid).remove(); }
	}
}

function _clear_preview_images(){$('div._promo_imgs').empty();}

function _update_div_ccp(){ 
$('div._selector').find('div.ccp').html($('temp').html()); 
}

</script>

<?php

function _get_content_picker_button_code($content_id)
{
	$s = "
		<div class='_selection_buttons' style='margin:2px;'>
			<div class='_to_select' onclick=\"_set_preview_image($(this), '{$content_id}', 1);_company_content_picker_ctp_select_item($(this), '{$content_id}', 1);_update_div_ccp();\">
				<black_button>Select.</black_button>
			</div>
			<div class='_selected' style=\"display:none;\" onclick=\"_set_preview_image($(this), '{$content_id}', 0);_company_content_picker_ctp_select_item($(this), '{$content_id}', 0);_update_div_ccp();\">
				<green_button style=\"border:'';\">Un-select.</green_button>
			</div>
		</div>
	";
	return $s;
}

?>

<div class="openCampaigns_add_form">
<?php echo $this->Form->create('OpenCampaign', array('onsubmit' => "validate_form('openCampaigns_add_form');")); ?>
	<fieldset>
		<legend><?php 
			//echo __('Create an Advertising Campaign'); 
			echo __('Add your coupon.'); 
			?></legend>
	<?php
		/*
		$company_data_select_list = array();
		
		foreach ($company_data as $company_id => $company)
		{
			$company_data_select_list[$company_id] = $company['name'] . " " . $company['website'];
		}
		*/
		
		echo $this->Form->input('start_now_aswell', array('default' => '0', 'type'=>'hidden', 'label' => 'Start the coupon after it is added'));
			
		$product_data_select_list = array();
		$product_data_select_list[0] = 'Promote our company';
		
		/*
		foreach ($product_data as $product_id => $product)
		{
			if (!empty($company_data['Company']['name']))
				$product_data_select_list[$product_id] = $product['name'] . ' by ' . $company_data['Company']['name'];
			else
				$product_data_select_list[$product_id] = $product['name'];
		}
		*/
		
		
		echo "<div style='clear:both'></div>";
		echo "<section style='width:700px;'>";
			//echo "<div style='position:relative; float:left;'>";
			
			echo $this->Form->input('product_id', array(
				'options' => $product_data_select_list,
				'default' => 0,
				'label' => false,
				'style' => 'display:none;' // this is hidden
			));
			
			//echo "</div>";
			
			$enabled_plats = SOCIAL_PLATS::$enabled_plats['dynamic'];
			$oc_type_list = array();
			if ($enabled_plats['blog'])
			{
				$oc_type_list['blog'] = 'Blog Post';
			}
			if ($enabled_plats['fb'])
			{
				$oc_type_list['fb_post'] = 'Facebook';
			}
			if ($enabled_plats['reddit'])
			{
				$oc_type_list['reddit'] = 'Reddit';
			}
			if ($enabled_plats['imgur'])
			{
				$oc_type_list['imgur'] = 'Imgur';
			}
			if ($enabled_plats['tw'])
			{
				$oc_type_list['tweet'] = 'Twitter';
			}
			if ($enabled_plats['signup'])
			{
				$oc_type_list['signup'] = 'Newsletter';
			}
			if ($enabled_plats['giveaway'])
			{
				$oc_type_list['giveaway'] = 'Give-away';
			}
			if ($enabled_plats['review'])
			{
				$oc_type_list['review'] = 'Review';
			}
			
			//echo "<div style='position:relative;float:left;'>";
			echo $this->Form->input('plat', array(
				'options' => $oc_type_list,
				'label' => 'Promotion Platform (where to promote you)',
				'onchange' => 'ResetConditions();'
			));
			
			$conditions_list = array();
			foreach ($conditions as $id => $name)
			{
				if ($id == 5) continue;
				$conditions_list[$id] = $name['name'];
			}
			$conditions_list[0] = '----None----';
			
			echo $this->Form->input('condition1', array(
				'options' => $conditions_list, 'label' => 'Coupon displays when users do following :', 'default' => 0,
				'onchange' => "_condition_change();",
				//'style'=>'width:300px;',
				//'css'=>'left-margin:10%;'
			));
			
			//echo "</div>";
		echo "</section>";
		
		// echo $this->Form->input('type');
		
		// echo $this->Form->input('active');
		
		$offer_type['coupon'] = "Coupon worth";
		//$offer_type['dollar'] = "Dollar amount worth";
		
		echo "<div style='clear:both'></div>";
		echo "<section style='width:700px;'>";
			//echo "<label>Pick offers to promoters</label>";
			echo "<label>Coupon Details</label>";
			
			/*
			echo $this->Form->input('condition1', array(
				'options' => $conditions_list, 'label' => 'For following condition (choose condition) ', 'default' => 0,
				//'style'=>'left-margin:10%;',
				//'css'=>'left-margin:10%;'
			));
			echo $this->Form->input('condition1_param1', array('label' => 'Of (enter a target for the condition in left)'));
			echo "<div style='clear:both'></div>";
			echo $this->Form->input('condition1_offer_type', array('options' => $offer_type, 'label' => 'You offer promoters'));
			echo $this->Form->input('condition1_offer_worth', array('label' => 'worth (enter total worth of each offering)'));
			
			echo $this->Form->input('max_count1', array('label' => 'how many of such offers you are giving?'));
			*/
			
			echo "<div style='display:none'>";
				echo $this->Form->input('condition1_param1', array('default' => '1', 'label' => 'Of (enter a target for the condition in left)'));
				echo $this->Form->input('condition1_offer_type', array('options' => $offer_type, 'default' => 'coupon', 'label' => 'You offer promoters'));
			echo "</div>";
			
			echo $this->Form->input('condition1_repo', array(
				'options' => $conditions_list, 'default' => 0,
				'style'=>'display:none;',
				'label' => false
				//'css'=>'left-margin:10%;'
			));
			
			echo "<div style='clear:both'></div>";
			$discount_type_list = array('percent_off' => '% off', 'dollar_off' => '$ off', 'sale' => 'sale');
			echo $this->Form->input('coupon_type', array(
				'options' => $discount_type_list, 'label' => 'Coupon type (dollar off / percent off / sale) ', 'default' => 0,
				//'style'=>'left-margin:10%;',
				//'css'=>'left-margin:10%;'
			));
			
			//echo "<div style='clear:both'></div>";
			//echo $this->Form->input('condition1_offer_type', array('options' => $offer_type, 'label' => ''));//You offer promoters'));
			echo "<div style='clear:both'></div>";
			echo "<section style='width:600px;'>";
				echo $this->Form->input('coupon_worth', array('type' => 'numeric', 'label' => 'Coupon worth (enter total dollar value or savings with coupon.)'));
				$cur_types = CUR_CODES::$currencies;
				echo $this->Form->input('coupon_worth_cur', array(
					'options' => $cur_types, 'label' => '', 'default' => 0,
					//'style'=>'left-margin:10%;',
					//'css'=>'left-margin:10%;'
				));
			echo "</section>";
			
			echo "<div style='clear:both'></div>";
			
			echo "<section style='width:600px;'>";
				echo $this->Form->input('coupon_code', array('label' => 'Coupon Code (max 30 characters).', 'maxlength' => '30'));
				echo $this->Form->input('coupon_line', array(
					'label' => 'One line for coupon offer (max 40 characters).', 
					'maxlength' => '60',
					'style' => "width:350px;"
					)
				);
				echo "<div style='clear:both'></div>";
				echo $this->Form->input('coupon_details', array(
					'type'=>'textarea', 
					'label' => 'Optional details for the coupon (max 300 characters).', 
					'maxlength' => '500',
					'style' => "width:550px;"
					)
				);
			echo "</section>";
			
		echo "</section>";
		
		echo "<div style='clear:both'></div>";
		/*
		echo "<section>";
			echo "<label>Pick another type of offer (optional)</label>";
		
			echo $this->Form->input('condition2', array(
				'options' => $conditions_list, 'label' => 'For following condition (choose condition) ', 'default' => 0,
				//'style'=>'left-margin:10%;',
				//'css'=>'left-margin:10%;'
			));
			echo $this->Form->input('condition2_param1', array('label' => 'Of (enter a target for the condition in left)'));
			echo "<div style='clear:both'></div>";
			echo $this->Form->input('condition2_offer_type', array('options' => $offer_type, 'label' => 'You offer promoters'));
			echo $this->Form->input('condition2_offer_worth', array('label' => 'worth (enter total worth of each offering)'));
			
			echo $this->Form->input('max_count2', array('label' => 'how many of such offers you are giving?'));
		echo "</section>";
		echo "<div style='clear:both'></div>";
		*/
		
		$valid_date_conditions_list = array();
		foreach ($conditions as $id => $name)
		{
			if ($id != 5) continue;
			$valid_date_conditions_list[$id] = $name['name'];
		}
		echo "<section style='width:700px;'>";
		
		/*
		echo $this->Form->input('condition3', array(
            'options' => $valid_date_conditions_list, 'label' => 'Pick the last date for promoters to fulfill above requirements.', 'default' => 5, 'disabled' => TRUE
        ));
		*/
		
		//echo $this->Form->input('condition3_param1', array('label' => 'Enter MM/DD/YYYY format'));
		echo $this->Form->input('condition3_param1', array(
			//'label' => 'Date',
			'label' => 'Coupon valid until',
			'type'=>'date',
			'dateFormat' => 'DMY',
			'minYear' => date('Y'),
			'maxYear' => date('Y')+1
		));
		echo "</section>";
		
		//$ccp = $this->element('content/company_content_picker', array('contents' => $content_data, 'content_type'=> array('news', 'image'), 'show_only' => 'active'));
		$iccp = $this->element('content/company_content_picker', array('contents' => $content_data, 'content_type'=> array('image'), 'show_only' => 'active', 'importer_if_empty'=>'image'));
		$vccp = $this->element('content/company_content_picker', array('contents' => $content_data, 'content_type'=> array('video'), 'show_only' => 'active', 'importer_if_empty'=>'video'));
		
		echo "<div style='clear:both'></div>";
		echo "
			<section style='min-width:700px;'>
				<div class='_dcsm' style='font-size:10px;color:black;'>What should people share to get coupon.</div>
				<div style='clear:both'></div>
				<div class='_selector'><div class='_fbimageselector'>
					<div class='ccp' style='display:none'>
					{$iccp}
					</div>
					<div>
						<black_button class='_selector_btn' style='margin:15px;margin-top:10px;margin-bottom:5px;' onclick=\"if(_has_items($(this),1)){disp_ccp($(this));}\">Choose 1 or more Pics</black_button>
						<br/>
						<div class='_promo_imgs' style='max-width:420px;'></div>
						<!--div style='font-size:10px;color:black;'>(what should users promote to get the coupon)</div-->
					</div>
					<div class='_ccp_suggestion'></div>
				</div></div>
				
				
			";
		echo $this->Form->input('approved_content_ids', array('default' => '0', 'type'=>'hidden', 'label' => 'Comma separated content ids'));
			
			echo "<div class='_fblinkposturl' style='display:none;'><section>";
				echo "<div style='clear:both'></div>";
				echo "<default_link_post>";
					echo $this->Form->input('default_linkforpost', array('style' => 'width:550px;', 'label'=>'Youtube/Vimeo Video URL.', 'maxlength'=>200));
				echo "</default_link_post>";
			echo "</section></div>";
			echo "<div class='_fbeventurl' style='display:none;'><section>";
				echo "<div style='clear:both'></div>";
				echo "<default_link_post>";
					echo $this->Form->input('default_fbeventurl', array('style' => 'width:550px;', 'label'=>'URL of Facebook Event.', 'maxlength'=>200));
				echo "</default_link_post>";
			echo "</section></div>";
			echo "<div class='_fblikepageurl' style='display:none;'><section>";
				echo "<div style='clear:both'></div>";
				echo "<default_like_fbpage_link>";
					echo $this->Form->input('default_fbpageurl', array('style' => 'width:550px;', 'label'=>'Facebook Page URL.', 'maxlength'=>200));
				echo "</default_like_fbpage_link>";
			echo "</section></div>";
			echo "<div class='_tweetline' style='clear:both;display:none;'><section>";
				//echo "<div style='clear:both'></div>";
				echo "<default_tweet>";
					echo $this->Form->input('default_tweet', array('style' => 'width:600px;', 'label'=>'Tweet line.', 'rows'=>2, 'maxlength'=>135));
				echo "</default_tweet>";
			echo "</section></div>";
			echo "<div class='_fbshareandtweet'><section>";
				echo "<div style='clear:both'></div>";
				echo "<default_title>";
					echo $this->Form->input('default_title', array('style' => 'width:300px;', 'label'=>'Facebook Post Line.', 'maxlength'=>80));
				echo "</default_title>";
				echo "<div style='clear:both;'></div>";
				echo "<default_share_link>";
					echo $this->Form->input('default_share_link', array('style' => 'width:300px;', 'label'=>'Facebook Share Link.', 'maxlength'=>200));
				echo "</default_share_link>";
				echo "<div style='clear:both;'></div><br/>";
				echo "<default_share_desc>";
					echo $this->Form->input('default_share_desc', array('style' => 'width:300px;', 'label' => 'Few lines to add to Facebook Post.','rows' => 4, 'maxlength'=>100));
				echo "</default_share_desc>";
			echo "</section></div>";
			
			echo "<div class='_emailsignup_options' style='display:none;'><section>";
				echo $this->Form->input('email_signup_ways', array(
					'options' => array(
						'single_email_ns_signup' => "Single Email Signup",
						'dual_email_ns_signup' => "Dual Email Signup"
						),
					'default' => 0,
					'style'=>'display:none;',
					'label' => false,
					'style'=>'width:300px;'
				));
				echo "<div class='_ccp_suggestion'>
					'Single Email Signup' - people signup using one email address.
					<br/>
					<br/>
					'Dual Email Signup' - people signup with two email addresses.
				</div>";
				
			echo "</section></div>";
			
			echo "<div class='_yelp_review_tip' style='display:none;'><section>";
				
				echo "<div style='background:#F3F2F2;color:black;width:300px;height:auto;padding: 5px;font-size:14px;text-align:justify;font-family:sans-serif;font-style:normal;font-weight:normal;'>
					People will provide link to their Yelp reviews.
					 You would be able to see those through the promotions in the dashboard.
					 You can approve the reviews you like and send coupon from promotions.
				</div>";
				
			echo "</section></div>";
			
			echo "<div class='_fbstatusesupdate' style='display:none;'><section>";
			echo "<div class='_fbstatusesupdate_next' style='display:none;'>2</div>";
			
			echo "<div onclick='_x_fb_status_update_field()' class='_x_fbstatuses_for_update'>x</div>";
			echo "<div onclick='_display_next_fb_status_update_field()' class='_add_fbstatuses_for_update'>+</div>";
			
			echo "<div style='clear:both;'></div><div class='_fbstatusesupdate1' style='display:block;'>";
			echo "<br/><label>Enter a facebook status that people should post to unlock coupon.</label><textarea value=''></textarea>";
			echo "</div>";
			
			echo "<div style='clear:both;'></div><div class='_fbstatusesupdate2' style='display:none;'>";
			echo "<br/><label>Enter another facebook status that people should post to unlock coupon.</label><textarea value=''></textarea>";
			echo "</div>";
			
			echo "<div style='clear:both;'></div><div class='_fbstatusesupdate3' style='display:none;'>";
			echo "<br/><label>Enter another facebook status that people should post to unlock coupon.</label><textarea value=''></textarea>";
			echo "</div>";
			
			echo "<div style='clear:both;'></div><div class='_fbstatusesupdate4' style='display:none;'>";
			echo "<br/><label>Enter another facebook status that people should post to unlock coupon.</label><textarea value=''></textarea>";
			echo "</div>";
			
			echo "<div style='clear:both;'></div><div class='_fbstatusesupdate5' style='display:none;'>";
			echo "<br/><label>Enter another facebook status that people should post to unlock coupon.</label><textarea value=''></textarea>";
			echo "</div>";
			
			echo "<br/>";
			echo "</section></div>";
			
		echo "			
			</section>
		";
		
	?>

	</fieldset>
	<div onclick="verify_dynamic_social_coupon_and_add($(this).closest('form'));"><green_button>Add Social Coupon</green_button></div>
	
	<div class="submit" style='display:none;'>
		<input type="submit" value="submit"></input>
	</div>
	
<?php echo $this->Form->end();//__('Add Social Coupon')); ?>
</div>
<!--div class="actions">
	<h3><?php //echo __('Actions'); ?></h3>
	<ul>

		<li><?php //echo $this->Html->link(__('List Open Campaigns'), array('action' => 'index')); ?></li>
	</ul>
</div-->
<div class="oc_activate_result" style="display:none"></div>
<div class="opencampaign_details_click_response" style="display:none"></div>

<?php
echo "
<div class='_iccp' style='display:none'>
	{$iccp}
</div>
<div class='_vccp' style='display:none'>
	{$vccp}
</div>
";
?>