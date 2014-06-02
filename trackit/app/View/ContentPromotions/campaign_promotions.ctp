<!--script src="/ab/js/custom/opencampaign.js" charset="utf-8"></script-->
<?php
echo $this->Html->css('promo_box');
?>
<?php 

if (!$is_ajax)
{
	echo $this->Html->script('custom/opencampaign'); 
}

echo "<h2>";
	echo __('Promotions');
echo "</h2>";

if (empty($ocResponses)) 
{
	echo "<div class='ocResponses'>
		There is no promotion yet, but we hope this fills up soon !.
	</div>";
	
}
else
{ 
echo "<div class='ocResponses'>";
	
	//echo "<h3>";
		/*
		echo "<div class='campaign topgap_10px' style='cursor:pointer; height:30px; width:150px; float:left; text-align:center'>";
			$onclick_activate_event = "getdetails_staticcampaign({$content_id});";
			echo "<a onclick=\"{$onclick_activate_event}\">Campaign</a>"; 
		echo "</div>";
		*/
		
		/*
		echo "<div class='promo_platform topgap_10px' style='height:30px; width:150px; float:left; text-align:center'>";
			$promo_plat = $oc['OpenCampaign']['type'];
			if ($promo_plat == 'blog')
			{
				echo "On Blog";
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
				echo "On Facebook";
			}
		echo "</div>";
		*/
	//echo "</h3>";
	
	echo "<div style='clear:both'></div>";
	echo "<p></p>";
	
	echo "<p>";
	/*echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));*/
	$total_ocrs = count($ocResponses);
	$end_ocr = strval(max(20, $total_ocrs));
	echo "Showing <span class='_page_start_no'>1</span> to <span class='_page_end_no'>{$end_ocr}</span> of " . strval (count($ocResponses));
	echo "</p>";
	echo "<div class='paging'>
	<span>prev</span>	<span>next</span>
	</div>";
	
	echo "<p></p> <p></p>";
	
	foreach ($ocResponses as $ocResponse)
	{
		$onclick_activate_event = "getdetails_opencampaign({$content_id});";
		$get_coupon_detail_click = "getdetails_opencampaign({$content_id});";
		
		echo "<div class='promo_box' style='height:auto;'>";
			/*
			echo "<div class='campaign topgap_10px' style='float:left;'>";
				$onclick_activate_event = "getdetails_opencampaign({$ocResponse['OcResponse']['oc_id']});";
				echo "<a onclick=\"{$onclick_activate_event}\">Campaign</a>"; 
			echo "</div>";
			*/
			/*
			echo "<div class='campaign topgap_10px' style='height:30px; width:150px; float:left; text-align:center'>";
				$promoter_social_profile = "/AB/bloggers/company_view/{$ocResponse['OcResponse']['blogger_id']}";
				echo "<a target='_blank' href=\"{$promoter_social_profile}\">Promoter</a>"; 
			echo "</div>";
			*/
			echo "<div style='clear:both;'></div>";
			
			$promo_plat = $ocResponse['ContentPromotion']['response_type'];
			
			echo "<div class='time'>";
				echo "Created " . h($ocResponse['ContentPromotion']['created']);
			echo "</div>";
			
		/*	echo "<div class='topgap_10px'>";
				echo "<div class='time'>";
					echo "Created " . h($ocResponse['OcResponse']['created']);
				echo "</div>";
			echo "</div>";
		*/		
		
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
						echo "<div class='requeue_it'>";
							echo "Requeue It";
						echo "</div>";
					}
				}
				else
				{
					echo "Unprocessed yet";
					echo "<div class='requeue_it'>";
						echo "Requeue It";
					echo "</div>";
				}
				echo "</div>";
			echo "</div>";
		*/	
			$link = $ocResponse['ContentPromotion']['response_blog_link'];
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
					$is_proper_link = strpos($ocResponse['ContentPromotion']['response_blog_link'], "facebook.com");
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
				else if ($promo_plat == 'tw' || $promo_plat == 'tweet')
				{
					$is_proper_link = strpos($ocResponse['ContentPromotion']['response_blog_link'], "twitter.com");
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
				
			echo "</div>";
						
			echo "<div class='code_info'>
					<span class='code' onclick=\"{$get_coupon_detail_click}\">Code {$ocResponse['ContentPromotion']['coupon_code']}</span>
					<div style='clear:both'></div>
					<span class='coupon_detail' onclick=\"{$get_coupon_detail_click}\">coupon info.</span>
					<div style='clear:both'></div>
				</div>
			";
		/*
			echo "<div class='topgap_10px blog_link'>";
				
				if ($promo_plat == 'blog')
				{
					$link = $this->CommonFunc->addhttp($ocResponse['OcResponse']['response_blog_link']);
					echo "<a target=\"_blank\" href=\"{$link}\">Blog Post</a>";
				}
				else if ($promo_plat == 'reddit')
				{
					$link = $this->CommonFunc->addhttp($ocResponse['OcResponse']['response_blog_link']);
					echo "<a target=\"_blank\" href=\"{$link}\">Reddit Comments</a>";
				}
				else if ($promo_plat == 'fb_post')
				{
					$is_proper_link = strpos($ocResponse['OcResponse']['response_blog_link'], "facebook.com");
					if ($is_proper_link > 0)
					{
						$link = $ocResponse['OcResponse']['response_blog_link'];
						echo "<a target=\"_blank\" href=\"{$link}\">Facebook Post</a>";
					}
					else
					{
						echo "Facebook Post";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'tw' || $promo_plat == 'tweet')
				{
					$link = "";
					$tweet_data = json_decode($ocResponse['OcResponse']['response_data'], true);
					//debug($tweet_data);
					
					if (!empty($tweet_data['user']['screen_name']) && !empty($tweet_data['id_str']))
					{
						$screen_name = $tweet_data['user']['screen_name'];
						$link = $this->CommonFunc->get_tweet_url_by_user_and_id($screen_name, $tweet_data['id_str']);
					}
					
					$is_proper_link = strpos($link, "twitter.com");
					
					
					if ($is_proper_link > 0)
					{
						//$link = $ocResponse['OcResponse']['response_blog_link'];
						echo "<a target=\"_blank\" href=\"{$link}\">Tweet</a>";
					}
					else
					{
						echo "Tweet";
					}
					
					if (!empty($screen_name))
					{
						echo " by {$screen_name}";
					}
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				
			echo "</div>";
		*/	
			
		echo "</div>";
	}
	
	
echo "</div>";
}
?>
<div class="opencampaign_details_click_response" style="display:none"></div>