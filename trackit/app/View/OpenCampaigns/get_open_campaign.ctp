<?php 
echo $this->Html->css('ad_view');
echo $this->Html->script('custom/update_resps'); 
echo $this->Html->script('custom/createadv'); 
echo $this->Html->script('custom/shareforcoupon'); 
echo $this->Html->script('custom/coupons'); 
echo $this->Html->script('twinf'); 
echo $this->Html->script('custom/content_picker'); 
?>

<script type="text/javascript">
function RunOnLoad()
{
	reposition_in_center_width('div.__brand_promote_holder');
	$textarea = $('div.brand_promote').find('div.customize_title').find('textarea');
	if ("" != $.trim($textarea.val()))
	{
		update_title($textarea);
	}
	
	//MakeDragAndDrop('content_news_box_sidebar', 'adv_news_item_drop');
	//MakeDragAndDrop('content_news_box_sidebar', 'adv_image_item_drop');
	
	$content_roll = $('.content_side_bar');//$('.content_roll_left');
	$('.content_side_bar .image_bar .content_roll_left').css('overflow', 'scroll');
	$('.content_side_bar .news_bar .content_roll_left').css('overflow', 'scroll');
	window.content_roll_orig_top = $content_roll.offset().top;
	window.content_roll_orig_pos = $content_roll.css('position');
	
	$(window).scroll(function() {
		
		$content_roll = $('.content_side_bar');
		
		var scrollTop     = $(window).scrollTop();
		var elementOffset = $content_roll.offset().top;
		var distance      = (elementOffset - scrollTop);
		
		if ($content_roll.css('position') == 'fixed')
		{
			if (scrollTop < window.content_roll_orig_top)
			{
				$content_roll.css('top', window.content_roll_orig_top);
				$content_roll.css('position', window.content_roll_orig_pos);
			}
		}
		else
		{
			if (scrollTop > window.content_roll_orig_top)
			{
				$content_roll.css('position', 'fixed');
				$content_roll.css('top', '0');
			}
		}
    });
	
	<?php
	if ($is_coupon_open)
	{
		echo "_OpenCouponMsg();";
	}
	else
	{
		echo "
		_auto_select_singular_element_bar();
		_update_message();
		";
	}
	
	if (!empty($layout_var) && 'popup' == $layout_var)
	{   echo "
		var sl = $('.tag').offset().top;
		$('body').animate({ scrollTop: sl}, 300, function(){});
		";
	}
	
	?>
}

function _auto_select_singular_element_bar()
{
	var $elements = $('.image_bar').find('.content_news_box_sidebar');
	if ($elements && (1==$elements.length))
	{
		$elements.first().find('black_button').click();
	}
	
	var $elements = $('.news_bar').find('.content_news_box_sidebar');
	if ($elements && (1==$elements.length))
	{
		$elements.first().find('black_button').click();
	}
	else if ($elements && ($elements.length > 0))
	{
		//$elements.first().find('black_button').click();
		$('._pick_news_btn').css('display', 'block');
	}
}

function _update_message()
{
	$msg = $("div.result.msg").text();
	if ("" != $msg.trim())
	{
		show_success_message($msg);
		reposition_in_center('div.success_msg');
	}
}

function _OpenCouponMsg()
{
	//show_success_message("Coupon is already open to you.");
	var $html_to_show = $("div.coupon_code_tag").html();
	show_success_message($html_to_show);
	reposition_in_center('div.success_msg');
}

function __select_news_btn_click($this)
{
	$this.closest('div.content_news_box_sidebar').find('content_news').click();
	$('div.success_msg').fadeOut('slow', cb_clk());
	
}
function __select_image_btn_click($this)
{
	$this.closest('div.content_news_box_sidebar').find('content_image').find('img').click();
	$('div.success_msg').fadeOut('slow', cb_clk());
}
function __select_video_btn_click($this)
{
	$this.closest('div.content_news_box_sidebar').find('content_video').find('img').click();
	$('div.success_msg').fadeOut('slow', cb_clk());
}

</script>
<?php
function _get_content_picker_button_code($content_id, $on_select_evt, $on_deselect_evt)
{
	$s = "
		<div class='_selection_buttons' style='margin:2px;'>
			<div class='_to_select' onclick=\"{$on_select_evt}\">
				<black_button>Select.</black_button>
			</div>
			<div class='_selected' style=\"display:none;\" onclick=\"{$on_deselect_evt}\">
				<green_button style=\"border:'';\">Un-select.</green_button>
			</div>
		</div>
	";
	return $s;
}

function echo_video_picker($view_this, $content_data, $settings = array())
{
$ccp = $view_this->element('content/user_content_picker', array('contents' => $content_data, 'content_type'=> array('video'), 'show_only' => 'active', 'settings'=>$settings));
echo "
	<div class='_has_ccp'>
		<div class='ccp' style='display:none'>
		{$ccp}
		</div>
		<div>
			<div class='tag' onclick='disp_ccp($(this));'>Pick Video</div>
		</div>	
	</div>
	";
}

function echo_image_picker($view_this, $content_data, $settings = array())
{
$ccp = $view_this->element('content/user_content_picker', array('contents' => $content_data, 'content_type'=> array('image'), 'show_only' => 'active', 'settings'=>$settings));
echo "
	<div class='_has_ccp'>
		<div class='ccp' style='display:none'>
		{$ccp}
		</div>
		<div>
			<div class='tag' onclick='disp_ccp($(this));'>Pick Image</div>
		</div>	
	</div>
	";
}

