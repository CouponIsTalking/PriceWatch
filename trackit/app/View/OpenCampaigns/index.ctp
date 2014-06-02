<?php echo $this->Html->script('custom/opencampaign'); ?>

<script type='text/javascript'>

function show_verifier($xid)
{
	cb_clk();
	
	$html = "<div>"
		+ "<label style='width:500px;' class='_find_verifier_label'>enter verifier</label>"
		+ "<br/> <div style='font-size:12px;'>(it is sent to users via email after they earn your coupon.) </div>"
		+ "<br/>"
		+ "<label style='width:500px;' class='_verifier_result'></label>"
		+ "<br/>"
		+ "<label style='width:500px;' class='_verifier_used_result'></label>"
		+ "<br/>"
		+ "<input style='width:500px;' class='_verifier_val' val='enter your facebook page url'></input>"
		+ "<div class='___xid' style='display:none;'>"+$xid+"</div>"
		+ "<br/>"
		+ "<green_button onclick=\"verify_verifier($(this));\">Verify</green_button>"
	+ "</div>"
	+"";
	
	cb_clk();
	show_success_message($html);
	fit_to_inner_content('div.success_msg');
	reposition_in_center('div.success_msg');
}

function show_used_val_in_verifier($xid,$verifier,$used_val){
	if(!$xid){
		$('._verifier_used_result').html("");
		return;
	}
	if(1==$used_val){
		$('._verifier_used_result').html("<purple_button>Used</purple_button><black_button onclick=\"mark_used("+$xid+",'"+$verifier+"',0);\">Mark as not used.</black_button>");
	}else if(0==$used_val){
		$('._verifier_used_result').html("<purple_button>Not Used</purple_button><black_button onclick=\"mark_used("+$xid+",'"+$verifier+"',1);\">Mark used.</black_button>");
	}else{
		$('._verifier_used_result').html(""+$used_val);
	}
}

function mark_used($xid, $verifier, $used_val)
{
	$('._verifier_used_result').html('updating ...');
		
	$url = $S_N+"user_actions/mark_used";
	
	$.ajax({type:"POST",url: $url,
		data:{xid: $xid, type : 'oc', used_val:$used_val, verifier:$verifier}, 
		success : function(data) {$result = IsJsonString(data);
			if ($result.success){
				show_used_val_in_verifier($xid,$verifier,$result.used);
			}
			else{
				show_used_val_in_verifier($xid,$verifier,$result.msg);
			}
		},
		error : function(data) {
		}
	});
	
}

function verify_verifier($this)
{
	$('._verifier_result').html('looking up, just a sec...');
	
	$xid = $this.parent().find('div.___xid').text().trim();
	$verifier = $this.parent().find('._verifier_val').val().trim();
	if (!$verifier)
	{
		show_verifier();
		return;
	}
	
	//cb_clk();
	
	$url = $S_N+"user_actions/verify_verifier";
	
	$.ajax({type:"POST",url: $url,
			data:{xid: $xid, type : 'oc', verifier : $verifier}, 
			success : function(data) {
				//console.log(data);
				$result = IsJsonString(data)
				if ($result)
				{
					if ($result.success)
					{
						$('._verifier_result').html("<green_button>Valid Coupon.</green_button>");
						show_used_val_in_verifier($xid,$verifier,$result.used);
					}
					else
					{
						$('._verifier_result').html("<black_button>Invalid Coupon.</black_button>");
						show_used_val_in_verifier(0,$verifier,0);
					}
				}
				
			},
			error : function(data) {
			   //alert("false");
			   //show_success_message("FB page Url does not seem right.");
			}
		});
		
}

</script>

<?php
if($is_admin)
{
	$company_data_select_list = array();
	
	foreach ($company_data as $company_id => $company)
	{
		$company_data_select_list[$company_id] = $company['name'] . " " . $company['website'];
	}
	
	$listing_by_cid = SITE_NAME . "open_campaigns/index/";		
	echo $this->Form->input('company_id', array(
		'options' => $company_data_select_list,
		'onchange' => "cid=$(this).val();moveTo('{$listing_by_cid}'+cid);",
		'label' => 'See by Company'
	));
}
?>

<?php
$show_product_name = false;
?>

