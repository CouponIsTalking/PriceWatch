<?php echo $this->Html->script('custom/update_resps'); ?>
<script type="text/javascript">
function RunOnLoad()
{
	MakeDragAndDrop('content_news_box_sidebar', 'adv_news_item_drop');
	MakeDragAndDrop('content_news_box_sidebar', 'adv_image_item_drop');
}
</script>

<div> <!--class="companies view"-->
<h2><?php echo h($company['Company']['name']); ?></h2>
<?php echo h($company['Company']['website']); ?>

<div style="float:right; text-align:right;" href="get_open_campaigns">
<?php 
	echo "Like {$company['Company']['name']} ?";
	echo "<br/>";
	echo "Used {$company['Company']['name']}'s products ?";
	echo "<br/>";
	echo "Worked at {$company['Company']['name']} ?";
	echo "<br/>";
	echo $company['Company']['name'] . " is looking for promoters.";
	echo "<br/>";
	echo $this->Html->link(__('See how you and '.$company['Company']['name'].' can help each other.'), array('action' => 'get_open_campaigns', $company['Company']['id']));
?>
</div>

	
</div>

<?php

echo "<div style='float:left;'>";
	//echo "<ul>";
		if (!empty($product_data))
		{
			echo "<li>";
			echo $this->Html->link(__('Products by '. $company['Company']['name']), array('controller' => 'products', 'action' => 'view_by_company', $company['Company']['id'])); 
			echo "</li>";
		}
		$link = SITE_NAME."/contents/view_by_company/{$company['Company']['id']}";
		$onclick_evt = "OpenInNewTab('{$link}');";
		echo "<div class='tag' onclick=\"{$onclick_evt}\">";
			echo "What {$company['Company']['name']} Does ?";
			//echo $this->Html->link(__('View stuff related to this company'), array('controller' => 'contents', 'action' => 'view_by_company', $company['Company']['id'])); 
		echo "</div>";
		
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



echo "<div class='content_roll_left' style=\"overflow-y:scroll;\">";
echo $this->element('content/company_content_grid_sidebar', array('contents' => $contents));
echo "</div>";

?>

<div style="margin-left:30%; width:600px; clear:both">


<?php

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
	$oc_id = $oc['OpenCampaign']['id'];
	
	echo "<div class=\"brand_promote\">";
	echo "<div class='hidden_oc_id' style='display:none'>{$oc['OpenCampaign']['id']}</div>";	
	
	if ($oc['OpenCampaign']['product_id'] == 0)
	{
		echo "<div style=\"float:left; margin-top:5%\">";
			echo "Promote " .$company['Company']['name']. " as a brand&nbsp";
		echo "</div>";
	}
	else if ($oc['OpenCampaign']['product_id'] > 0)
	{
		echo "<div style=\"float:left; margin-top:5%\">";
			echo "Promote " .$product_data['Product']['name']. "&nbsp";
		echo "</div>";
	}
	
	if ($oc['OpenCampaign']['type'] == 'blog')
	{
		echo "<div style=\"float:left; margin-top:5%\">";
			echo "on your Blog";
		echo "</div>";
	}
	else if ($oc['OpenCampaign']['type'] == 'fb_post')
	{
		echo "<div style=\"float:left; margin-top:5%\">";
			echo "on Facebook ";
		echo "</div>";
		echo "<div style='clear:both'>";
		echo "by posting something positive on facebook about {$company['Company']['name']}";
		echo "<br/>";
		//echo "with hashtag #{$company['Company']['name']} and give it a public visibility.";
		echo "At the end of campaign, rewards will be given based on minimum conditions set. In case of tie, the post created first will be prioritized.";
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
				echo "<input id='blogpost_link{$oc_id}'> </input>";
				echo "<div id='redeem_result{$oc_id}'></div>";
				echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"send_blog_response({$oc_id})\">Redeem</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'fb_post')
		{
			echo "<div style=\"width:400px; margin-left:5%; margin-right:5%;\">";
				echo "<br/>";
				echo "Write one or two lines on - ";
				echo "<br/>";
				echo "why {$company['Company']['name']} is awesome";
				echo " Or <br/>";
				echo "what makes you love {$company['Company']['name']}";
				echo " Or <br/>";
				echo "why people should use {$company['Company']['name']}";
				echo " Or <br/>";
				echo "how {$company['Company']['name']} is different than others";
				echo "<div class='adv_image_item_drop'>";
					echo "Drag and drop an image from left, to link with your facebook post.";
				echo "</div>";
				echo "<div class='adv_news_item_drop'>";
					echo "Drag and drop a news item from left, to link with your facebook post.";
					echo "<adv_box></adv_box>";
				echo "</div>";
				echo "<input id='fb_post_content{$oc_id}'> </input>";
				echo "<div id='post_on_fb{$oc_id}'></div>";
				echo "<button inst=$oc_id class=\"redeem_button\" onclick=\"post_on_facebook({$oc_id})\">Post on Facebook</button>";
			echo "</div>";
		}
		else if ($oc['OpenCampaign']['type'] == 'reddit')
		{
			echo "<div style=\"width:400px; margin-left:5%; margin-right:5%;\">";
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
			echo "<div style=\"width:400px; margin-left:5%; margin-right:5%;\">";
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
	
}

echo "</div>";

?>