<?php

class MiscComponent extends Component {
	
	
	
	public function slugify($text)
	{ 
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

		// trim
		$text = trim($text, '-');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
		return 'n-a';
		}

		return $text;
	}
	
	public function random_slugify($text)
	{
		return $this->slugify($text) . '_' . strval(rand(1, 100000));
	}
	
	public function time_slugify($text)
	{
		return $this->slugify($text) . '_' . strval(time());
	}
	
	
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
}

?>