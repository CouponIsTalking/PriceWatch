<?php

class VidComponent extends Component {
	
	var $components = array('Misc');
	
	public function vid_type($url)
	{
		if ($this->is_youtube($url)){
			return "youtube";
		}else if ($this->is_youtube($url)){
			return "vimeo";
		}else{
			return "";
		}
	}
	
	public function is_youtube($url)
	{
		$url = strtolower($url);
		$p = parse_url($url);
		
		if (!empty($p['host'])
			&&(strpos($p['host'], "youtube.com") !== FALSE))
		{
			return true;
		}
		
		return false;
	}
	
	public function is_vimeo($url)
	{
		$url = strtolower($url);
		$p = parse_url($url);
		
		if (!empty($p['host'])
			&&(strpos($p['host'], "vimeo.com") !== FALSE))
		{
			return true;
		}
		
		return false;
	}
	
	public function get_large_thumbmail_src($vid_url){
		
		$thumbnail_src = "";
		
		$vid_url = trim($vid_url);
		if (empty($vid_url)){return $thumbnail_src;}
		
		$vid_url = $this->Misc->addhttp($vid_url);		
		
		if ($this->is_youtube($vid_url)){
			$thumbnail_src = $this->get_youtube_thumbnail_src($vid_url);
		}
		else if ($this->is_vimeo($vid_url)){
			$thumbnail_src = $this->get_vimeo_thumbnail_src($vid_url);
			
		}
		
		return $thumbnail_src;
		
	}
	
	// Extracts Vimeo Video ID's from a Youtube URL 
	function getVimeoVideoId($url = ""){
	
		$i_of_vimeo = strpos(strtolower($url), "vimeo.com");
		if (strlen($url) > $i_of_vimeo + 9){
			$url = substr ($url, $i_of_vimeo + 9);
			$url = strtolower($url);
		
			$pieces = explode ('/', $url);
			$total_pieces = count($pieces);
			$i = 0;
			while ($i < $total_pieces){
				if (strlen($pieces[$i]) > 2 && is_numeric($pieces[$i])){
					return $pieces[$i];
				}
				$i = $i +1;
			}
		}
		return "";
	}
	
	// Extracts Youtube Video ID's from a Youtube URL 
	function getYoutubeVideoId($url = ""){ 
		parse_str(parse_url($url, PHP_URL_QUERY), $params); 
		return (isset($params['v']) ? $params['v'] : $url); 
	}
	
	// Outputs Youtube video image 
	function get_youtube_thumbnail_src($url, $size = 'large', $options = array()){
		$video_id = $this->getYoutubeVideoId($url);
		// Humanized array of allowed image sizes 
		$accepted_sizes = array( 
			'thumb'  => 'default', // 120px x 90px 
			'large'  => 0,         // 480px x 360px 
			'thumb1' => 1,         // 120px x 90px at position 25% 
			'thumb2' => 2,         // 120px x 90px at position 50% 
			'thumb3' => 3          // 120px x 90px at position 75% 
		); 

		// Build url to image file 
		$image_url = sprintf("http://i.ytimg.com/vi/%s/%s.jpg", $video_id, $accepted_sizes[$size]); 
		
		return $image_url;
	}
	
	// gets the Vimeo video related info 
	function get_vimeo_thumbnail_src($url) { 

		// Sets the video ID for the image API 
		$video_id = $this->getVimeoVideoId($url); 

		// Build url to image file 
		$q_url = sprintf("http://vimeo.com/api/v2/video/%s.php", $video_id); 
		
		//$video_info_serialized = $this->curl_get($q_url);
		$curl = curl_init($q_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 2);
		$video_info_serialized = curl_exec($curl);
		curl_close($curl);
		
		$video_info = unserialize($video_info_serialized);
		if (!empty($video_info[0]['thumbnail_large']))
		{
			return $video_info[0]['thumbnail_large'];
		}
		
		return "";
	}
}

?>