function echo_news_picker($view_this, $content_data)
{
$ccp = $view_this->element('content/user_content_picker', array('contents' => $content_data, 'content_type'=> array('news'), 'show_only' => 'active'));
echo "
	<div class='_has_ccp'>
		<div class='ccp' style='display:none'>
		{$ccp}
		</div>
		<div class='_pick_news_btn' style='display:none'><div>
			<div class='tag' onclick='disp_ccp($(this));'>Pick a Link</div>
		</div></div>
	</div>
	";
}

?>
<?php
/*
$cutter_src = SITE_NAME . "img/cutter.jpg";
echo "<div class='_ad_template'>
		<img style='left:-16px;top:50%;position:absolute;' src=\"{$cutter_src}\"></img>				
		<span class='_ad_template_bg' style='top:40px;left:170px;font-size:350px;color:rgba(19, 202, 19, 0.63);font-family:Helvetica sans-serif;'>
				$
		</span>
</div>";
*/
?>

<?php
//$is_coupon_open = false;
if (empty($is_coupon_open)) 
{
	$is_coupon_open = false;
}

{
	$msg = "";
	if (!empty($result))
	{
		if(!empty($result['msg']))
		{
			$msg = $result['msg'];
		}
	}
	echo
		"
		<div class='result' style ='display:none'>
			<div class='msg'>
				{$msg}
			</div>
		</div>
		";
}

$company_name = $company['Company']['name'];

?>

<div> <!--class="companies view"-->
<h2><?php echo h($company['Company']['name']); ?></h2>
<?php echo "<span style='cursor:pointer;' onmouseout=\"$(this).css('border-bottom', '3px solid white');\" onmouseover=\"$(this).css('border-bottom', '3px solid rgba(0,0,0,0.5)');\">{$company['Company']['website']}</span>"; ?>

<div style="float:right; text-align:right;" href="get_open_campaigns">
<?php 
	if (!empty($layout_var) && 'popup' == $layout_var)
	{
		$company_running_campaign_url = SITE_NAME . "open_campaigns/running_campaigns?c={$company['Company']['id']}&layout=popup";
	}
	else
	{
		$company_running_campaign_url = SITE_NAME . "open_campaigns/running_campaigns?c={$company['Company']['id']}";
	}
	echo "
	<div class='tag' style='height:auto;' onclick=\"moveTo(&quot;{$company_running_campaign_url}&quot;)\">
	More Coupons by {$company['Company']['name']}
	</div>
	";
	/*
	echo "Like {$company['Company']['name']} ?";
	echo "<br/>";
	echo "Used {$company['Company']['name']}'s products ?";
	echo "<br/>";
	echo "Worked at {$company['Company']['name']} ?";
	echo "<br/>";
	echo $company['Company']['name'] . " is looking for promoters.";
	echo "<br/>";
	echo $this->Html->link(__('See how you and '.$company['Company']['name'].' can help each other.'), array('action' => 'get_open_campaigns', $company['Company']['id']));
	*/
?>
</div>

	
</div>

<?php

echo "<div style='float:left;'>";
	//echo "<ul>";
	/*	
		if (!empty($product_data))
		{
			echo "<li>";
			echo $this->Html->link(__('Products by '. $company['Company']['name']), array('controller' => 'products', 'action' => 'view_by_company', $company['Company']['id'])); 
			echo "</li>";
		}
		$link = SITE_NAME."contents/view_by_company/{$company['Company']['id']}";
		$onclick_evt = "OpenInNewTab('{$link}');";
		echo "<div class='tag' onclick=\"{$onclick_evt}\">";
			echo "What {$company['Company']['name']} Does ?";
			//echo $this->Html->link(__('View stuff related to this company'), array('controller' => 'contents', 'action' => 'view_by_company', $company['Company']['id'])); 
		echo "</div>";
	*/	
		echo "<br/>";
		
		if (!empty($company['Company']['support_type']))
		{
			echo "<div class='greyed_tag'>";
				$support_type = $company['Company']['support_type'];
				if ($support_type == 'means_support')
				{
					echo "Actively supports UN goals.";
				}
				else if ($support_type == 'no_means_support')
				{
					echo "Doesn't have sufficient means, but still supports UN goals.";
				}
				if ($support_type == 'support_when_means')
				{
					echo "Is committed to support UN goals when it would have sufficient means.";
				}
			echo "</div>";
			
			$goal_ids = array();
			$goal_ids[] = $company['Company']['un_goal1'];
			$goal_ids[] = $company['Company']['un_goal2'];
			$goal_ids[] = $company['Company']['un_goal3'];
			$goal_ids[] = $company['Company']['un_goal4'];
			$goal_ids[] = $company['Company']['un_goal5'];
			
			foreach($goal_ids as $index => $goal_id)
			{
				if (empty($goal_id)) { continue;}
				$goal = $goals[$goal_id];
				$link = SITE_NAME . $goal['img'];
				echo "<div class='goal_image_homepage' onclick='goal_change(this, {$goal_id})'>";
					echo "<goal_image>";
						echo "Favourite UN Goal";
						echo "<img src='{$link}'></img>";
						echo "<br/>";
						//echo "<div class='tag'>";
						echo "<goal_name>";
							echo $goal['name'];
						echo "</goal_name>";
						echo "<div class='goal_id' style='display:none'>";
							echo $goal_id;
						echo "</div>";
						//echo "</div>";
					echo "</goal_image>";
				echo "</div>";
			}
		}
		
	//echo "</ul>";
