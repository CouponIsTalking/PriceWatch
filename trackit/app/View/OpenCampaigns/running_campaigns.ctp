<style type='text/css'>
* ._get_coupon_btn_cls{
	color:rgba(0,0,0,0.75);
	border-bottom:2px solid transparent;
	cursor:pointer;
}
* ._get_coupon_btn_cls:hover{
	border-bottom:2px solid rgba(0,0,0,0.5);
}
</style>

<script type='text/javascript'>
function RunOnLoad()
{
	//$('#content').css('background', '#A28989');
	<?php
	if (!empty($layout_var) && 'popup' == $layout_var)
	{   echo "
		var sl = $('.stack_info_running_campaign').offset().top;
		$('body').animate({ scrollTop: sl}, 300, function(){});
		";
	}
	?>
}
</script>

<?php
echo $this->Html->script('custom/update_resps');
?>

<!--div class="companies view">
<h2><?php //echo h($company['Company']['name']); ?></h2>
<?php //echo h($company['Company']['website']); ?>

<div style="float:right; text-align:right;" href="get_open_campaigns">
<?php 
/*	echo "Like {$company['Company']['name']} ?";
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

	
</div-->


<div style="margin:40px 10px 10px; width:auto; float:left">


<?php

if (empty($ocs))
{
	echo "<div class=\"brand_promote\" style='background:white;'>";
	//echo "Looks like you haven't created any Advertising campaign yet. ";
	echo "Looks like no campaign is running right now. Please check back after sometime.";
	echo "<br/>";
	//echo "<a href='/AB/open_campaigns/add' style=''> Lets start by adding an Adv Campaign </a>";
	echo "Also, you can follow us to be informed once they launch a campaign.";
	echo "</div>";
}

$company_to_campaign = array();

if (!empty($logged_in_company_id))
{
	echo "<span class='_get_coupon_btn_cls' onclick=\"cmn_s_m_f_r_f(0,$('._gcbc').html(),0,0);\">Get Coupon Button</span>";
	
	$popup_code_html = SITE_NAME . "socials/extra_coupons/{$logged_in_company_id}";
	echo "<div class='_gcbc' style='display:none;'>
	Copy the single line of code below, and add to your site where you want the coupon button. As easy as that !
	<textarea id='code' class='b2-text' rows='5' cols='150' border='2' readonly='' style='font-size:12px;'>
	<iframe src='{$popup_code_html}' frameBorder='0' height='27' width='75' border='0' marginheight='0' marginwidth='0'></iframe>
	</textarea>
	</div>
	";
	
}

echo "<div class='stack_info_running_campaign'>";

$cids = array();
foreach($ocs as $index =>$oc)
{
	$oc_id = $oc['OpenCampaign']['id'];
	$company_id = $oc['OpenCampaign']['company_id'];
	if (empty($cids[$company_id])) {$cids[$company_id] = array();}	
	$cids[$company_id][] = $oc_id;
}

foreach($cids as $company_id=>$ocs_comp)
{	
	$class_name = "_cid_{$company_id}";
	
	echo "<div class='{$class_name}' style=\"background:'pink';border: '2px solid darkbrown';\">";
	
	echo "<div style='clear:both'></div>";
	
	$dem_only = "";
	/*
	if ('LOFT' == strtoupper($companies[$company_id]['name']))
	{
		$dem_only = "<span style='font-size:13px;margin-left:15px;font-style:italic;'>Loft coupons are demonstration only. Not real coupons.</span>";
	}
	*/
	echo "
		<div style=\"text-transform:uppercase;text-decoration:underline;float:left;height:auto;\">
			{$companies[$company_id]['name']}
		</div>
		{$dem_only}		
		<div style='clear:both;'></div>
	";
	foreach($ocs as $index =>$oc)
	{
		$oc_id = $oc['OpenCampaign']['id'];
		$type = $oc['OpenCampaign']['type'];
		$coupon_code = $oc['OpenCampaign']['coupon_code'];
		$coupon_line = $oc['OpenCampaign']['coupon_line'];
		
		if ($company_id != $oc['OpenCampaign']['company_id'])
		{
			continue;
		}
		if (empty($company_to_campaign[$company_id]))
		{
			$company_to_campaign[$company_id] = array();
		}
		$company_to_campaign[$company_id][$type] = true;
		
		$s = "";
		if ($type == 'blog')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "Blog";
		}
		else if ($type == 'giveaway')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "CODE: " . $coupon_code; //"Open Coupon Code";
		}
		else if ($type == 'yelp_review')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "Review on Yelp";
		}
		else if ($type == 'single_email_ns_signup')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "Newsletter signup";
		}
		else if ($type == 'dual_email_ns_signup')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "Newsletter signup (dual)";
		}
		else if ($type == 'fb_post')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "Facebook";
		}
		else if ($type == 'fb_like_pic')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "FB Like Pic";
		}
		else if ($type == 'fb_like_page')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "FB Like Page";
		}
		else if ($type == 'fb_like_video')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "FB Like Video";
		}
		else if ($type == 'fb_post_video')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "FB Post Video";
		}
		else if ($type == 'fb_event_share')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "FB Share Event";
		}
		else if ($type == 'fb_event_join')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "FB Join Event";
		}
		else if ($type == 'tw' || $type == 'tweet')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "Twitter";
		}
		else if ($type == 'reddit')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "Reddit";
		}
		else if ($type == 'imgur')
		{
			if ($s != "") $s = $s.", ";
			$s = $s . "Imgur";
		}
		
		if (!empty($layout_var) && 'popup' == $layout_var)
		{
			$link_to_company_oc_page = SITE_NAME."open_campaigns/get_open_campaign/{$oc_id}?layout=popup";
		}
		else
		{
			$link_to_company_oc_page = SITE_NAME."open_campaigns/get_open_campaign/{$oc_id}";;
		}
		//$onclick_evt = "OpenInNewTab('{$link_to_company_oc_page}');";
		$onclick_evt = "moveTo('{$link_to_company_oc_page}');";
		
		if (!empty($coupon_code))
		{
			//$s = $s . " coupon for <campanyname>{$companies[$company_id]['name']}</companyname>";
		}
		else
		{
			$s = $s . " campaign for <campanyname>{$companies[$company_id]['name']}</companyname>";
		}
		echo "<li>";
			echo "<div class='info_running_campaign' onclick=\"{$onclick_evt}\">";
				echo "<div style='font-size:22px;color:white;margin-bottom:15px;'>{$coupon_line}</div>";
				echo "<div style='width:auto;bottom: 0;font-size: 18px;line-height: 14px;position: relative;bottom: 0;background: rgb(167, 0, 27);color: white;padding: 15px;border: 3px dotted white;'>{$s}</div>";
			echo "</div>";
		echo "</li>";	
	}
	
	echo "</div>";
}

