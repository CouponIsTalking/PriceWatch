<?php
class CommonFuncHelper extends AppHelper{

	// common functions
	function addhttp($url) {
		if (empty($url))
		{
			return "";
		}
		
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}
	
	function reduceContent($full_length_content, $max_chars)
	{
		$content_length = strlen($full_length_content);
		if ($content_length > $max_chars)
		{
			return substr($full_length_content,0, $max_chars - 3) . "...";	
		}
		else
		{
			return $full_length_content;
		}
	}
	
	function full_url($s)
	{
		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
		$sp = strtolower($s['SERVER_PROTOCOL']);
		$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
		$port = $s['SERVER_PORT'];
		$port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? '' : ':'.$port;
		$host = isset($s['HTTP_X_FORWARDED_HOST']) ? $s['HTTP_X_FORWARDED_HOST'] : isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : $s['SERVER_NAME'];
		return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
	}
	
	function get_full_current_url()
	{
		return $this->full_url($_SERVER);
	}
	
	function create_html_quoted_string($data, $double_encode)
	{
		$encoding = mb_detect_encoding($data);
		$flags = ENT_QUOTES | ENT_HTML401;
		
		//return htmlspecialchars ($data, $flags, $encoding, $flags);
		$encoded_data = str_replace(array("<", ">", "&","\"", "'"), array("&lt;", "&gt;", "&amp;", "&quot;", "&#039;"), $data);
		if ($double_encode)
		{
			$encoded_data = str_replace(array("<", ">", "&","\"", "'"), array("&lt;", "&gt;", "&amp;", "&quot;", "&#039;"), $encoded_data);
			
		}
		return $encoded_data;
	}
	
	function get_tweet_url_by_user_and_id($user_name, $id)
	{
		if (empty($user_name) || empty($id))
		{
			return "";
		}
		
		$id = strval($id);
		$tweet_url = "https://twitter.com/{$user_name}/status/{$id}";
		return $tweet_url;
	}

}
?>