echo "</div>";

echo "<div style='clear:both;'></div>";

echo "<br/>";

echo "<div style='display:none'>";

echo "<div class='content_side_bar' style='width:auto; position:absolute;'>";

echo "<div class='video_bar'>";
echo "<div class='content_roll_left' style=\"overflow:scroll;\">";
echo $this->element('content/company_content_grid_sidebar', array('contents' => $contents, 'content_type'=>'video'));
echo "</div>";
echo "</div>";

echo "<div class='image_bar'>";
echo "<div class='content_roll_left' style=\"overflow:scroll;\">";
echo $this->element('content/company_content_grid_sidebar', array('contents' => $contents, 'content_type'=>'image'));
echo "</div>";
echo "</div>";

echo "<div class='news_bar'>";
echo "<div class='content_roll_left' style=\"position:relative; overflow:scroll;\">";
echo $this->element('content/company_content_grid_sidebar', array('contents' => $contents, 'content_type'=>'news'));
echo "</div>";
echo "</div>";

echo "</div>";

echo "</div>";

echo "<div style=\"/*margin:auto;*/ width:500px; clear:both;\">";
//echo "<div class='__brand_promote_holder' style=\"background-color:antiquewhite;\">";

if (empty($ocs))
{
	echo "<div class=\"brand_promote\">";
	//echo "Looks like you haven't created any Advertising campaign yet. ";
	echo "Looks like none of {$company['Company']['name']}'s campaign is running right now. Please check back after sometime.";
	echo "<br/>";
	//echo "<a href='/AB/open_campaigns/add' style=''> Lets start by adding an Adv Campaign </a>";
	echo "Also, if you can follow {$company['Company']['name']} to be informed once they launch a campaign.";
	echo "</div>";
}

