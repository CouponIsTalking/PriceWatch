<script type="text/javascript" src="/AB/js/custom/opencampaign.js"></script>

<?php

/*
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
	*/
echo "<h2>";
	echo "What ". $company['Company']['name'] . " is Doing ?";
	
echo "</h2>";

echo $this->element('content/company_content_grid', array('contents' => $contents, 'aLayout' => 'dynamic'));
/*				
foreach ($contents as $index => $content)
{

echo "<div class='content_news_box'>";
			
$type = $content['Content']['type'];

if (!empty($editable) && $editable == true)
{
	echo "<toggle_state>";
		$active = $content['Content']['state'];
		if (!$active)
		{
			$onclick_event = "set_content_state('{$content['Content']['id']}', 1);";
			echo "<a onclick=\"{$onclick_event}\">Activate</a>";
		}
		else
		{
			$onclick_event = "set_content_state('{$content['Content']['id']}', 0);";
			echo "<a onclick=\"{$onclick_event}\">Deactivate</a>";
		}
	echo "</toggle_state>";
}

echo "<heading>";
$heading = $content['Content']['title'];
$lheading = strtolower($heading);

if ($type == 'image' && (strpos($lheading, '.png') > 0|| strpos($lheading, '.jpeg')>0 || strpos($lheading, '.jpg') >0 || strpos($lheading, '.bmp') >0) )
{
	// dont print title
}
else
{
	$link = $content['Content']['link'];
	if (!empty($link))
	{
		$onclick_event = "OpenInNewTab('{$link}')";
		echo "<div onclick=\"{$onclick_event};\">{$heading}</div>";
		
	}
	else
	{
		echo "<div>{$heading}</div>";
	}
}

echo "</heading>";



if ($type == 'news')
{
	echo "<content_news>";
	$reduced_content = $this->CommonFunc->reduceContent($content['Content']['desc'], 500);

	echo $reduced_content;
	echo "</content_news>";
}
else if ($type == 'image')
{
	echo "<content_image>";
	$imagename = "";
	$fullpath = "";
	
	if (!empty($content['Content']['link']))
	{
		$imagename = $content['Content']['link'];		
	}
	else
	{
		$imagename = $content['Content']['title'];		
	}
	
	if ( strpos($imagename, '/') > 0)
	{
		$fullpath = $imagename;
	}
	else
	{
		$fullpath = CONTENT_IMG_FOLDER.$imagename;
	}
	$reduced_content = $this->CommonFunc->reduceContent($content['Content']['desc'], 500);
	
	//debug($fullpath);
	echo "<img src='{$fullpath}' onclick=\"zoomImage('{$fullpath}');\"></img>";
	echo $reduced_content;
	echo "</content_image>";
}
else if ($type == 'video')
{
	echo "<content_video>";
	
	$video_link = $content['Content']['link'];		
	$reduced_content = $this->CommonFunc->reduceContent($content['Content']['desc'], 500);
	
	//debug($fullpath);
	$video_w = 425;
	$video_h = 350;
	
	//echo "<video_image_wrapper>";
	if (strpos($video_link, 'youtube.com') > 0)
	{
		$video_thumbnail_src = $this->Youtube->get_thumbnail_src($video_link, 'large');
		$play_button_url = SITE_NAME . "/img/video_play_button.png";
		$formatted_video_link = $this->Youtube->formatVideoUrl($video_link, array('autoplay'=>true), array());
		
		echo "<img onclick=\"zoomYoutubeVideo('{$formatted_video_link}')\" src='{$play_button_url}' style=\" background:URL('{$video_thumbnail_src}')\"/>";
	
	}
	else if (strpos($video_link, 'vimeo.com') > 0)
	{
		$video_info = $this->Vimeo->get_video_info($video_link);
		$video_thumbnail_src = $video_info['thumbnail_large'];
		$play_button_url = SITE_NAME . "/img/video_play_button.png";
		$formatted_video_link = $this->Vimeo->formatVideoUrl($video_link);
		
		echo "<img onclick=\"zoomVimeoVideo('{$formatted_video_link}')\" src='{$play_button_url}' style=\" background:URL('{$video_thumbnail_src}')\"/>";
	
	}
	
	echo $reduced_content;
	echo "</content_video>";
	//debug($video_info);
		
}


echo "<bottom>";
echo "</bottom>";

?>

<!--td class="actions">
	<?php //echo $this->Html->link(__('View'), array('action' => 'view', $content['Content']['id'])); ?>
	<?php //echo $this->Html->link(__('Edit'), array('action' => 'edit', $content['Content']['id'])); ?>
	<?php //echo $this->Html->link(__('Delete'), array('action' => 'delete', $content['Content']['id']), null, __('Are you sure you want to delete # %s?', $content['Content']['id'])); ?>
	<?php //echo $this->Html->link(__('Pricing'), array('controller' => 'content_prices', 'action' => 'get_content_pricing', $content['Content']['id'])); ?> 
</td-->

<?php

echo "</div>";
}
*/
//echo "</div>";
?>