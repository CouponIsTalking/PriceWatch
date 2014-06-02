<!--script src="/ab/js/custom/opencampaign.js" charset="utf-8"></script-->
<?php
echo $this->Html->css('promo_box');
?>

<script type='text/javascript'>
function _man_s_def_c_c($this,$ocrid){
	
	$this.text("Sending code...");
	
	$.ajax({type:'POST', data:{ocr_id:$ocrid},url:$S_N+"user_actions/send_deferred_coupon",
	success:function(data){$d=IsJsonString(data);
	if($d&&$d['s']){
		$this.text('Code '+$d['ccode']);
		$this.attr('onclick','').unbind('click');		
	}
	else if($d['m']){cmn_s_m_f_r_f(0,$d['m'],0,0);$this.text("Send Code.");}
	else{cmn_s_m_f_r_f(0,"An network error occured. If you don't expect it, then please let us know of it.",0,0);
		$this.text("Send Code.");
	}},
	error:function(){cmn_s_m_f_r_f(0,"An network error occured. Please check your network connection. <br/>If this issue persists, then please let us know of it.",0,0);
		$this.text("Send Code.");}
	});
}
</script>

<?php 

if (!$is_ajax)
{
	echo $this->Html->script('custom/opencampaign'); 
}

echo "<h2>";
	echo __('Promotions Created');
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
		echo "<div class='campaign topgap_10px' style='cursor:pointer; height:30px; width:150px; float:left; text-align:center'>";
			$onclick_activate_event = "getdetails_opencampaign({$oc['OpenCampaign']['id']});";
			echo "<a onclick=\"{$onclick_activate_event}\">Campaign</a>"; 
		echo "</div>";
		
		$export_email_href = SITE_NAME."oc_responses/export_emails/{$oc['OpenCampaign']['id']}";
		
		//echo "<div class='promo_platform topgap_10px' style='height:30px; width:150px; float:left; text-align:center'>";
		echo "<span style='font-size:12px;cursor:pointer;color:rgba(0,0,0,0.8);border-bottom:2px dotted rgba(0,0,0,0.8);'
			onmouseover=\"$(this).css('border-bottom', '2px solid rgba(0,0,0,0.8)');\" 
			onmouseout=\"$(this).css('border-bottom', '2px dotted rgba(0,0,0,0.8)');\" 
			onclick=\"moveTo('{$export_email_href}')\"
		>";
			$promo_plat = $oc['OpenCampaign']['type'];
			if ($promo_plat == 'single_email_ns_signup')
			{
				echo "Export Signedup Emails.";
			}
			else if ($promo_plat == 'dual_email_ns_signup')
			{
				echo "Export Signedup Emails.";
			}
		echo "</span>";
		//echo "</div>";
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
		$onclick_activate_event = "getdetails_opencampaign({$ocResponse['OcResponse']['oc_id']});";
		$get_coupon_detail_click = "getdetails_opencampaign({$ocResponse['OcResponse']['oc_id']});";
		
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
			
			$promo_plat = $ocResponse['OcResponse']['response_type'];
			
			/*
			echo "<div class='promo_platform topgap_10px' style='float:right;'>";
				if ($promo_plat == 'blog')
				{
					echo "Blog";
				}
				else if ($promo_plat == 'fb_post')
				{
					echo "Facebook";
				}
				else if ($promo_plat == 'tw' || $promo_plat == 'tweet')
				{
					echo "Twitter";
				}
			echo "</div>";
			*/
			
			echo "<div class='time'>";
				echo "Created " . h($ocResponse['OcResponse']['created']);
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
				else if ('fb_like_pic' == $promo_plat)
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
				else if ('fb_like_video' == $promo_plat)
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
				else if ('fb_post_video' == $promo_plat)
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
						
			echo "<div class='code_info'>";
			
			if(!empty($ocResponse['OcResponse']['coupon_code'])){
			echo "	<span class='code' onclick=\"{$get_coupon_detail_click}\">Code {$ocResponse['OcResponse']['coupon_code']}</span>";
			}else if('yelp_review' == $promo_plat){
			$send_code_evt = "_man_s_def_c_c($(this),'{$ocResponse['OcResponse']['id']}');";
			echo "	<span class='code' onclick=\"{$send_code_evt}\">Send Code</span>";
			}
			
			echo "	<div style='clear:both'></div>
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