echo "</div>";

//debug($company_to_campaign);
/*
echo "<div class='stack_info_running_campaign'>";
foreach ($company_to_campaign as $company_id => $types)
{
	$s = "";
	if (!empty($types['blog']))
	{
		if ($s != "") $s = $s.", ";
		$s = $s . "Blog";
	}
	if (!empty($types['fb_post']))
	{
		if ($s != "") $s = $s.", ";
		$s = $s . "Facebook";
	}
	if (!empty($types['tw']))
	{
		if ($s != "") $s = $s.", ";
		$s = $s . "Twitter";
	}
	if (!empty($types['reddit']))
	{
		if ($s != "") $s = $s.", ";
		$s = $s . "Reddit";
	}
	if (!empty($types['imgur']))
	{
		if ($s != "") $s = $s.", ";
		$s = $s . "Imgur";
	}
	
	$link_to_company_oc_page = SITE_NAME."/companies/get_open_campaigns/{$company_id}";
	$onclick_evt = "OpenInNewTab('{$link_to_company_oc_page}');";
	
	$s = $s . " campaign for <campanyname>{$companies[$company_id]['name']}</companyname>";
	echo "<li>";
		echo "<div class='info_running_campaign' onclick=\"{$onclick_evt}\">";
			echo $s;
		echo "</div>";
	echo "</li>";
}
echo "</div>";


echo "<div style='clear:both;'></div>";

$i = 0;

foreach($ocs as $index =>$oc)
{
	$oc_id = $oc['OpenCampaign']['id'];
	
	$company_id = $oc['OpenCampaign']['company_id'];
	$company = $companies[$company_id];
	$product_id = $oc['OpenCampaign']['product_id'];
	$product_data = array();
	if ($product_id)
	{
		$product_data = $products[$product_id];
	}
	
	echo "<div style='position:relative; float:left'>";
	
	echo "<div class=\"brand_promote\">";
		
	if ($oc['OpenCampaign']['product_id'] == 0)
	{
		echo "<div style=\"float:left; margin-top:5px\">";
			echo "Promote " .$company['name']. " as a brand&nbsp";
		echo "</div>";
	}
	else if ($oc['OpenCampaign']['product_id'] > 0)
	{
		echo "<div style=\"float:left; margin-top:5px\">";
			echo "Promote " .$product_data['name']. "&nbsp";
		echo "</div>";
	}
	
	if ($oc['OpenCampaign']['type'] == 'blog')
	{
		echo "<div style=\"float:left; margin-top:5px\">";
			echo "on your Blog";
		echo "</div>";
	}
	else if ($oc['OpenCampaign']['type'] == 'fb_post')
	{
		echo "<div style=\"float:left; margin-top:5px\">";
			echo "on Facebook ";
		//echo " by posting something positive on facebook about {$company['name']}";
		echo " by posting something positive about it.";
		echo "<br/>";
		echo "</div>";
		echo "<div style='clear:both'>";
		//echo "with hashtag #{$company['name']} and give it a public visibility.";
		echo "Once your post has enough likes and comments to fulfill conditions, <br/>come back here to redeem your coupon, as simple as that";
		echo "</div>";
	}
	
	echo "<div style=\"clear:both\"> </div>";
	echo "<div>";
		//echo "Conditions -";
		echo "<br/>";
		foreach ($oc_conditions[$oc_id] as $index => $condition)
		{
			$condition_id = $condition['condition_id'];
			if ($condition_id == 0)
			{
				continue;
			}
			$condition_name = $condition_data[$condition_id]['name'];
			$param1 = $condition['param1'];
			echo "<div style=\"float:left; \">";
			
				$offer_type = $condition['offer_type'];
				$offer_worth = $condition['offer_worth'];
				if ($offer_type == 'coupon')
				{
					echo "<div style=\"float:left\">";
						echo "Coupon worth \${$offer_worth} For&nbsp";
					echo "</div>";
				}
				else if ($offer_type == 'dollar')
				{
					echo "<div style=\"float:left\">";
						echo "Offering \${$offer_worth} For&nbsp";
					echo "</div>";
				}
				
				echo "<div style=\"float:left\">";
					echo $condition_name;
					echo "<div style=\"position:relative; margin-left:30px; float:right\">";
						echo $param1;
					echo "</div>";
				echo "</div>";
				
			echo "</div>";
			echo "<br/>";
		}
		
		if ($oc['OpenCampaign']['type'] == 'blog')
		{
			echo "<div style=\"width:400px; margin-left:5%; margin-right:5%;\">";
				echo "<br/>";
				echo "Created a Blog, got the comments, looking to redeem ?";
				echo "<br/>";
				echo "Enter the direct link to your 'blog post' below:";
				echo "<input id='blogpost_link{$oc_id}' style='width:80%'> </input>";
				echo "<div id='redeem_result{$oc_id}'></div>";
				echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"send_blog_response({$oc_id})\">Redeem</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'reddit')
		{
			echo "<div style=\"width:400px; margin-left:5%; margin-right:5%;\">";
				echo "<br/>";
				echo "Tell why {$company['name']} is a good company ";
				echo "<br/>";
				echo "by sharing a link, image or news about {$company['name']}.";
				echo "<br/>";
				echo "Enter the direct link to the comments page of your share on reddit:";
				echo "<input id='imgurpost_link{$oc_id}' style='width:80%'> </input>";
				echo "<div id='redeem_result{$oc_id}'></div>";
				echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"send_reddit_response({$oc_id})\">Update comment page link of reddit post & Redeem</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'imgur')
		{
			echo "<div style=\"width:400px; margin-left:5%; margin-right:5%;\">";
				echo "<br/>";
				echo "Share an image on <a target='_blank' href=\"http://www.imgur.com\">imgur</a> that shows why {$company['name']} is a good company ";
				echo "<br/>";
				echo "Enter the direct link of your share on imgur:";
				echo "<input id='redditpost_commentpage_link{$oc_id}' style='width:80%'> </input>";
				echo "<div id='redeem_result{$oc_id}'></div>";
				echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"send_imgur_response({$oc_id})\">Update imgur post & Redeem</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_post')
		{
			echo "<div style=\"width:400px; margin-left:5%; margin-right:5%;\">";
				echo "<br/>";
				echo "Write one or two lines on - ";
				echo "<br/>";
				echo "why {$company['name']} is awesome";
				echo " Or <br/>";
				echo "what makes you love {$company['name']}";
				echo " Or <br/>";
				echo "why people should use {$company['name']}";
				echo " Or <br/>";
				echo "how {$company['name']} is different than others";
				echo "<input id='fb_post_content{$oc_id}'> </input>";
				echo "<div id='post_on_fb{$oc_id}'></div>";
				echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"post_on_facebook({$oc_id})\">Post on Facebook</button>";
			echo "</div>";
		}
	echo "</div>";
	//echo "<div style=\"clear:both\"> </div>";
	echo "</div>";
	echo "</div>";
}

echo "</div>";
*/
?>