<div class="openCampaigns">
	<h2><?php 
			if (!empty($comp_name))
			{
				echo __($comp_name . "'s Campaigns"); 
			}
			else
			{
				echo __('Your Campaigns');
			}
		?>
	</h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<?php
		if ($show_comp_name) {
		?>
			<th><?php echo $this->Paginator->sort('company'); ?></th>
		<?php
		}
		?>
			<?php if ($show_product_name)
				{
					echo "<th>"; echo $this->Paginator->sort('Campaign For'); echo "</th>"; 
				}
			?>
			<th><?php echo $this->Paginator->sort('Promo Platform'); ?></th>
			<th><?php echo $this->Paginator->sort('Coupon Code'); ?></th>
			<th><?php echo $this->Paginator->sort('Running ?'); ?></th>
			<th class="actions"></th>
	</tr>
	<?php foreach ($openCampaigns as $openCampaign): ?>
	<tr>
		<?php
		if ($show_comp_name) {
		?>
		<td><?php 
				$company_id = $openCampaign['OpenCampaign']['company_id'];
				echo h($company_data[$company_id]['name']); 
				//echo h($openCampaign['OpenCampaign']['company_id']); 
			?>&nbsp;</td>
		<?php
		}
		?>
		
		<?php 
		if ($show_product_name)
		{
			echo "<td>";
				$product_id = $openCampaign['OpenCampaign']['product_id'];
				if ($product_id == 0)
				{
					echo "Overall Company";
				}
				else
				{
					echo h($product_data[$product_id]['name']); 
				} 
				// echo h($openCampaign['OpenCampaign']['product_id']); 
			echo "&nbsp;</td>";
		}
		?>
		
		<td><?php 
				$promo_plat = $openCampaign['OpenCampaign']['type'];
				if ($promo_plat == 'blog')
				{
					echo "Blogs";
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
					echo "FB post";
				}
				else if ($promo_plat == 'fb_like_pic')
				{
					echo "FB like pic";
				}
				else if ($promo_plat == 'fb_like_page')
				{
					echo "FB like page";
				}
				else if ($promo_plat == 'fb_like_video')
				{
					echo "FB like video";
				}
				else if ($promo_plat == 'fb_post_video')
				{
					echo "FB post video";
				}
				else if ($promo_plat == 'fb_share_video')
				{
					echo "FB share video";
				}
				else if ($promo_plat == 'fb_event_share')
				{
					echo "FB share event";
				}
				else if ($promo_plat == 'fb_event_join')
				{
					echo "FB join event";
				}
				else if ($promo_plat == 'tweet' || $promo_plat == 'tw')
				{
					echo "Tweet";
				}
				else if ($promo_plat == 'single_email_ns_signup')
				{
					echo "Single Email Signup";
				}
				else if ($promo_plat == 'dual_email_ns_signup')
				{
					echo "Dual Email Signup";
				}
				else if ($promo_plat == 'giveaway')
				{
					echo "Give-away";
				}
				else if ($promo_plat == 'yelp_review')
				{
					echo "Review on Yelp";
				}
			?>
			&nbsp;
		</td>
		<td>
			<?php 
			echo $openCampaign['OpenCampaign']['coupon_code'];
			?>
		</td>
		<td><?php 
				$is_active = $openCampaign['OpenCampaign']['active'];
				if ($is_active == 1)
				{
					echo "Yes";
				}
				else if ($is_active == 0)
				{
					echo "No";
				}
			?>&nbsp;</td>
		<td class="actions">
			<?php 
				$promotions_link = SITE_NAME . "oc_responses/campaign_promotions/{$openCampaign['OpenCampaign']['id']}"; 
				echo "<a href=\"{$promotions_link}\">Promotions</a>";
					
				$onclick_activate_event = "activate_opencampaign({$openCampaign['OpenCampaign']['id']});";
				if (0 == $is_active)
				{
					echo "<a href='javascript: void(0);' onclick=\"{$onclick_activate_event}\">Start</a>";
				}
				else
				{
					$onclick_activate_event = "deactivate_opencampaign({$openCampaign['OpenCampaign']['id']});";
					echo "<a href='javascript: void(0);' onclick=\"{$onclick_activate_event}\">Stop</a>";
				}
			?>
			<?php 
				$onclick_activate_event = "getdetails_opencampaign({$openCampaign['OpenCampaign']['id']});";
				echo "<a href='javascript: void(0);' onclick=\"{$onclick_activate_event}\">Details</a>";
			?>
			<?php //echo $this->Html->link(__('View'), array('action' => 'view', $openCampaign['OpenCampaign']['id'])); ?>
			<?php 
				if ($is_active == 0)
				{
					echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $openCampaign['OpenCampaign']['id']), null, __('Are you sure you want to delete # %s?', $openCampaign['OpenCampaign']['id'])); 
				}
			?>
			
			<?php
				$onclick_activate_event = "show_verifier({$openCampaign['OpenCampaign']['id']});";
				echo "<a href='javascript: void(0);' onclick=\"{$onclick_activate_event}\">Verify Coupons</a>";
			?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<!--div class="actions">
	<h3><?php //echo __('Actions'); ?></h3>
	<ul>
		<li><?php //echo $this->Html->link(__('New Open Campaign'), array('action' => 'add')); ?></li>
	</ul>
</div-->
<div class="oc_activate_result" style="display:none"></div>
<div class="opencampaign_details_click_response" style="display:none"></div>