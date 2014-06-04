<?php

// localserver
if ('localhost' == $_SERVER['HTTP_HOST']){
  class ProdApiConfig {
    public static $keys = array(
        'api_key' => 'adadad348jqdj2~!@!!@',
        'priv_key' => 'localhost3422ref545-r24',
        'pub_key' => 'localhostsdf334559t5t9tf'
		);
	}
}
// devapi smarthaggler
else if ('devapi.smarthaggler.com' == $_SERVER['HTTP_HOST']){
  class ProdApiConfig {
	public static $keys = array(
        'api_key' => 'adadad348jqdj2~!@!!@',
        'priv_key' => 'smarthaggler3422ref545-r24',
        'pub_key' => 'smarthagglersdf334559t5t9tf'
		);
	}
}
// live server
else{
  class ProdApiConfig {
	public static $keys = array(
        'api_key' => 'alphacouponistalkingaskjdkj3240-3024#@$',
        'priv_key' => 'alphacouponistalking3422ref545-r24',
        'pub_key' => 'alphacouponistalkingsdf334559t5t9tf'
		);
	}
}

?>