<?php
echo $this->Html->script('c_coup/codechk'); 
?>
<script type="text/javascript">
function ResetType()
{
	$news_link = $("#ContentNewsLink");
	$video_link = $("#ContentVideoLink");
	$image = $("#ContentImage");
	$type = $("#ContentType").val();
	
	if ($type == 'news')
	{
		$news_link.removeAttr("disabled");
		$video_link.attr('disabled', 'disabled');
		$image.attr('disabled', 'disabled');
	}
	else if ($type == 'video')
	{
		$news_link.attr('disabled', 'disabled');
		$video_link.removeAttr("disabled");
		$image.attr('disabled', 'disabled');
	}
	else if ($type == 'image')
	{
		$news_link.attr('disabled', 'disabled');
		$video_link.attr('disabled', 'disabled');
		$image.removeAttr("disabled");		
	}
	
	return;
}

function validate_form($form)
{
	//$form = $("."+form_container_class).find('form');
	show_success_message("One moment, while we check few things..",0,0);
	
	$share_image = $form.find("#ContentImage").val();
	$share_line = $form.find("#ContentTitle").val();
	$share_desc = $form.find("#ContentDesc").val();
	$fb_offer = $form.find("#ContentFbOffer").val();
	$fb_coupon_code = $form.find("#ContentFbCouponCode").val();
	$tw_offer = $form.find("#ContentTwOffer").val();
	$tw_coupon_code = $form.find("#ContentTwCouponCode").val();

	var $error_msg = "";
	var $error_ele = 0;
	
	//alert($cids + "+" + $form.find("#OpenCampaignApprovedContentIds").html());
	if (typeof $share_image =='undefined' || !($share_image) || "" == $share_image.trim())
	{
		$error_msg = "Please select an image to be part of 'Facebook Share' or 'Tweet'";
		$error_ele = $form.find("#ContentImage").parent().first();
	}
	else if (typeof $share_line =='undefined' || !($share_line) || "" == $share_line.trim())
	{
		$error_msg = "Please enter 'Facebook Share Title' or 'Tweet' Line";
		$error_ele = $form.find("#ContentTitle").parent().first();
	}
	else if (typeof $share_desc =='undefined' || !($share_desc) || "" == $share_desc.trim())
	{
		$error_msg = "Please enter 'Facebook Share Description'";
		$error_ele = $form.find("#ContentDesc").parent().first();
	}
	else if (typeof $fb_offer =='undefined' || !($fb_offer) || "" == $fb_offer.trim())
	{
		$error_msg = "Please enter 'Facebook Coupon Offer'";
		$error_ele = $form.find("#ContentFbOffer").parent().first();
	}
	else if (typeof $fb_coupon_code =='undefined' || !($fb_coupon_code) || "" == $fb_coupon_code.trim())
	{
		$error_msg = "Please enter 'Facebook Coupon Code'";
		$error_ele = $form.find("#ContentFbCouponCode").parent().first();
	}
	else if (typeof $tw_offer =='undefined' || !($tw_offer) || "" == $tw_offer.trim())
	{
		$error_msg = "Please enter 'Twitter Coupon Offer'";
		$error_ele = $form.find("#ContentTwOffer").parent().first();
	}
	else if (typeof $tw_coupon_code =='undefined' || !($tw_coupon_code) || "" == $tw_coupon_code.trim())
	{
		$error_msg = "Please enter 'Twitter Coupon Code'";
		$error_ele = $form.find("#ContentTwCouponCode").parent().first();
	}
	
	
	if ("" == $error_msg)
	{
		$is_available = coupcodechk($fb_coupon_code);
		if ($is_available) { 
			$is_available = coupcodechk($tw_coupon_code); 
			if ($is_available){return true;}
			else{
				$error_msg = "This coupon code ("+$tw_coupon_code+") has already been used by you. Use the same code again? If you use the same code again, it won't be locked for the people who have already unlocked it before."
				$error_ele = $form.find("#ContentTwCouponCode").parent().first();
			}
		}
		else
		{
			$error_msg = "This coupon code ("+$fb_coupon_code+") has already been used by you. Use the same code again? If you use the same code again, it won't be locked for the people who have already unlocked it before."
			$error_ele = $form.find("#ContentFbCouponCode").parent().first();
		}		
	}
	
	show_success_message($error_msg, slide_to_view, $error_ele);
	fit_to_inner_content('div.success_msg');
	reposition_in_center('div.success_msg');
	return false;
		
}

function validate_and_submit_form($this)
{
	var $form = $this.closest('form');
	
	var $form_is_correct = validate_form($form);
	if (!$form_is_correct)
	{
		return false;
	}
	else
	{
		$("#form_submit").click();
	}
	
}

function RunOnLoad()
{
	ResetType();
}

</script>

