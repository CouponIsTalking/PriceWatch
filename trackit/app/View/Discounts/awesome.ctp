<?php
//echo $this->Html->script('tracker/append_prods');
echo $this->Html->css('company_tags');
?>

<style type='text/css'>
* ._get_coupon_btn_cls{
	color:rgba(0,0,0,0.75);
	border-bottom:2px solid transparent;
	cursor:pointer;
}
* ._get_coupon_btn_cls:hover{
	border-bottom:2px solid rgba(0,0,0,0.5);
}

* ._main_box{
	margin: 1% 1%;
	padding-top: 40px;
	-box-shadow:0 0px 10px 10px rgba(170, 170, 170, 0.57);
	-webkit-box-shadow:0 0px 10px 10px rgba(170, 170, 170, 0.57);
	-moz-box-shadow:0 0px 10px 10px rgba(170, 170, 170, 0.57);
	border-radius: 10px;
	width:auto;
	float:left;
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
	
	$(window).scroll(function (){
		if(undefined != window['checkAndAttachMore']){checkAndAttachMore('._prod_list_div');}
	});
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


<div class='_main_box'>


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

echo "<div class='stack_info_running_campaign' style='margin-top:0px;max-width:48%;float:left;'>";

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
		echo "<li style='width:100%;'>";
			echo "<div class='info_running_campaign' style='width:96%;padding-left:2%;padding-right:2%;' onclick=\"{$onclick_evt}\">";
				echo "<div style='font-size:22px;color:white;margin-bottom:15px;'>{$coupon_line}</div>";
				echo "<div style='width:auto;bottom: 0;font-size: 18px;line-height: 14px;position: relative;bottom: 0;background: rgb(167, 0, 27);color: white;padding: 15px;border: 3px dotted white;'>{$s}</div>";
			echo "</div>";
		echo "</li>";	
	}
	
	echo "</div>";
}

echo "</div>";
?>

<?php
//companies list

echo "<div style='margin-top:0px;max-width:48%;float:right;'>";
echo "<span class='_company_list' style=\"position:relative;display:inline-block;\">";// left:50%\">";
	echo "<span style='display:inline-block;'>";
	$i = 0;
	foreach ($companies as $company_id => $company)
	{
		$i = $i+1;
		//$encoded_url = urlencode($company['Company']['website']);
		$encoded_url = urlencode($company['website']);
		$link = SITE_NAME."open_campaigns/running_campaigns/?c={$company_id}";
		$onclick_evt = "window.location = '{$link}'";//"OpenInNewTab('{$link}');";
		echo "<div class='company_discount_tag' style='float:left; width:30%; padding:1%; margin:1%;' onclick=\"{$onclick_evt}\">";
			//echo $company['Company']['name'];
			echo $company['name'];
		echo "</div>";
		
		/*if ($i%3 == 0)
		{
			echo "<div style='clear:both'></div>";
		}*/
	}
	echo "</span>";
echo "</span>";

echo "</div>";
?>

</div>