foreach($ocs as $index =>$oc)
{	
	
	if ('fb_like_pic' == $oc['OpenCampaign']['type']
		|| 'fb_like_page' == $oc['OpenCampaign']['type']
		|| 'fb_like_video' == $oc['OpenCampaign']['type']
	)
	{
		echo $this->Html->script('fbs/fch'); 
		echo $this->Html->script('fbs/fblike');
		echo $this->Html->script('fbs/fbperms');
	}
	else if ('fb_post_video' == $oc['OpenCampaign']['type'])
	{
		echo $this->Html->script('fbs/fch'); 
		echo $this->Html->script('fbs/fbpostvid');
		echo $this->Html->script('fbs/fbperms');
		//echo $this->Html->script('vids/vtnails');
	}
	else if ('fb_event_join' == $oc['OpenCampaign']['type'])
	{
		echo $this->Html->script('fbs/fch'); 
		echo $this->Html->script('fbs/fb_evt_inf');
		echo $this->Html->script('fbs/fbperms');
		//echo $this->Html->script('vids/vtnails');
	}
	else if ('fb_event_share' == $oc['OpenCampaign']['type'])
	{
		echo $this->Html->script('fbs/fch'); 
		echo $this->Html->script('fbs/fb_ls_inf');
		echo $this->Html->script('fbs/fbperms');
	}
	else if ('single_email_ns_signup' == $oc['OpenCampaign']['type']
			|| 'dual_email_ns_signup' == $oc['OpenCampaign']['type']
	)
	{
		echo $this->Html->script('nssign/nsch'); 
		//echo $this->Html->script('nssign/signup');
		echo $this->Html->script('ue/extraemgmt');
	}
	else if ('giveaway' == $oc['OpenCampaign']['type'])
	{
		echo $this->Html->script('giveaway/gawy');
	}
	else if ('yelp_review' == $oc['OpenCampaign']['type'])
	{
		echo $this->Html->script('rvw/yr');
	}
	
	$oc_id = $oc['OpenCampaign']['id'];
	$company_id = $oc['OpenCampaign']['company_id'];
	$coupon_code = $oc['OpenCampaign']['coupon_code'];
	$coupon_line = $oc['OpenCampaign']['coupon_line'];
	$coupon_worth = $oc['OpenCampaign']['coupon_worth'];
	$coupon_worth_cur = $oc['OpenCampaign']['coupon_worth_cur'];
	$coupon_details = $oc['OpenCampaign']['coupon_details'];
	$coupon_valid_until_date = $oc['OpenCampaign']['coupon_valid_until_date'];
	$coupon_type = $oc['OpenCampaign']['coupon_type'];
	
	$default_title = $oc['OpenCampaign']['default_title'];
	$default_link = $this->CommonFunc->addhttp($oc['OpenCampaign']['default_link']);
	$default_desc = $oc['OpenCampaign']['default_desc'];
	
	$promo_type = $oc['OpenCampaign']['type'];
	$tip_msg = "";
	
	if ('fb_post' == $promo_type){
		$tip_msg = "Spread The Word By Sharing On Facebook And Unlock Coupon.";
	}else if ('fb_like_pic' == $promo_type){
		$tip_msg = "Spread The Word By Liking A Pic Of Your Choice And Unlock Coupon.";
	}else if ('fb_like_video' == $promo_type){
		$tip_msg = "Spread The Word By Liking A Video Of Your Choice And Unlock Coupon.";
	}else if ('fb_post_video' == $promo_type){
		$tip_msg = "Spread The Word By Sharing \"{$company_name}'s\" Video And Unlock Coupon.";
	}else if ('fb_like_page' == $promo_type){
		$tip_msg = "Spread The Word By Liking \"{$company_name}'s\" Facebook Page And Unlock Coupon.";
	}else if ('fb_event_share' == $promo_type){
		$tip_msg = "Spread The Word By Sharing \"{$company_name}'s\" Event And Unlock Coupon.";
	}else if ('fb_event_join' == $promo_type){
		$tip_msg = "Join \"{$company_name}'s\" Event And Unlock Coupon.";
	}else if ('tw' == $promo_type || 'tweet' == $promo_type){
		$tip_msg = "Spread The Word By Sharing A Tweet And Unlock Coupon.";
	}else if ('single_email_ns_signup' == $promo_type){
		$tip_msg = "Sign Up To Newsletter of '{$company['Company']['name']}' And Unlock Coupon.";
	}else if ('dual_email_ns_signup' == $promo_type || 'tweet' == $promo_type){
		$tip_msg = "Sign Up To Newsletter of '{$company['Company']['name']}' With 2 Emails And Unlock Coupon.";
	}else if ('yelp_review' == $promo_type){
		$tip_msg = "Enter link to your Yelp review for a 50% chance to win the coupon. (To find link to your review, hover next to your name in your yelp review, click on share review, a link will appear in popup, copy that link and past here.)";
	}else if ('giveaway' == $promo_type){
		$tip_msg = "When you save this coupon, we will email it to you as well.";
	}
	
	echo "<div class=\"brand_promote\">";
	
	
	$cur_sign = "";
	if ('dollar_off' == $coupon_type){
		if ('usd' == $coupon_worth_cur){$cur_sign = "$";}
	}
	
	$cur_worths = explode('.',$coupon_worth);
	$dol_amt = $cur_worths[0];
	// calc cent amount
	if ('percent_off' == $coupon_type){
		$cent_amt = "%";
	}
	else {
		if(1==count($cur_worths)){$cent_amt = "00";}
		else{$cent_amt=$cur_worths[1];
			if(1==strlen($cent_amt)) {$cent_amt = "0".$cent_amt;}
		}
	}
	
	$cutter_src = SITE_NAME . "img/cutter.jpg";
	echo "<div class='_ad_template'>
			<img style='z-index:-2;left:-14px;top:50%;position:absolute;' src=\"{$cutter_src}\"></img>				
			<span class='_ad_template_bg' style='top:40px;left:400px;font-size:350px;color:rgba(19, 202, 19, 0.56);font-family:Helvetica sans-serif;'>
			";
	if ('dollar_off' == $coupon_type) {echo "$";} 
	else if ('percent_off' == $coupon_type) {echo "%";}
	else {echo "$";}
	
	echo	"</span>
			<div class='_left_print'>
				<div>
				<span class='_dol_sign'>{$cur_sign}</span>
				<span style='line-height:200px;'>
				";
	
	if (intval($dol_amt)<10)
	{
		echo		"<span class='_dol_amount'>{$dol_amt}</span>";	
	}
	else
	{
		echo		"<span class='_dol_amount' style='font-size:100px;'>{$dol_amt}</span>";
	}
	echo 			"<span class='_cent_amount'>{$cent_amt}</span>
					<span style='font-size:40px;bottom:40px;position:absolute;'>OFF</span>
				</span>
				</div></span>
				<div class='_main_line'>{$coupon_line}</div>
			</div>
			<div class='_right_print'>
			<div class='_clogo'>{$company['Company']['name']}</div>
			<div class='_fine_print'>
				{$coupon_details}
				<p>
					Valid until : {$coupon_valid_until_date}&nbsp&nbsp(yyyy-mm-dd)
				</p>
			</div>
			</div>
			
		";

	
	echo "<div class='hidden_oc_id' style='display:none'>{$oc_id}</div>";	
	echo "<div class='hidden_comp_id' style='display:none'>{$company_id}</div>";	
	echo "<div class='_hidden_octype' style='display:none'>{$oc['OpenCampaign']['type']}</div>";
	
	if (!empty($is_coupon_open) || ('giveaway'==$oc['OpenCampaign']['type']))
	{
		echo "
			<div class='coupon_code_tag' style='display:block;'>
			";
	}
	else
	{
		echo "
			<div class='coupon_code_tag' style='display:none;'>
			";
	}	
	
	echo "
		<div class='tag'>
				Coupon Code - 
				<div class='coupon_code'>
				{$coupon_code}
				</div>
			</div>
		</div>
		";
	
	if(empty($is_coupon_open) || ('giveaway'==$oc['OpenCampaign']['type'])){
		echo "
			<div style='margin-bottom:3px;padding:10px;color:white;background-color:rgba(0,0,0,0.8);text-transform:uppercase;font-size:12px;font-family:Arial;font-weight:bold;'>
			{$tip_msg}
			</div>
		";
	}
	
	if (false && $oc['OpenCampaign']['type'] == 'blog')
	{
		echo "<div style=\"float:left; margin-top:5%\">";
			echo "on your Blog";
		echo "</div>";
	}
	else if (false && $oc['OpenCampaign']['type'] == 'fb_post')
	{
		echo "<div style=\"float:left; margin-top:5%\">";
			echo "on Facebook ";
		echo "</div>";
		echo "<div style='clear:both'>";
		echo "by mixing and matching image/news from left with your feedback and posting it on Facebook.";
		/*
		echo "<br/>";
		//echo "with hashtag #{$company['Company']['name']} and give it a public visibility.";
		echo "At the end of campaign, rewards will be given based on minimum conditions set. In case of tie, the post created first will be prioritized.";
		*/
		echo "</div>";
	}
	else if (false && ($oc['OpenCampaign']['type'] == 'tw' || $oc['OpenCampaign']['type'] == 'tweet'))
	{
		echo "<div style=\"float:left; margin-top:5%\">";
			echo "on Twitter ";
		echo "</div>";
		echo "<div style='clear:both'>";
		echo "by choosing an image and a news from left and adding your feedback and tweet-ing it.";
		/*
		echo "<br/>";
		//echo "with hashtag #{$company['Company']['name']} and give it a public visibility.";
		echo "At the end of campaign, rewards will be given based on minimum conditions set. In case of tie, the post created first will be prioritized.";
		*/
		echo "</div>";
	}
	
	echo "<div style=\"clear:both\"> </div>";
	
	echo "<div class='_after_open_fadeout' style='margin-bottom:10px;width:500px;position:relative;left:110px;border:2px solid rgba(0,0,0,0.4);'>";
	
		echo "
			<div style='display:none;' class='_default_share_info'>
				<default_title>{$default_title}</default_title>
				<default_link>{$default_link}</default_link>
				<default_desc>{$default_desc}</default_desc>
			</div>
		";
		
		if ($is_coupon_open && ('giveaway'!=$oc['OpenCampaign']['type']))
		{
			/*
			echo "
				<div class='info_button' style='width:200px;' onclick='point_wise_message($(this));'>
				Info - how to unlock this offer.
					<div class='how_to_messages' style='display:none'>
						<div>You have already earned this coupon, and the coupon is open to you. No action required.</div>
						<div>Coupon Code - {$coupon_code}</div>
					</div>					
				</div>
			";
			echo "<div style=\"height:400px;\"></div>";
			*/
		}
		else if ($oc['OpenCampaign']['type'] == 'blog')
		{
			echo "<div style=\"width:400px; /*margin-left:5%; margin-right:5%;*/\">";
				echo "<br/>";
				echo "Created a Blog, got the comments, looking to redeem ?";
				echo "<br/>";
				echo "Enter the direct link to your 'blog post' below:";
				echo "<input id='blogpost_link{$oc_id}'> </input>";
				echo "<div id='redeem_result{$oc_id}'></div>";
				//echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"send_blog_response({$oc_id})\">Redeem</button>";
				echo "<button class=\"redeem_button\" onclick=\"send_blog_response({$oc_id})\">Redeem</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_post')
		{
			
			echo "<div style='clear:both;'></div>";
			//echo "<div style=\"margin:'auto';text-align:'center';width:'auto';\">";
			echo "<div style=\"padding:0px; margin:0px; max-width:300px;\">";
				echo "<span style='display:inline-block; float:left;'>";
					echo_image_picker($this, $contents);
				echo "</span>";
				echo "<span style='display:inline-block; float:left;'>";
					echo_news_picker($this, $contents);
				echo "</span>";
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			
			
			echo "<div style=\"min-width:400px;\">";
				
				echo "<div class='adv_image_item_drop'>";
					echo "Pick an image to link with your facebook post.";
				echo "</div>";
				echo "<div class='adv_news_item_drop' style='display:none;'>";
					//echo "Pick a news from left, to link with your facebook post.";
					echo "<adv_box>
							<div class='adv_title_customize'>
								
							</div>
						</adv_box>";
				echo "</div>";
			
				echo "
				<div style='clear:both;'></div>
					<div class='customize_title'>
						<label>Write oneline on {$company['Company']['name']} or their products.</label>
						<textarea onkeyup='update_title($(this));'></textarea>
					</div>
					
				<div style='clear:both;'></div>
				";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_fb_post_to_get_dynamic_coupon($(this), {$is_user_logged_in});";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "
					<black_button onclick=\"{$onclickevt}\">Share On Facebook And Unlock Coupon</black_button>
				";
				//echo "<input id='fb_post_content{$oc_id}'> </input>";
				//echo "<div id='post_on_fb{$oc_id}'></div>";
				//echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"post_on_facebook({$oc_id})\">Post on Facebook</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_like_pic')
		{
			//$fbpageurl = $this->CommonFunc->addhttp($oc['OpenCampaign']['fbobjecturl']);
			//$fbobjectid = $oc['OpenCampaign']['fbobjectid'];
			
			//$desc = $oc['OpenCampaign']['default_desc'];
			//$page_props = json_decode($desc, true);
			
			echo "<div style='clear:both;'></div>";
			
			echo "<div class='adv_foinfo'>
					<div class='_likeobjurl' style='display:none'></div>
					<div class='_likeobjid' style='display:none'></div>
				</div>";
			
			//echo "<div style=\"margin:'auto';text-align:'center';width:'auto';\">";
			echo "<div style=\"padding:0; margin:auto; max-width:300px;\">";
				echo "<span style='display:inline-block; float:left;'>";
					echo_image_picker($this, $contents, array('show_with_fbid_only' => true));
				echo "</span>";
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			
			
			echo "<div style=\"width:400px;\">";
				
				echo "<div class='adv_image_item_drop'>";
					echo "Pick an image to like.";
				echo "</div>";
				
				echo "<div style='clear:both;'></div><br/>";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_picltgdc($(this), {$is_user_logged_in});";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "
					<black_button onclick=\"{$onclickevt}\">Like On Facebook And Unlock Coupon</black_button>
				";
				//echo "<input id='fb_post_content{$oc_id}'> </input>";
				//echo "<div id='post_on_fb{$oc_id}'></div>";
				//echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"post_on_facebook({$oc_id})\">Post on Facebook</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_like_page')
		{
			$fbpageurl = $this->CommonFunc->addhttp($oc['OpenCampaign']['default_link']);
			$desc = $oc['OpenCampaign']['default_desc'];
			$page_props = json_decode($desc, true);
			
			echo "<div style='clear:both;'></div><br/>";
			
			//echo "<div class='_likeobjurl' style='display:none'>{$fbpageurl}</div>";
			//echo "<div class='_likeobjid' style='display:none'>{$page_props['id']}</div>";
			
			echo "<div class='adv_foinfo'>
					<div class='_likeobjurl' style='display:none'>{$fbpageurl}</div>
					<div class='_likeobjid' style='display:none'>{$page_props['id']}</div>
				</div>";
				
			echo "<br/><br/><a target='_blank' href=\"{$fbpageurl}\"><red_button>Check out Facebook Page</red_button></a>";
			
			echo "<div style='clear:both;'></div><br/><br/>";
			
			echo "<div style=\"width:400px;\">";
				
				//$likehtml = "<div class=\"fb-like\" data-href=\"{$fbpageurl}\" data-width=\"300\" data-height:\"100\" data-layout=\"standard\" data-action=\"like\" data-show-faces=\"false\" data-share=\"false\"></div>";
				//echo $likehtml;
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_pageltgdc($(this), {$is_user_logged_in});";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "<div style='clear:both;'></div><br/><br/>";
				
				echo "
					<div class='_unlockbtn'><black_button onclick=\"{$onclickevt}\">Like On Facebook And Unlock Coupon</black_button></div>
					<br/>
				";
				//echo "<input id='fb_post_content{$oc_id}'> </input>";
				//echo "<div id='post_on_fb{$oc_id}'></div>";
				//echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"post_on_facebook({$oc_id})\">Post on Facebook</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_event_join')
		{
			$fbeventurl = $this->CommonFunc->addhttp($oc['OpenCampaign']['default_link']);
			$pos = strpos (strtolower($fbeventurl),'facebook.com/events/');
			$event_id = 0;
			if(FALSE !== $pos){
				$temp_u = substr($fbeventurl,$pos);// + strlen('facebook.com/events/'));
				$temp_exp = explode('/', $temp_u);
				$event_id = $temp_exp[2];
			}
			
			$desc = $oc['OpenCampaign']['default_desc'];
			$event_props = json_decode($desc, true);
			
			echo "<div style='clear:both;'></div><br/>";
			
			echo "<div class='adv_foinfo'>
					<div class='_joinevtu' style='display:none'>{$fbeventurl}</div>
					<div class='_joinevti' style='display:none'>{$event_id}</div>
				</div>";
				
			echo "<br/><br/><a target='_blank' href=\"{$fbeventurl}\"><red_button>Check out Facebook Event</red_button></a>";
			
			echo "<div style='clear:both;'></div><br/><br/>";
			
			echo "<div style=\"width:400px;\">";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_evtjtgdc($(this), {$is_user_logged_in});";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "<div style='clear:both;'></div><br/><br/>";
				
				echo "
					<div class='_unlockbtn'><black_button onclick=\"{$onclickevt}\">Joined Event? Unlock Coupon</black_button></div>
					<br/>
				";				
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_event_share')
		{
			$fbeventurl = $this->CommonFunc->addhttp($oc['OpenCampaign']['default_link']);
			$pos = strpos (strtolower($fbeventurl),'facebook.com/events/');
			$event_id = 0;
			if(FALSE !== $pos){
				$temp_u = substr($fbeventurl,$pos);// + strlen('facebook.com/events/'));
				$temp_exp = explode('/', $temp_u);
				$event_id = $temp_exp[2];
			}
			
			$desc = $oc['OpenCampaign']['default_desc'];
			$event_props = json_decode($desc, true);
			
			echo "<div style='clear:both;'></div><br/>";
			
			echo "<div class='adv_foinfo'>
					<div class='_joinevtu' style='display:none'>{$fbeventurl}</div>
					<div class='_joinevti' style='display:none'>{$event_id}</div>
				</div>";
				
			echo "<br/><br/><a target='_blank' href=\"{$fbeventurl}\"><red_button>Check out Facebook Event</red_button></a>";
			
			echo "<div style='clear:both;'></div><br/><br/>";
			
			echo "<div style=\"width:400px;\">";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_linkstgdc($(this), {$is_user_logged_in});";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "<div style='clear:both;'></div><br/><br/>";
				
				echo "
					<div class='_unlockbtn'><black_button onclick=\"{$onclickevt}\">Share Event and Unlock Coupon</black_button></div>
					<br/>
				";				
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_like_video')
		{
			echo "<div class='_linkonlypost'></div>";
			
			echo "<div style='clear:both;'></div>";
			
			echo "<div class='adv_foinfo'>
					<div class='_likeobjurl' style='display:none'></div>
					<div class='_likeobjid' style='display:none'></div>
				</div>";
			
			//echo "<div style=\"margin:'auto';text-align:'center';width:'auto';\">";
			echo "<div style=\"padding:0; margin:auto;max-width:300px;\">";
				echo "<span style='display:inline-block; float:left;'>";
					echo_video_picker($this, $contents, array('show_with_fbid_only' => true));
				echo "</span>";
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			
			
			echo "<div style=\"width:400px;\">";
				
				echo "<div class='adv_image_item_drop'>";
					echo "Pick a video to like.";
				echo "</div>";
				
				echo "<div style='clear:both;'></div><br/>";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_vidltgdc($(this), {$is_user_logged_in});";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "
					<div class='_unlockbtn'><black_button onclick=\"{$onclickevt}\">Like On Facebook And Unlock Coupon</black_button></div>
				";
				
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_post_video')
		{
			$video_link = $oc['OpenCampaign']['default_link'];
			$desc = $oc['OpenCampaign']['default_desc'];
			$desc = json_decode($desc, true);
			$thumbnail = "";
			if(!empty($desc['thumbnail'])){$thumbnail = $desc['thumbnail'];}
			
			echo "<div style='clear:both;'></div><br/>";
			
			echo "<div class='adv_foinfo'>
					<div class='_postobjurl' style='display:none'>{$video_link}</div>
					<div class='_postobjthumbnail' style='display:none'>{$thumbnail}</div>
				</div>";
			
			echo "<br/><br/><a target='_blank' href=\"{$video_link}\"><red_button>Check out the video</red_button></a>";
			
			if(!empty($vid_type))
			{
				$vid_embed_html = "";
				if ('youtube'==$vid_type){
					$vid_embed_html=$this->Youtube->video($video_link, array(), array('width'=>500));
				}else if('vimeo' == $vid_type){
					$vid_embed_html=$this->Vimeo->video($video_link, array('width'=>500, 'height'=>281));
				}
				//debug($vid_embed_html);
				echo $vid_embed_html;
			}
			
			echo "<div style='clear:both;'></div><br/><br/>";
			
			echo "<div style=\"width:400px;\">";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_vidptgdc($(this), {$is_user_logged_in});";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "<div style='clear:both;'></div><br/><br/>";
				echo "
					<div class='_unlockbtn'><black_button onclick=\"{$onclickevt}\">Post Video And Unlock Coupon</black_button></div>
					<br/>
				";
				
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'tw' || $oc['OpenCampaign']['type'] == 'tweet')
		{
			$default_title = $oc['OpenCampaign']['default_title'];
			if (empty($default_title))
			{
				$default_title = "I like {$company['Company']['name']} because I love it.";
			}
			
			echo "<div style='clear:both;'></div>";
			//echo "<div style=\"margin:'auto';text-align:'center';width:'auto';\">";
			echo "<div style=\"padding:0; margin:auto; max-width:300px;\">";
				echo "<span style='display:inline-block; float:left;'>";
					echo_image_picker($this, $contents);
				echo "</span>";
				echo "<span style='display:inline-block; float:left;'>";
					echo_news_picker($this, $contents);
				echo "</span>";
			echo "</div>";
			echo "<div style='clear:both;'></div>";
			
			echo "<div style=\"width:400px;\">";
				
				echo "<div class='adv_image_item_drop'>";
					echo "Pick an image to link with your tweet.";
				echo "</div>";
				echo "<div class='adv_news_item_drop' style='display:none'>";
					//echo "Pick a news from left, to link with your tweet.";
					echo "<adv_box>
							<div class='adv_title_customize'>
								{$default_desc}
							</div>
						</adv_box>";
				echo "</div>";
			
				echo "
				<div style='clear:both;'></div>
					<div class='customize_title'>
						<label>Write oneline on {$company['Company']['name']} or their products.</label>
						<textarea onkeyup='update_title($(this));'>{$default_title}</textarea>
					</div>
				<div style='clear:both;'></div>
				";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "initiate_tw_share_for_dynamic_coupon($(this), {$is_user_logged_in});";
					
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "
					<black_button onclick=\"{$onclickevt}\">Tweet It And Unlock Coupon</black_button>
				";
				//echo "<input id='fb_post_content{$oc_id}'> </input>";
				//echo "<div id='post_on_fb{$oc_id}'></div>";
				//echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"post_on_facebook({$oc_id})\">Post on Facebook</button>";
			echo "</div>";
			
			echo "<div style='display:none'>";
			echo $this->Form->create('user_actions', array('action' => 'tweetit_for_coupon'));
				echo $this->Form->input('post_action_redirect', array('type'=> 'text', 'default' => $this->CommonFunc->get_full_current_url(), 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo $this->Form->input('tw_oc_id', array('type'=> 'text', 'default' => $oc_id, 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo $this->Form->input('tw_coupon_code', array('type'=> 'text', 'default' => $coupon_code, 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo $this->Form->input('tw_is_custom', array('type'=> 'text', 'default' => '1', 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo $this->Form->input('tw_title', array('type'=> 'text', 'default' => '', 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo $this->Form->input('tw_image_link', array('type'=> 'text', 'default' => '', 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo $this->Form->input('tw_desc', array('type'=> 'text', 'default' => '', 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo $this->Form->input('tw_news_title', array('type'=> 'text', 'default' => '', 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo $this->Form->input('tw_news_link', array('type'=> 'text', 'default' => '', 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
				echo "<input type='submit' id='tw_button'>submit</input>";
			echo $this->Form->end();
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'single_email_ns_signup')
		{
			echo "<div style='clear:both;'></div>";
			echo "<div style=\"width:400px;\">";
				echo "
				<div style='clear:both;'></div>
					<div class='customize_title'>
						<label>Enter your email.</label>
						<textarea maxlength='40' class='_first_email'>{$user_email}</textarea>
					</div>
					<br/>
				<div style='clear:both;'></div>
				";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_enss_for_coupon($(this), {$is_user_logged_in},1);";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "
					<div class='_unlockbtn'><black_button onclick=\"{$onclickevt}\">Signup for Mail-list And Unlock Coupon</black_button></div>					
				";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'dual_email_ns_signup')
		{
			echo "<div style='clear:both;'></div>";
			echo "<div style=\"width:400px;\">";
				echo "
				<div style='clear:both;'></div>
					<div class='customize_title'>
						<label>Enter your first email.</label>
						<textarea maxlength='40' class='_first_email'>{$user_email}</textarea>
					</div>
					<br/>
					<div class='customize_title'>
						<label>Enter your second email.</label>
						<textarea maxlength='40' class='_second_email'></textarea>
					</div>
					<br/>
				<div style='clear:both;'></div>
				";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "call_enss_for_coupon($(this), {$is_user_logged_in},2);";
				}
				else
				{
					$onclickevt = "_OpenCouponMsg();";
				}
				
				echo "
					<div class='_unlockbtn'><black_button onclick=\"{$onclickevt}\">Signup for Mail-list And Unlock Coupon</black_button></div>
				";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'yelp_review')
		{
			echo "<div style='clear:both;'></div>";
			echo "<div style=\"width:400px;padding-top:15px;\">";
				echo "
				<div style='clear:both;'></div>					
					<div class='_yelp_review_link' style='padding:15px;padding-left:30px;'>
						<label>Link of your yelp review</label>
						<textarea maxlength='400' rows='3' class='_yelp_review_link_textarea' style='font-size:12px;'></textarea>
					</div>
					<br/>
				<div style='clear:both;'></div>
				";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "save_yr_coupon({$is_user_logged_in});";
					$blk_btn_txt = "<span style='cursor:pointer;' 
						onclick=\"{$onclickevt}\" 
						onmouseover=\"$(this).css('font-style', 'italic');\" 
						onmouseout=\"$(this).css('font-style', 'normal');\"
						>Enter in for a 50% chance to win Coupon.</span>";
				}
				else if(!empty($deferred_action))
				{
					$onclickevt = "";
					$blk_btn_txt = "You are already in for the chance to win Coupon!";
				}
				else
				{
					$onclickevt = "";
					$blk_btn_txt = "Coupon Emailed!";
				}
				
				echo "
					<div class='_unlockbtn'>
						<div style='background:rgba(0,0,0,0.8);color:white;font-size:14px;padding:15px;margin-left:30px;width:100%;'> 
						{$blk_btn_txt}
						</div>
					</div>
				";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'giveaway')
		{
			echo "<div style='clear:both;'></div>";
			echo "<div style=\"width:400px;\">";
				echo "
				<div style='clear:both;'></div>					
					<br/>
				<div style='clear:both;'></div>
				";
				
				if (empty($is_coupon_open))
				{
					$onclickevt = "save_giveaway_coupon({$is_user_logged_in});";
					$blk_btn_txt = "<span style='cursor:pointer;' 
						onclick=\"{$onclickevt}\" 
						onmouseover=\"$(this).css('font-style', 'italic');\" 
						onmouseout=\"$(this).css('font-style', 'normal');\"
						>Save and Get in Email</span>";
				}
				else
				{
					$onclickevt = "";
					$blk_btn_txt = "Saved and Emailed!";
				}
				
				echo "
					<div class='_unlockbtn'>
						<div style='background:rgba(0,0,0,0.8);color:white;font-size:14px;padding:15px;margin-left:30px;width:100%;'> 
						{$blk_btn_txt}
						</div>
					</div>
				";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'reddit')
		{
			echo "<div style=\"width:400px; /*margin-left:5%; margin-right:5%;*/\">";
				echo "<br/>";
				echo "Tell why {$company['Company']['name']} is a good company ";
				echo "<br/>";
				echo "by sharing a link, image or news about {$company['Company']['name']}.";
				echo "<br/>";
				echo "<div class='adv_news_item_drop'>";
					echo "Drag and drop here, a news item from left.";
				echo "</div>";
				echo "Enter the direct link to the comments page of your share on reddit:";
				echo "<input id='imgurpost_link{$oc_id}' style='width:80%'> </input>";
				echo "<div id='redeem_result{$oc_id}'></div>";
				echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"send_reddit_response({$oc_id})\">Update comment page link of reddit post & Redeem</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'imgur')
		{
			echo "<div style=\"width:400px; /*margin-left:5%; margin-right:5%;*/\">";
				echo "<br/>";
				echo "Share an image on <a target='_blank' href=\"http://www.imgur.com\">imgur</a> that shows why {$company['Company']['name']} is a good company ";
				echo "<br/>";
				echo "<div class='adv_news_item_drop'>";
					echo "Drag and drop here, a news item from left.";
				echo "</div>";
				echo "Enter the direct link of your share on imgur:";
				echo "<input id='redditpost_commentpage_link{$oc_id}' style='width:80%'> </input>";
				echo "<div id='redeem_result{$oc_id}'></div>";
				echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"send_imgur_response({$oc_id})\">Update imgur post & Redeem</button>";
			echo "</div>";
		}
		
	echo "</div>";
	echo "<div style=\"clear:both\"> </div>";
	echo "</div>";
	
	echo "
	</div>"; // ._ad_template
}

echo "</div>";

?>