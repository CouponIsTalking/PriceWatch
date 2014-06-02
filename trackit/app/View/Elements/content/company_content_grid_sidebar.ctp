<?php

if (empty($content_type))
{
	$content_type = false;
}

foreach ($contents as $index => $content)
{

			
$type = $content['Content']['type'];

if($content_type && ($content_type != $type))
{
	continue;
}

echo "<div class='content_news_box_sidebar'>";

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
$single_encoded_heading = $this->CommonFunc->create_html_quoted_string ($content['Content']['title'], false);
$double_encoded_heading = $this->CommonFunc->create_html_quoted_string ($single_encoded_heading, false);
//$heading = $single_encoded_heading;

$lheading = strtolower($heading);

if ( ('image'==$type))// && (strpos($lheading, '.png') > 0|| strpos($lheading, '.jpeg')>0 || strpos($lheading, '.jpg') >0 || strpos($lheading, '.bmp') >0) )
{
	// dont print title
}
else
{
	$link = $this->CommonFunc->create_html_quoted_string ($content['Content']['link'], true);
	$single_encoded_link = $this->CommonFunc->create_html_quoted_string ($link, false);
	$double_encoded_link = $this->CommonFunc->create_html_quoted_string ($single_encoded_link, false);
	
	if (!empty($link))
	{
		$onclick_event = "OpenInNewTab('{$double_encoded_link}')";
		echo "<div onclick=\"{$onclick_event};\">{$single_encoded_heading}</div>";
		
	}
	else
	{
		echo "<div>{$single_encoded_heading}</div>";
	}
}

echo "</heading>";


if ($type == 'news')
{
	if ('image' == $content_type)
	{
		echo "<black_button onclick=\"update_image_in_ad('{$fullpath}', 0);\">Pick This.</black_button>";
		echo "<br/>";
	}
	else if ('news' == $content_type)
	{
		echo "<black_button onclick=\"update_news_in_ad('{$double_encoded_heading}', '{$double_encoded_link}', $(this));\">Pick This.</black_button>";
		echo "<br/>";
	}
	else
	{
		echo "<black_button onclick=\"update_news_in_ad('{$double_encoded_heading}', '{$double_encoded_link}', $(this));\">Pick This.</black_button>";
		echo "<br/>";
	}
	
	echo "<content_news onclick=\"update_news_in_ad('{$double_encoded_heading}', '{$double_encoded_link}', $(this).siblings('black_button').first());\">";
	$reduced_content = $this->CommonFunc->reduceContent($content['Content']['desc'], 500);

	echo $reduced_content;
	echo "</content_news>";
}
else if ($type == 'image')
{
	
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
	
	$single_encoded_fullpath = $this->CommonFunc->create_html_quoted_string ($fullpath, false);
	$double_encoded_fullpath = $this->CommonFunc->create_html_quoted_string ($single_encoded_fullpath, false);
	$reduced_content = $this->CommonFunc->reduceContent($content['Content']['desc'], 500);
	
	if ('image' == $content_type)
	{
		echo "<black_button onclick=\"update_image_in_ad('{$double_encoded_fullpath}', 0);\">Pick This.</black_button>";
		echo "<br/>";
	}
	else if ('news' == $content_type)
	{
		continue;
		echo "<black_button onclick=\"update_news_in_ad('{$double_encoded_heading}', '{$double_encoded_fullpath}', $(this));\">Pick This.</black_button>";
		echo "<br/>";
	}
	else
	{
		echo "<black_button onclick=\"update_image_in_ad('{$double_encoded_fullpath}', 0);\">Pick This.</black_button>";
		echo "<br/>";
	}
	
	//debug($fullpath);
	echo "
		<content_image>";
			//echo "<img src='{$single_encoded_fullpath}' onclick=\"zoomImage('{$double_encoded_fullpath}');\"></img>";
	echo"	<img src='{$single_encoded_fullpath}' onclick=\"update_image_in_ad('{$double_encoded_fullpath}', 0);\"></img>
			{$reduced_content}
		</content_image>
		";
}
else if ($type == 'video')
{
	echo "<content_video>";
	
	$video_link = $content['Content']['link'];		
	//$reduced_content = $this->CommonFunc->reduceContent($content['Content']['desc'], 500);
	
	//debug($fullpath);
	$video_w = 425;
	$video_h = 350;
	
	//echo "<video_image_wrapper>";
	if (strpos($video_link, 'youtube.com') > 0)
	{
		$video_thumbnail_src = $this->Youtube->get_thumbnail_src($video_link, 'large');
		$play_button_url = SITE_NAME . "/img/video_play_button.png";
		$formatted_video_link = $this->Youtube->formatVideoUrl($video_link, array('autoplay'=>true), array());
		
		echo "<img onclick=\"zoomYoutubeVideo('{$formatted_video_link}')\" src='{$play_button_url}' style=\" background:URL('{$video_thumbnail_src}') center no-repeat;\"/>";
	
	}
	else if (strpos($video_link, 'vimeo.com') > 0)
	{
		$video_info = $this->Vimeo->get_video_info($video_link);
		$video_thumbnail_src = $video_info['thumbnail_large'];
		$play_button_url = SITE_NAME . "/img/video_play_button.png";
		$formatted_video_link = $this->Vimeo->formatVideoUrl($video_link);
		
		echo "<img onclick=\"zoomVimeoVideo('{$formatted_video_link}')\" src='{$play_button_url}' style=\" background:URL('{$video_thumbnail_src}') center no-repeat;\"/>";
	
	}
	
	//echo $reduced_content;
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
//echo "</div>";
?>