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
			echo __('Add your promotional stuff'); 
		echo "</legend>";
		
		/*
		echo "<h4>
			Dynamic coupons allow users to add their feedback and then create your promotion. Users will use the images, videos or news items you add here to create your advertisements.
		</h4>
		";
		*/
		
		/*
		$product_data_select_list = array();
		
		$product_data_select_list[0] = 'Overall Brand';
		foreach ($product_data as $product_id => $product)
		{
			$product_data_select_list[$product_id] = $product['name'];
		}
		*/
		
		echo "<div style='clear:both'></div>";
		echo "<left_part>";
		/*
		echo "<section>";
		echo $this->Form->input('product_id', array(
            'options' => $product_data_select_list,
			'default' => 0
        ));
		echo "</section>";
		*/
		
		$content_type = array();
		$content_type['news']  = 'News Or Blog';
		$content_type['image'] = 'Image';
		$content_type['video'] = 'Video';
		
		echo "<section>";
		echo $this->Form->input('type', array(
            'options' => $content_type,
			'onchange' => "ResetType();"
        ));
		echo "</section>";
		
		echo "<div style='clear:both'></div>";
		echo "<section>";
		echo $this->Form->input('news_link', array('label' => 'Link of the news or blog post'));
		echo "<br/>";
		echo $this->Form->input('video_link', array('label' => 'Link of video on youtube or vimeo (currently we support only these 2 platforms.)'));
		echo "<br/>";
		echo $this->Form->file('image', array('label' => 'Upload image', 'type' => 'file'));
		echo "</section>";
		
		echo "</left_part>";
		
		
		//echo "<div style='clear:both'></div>";
		echo "<section>";
		echo "<content_title>";
		echo $this->Form->input('title', array('label'=>'Tag line for this promotional content.', 'maxlength'=>100));
		echo "</content_title>";
		echo "</section>";
		echo "<section>";
		echo "<div style='clear:both'></div>";
		echo "<desc>";
		echo "<label>Few lines about this content (Promoters may use this directly, so pick attractive lines).</label>";
		echo $this->Form->textarea('desc', array('maxlength'=>1000));
		echo "</desc>";
		echo "</section>";
		
		
		echo "<section>";
			echo "<input type='submit' value='Add Promotional Content'  class='big_form_save_button'></input>";
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