<?php
echo "<div class='addcontent_add_form'>";
echo $this->Form->create('Content',array('type' => 'file')); 
	echo "<fieldset>";
		echo "<legend>";
			echo __("Create Simple Coupon."); 
		echo "</legend>";
		
		echo "<h4>
			'Simple' Coupon means that your promotional advertisement will be completely determined by you. Unlike this, Customizable coupons allow users to add their feedback and then create your promotion.
			</h4>
			";
			
		echo "<div style='display:none;'>";
			$product_data_select_list = array();
			
			$product_data_select_list[0] = 'Fill in the info that should be shared.';
					
			echo "<div style='clear:both'></div>";
			echo "<left_part>";
			
			
			echo "<section>";
			echo $this->Form->input('product_id', array(
				'options' => $product_data_select_list,
				'default' => 0
			));
			echo "</section>";
			
			$content_type = array();
			//$content_type['news']  = 'News Or Blog';
			$content_type['image'] = 'Image';
			//$content_type['video'] = 'Video';
			
			echo "<section>";
			
			echo $this->Form->input('type', array(
				'options' => $content_type,
				'onchange' => "ResetType();"
			));
			echo "</section>";
		
		echo "</div>";		
		
		echo "<div style='clear:both'></div>";
	/*	echo "<section>";
		//echo $this->Form->input('news_link', array('label' => 'Link of the news or blog post'));
		//echo "<br/>";
		//echo $this->Form->input('video_link', array('label' => 'Link of video on youtube or vimeo (currently we support only these 2 platforms.)'));
		//echo "<br/>";
		echo $this->Form->file('image', array('label' => 'Choose an image that you want to be part of facebook shares or tweets.', 'type' => 'file'));
		echo "</section>";
	*/
		echo "</left_part>";
		
		
		echo "<div style='clear:both'></div>";
		echo "<left_part>";
		echo "<section>";
			echo "<label style='right:0px; margin: 0px 0px 3px 0px; text-decoration:underline;'>Social Share Details.</label>";
		
			echo "<div style='clear:both'></div>";
			echo "<content_title>";
				echo "<label for=\"ContentImage\">Image for Facebook Share or Tweet.</label>";
				echo $this->Form->file('image', array('label' => 'Image for Facebook Share or Tweet.', 'type' => 'file'));
			echo "</content_title>";
			echo "<div style='clear:both'></div>";
			echo "<div style='margin: 3px 0px 0px 0px;'><br/></div>";
			echo "<div style='clear:both'></div>";
			echo "<content_title>";
				echo $this->Form->input('title', array('label'=>'Facebook Post or Tweet Line.', 'maxlength'=>80));
			echo "</content_title>";
			echo "<div style='clear:both'></div>";
			echo "<desc>";
				echo "<label>Description for Facebook Share.</label>";
				echo $this->Form->textarea('desc', array('maxlength'=>1000));
			echo "</desc>";
		echo "</section>";
		echo "</left_part>";
		
		echo "<div style='clear:both'></div>";
		
		echo "<div style='clear:both'></div>";
		echo "<section>";
			echo "<label style='right:0px; margin: 0px 0px 3px 0px; text-decoration:underline;'>Facebook Coupon Details.</label>";
			echo "<content_title>";
				echo $this->Form->input('fb_offer', array('label'=>'Facebook Coupon Offer.', 'maxlength'=>90));
			echo "</content_title>";
			echo "<div style='clear:both'></div>";
			echo "<content_title>";
				echo $this->Form->input('fb_coupon_code', array('label'=>'Facebook Coupon Code.', 'maxlength'=>18));
			echo "</content_title>";
		echo "</section>";
		
		echo "<div style='clear:both'></div>";
		
	/*	echo "<div style='clear:both'></div>";
		echo "<section>";
		echo "<content_title>";
		echo $this->Form->input('fb_coupon_code', array('label'=>'Coupon code to show after facebook share by user.', 'maxlength'=>100));
		echo "</content_title>";
		echo "</section>";
		
		echo "<div style='clear:both'></div>";
	*/	
		
		echo "<div style='clear:both'></div>";
		echo "<section>";
			echo "<label style='right:0px; margin: 0px 0px 3px 0px; text-decoration:underline;'>Twitter Coupon Details.</label>";
			echo "<content_title>";
				echo $this->Form->input('tw_offer', array('label'=>'Twitter Coupon Offer.', 'maxlength'=>90));
			echo "</content_title>";
			echo "<div style='clear:both'></div>";
			echo "<content_title>";
				echo $this->Form->input('tw_coupon_code', array('label'=>'Twitter Coupon Code.', 'maxlength'=>18));
			echo "</content_title>";
		echo "</section>";
		
		echo "<div style='clear:both'></div>";
		
	/*	echo "<section>";
		echo "<content_title>";
		echo $this->Form->input('tw_coupon_code', array('label'=>'Coupon code to show after tweet by user.', 'maxlength'=>100));
		echo "</content_title>";
		echo "</section>";
		
		echo "<div style='clear:both'></div>";
	*/	
	/*	echo "<section>";
		echo "<div style='clear:both'></div>";
		echo "<desc>";
		echo "<label>3-4 lines of description.</label>";
		echo $this->Form->textarea('desc', array('maxlength'=>1000));
		echo "</desc>";
		echo "</section>";
	*/	
		
		echo "<section>";
			echo "<green_button value='Create Simple Coupon' onclick=\"validate_and_submit_form($(this));\" disabled='true' class='big_form_save_button'>Create Simple Coupon</green_button>";
			echo "<input type='submit' id='form_submit' value='Create Simple Coupon' class='big_form_save_button' style='display:none;'></input>";
		echo "</section>";
		
		// echo $this->Form->input('topic1');
		/*echo $this->Form->input('topic1', array(
            'options' => $topic_data_select_list
        ));
		*/
		
		// echo $this->Form->input('state');
	echo "</fieldset>";

echo "</div>"
?>