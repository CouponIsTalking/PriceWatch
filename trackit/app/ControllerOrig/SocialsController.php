<?php
class SocialsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData');
	
	public $uses = false;
	
	public function saveme_button()
	{
		$this->layout = 'ajax';
	}
	
	public function saveme_button_quicker()
	{
		$this->layout = 'ajax';
	}
	
	public function saveme_button_quick_auto()
	{
		$this->layout = 'ajax';
	}
	
	public function trackit_mainpage()
	{
		$this->layout = 'ajax';
		$user_email = $this->UserData->getUserEmail();
		$this->set('user_email', $user_email);
	}
	
	public function trackit_mainpage_quicker()
	{
		$this->layout = 'ajax';
		$user_email = $this->UserData->getUserEmail();
		$this->set('user_email', $user_email);
	}
	
	public function share_n_get_coupon_button($unix_timestamp_and_id)
	{
		$this->layout = 'button_in_iframe';
		$this->set('unix_timestamp_and_id', $unix_timestamp_and_id);
	}
	
	
}