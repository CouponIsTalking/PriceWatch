<?php
	
// localserver
if ('localhost' == $_SERVER['HTTP_HOST']){
  class EmailConfig {
    public $postmark = array(
        'transport' => 'Postmark.Postmark',
        'uri' => 'http://api.postmarkapp.com/email',
        'key' => 'fb5a207f-b901-40e3-b007-13286087f7ac'
    );
	}
}
else if ('devapi.smarthaggler.com' == $_SERVER['HTTP_HOST']){
  class EmailConfig {
	public $postmark = array(
		'transport' => 'Postmark.Postmark',
		'uri' => 'http://api.postmarkapp.com/email',
		'key' => 'fb5a207f-b901-40e3-b007-13286087f7ac'
	);
	}
}
// live server
else
{
  class EmailConfig {
    public $postmark = array(
        'transport' => 'Postmark.Postmark',
        'uri' => 'http://api.postmarkapp.com/email',
        'key' => '4c25b1b3-45f7-470f-975b-e178f66aa5f4'
	);

	}
}

?>