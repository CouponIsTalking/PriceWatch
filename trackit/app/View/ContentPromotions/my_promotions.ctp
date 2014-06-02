<script src="/ab/js/custom/opencampaign.js" charset="utf-8"></script>
<?php if (!empty($ocResponses)) { ?>
<div class="ocResponses">
	<h2><?php echo __('Your Entries'); ?></h2>
	
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
		echo "<div class='oc_response_pin_blog'>";
			echo "<div class='campaign topgap_10px' style='float:left;'>";
				$onclick_activate_event = "getdetails_opencampaign({$ocResponse['OcResponse']['oc_id']});";
				echo "<a onclick=\"{$onclick_activate_event}\">Campaign</a>"; 
			echo "</div>";
			echo "<div class='promo_platform topgap_10px' style='float:right;'>";
				$promo_plat = $ocResponse['OcResponse']['response_type'];
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
			echo "</div>";
			echo "<div class='topgap_10px'>";
				echo "<div class='time'>";
					echo "Created " . h($ocResponse['OcResponse']['created']);
				echo "</div>";
			echo "</div>";
			
			echo "<div class='is_processed topgap_10px'>";
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
				
			echo "</div>";
			
		echo "</div>";
	}
	?>

	<!--/table-->
	
</div>
<?php } ?>
<div class="opencampaign_details_click_response" style="display:none"></div>