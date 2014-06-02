<script type="text/javascript" src="/AB/js/custom/opencampaign.js"></script>

<?php
//App::import('Helper', 'Youtube');
//$youtube_helper = new YoutubeHelper($this->view);
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
	echo "Promotional Content for ". $product['Product']['name'];
echo "</h2>";
/*
echo "<div style=\"float:left; width:800px; background:grey; color:white;\">";
$i = 0;
*/
foreach ($contents as $index => $content)
{
/*if ($i%2) echo "<div style=\"background:green; position:relative; clear:both\">";
else echo "<div style=\"background:orange; position:relative; clear:both\">";
$i = $i+1;

$product_name = " product id : " . $content['Content']['product_id'];
$company_name = " company id : " . $content['Content']['company_id'];
$company_site = "";

if (!empty($product_data[$content['Content']['product_id']]['name']))
{
	$product_name = $product_data[$content['Content']['product_id']]['name'];
}

if (!empty ($company_data[$content['Content']['company_id']]['name']))
{
	$company_name = $company_data[$content['Content']['company_id']]['name'];
	$company_site = $company_data[$content['Content']['company_id']]['website'];
}

echo "<div>";
echo $product_name;
echo "</div>";
echo " by ";
echo "<div>";
echo $company_name;
echo "</div>";
echo " ";
echo "<div>";
echo $company_site;
echo "</div>";
				
echo "<div>";
echo $content['Content']['title'];
echo "</div>";

echo "<div>";
echo $content['Content']['desc'];
echo "</div>";
*/

echo "<div class='content_news_box'>";
/*
$i = 0;
foreach ($contents as $index => $content)
{
if ($i%2) echo "<div style=\"background:green; position:relative; clear:both\">";
else echo "<div style=\"background:orange; position:relative; clear:both\">";
$i = $i+1;

$product_name = " product id : " . $content['Content']['product_id'];
$company_name = " company id : " . $content['Content']['company_id'];
$company_site = "";

if (!empty($product_data[$content['Content']['product_id']]['name']))
{
	$product_name = $product_data[$content['Content']['product_id']]['name'];
}

if (!empty ($company_data[$content['Content']['company_id']]['name']))
{
	$company_name = $company_data[$content['Content']['company_id']]['name'];
	$company_site = $company_data[$content['Content']['company_id']]['website'];
}

echo "<div>";
echo $product_name;
echo "</div>";
echo " by ";
echo "<div>";
echo $company_name;
echo "</div>";
echo " ";
echo "<div>";
echo $company_site;
echo "</div>";
*/				
$type = $content['Content']['type'];

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
		//echo "<video_play_button>";
		//echo "</video_play_button>";
	//echo "</video_image_wrapper>";
	/*
	//echo "<object width='". $video_w ."' height='". $video_h ."' data='". $video_link."' type='application/x-shockwave-flash'>";
	echo "<object width='". $video_w ."' height='". $video_h ."'>";
		echo "<param name='wmode' value='transparent' />";
		echo "<param value='true' name='allowFullScreen' />";
		echo "<param value='always' name='allowScriptAccess' />";
		echo "<param name='movie' value='" . $video_link. "' />";
		echo "<embed src='{$video_link}' 
					type='application/x-shockwave-flash' 
					allowfullscreen='true' 
					allowscriptaccess='always' 
					width='{$video_w}' 
					height='{$video_h}'
					>
				</embed>";
				
	echo "</object>";
	*/
	
	echo $reduced_content;
	echo "</content_video>";
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