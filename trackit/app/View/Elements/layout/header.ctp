<?php
//echo $this->Html->script('custom/fb_pic_import');
?>
<script type="text/javascript">

function _call_photo_import_from_fb_page()
{
	params = [];
	params['script'] = 'custom/fb_pic_import';
	params['call_to_load'] = 'photo_import_from_fb_page';
	params['scn'] = 'photo_import_from_fb_page';
	params['scp'] = 0;
	params['ecn'] = 'show_success_message';
	params['ecp'] = "Umm... something didn't work. Reload page and try again !";
	
	script_load_and_call(params);
}

function _call_photo_import_from_fb_account()
{
	params = [];
	params['script'] = 'custom/fb_pic_import';
	params['call_to_load'] = 'photo_import_from_fb_account';
	params['scn'] = 'photo_import_from_fb_account';
	params['scp'] = 0;
	params['ecn'] = 'show_success_message';
	params['ecp'] = "Umm... something didn't work. Reload page and try again !";
	
	script_load_and_call(params);
}

function _call_video_import_from_fb_page()
{
	params = [];
	params['script'] = 'fbs/fb_vdo_import';
	params['call_to_load'] = 'vdo_import_from_fb_page';
	params['scn'] = 'vdo_import_from_fb_page';
	params['scp'] = 0;
	params['ecn'] = 'show_success_message';
	params['ecp'] = "Umm... something didn't work. Reload page and try again !";
	
	script_load_and_call(params);
}

function _call_video_import_from_fb_account()
{
	params = [];
	params['script'] = 'fbs/fb_vdo_import';
	params['call_to_load'] = 'vdo_import_from_fb_account';
	params['scn'] = 'vdo_import_from_fb_account';
	params['scp'] = 0;
	params['ecn'] = 'show_success_message';
	params['ecp'] = "Umm... something didn't work. Reload page and try again !";
	
	script_load_and_call(params);
}


function _header_ctp_fb_img_import_for_ad()
{
	$html = ""
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a onclick='_call_photo_import_from_fb_page()' style=''>Import From FB Page</a>"
			+"</div>"
			+"<div style='clear:both'></div>"
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a onclick='_call_photo_import_from_fb_account()' style=''>Import From FB Account</a>"
			+"</div>"
			+"";
	
	show_success_message($html);
	reposition_in_center('div.success_msg');
	reposition_in_center_width('div.success_msg');
	fit_to_inner_content('div.success_msg');
}

function _header_ctp_fb_vdo_import_for_ad()
{
	$html = ""
			+"<div style='clear:both'></div>"
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a onclick='_call_video_import_from_fb_page()' style=''>Import videos From FB Page</a>"
			+"</div>"
			+"<div style='clear:both'></div>"
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a onclick='_call_video_import_from_fb_account()' style=''>Import videos FB Account</a>"
			+"</div>"
			+"";
	
	show_success_message($html);
	reposition_in_center('div.success_msg');
	reposition_in_center_width('div.success_msg');
	fit_to_inner_content('div.success_msg');
}

function _header_ctp_manage_ad_content()
{
	$html = ""
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a href=\""+ $S_N+"contents/view_by_company\" style=''>View Advertising Content</a>"
			+"</div>"
			+"<div style='clear:both'></div>"
			+"<!--div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a href=\""+ $S_N+"contents/add\" style=''>Add Advertising Content</a>"
			+"</div-->"
			+"<div style='clear:both'></div>"
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a onclick='_header_ctp_fb_img_import_for_ad()' style=''>Import Photos From FB</a>"
			+"</div>"
			+"<div style='clear:both'></div>"
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a onclick='_header_ctp_fb_vdo_import_for_ad()' style=''>Import Videos From FB</a>"
			+"</div>"
			+"";
	
	show_success_message($html);
	reposition_in_center('div.success_msg');
	reposition_in_center_width('div.success_msg');
	fit_to_inner_content('div.success_msg');
}

