<?php

foreach ($contents as $index => $content)
{

//debug($aLayout);

if (!( ('static' == $aLayout && (1==$content['Content']['has_fixed_social_coupons'])) 
	|| ('dynamic' == $aLayout && (0 == $content['Content']['has_fixed_social_coupons']))
	|| ('show_all' == $aLayout)
	)
	)//$content['Content']['has_fixed_social_coupons'])
{
	continue;
}

echo "<div class='content_news_box'>";
			
$type = $content['Content']['type'];

if (!empty($editable) && $editable == true)
{
	echo "<toggle_state>";
		$active = $content['Content']['state'];
		$is_simple_coupon = $content['Content']['has_fixed_social_coupons'];
		
		if (!$active)
		{
			$onclick_event = "set_content_state('{$content['Content']['id']}', 1);";
			if ($is_simple_coupon)
			{
				echo "<a onclick=\"{$onclick_event}\">start this coupon</a>";
			}
			else
			{
				echo "<a onclick=\"{$onclick_event}\">enable</a>";
			}
		}
		else
		{
			$onclick_event = "set_content_state('{$content['Content']['id']}', 0);";
			//echo "<a onclick=\"{$onclick_event}\">disable</a>";
			if ($is_simple_coupon)
			{
				echo "<a onclick=\"{$onclick_event}\">stop this coupon</a>";
			}
			else
			{
				echo "<a onclick=\"{$onclick_event}\">disable</a>";
			}
		}
			
	echo "</toggle_state>";
	
	if ($is_simple_coupon)
	{
		echo "<toggle_state>";
			$promo_page = SITE_NAME . "content_promotions/campaign_promotions/{$content['Content']['id']}";
			$onclick_event = "OpenInNewTab('{$promo_page}')";
			echo "<a onclick=\"{$onclick_event}\">Promotions</a>";
		echo "</toggle_state>";
	}
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
	
	$fbobjectid = $content['Content']['fbobjectid'];
	$video_link = $content['Content']['link'];		
	//$reduced_content = $this->CommonFunc->reduceContent($content['Content']['desc'], 500);
	
	//debug($fullpath);
	$video_w = 425;
	$video_h = 350;
	
	//echo "<video_image_wrapper>";
	if (!empty($fbobjectid))
	{
		$embed_html = $content['Content']['fbobjecturl'];
		$starts_with_iframe = strpos($embed_html, "<iframe");
		if (FALSE !== $starts_with_iframe && 0==$starts_with_iframe)
		{
			$video_thumbnail_src = $content['Content']['link'];
			$play_button_url = SITE_NAME . "/img/video_play_button.png";
			$formatted_video_link = "";//$this->Youtube->formatVideoUrl($video_link, array('autoplay'=>true), array());
			echo "<img onclick=\"zoomFBVideoFromCommentedIframe($(this).parent().find('div.__fb_vid_iframe').html());\" src='{$play_button_url}' style=\"background:URL('{$video_thumbnail_src}') center no-repeat;\"/>";
			echo "<div class='__fb_vid_iframe'><!-- " . $embed_html . " --></div>";
		}
		else
		{
			echo $embed_html;
		}
	}
	else if (strpos($video_link, 'youtube.com') > 0)
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

echo "<div style='clear:both'></div>";

if ('static' == $aLayout || 'show_all' == $aLayout)
{

echo "<div>";

if ($content['Content']['has_fixed_social_coupons'])
{
echo "<h4>";
echo "This is a Simple social coupon, which means that its promotions are fully decided by the business.";
echo "</h4>";
echo "<br/>";

echo "<h3>";
echo "FB Share Title              - {$content['Content']['fb_offer']}";
echo "<br/>";
echo "Coupon shown after FB Share - {$content['Content']['fb_coupon_code']}";
echo "<br/>";
echo "<br/>";
echo "Tweet Line                  - {$content['Content']['tw_offer']}";
echo "<br/>";
echo "Coupon shown after Tweet    - {$content['Content']['tw_coupon_code']}";
echo "<br/>";
echo "</h3>";
echo "</div>";

echo "<h4> Copy the code below to place this simple social coupon within your website</h4>";

$import_iframe_code = SITE_NAME . "socials/share_n_get_coupon_button/".strval($content['Content']['unix_timestamp']) . "_" . strval($content['Content']['id']);
echo "
<textarea id='code' class='b2-text' rows='5' cols='150' border='2' readonly='' style='font-size:12px;'>
<iframe src=\"{$import_iframe_code}\" frameBorder='0' height='27' width='75' border='0' marginheight='0' marginwidth='0'></iframe>
</textarea>
";

/*
$import_iframe_code = "<img onclick=\"var etu=encodeURIComponent(window.top.location.href); var url='http://alpha.usemenot.com/trackit/contents/getcoupon_button_click?&id={$content['Content']['unix_timestamp']}&url='+etu;
	window.open(url, 'Coupons via usemenot.com','_blank','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=300,left = 10,top = 10');\" src='http://alpha.usemenot.com/trackit/img/get-coupons.png' style='width:60px;height:19px; display:block; cursor:pointer; padding:0px; margin: 0px; border:0px;' onmouseover=\"this.src='http://alpha.usemenot.com/trackit/img/get-coupons-hover.png'\" onmouseout=\"this.src='http://alpha.usemenot.com/trackit/img/get-coupons.png'\">
</img>";

echo "
<textarea id='code' class='b2-text' rows='5' cols='150' border='2' readonly='' style='font-size:12px;'>
{$import_iframe_code}
</textarea>
";
*/

}
else
{
echo "<h4>";
echo "This is a Promotional Content for Customizable dynamic Coupon.";
echo "</h4>";
echo "<br/>";
echo "</div>";
}

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