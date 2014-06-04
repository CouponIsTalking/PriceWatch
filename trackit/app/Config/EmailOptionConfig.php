<?php

// localserver
if ('localhost' == $_SERVER['HTTP_HOST']){
  class EmailOptionConfig {
	public static $addresses = array(
        'team_email' => 'team@usemenot.com',
        'no_reply_email' => 'team@usemenot.com'
		);
	}
}
// devapi smarthaggler
else if ('devapi.smarthaggler.com' == $_SERVER['HTTP_HOST']){
  class EmailOptionConfig {
	public static $addresses = array(
        'team_email' => 'team@usemenot.com',
        'no_reply_email' => 'team@usemenot.com'
		);
	}
}
// live server
else{
  class EmailOptionConfig {
	public static $addresses = array(
        'team_email' => 'team@couponistalking.com',
        'no_reply_email' => 'team@couponistalking.com'
		);
	}
}

?>