function _header_ctp_manage_ad_campaign(){
	moveTo($S_N+"open_campaigns/index");return;
	/*
	$html = ""
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a href=\""+ $S_N+"contents/fixed_offers_by_company\" style=''>Simple Coupon Ads</a>"
			+"</div>"
			+"<div style='clear:both'></div>"
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a href=\""+ $S_N+"open_campaigns/index\" style=''>User Customizable Coupon Ads</a>"
			+"</div>"
			+"";
	
	show_success_message($html);
	reposition_in_center('div.success_msg');
	reposition_in_center_width('div.success_msg');
	fit_to_inner_content('div.success_msg');*/
}

function _header_ctp_create_ad_campaign(){
	moveTo($S_N+"open_campaigns/add");return;
	/*
	$html = ""
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a href=\""+ $S_N+"contents/add_static_coupon\" style=''>Create Simple Coupon Ad</a>"
			+"</div>"
			+"<div style='clear:both;'></div>"
			+"<div class='header_tabs' style='color:white;margin:3px;'>"
			+	"<a href=\""+ $S_N+"open_campaigns/add\" style=''>Create Customizable Coupon Ad</a>"
			+"</div>"
			+"";
	
	show_success_message($html);
	reposition_in_center('div.success_msg');
	reposition_in_center_width('div.success_msg');
	fit_to_inner_content('div.success_msg');*/
}

function _header_ctp_check_campaign_page()
{
	$url = $S_N+"open_campaigns/running_campaigns"
	moveTo($url);
}

function _header_ctp_check_promotions_page()
{
	$url = $S_N+"promotions/index"
	moveTo($url);
}

function _header_ctp_login_menu($this,$show)
{
	if(1==$show){
		$d=$this.find('._sub_login').css('display');
		if($d=='none'){
			$this.find('._main_text').css('display', 'none');
			$this.find('._sub_login').fadeIn('slow');
		}
	}else if(0==$show){
		$this.find('._main_text').css('display', 'block');
		$this.find('._sub_login').css('display', 'none');
	}
}

</script>

<?php

App::import('Component', 'UserData');
//$session = new SessionComponent(new ComponentCollection());
$UserDataComp = new UserDataComponent(new ComponentCollection());
//$UserDataComp->load_session();
$welcome_name = $UserDataComp->getWelcomeName();

/*$welcome_name = $user_data_component->getWelcomeName();
debug($welcome_name);
$session = new SessionComponent(new ComponentCollection());
$u = $session->read('user');
debug($u);*/
//$welcome_name = $preset_var_welcome_name;
?>

<?php
$collection_link = SITE_NAME . "collections/my";
$track_item_link = SITE_NAME . "socials/trackproduct";
		
