<script src="/ab/js/custom/opencampaign.js" charset="utf-8"></script>
<?php if (!empty($ocResponses)) { ?>
<div class="ocResponses">
	<h2><?php echo __('Queued Promotions'); ?></h2>
	
	<?php
/*	echo "<p>";
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	echo "</p>";
	echo "<div class='paging'>";
	
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	
	echo "</div>";
*/	
	$conditions_list = array();
	foreach ($conditions as $id => $name)
	{
		if ($id == 5) continue;
		$conditions_list[$id] = $name['name'];
	}
	$conditions_list[0] = '----None----';
	
	echo "<p></p> <p></p>";
	
	$i = 0;
	foreach ($ocResponses as $ocResponse)
	{
		$i++;
		
		echo "<div class='oc_response_pin_blog' style='height:auto;'>";
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
					echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				else if ($promo_plat == 'fb_post')
				{
					echo "Facebook Post";
					//echo h($ocResponse['OcResponse']['response_blog_link']);
				}
				
			echo "</div>";
			
			echo "<div class='topgap_10px blog_link'>";
				$accept_con_id = 'accept_condition'. strval($i);
				$onclick_event = "accept_ocr({$ocResponse['OcResponse']['id']}, {$accept_con_id});";
				
				echo "<a onclick=\"{$onclick_event}\">Qualify</a>";
				echo $this->Form->input($accept_con_id, array(
					'options' => $conditions_list, 'label' => 'accept on', 'default' => 0,
					//'style'=>'left-margin:10%;',
					//'css'=>'left-margin:10%;'
				));
				
			echo "</div>";
			echo "<div class='topgap_10px blog_link'>";
				$onclick_event = "unaccept_ocr({$ocResponse['OcResponse']['id']});";
				echo "<a onclick=\"{$onclick_event}\">Dis-Qualify</a>";
			echo "</div>";
			
		echo "</div>";
	}
	?>

	<!--/table-->
	
</div>
<?php } ?>
<div class="opencampaign_details_click_response" style="display:none"></div>