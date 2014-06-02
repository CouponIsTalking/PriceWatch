<?php

//echo "<div class='ccp_div_html'>";

echo "<div class='content_picker_msg'></div>";
echo "<div class='content_roll_left' style=\"height:auto; width:auto; max-height:100%; max-width:100%; overflow:scroll;\">";

if (empty($content_type))
{
	$content_types = array('news', 'image', 'video');
}
else if (is_array($content_type))
{
	$content_types = $content_type;
}
else
{
	$content_types = array($content_type);
}

foreach($content_types as $ct_index => $content_type)
{

echo "<div style=\"overflow:hidden;\">";

	foreach ($contents as $index => $content)
	{

		$content_id = $content['Content']['id'];		
		$type = $content['Content']['type'];
		$active = $content['Content']['state'];
		
		if($content_type && ($content_type != $type))
		{
			continue;
		}
		
		if ('active' == $show_only && (!$active))
		{
			continue;
		}
		
		if (!empty($settings['show_with_fbid_only']) 
			&& (true == $settings['show_with_fbid_only'])
			&& empty($content['Content']['fbobjectid'])
			)
		{
			continue;
		}
		
		echo "<div class='content_news_box_sidebar' style='width:25%;height:25%;'>";

		if (!empty($editable) && $editable == true)
		{
			echo "<toggle_state>";
				//$active = $content['Content']['state'];
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

		echo "<heading style='font-size:12px;'>";

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
			$on_select_evt = "__select_news_btn_click($(this));";
			$on_deselect_evt = "";
			echo _get_content_picker_button_code($content_id, $on_select_evt, $on_deselect_evt);
			
			echo "<content_news style='height:20%;' onclick=\"update_news_in_ad('{$double_encoded_heading}', '{$double_encoded_link}', $(this).siblings('black_button').first());\">";
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
			$reduced_content = '';//$this->CommonFunc->reduceContent($content['Content']['desc'], 500);
			
			$fbobjecturl = $content['Content']['fbobjecturl'];
			$fbobjectid = $content['Content']['fbobjectid'];
			
			$on_select_evt = "__select_image_btn_click($(this));";
			$on_deselect_evt = "";
			
			echo _get_content_picker_button_code($content_id, $on_select_evt, $on_deselect_evt);
			/*
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
			*/
			//debug($fullpath);
			echo "
				<content_image>";
					//echo "<img src='{$single_encoded_fullpath}' onclick=\"zoomImage('{$double_encoded_fullpath}');\"></img>";
			echo"	<img style='' src='{$single_encoded_fullpath}' onclick=\"update_image_in_ad('{$double_encoded_fullpath}', $(this));\"></img>
					<div class='__fou' style='display:none;'>{$fbobjecturl}</div>
					<div class='__foid' style='display:none;'>{$fbobjectid}</div>
					<div class='__desc' style='display:none;'>{$reduced_content}</div>
				</content_image>
				";
		}
		else if ($type == 'video')
		{
			echo "<content_video>";
			
			$play_button_url = SITE_NAME . "img/video_play_button.png";
			$video_link = $content['Content']['link'];		
			$fbobjectid = $content['Content']['fbobjectid'];		
			//$reduced_content = '';//$this->CommonFunc->reduceContent($content['Content']['desc'], 500);
			
			//debug($fullpath);
			$video_w = 425;
			$video_h = 350;
			
			//echo "<video_image_wrapper>";
			$on_select_evt = "__select_video_btn_click($(this));";
			$on_deselect_evt = "";
			echo _get_content_picker_button_code($content_id, $on_select_evt, $on_deselect_evt);
			
			
			
			//echo "<video_image_wrapper>";
			if (!empty($fbobjectid))
			{
				$video_thumbnail_src = $content['Content']['link'];
				$fbobjecturl = "http://www.facebook.com/{$fbobjectid}";//$content['Content']['fbobjectid'];
				
				$single_encoded_fullpath = $this->CommonFunc->create_html_quoted_string ($video_thumbnail_src, false);
				$double_encoded_fullpath = $this->CommonFunc->create_html_quoted_string ($single_encoded_fullpath, false);
			
				//echo "<img onclick=\"zoomFBVideo('span.__{$fbobjectid}fbvideoiframe')\" src=\"{$play_button_url}\" style=\"max-width:90%; max-height:90%; background: url('{$video_thumbnail_src}') center no-repeat;\"/>";
				echo "
				<content_fb_video>
					<img onclick=\"update_video_in_ad('{$double_encoded_fullpath}', $(this));\" src=\"{$play_button_url}\" style=\"background: url('{$video_thumbnail_src}') center no-repeat;\"/>
					<div class='__fou' style='display:none;'>{$fbobjecturl}</div>
					<div class='__foid' style='display:none;'>{$fbobjectid}</div>
				</content_fb_video>
					";
				//echo "<span style='display:none' class='__{$fbobjectid}fbvideoiframe'>{$fbobjecturl}</span>";
			}
			else if (strpos($video_link, 'youtube.com') > 0)
			{
				$video_thumbnail_src = $this->Youtube->get_thumbnail_src($video_link, 'large');
				$formatted_video_link = $this->Youtube->formatVideoUrl($video_link, array('autoplay'=>true), array());
				
				echo "<img onclick=\"zoomYoutubeVideo('{$formatted_video_link}')\" src='{$play_button_url}' style=\" background:URL('{$video_thumbnail_src}') center no-repeat\"/>";
			
			}
			else if (strpos($video_link, 'vimeo.com') > 0)
			{
				$video_info = $this->Vimeo->get_video_info($video_link);
				$video_thumbnail_src = $video_info['thumbnail_large'];
				$play_button_url = SITE_NAME . "/img/video_play_button.png";
				$formatted_video_link = $this->Vimeo->formatVideoUrl($video_link);
				
				echo "<img onclick=\"zoomVimeoVideo('{$formatted_video_link}')\" src='{$play_button_url}' style=\" background:URL('{$video_thumbnail_src}') center no-repeat\"/>";
			
			}
			
			//echo $reduced_content;
			echo "</content_video>";
			//debug($video_info);
				
		}


	echo "<bottom>";
	echo "</bottom>";

	echo "</div>";
	}

echo "</div>";
echo "<div style='clear:both'></div>";
}

echo "</div>";

/*
echo "
<div style='float:left' onclick=\"_select_all($(this));\">
<black_button>Select All</black_button>
</div>
";
echo "
<div style='float:left' onclick=\"_deselect_all($(this));\">
<black_button>Un-Select All</black_button>
</div>
";

echo "
<div style='float:left' onclick=\"_finish_content_selection($(this), 1);\">
<black_button>Save and Continue</black_button>
</div>
";

echo "
<div style='float:left' onclick=\"_finish_content_selection($(this), 0);\">
<black_button>Don't Save</black_button>
</div>
";
*/

//echo "</div>";

?>