if (empty($welcome_name))
{
echo "
<div class='header_tabs'>
	<a href=\"".SITE_NAME."companies/merchant_suggestions\" style=''>Suggest A Merchant</a>
</div>
<!--div onclick='show_user_register_form();' class='header_tabs'>
	Register
</div-->
<div onmouseover='_header_ctp_login_menu($(this),1);' class='header_tabs'>
	<span class='_main_text'>Sign-in</span>
	<span class='_sub_login' style='display:none;'>
		<span class='blk_underline' onclick='show_user_login_form();' style='margin-top:10px;'>User Sign-in</span><br/>
		<br/>
		<span class='blk_underline' onclick='show_user_login_form(1);' style='margin-top:10px;'>Business Sign-in</span>
	</span>
</div>
<div class='header_tabs'>
	<span class='blk_underline'>
		<span class='_trk_btn' onmouseover=\"$(this).find('._trk_links').fadeTo('slow',1);\" style='text-align:center;display:block;'>
		Tracker
		&nbsp;<br/><br/>
			<span class='_trk_links' style='font-size:12px;display:block;opacity:0;text-align:left;'>
				<a class='blk_underline' href=\"{$track_item_link}\">Track an Item</a>&nbsp;
				<br/><br/>
				<a class='blk_underline' href=\"{$collection_link}\">Collection</a>&nbsp;
				<br/>
			</span>
		</span>
	</span>
</div>
<div class='header_tabs'>
	<a href=\"".SITE_NAME."discounts/awesome\" style=''>Discounts</a>
</div>
<div class='header_tabs'>
	<a href=\"".SITE_NAME."discounts/products\" style=''>Items</a>
</div>

";

}
else
{
$feedback_url = SITE_NAME."contacts/speak_with_us";
$account_url = SITE_NAME."companies/edit";
$user_type = $UserDataComp->getUserType();
$user_is_admin = $UserDataComp->isAdminInDB();

echo "<div style='float:right;'>
<!--div onclick='logout_user();' class='header_tabs'>
	logout
</div-->
<div class='header_tabs' style='min-width:182px;' onmouseover=\"$(this).find('._welcome_sub_logout_btn').fadeIn('slow');\">
	Welcome {$welcome_name}<br/>
	<br/>";
	if ($user_type == 'company'){
	echo "<span class='blk_underline'><span onclick=\"moveTo('{$account_url}');\" class='_welcome_sub_logout_btn' style='display:none;'>Account</span></span>
	&nbsp;";
	}
	
	if ($user_is_admin){
		$set_admin_mode_link = SITE_NAME . "aops/setamode";
		$admin_menu_link = SITE_NAME . "aops/menu";
		echo "<a style='font-size:12px;display:none;color:black;' class='_welcome_sub_logout_btn' href=\"{$set_admin_mode_link}\">Set Admin Mode</a><br/>";
		echo "<a style='font-size:12px;display:none;color:black;' class='_welcome_sub_logout_btn' href=\"{$admin_menu_link}\">Admin Menu</a><br/>";
	}
	
	echo "
	<span class='blk_underline'><span onclick='logout_user();' class='_welcome_sub_logout_btn' onmouseover=\"$(this).closest('.header_tabs').find('._welcome_sub_feedback').fadeIn('fast');\" style='display:none;'>Logout</span></span>
	&nbsp;
	<span class='blk_underline'><span class='_welcome_sub_feedback' style='font-size:12px;display:none;' onclick=\"moveTo('{$feedback_url}');\">Drop feedback</span></span>
	<br/>
</div></div>
";
	if (!empty($layout_var) && ('popup' == $layout_var))
	{
		// if we are in popup layout, then simply pass
	}
	else
	{
		
		if ($user_type == 'company')
		{
			$company = $UserDataComp->getCompanyData();
			if (!empty($company))
			{
				$create_punchcard_link = SITE_NAME."pcards/create";
				$list_punchcard_link = SITE_NAME."pcards/index";
				$lookup_punchcard_link = SITE_NAME."pcard_custs/lookup";
				
				$s = "
		<!--div class='header_tabs'>
			<a href='/trackit/contents/fixed_offers_by_company' style=''>Static SocialCoupons</a>
		</div-->
		<!--div class='header_tabs'>
			<a href='/trackit/contents/view_by_company' style=''>Advertising Content</a>
		</div>
		<div class='header_tabs'>
			<a href='/trackit/contents/add' style=''>Add Advertising Content</a>
		</div-->

		<!--div class='header_tabs'>
			<a href='/trackit/open_campaigns/add' style=''>Create Dynamic SocialCoupon</a>
		</div>

		<div class='header_tabs'>
			<a href='/trackit/contents/add_static_coupon' style=''>Create Static SocialCoupon</a>
		</div-->

		<!--div class='header_tabs'>
			<a href='/trackit/open_campaigns/index' style=''>Your Campaigns</a>
		</div-->

		<div class='header_tabs'>
			<a onclick='_header_ctp_manage_ad_campaign();' style=''>Dashboard</a>
		</div>

		<div class='header_tabs'>
			<a onclick='_header_ctp_create_ad_campaign();' style=''><span class='blk_underline'>Create Coupon Ads</span></a>
			<br/><br/>
			<span class='blk_underline'>
				<span class='_punchcard_btn' onmouseover=\"$(this).find('._punchcard_options').fadeIn('slow');\" style='display:block;'>
				Punchcards
				&nbsp;<br/>
					<span class='_punchcard_options' style='font-size:12px;display:none;'>
						<span class='blk_underline' onclick=\"moveTo('{$create_punchcard_link}');\">Create</span>&nbsp;
						<span class='blk_underline' onclick=\"moveTo('{$list_punchcard_link}');\">List</span>&nbsp;
						<span class='blk_underline' onclick=\"moveTo('{$lookup_punchcard_link}');\">Lookup</span>
					</span>
				</span>
			</span>
		</div>

		<div class='header_tabs'>
			<a onclick='_header_ctp_manage_ad_content();' style=''>Manage Advertising Content</a>
		</div>

		<!--div class='header_tabs'>
			<a onclick='_header_ctp_check_promotions_page();' style=''>Promotions</a>
		</div-->

		<div class='header_tabs'>
			<a onclick='_header_ctp_check_campaign_page();' style=''>Campaign Page</a>
		</div>

		";

				echo $s;
			}
			else
			{
				$s = "
		<div class='header_tabs'>
			<a href=\"".SITE_NAME."companies/add\" style=''>Add Your Company</a>
		</div>
		<div class='header_tabs'>
			<a onclick='_header_ctp_manage_ad_content();' style=''>Manage Advertising Content</a>
		</div>
		";
				echo $s;
			}
			
		}
		else if ($user_type == 'blogger')
		{
		/*	$blogger = $UserDataComp->getBloggerData();
			if (!empty($blogger))
			{
				$s = "
		<div class='header_tabs'>
			<a href='/trackit/user_products/my_tracks' style=''>Products I'm Tracking</a>
		</div>
		<div class='header_tabs'>
			<a href='/trackit/products/index' style=''>Check All Products</a>
		</div>
		<div class='header_tabs'>
			<a href='/trackit/user_products/notifications' style=''>Price Notifications</a>
		</div>
		";
				echo $s;
			}
			else
			{
				$s = "
		<div class='header_tabs'>
			<a href='/trackit/user_products/my_tracks' style=''>Products I'm Tracking</a>
		</div>
		<div class='header_tabs'>
			<a href='/trackit/products/index' style=''>Check All Products</a>
		</div>
		<div class='header_tabs'>
			<a href='/trackit/user_products/notifications' style=''>Price Notifications</a>
		</div>
		";
				echo $s;
			}
		*/
		/*
		$s = "
		<div class='header_tabs'>
			<a href='/products/user_view' style=''>See Stuff</a>
		</div>
		";
		*/
		$s = "
		<div class='header_tabs'>
			<a href=\"".SITE_NAME."companies/merchant_suggestions\" style=''>Suggest A Merchant</a>
		</div>
		<div class='header_tabs'>
			<span class='blk_underline'>
				<span class='_trk_btn' onmouseover=\"$(this).find('._trk_links').fadeTo('slow',1);\" style='text-align:center;display:block;'>
				Tracker
				&nbsp;<br/><br/>
					<span class='_trk_links' style='font-size:12px;display:block;opacity:0;text-align:left;'>
						<a class='blk_underline' href=\"{$track_item_link}\">Track an Item</a>&nbsp;
						<br/><br/>
						<a class='blk_underline' href=\"{$collection_link}\">Collection</a>&nbsp;
						<br/>
					</span>
				</span>
			</span>
		</div>
		<div class='header_tabs'>
			<a href=\"".SITE_NAME."oc_responses/my_promotions\" style=''>History</a>
		</div>
		<div class='header_tabs'>
			<a href=\"".SITE_NAME."discounts/awesome\" style=''>Discounts</a>
		</div>
		<div class='header_tabs'>
			<a href=\"".SITE_NAME."discounts/products\" style=''>Items</a>
		</div>
		<!--div class='header_tabs'>
			<a href=\"".SITE_NAME."open_campaigns/running_campaigns\" style=''>Share and Get Coupon</a>
		</div>
		<div class='header_tabs'>
			<a href=\"".SITE_NAME."companies/coupons\" style=''>Search Discounts</a>
		</div-->
		";
		echo $s;
		}
	}
	
}
?>

<div class='user_register_form'></div>
<div class='user_login_form'></div>