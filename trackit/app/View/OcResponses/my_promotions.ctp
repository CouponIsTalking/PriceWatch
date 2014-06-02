<!--script src="/ab/js/custom/opencampaign.js" charset="utf-8"></script-->
<?php
echo $this->Html->script('custom/opencampaign');
echo $this->Html->css('promo_box');
?>

<?php if (!empty($ocResponses)) { ?>
<div class="ocResponses">
	<h2><?php echo __('Your Coupons'); ?></h2>
	
	<?php
	echo "<p>";
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	echo "</p>";
	echo "<div class='paging'>";
	
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	
	echo "</div>";
	
	echo "<p></p> <p></p>";
	
	foreach ($ocResponses as $ocResponse)
	{
		$onclick_activate_event = "getdetails_opencampaign({$ocResponse['OcResponse']['oc_id']});";
		$get_coupon_detail_click = "getdetails_opencampaign({$ocResponse['OcResponse']['oc_id']});";
				
		echo "<div class='promo_box' style='height:auto;'>";
			/*echo "<div class='campaign topgap_10px' style='float:left;'>";
				$onclick_activate_event = "getdetails_opencampaign({$ocResponse['OcResponse']['oc_id']});";
				echo "<a onclick=\"{$onclick_activate_event}\">Campaign</a>"; 
			echo "</div>";*/
			
			$promo_plat = $ocResponse['OcResponse']['response_type'];
				
			/*
			echo "<div class='promo_platform' style='float:right;'>";
				if ($promo_plat == 'blog')
				{
					echo "Blog";
				}
				else if ($promo_plat == 'reddit')
				{
					echo "Reddit";
				}
				else if ($promo_plat == 'imgur')
				{
					echo "Imgur";
				}
				else if ($promo_plat == 'fb_post')
				{
					echo "Facebook";
				}
				else if ($promo_plat == 'tw' || $promo_plat == 'tweet')
				{
					echo "Tweet";
				}
			echo "</div>";
			*/
			
			echo "<div class='time'>";
				echo "Created " . h($ocResponse['OcResponse']['created']);
			echo "</div>";
			
		/*	echo "<div class='is_processed topgap_10px'>";
				
				$processed = $ocResponse['OcResponse']['processed'];
				
				echo "<div class='processing_result'>";
				if ($processed)
				{
					$prcessing_result = $ocResponse['OcResponse']['processing_result'];
					if ($prcessing_result)
					{
						echo "Qualified";
					}
					else
					{
						echo "Unqualified";
						$onclick_event = "requeue_ocr({$ocResponse['OcResponse']['id']});";
						
						echo "<div class='requeue_it' onclick=\"{$onclick_event}\">";
							echo "<a>Requeue It</a>";
						echo "</div>";
					}
				}
				else
				{
					echo "Unprocessed yet";
					$onclick_event = "requeue_ocr({$ocResponse['OcResponse']['id']});";
					
					echo "<div class='requeue_it' onclick=\"{$onclick_event}\">";
						echo "<a>Requeue It</a>";
					echo "</div>";
				}
				echo "</div>";
				
			echo "</div>";
		*/	
			$link = $ocResponse['OcResponse']['response_blog_link'];
			$link = $this->CommonFunc->addhttp($link);
			$open_live_post_evt = "OpenInNewTab('{$link}')";
			
			echo "<div class='live_promo_link'>";
				
				if ($promo_plat == 'blog')
				{
					//$link = $this->CommonFunc->addhttp($ocResponse['OcResponse']['response_blog_link']);
					echo "<a target=\"_blank\" href=\"{$link}\">Live Blog Post</a>";
				}
				else if ($promo_plat == 'reddit')
				{
					//$link = $this->CommonFunc->addhttp($ocResponse['OcResponse']['response_blog_link']);
					echo "<a target=\"_blank\" href=\"{$link}\">Reddit Comments</a>";
				}
				else if ($promo_plat == 'fb_post')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "facebook.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">Live Facebook Post</div>";
					}
					else
					{
						echo "Facebook Post";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'fb_like_page')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "facebook.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">FB Page Like</div>";
					}
					else
					{
						echo "FB Page Like";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'fb_event_share')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "facebook.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">FB Event Share</div>";
					}
					else
					{
						echo "FB Event Share";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'fb_event_join')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "facebook.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">FB Event Join</div>";
					}
					else
					{
						echo "FB Event Join";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'fb_like_pic')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "facebook.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">FB Pic Like</div>";
					}
					else
					{
						echo "FB Pic Like";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'fb_like_video')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "facebook.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">FB Video Like</div>";
					}
					else
					{
						echo "FB Pic Like";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'fb_post_video')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "facebook.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">FB Video Post</div>";
					}
					else
					{
						echo "FB Post Video";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'tw' || $promo_plat == 'tweet')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "twitter.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">Live Tweet</div>";
					}
					else
					{
						echo "Tweet";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ('single_email_ns_signup' == $promo_plat)
				{
					$email = $ocResponse['OcResponse']['response_blog_link'];
					$show_signup_email_evt = "cmn_s_m_f_r_f(0, 'Signup with email <green_button>".$email."</green_button>');";
					echo "<div class='link' onclick=\"{$show_signup_email_evt}\">Newsletter Signup</div>";
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ('dual_email_ns_signup' == $promo_plat)
				{
					$email = $ocResponse['OcResponse']['response_blog_link'];
					$show_signup_email_evt = "cmn_s_m_f_r_f(0, 'Signup with email <green_button>".$email."</green_button>');";
					echo "<div class='link' onclick=\"{$show_signup_email_evt}\">Newsletter Signup</div>";
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ('yelp_review' == $promo_plat)
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "yelp.com");
					if ($is_proper_link > 0)
					{
						echo "<div class='link' onclick=\"{$open_live_post_evt}\">Live Yelp Review</div>";
					}
					else
					{
						echo "Yelp Review";
					}
				}
				else if ('giveaway' == $promo_plat)
				{
					echo "Giveaway";
				}
				
			echo "</div>";
						
			echo "<div class='code_info'>
					<span class='code' onclick=\"{$get_coupon_detail_click}\">Code {$ocResponse['OcResponse']['coupon_code']}</span>
					<div style='clear:both'></div>
					<span class='coupon_detail' onclick=\"{$get_coupon_detail_click}\">coupon info.</span>
					<div style='clear:both'></div>
				</div>
			";
		echo "</div>";
	}
	?>

	<!--/table-->
	
</div>
<?php } 
else{
$rc = SITE_NAME . 'open_campaigns/running_campaigns';
echo "
<div style='padding:3%;padding-top:100px;color:rgba(0,0,0,0.7);font-size:24px;'> Looks like, you haven't unlocked any coupon yet :(. 
<a href=\"{$rc}\" style='border-bottom:2px solid rgba(0,0,0,0.5);cursor:pointer;text-decoration:none;color:inherit;font-style:inherit;font-weight:inherit;'
 onmouseover=\"$(this).css('border-bottom','4px solid rgba(0,0,0,0.5)');\"
 onmouseout=\"$(this).css('border-bottom','2px solid rgba(0,0,0,0.5)');\"
>
Lets Get Started.
</a>
</div>";
}
?>
<div class="opencampaign_details_click_response" style="display:none"></div>