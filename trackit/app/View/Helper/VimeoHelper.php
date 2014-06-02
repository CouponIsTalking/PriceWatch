<?php 
/** 
 * @name       Youtube Helper 
 * @author     Carly Marie 
 * @version    1.4 
 * @license    MIT License (http://www.opensource.org/licenses/mit-license.php) 
 * 
*/ 
App::uses('AppHelper', 'View/Helper');
    App::import('Helper', 'Html'); 
    class VimeoHelper extends HtmlHelper { 

        // An array of Youtube API's this helper will use 
        var $apis = array( 
            //'image'  => 'http://i.ytimg.com/vi/%s/%s.jpg', // Location of youtube images 
            //'player' => 'http://www.youtube.com/v/%s?%s'   // Location of youtube player 
			'query_from_video_id' => 'http://vimeo.com/api/v2/video/%s.php'
        ); 

		/*
		// All these settings can be changed on the fly using the $player_variables option in the video function 
        var $player_variables = array( 
            'type'              => 'application/x-shockwave-flash', 
            'class'             => 'youtube', 
            'width'             => 624,          // Sets player width 
            'height'            => 369,          // Sets player height 
            'allowfullscreen'   => 'true',       // Gives script access to fullscreen (This is required for the fs player setting to work)
            'allowscriptaccess' => 'always', 
            'wmode'             => 'transparent' // Ensures player stays under overlays such as lightbox/fancybox
        ); 

        // All these settings can be changed on the fly using the $player_settings option in the video function 
        var $player_settings = array( 
            'fs'        => true,   // Enables / Disables fullscreen playback 
            'hd'        => true,   // Enables / Disables HD playback (Chromeless player does not support this setting)
            'egm'       => false,  // Enables / Disables advanced context (Right-Click) menu 
            'rel'       => false,  // Enables / Disables related videos at the end of the video 
            'loop'      => false,  // Loops video once its finished 
            'start'     => 0,      // Start the video at X seconds 
            'version'   => 3,      // For chromeless player set version to 3 
            'autoplay'  => false,  // Automatically starts video when page is loaded 
            'autohide'  => false,  // Automatically hides controls once the video begins 
            'controls'  => true,   // Enables / Disables player controls (Chromeless Only) 
            'showinfo'  => false,  // Enables / Disables information like the title before the video starts playing
            'disablekb' => false,  // Enables / Disables keyboard controls 
            'theme'     => 'light' // Dark / Light style themes 
        ); 
		*/
		
        // Curl helper function
		function curl_get($url) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			$return = curl_exec($curl);
			curl_close($curl);
			return $return;
		}
		
		
		// gets the video related info 
        function get_video_info($url) { 

            // Sets the video ID for the image API 
            $video_id = $this->getVideoId($url); 

            // Build url to image file 
            $q_url = sprintf($this->apis['query_from_video_id'], $video_id); 
			
			$video_info_serialized = $this->curl_get($q_url);
			$video_info = unserialize($video_info_serialized);
			if (!empty($video_info[0]))
			{
				return $video_info[0];
			}
			
			return null;
        }		
		
		function video($url, $options){
			$video_id = $this->getVideoId($url);
			
			$req = "//player.vimeo.com/video/{$video_id}";
			
			$width_str="";
			if (!empty($options['width'])){
				$width_str = " width=\"{$options['width']}\"";
			}
			
			$height_str="";
			if (!empty($options['height'])){
				$height_str = " height=\"{$options['height']}\"";
			}
			
			$iframe_str = "
			<iframe src=\"{$req}\"{$width_str}{$height_str} frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen>
			</iframe> 
			";
			return $iframe_str;
		}

		function formatVideoUrl($url) {
			$video_id = $this->getVideoId($url);
			$req = "http://player.vimeo.com/video/%s?api=1&player_id=player1";
			$formatted_url = sprintf($req, $video_id);
			
			return $formatted_url;
		}

        // Extracts Video ID's from a Youtube URL 
        function getVideoId($url = null){ 
			
			$i_of_vimeo = strpos(strtolower($url), "vimeo.com");
			if (strlen($url) > $i_of_vimeo + 9)
			{
				$url = substr ($url, $i_of_vimeo + 9);
				$url = strtolower($url);
			
				$pieces = explode ('/', $url);
				$total_pieces = count($pieces);
				$i = 0;
				while ($i < $total_pieces)
				{
					if (strlen($pieces[$i]) > 2 && is_numeric($pieces[$i]))
					{
						return $pieces[$i];
					}
					$i = $i +1;
				}
			}
			
			return "";

        } 
		
		
    } 
?>