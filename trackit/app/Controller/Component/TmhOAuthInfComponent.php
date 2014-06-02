<?php

class TmhOauthInfComponent extends Component {
	
	var $tmhOAuth_lib = null;
	
	var $components = array('UserData'); 
	
	var $is_initialized = false;
	
	private $consumer_key = null;
	private $consumer_secret = null;
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		
		$this->consumer_key = Configure::read('Twitter.consumerKey');
		$this->consumer_secret = Configure::read('Twitter.consumerSecret');
				
	}
	
	public function app_only_tmh_lib_init()
	{
		App::import('Vendor', 'tmhOAuth/tmhOAuth');
		
		$user_tokens = $this->UserData->getTwitterUserTokens();
		
		$this->tmhOAuth_lib = new tmhOAuth(array(
		  'consumer_key'    => $this->consumer_key,
		  'consumer_secret' => $this->consumer_secret
		));
		
		$this->is_initialized = true;
	
	}
	
	public function initialize_tmh_lib()
	{
		//App::uses('tmhOAuth', 'Vendor');
		
		App::import('Vendor', 'tmhOAuth/tmhOAuth');
		
		$twitter_consumer_key = Configure::read('Twitter.consumerKey');
		$twitter_consumer_secret = Configure::read('Twitter.consumerSecret');
		
		$user_tokens = $this->UserData->getTwitterUserTokens();
		if(empty($user_tokens))
		{
			$user_tokens = array ('oauth_token' => null, 'oauth_token_secret'=>null);
		}
		
		$this->tmhOAuth_lib = new tmhOAuth(array(
		  'consumer_key'    => $this->consumer_key,
		  'consumer_secret' => $this->consumer_secret,
		  'user_token'      => $user_tokens['oauth_token'],
		  'user_secret'     => $user_tokens['oauth_token_secret'],
		));
		
		$this->is_initialized = true;
	}
	
	public function verify_credentials()
	{
		$code = $this->tmhOAuth_lib->request('GET', $this->tmhOAuth_lib->url('1.1/account/verify_credentials'));
		
		//return $code;
		//debug($code);
		$response = $this->tmhOAuth_lib->response['response'];
		$op_result = $this->_buildResponse($response, $code);
		return $op_result;
	}
	
	function request_token($is_ajax) 
	{
	
		$callback_url = "";
		if ($is_ajax)
		{
			$callback_url = SITE_NAME . "twitters/after_login_landing_page/1";
		}
		else
		{
			$callback_url = SITE_NAME . "twitters/after_login_landing_page/0";
		}
		
		$code = $this->tmhOAuth_lib->apponly_request(array(
			'without_bearer' => true,
			'method' => 'POST',
			'url' => $this->tmhOAuth_lib->url('oauth/request_token', ''),
			'params' => array(
			  'oauth_callback' => $callback_url,
			),
		));
		
		//debug($code);
		
		if ($code != 200) {
			//error("There was an error communicating with Twitter. {$this->tmhOAuth_lib->response['response']}");
			return false;
		}

		// store the params into the session so they are there when we come back after the redirect
		$oauth_response = $this->tmhOAuth_lib->extract_params($this->tmhOAuth_lib->response['response']);

		// check the callback has been confirmed
		if ($oauth_response['oauth_callback_confirmed'] !== 'true') {
			return false;
		} else {
			return $oauth_response;
			
			//$url = $tmhOAuth->url('oauth/authorize', '') . "?oauth_token={$oauth_token}";
		}
		
		return false;
	}
	
	public function get_and_set_access_token($oauth_token, $oauth_verifier)
	{
		$this->app_only_tmh_lib_init();
		
		$req_token_response = $this->UserData->read_tw_login_step_response('request_token');
		
		//debug($req_token_response);
		
		// verify that tokens are match
		if ($req_token_response['oauth_token'] != $oauth_token)
		{
			return false;
		}
		
		$oauth_token_secret = $req_token_response['oauth_token_secret'];
		
		
		// update with the temporary token and secret
		$this->tmhOAuth_lib->reconfigure(array_merge($this->tmhOAuth_lib->config, array(
			'token'  => $oauth_token,
			'secret' => $oauth_token_secret,
		)));

		
		// make access token request
		$code = $this->tmhOAuth_lib->user_request(array(
			'method' => 'POST',
			'url' => $this->tmhOAuth_lib->url('oauth/access_token', ''),
			'params' => array(
			  'oauth_verifier' => trim($oauth_verifier),
			)
		));
	
		if ($code == 200) {
			$oauth_creds = $this->tmhOAuth_lib->extract_params($this->tmhOAuth_lib->response['response']);
			//debug($oauth_creds);
			
			// set the useful data
			$this->UserData->setTwitterUserTokens($oauth_creds['oauth_token'], $oauth_creds['oauth_token_secret']);
			
			// delete the twitter response of request_token step, as it is not useful now.
			$this->UserData->delete_tw_login_step_response();
			
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function build_authorize_url($oauth_token)
	{
		$this->app_only_tmh_lib_init();
		$url = $this->tmhOAuth_lib->url('oauth/authorize', '') . "?oauth_token={$oauth_token}";
		return $url;
	}
	
	public function login_user($is_ajax)
	{
		$this->UserData->delete_tw_login_step_response();
		$this->app_only_tmh_lib_init();
		$oauth_response = $this->request_token($is_ajax);
		
		if(empty($oauth_response))
		{
			return false;
		}
		
		$this->UserData->write_tw_login_step_response('request_token', $oauth_response);
		$oauth_token = $oauth_response['oauth_token'];
			
		
		$url = $this->tmhOAuth_lib->url('oauth/authorize', '') . "?oauth_token={$oauth_token}";
		//$this->controller->redirect($url);
		if (!$is_ajax)
		{
			$this->controller->redirect(array('controller' => 'twitters', 'action' => 'request_user_auth', $oauth_token ));
		}
		else
		{
			return $oauth_token;
		}
		
		//debug($oauth_token);
		//debug($url);
		//$response = $this->tmhOAuth_lib->response['response'];
		//debug($response);
		
		//$this->redirect("https://api.twitter.com/oauth/authorize?oauth_token=".$oauth_token);
		
		
	}
	
	public function url_to_request_user_auth($oauth_token)
	{
		return SITE_NAME. "twitters/request_user_auth/".$oauth_token;
	}
	
	public function status_update_with_media($tweet_title, $image_link)
	{
		$result = array('resp' => false, 're' => false);
		
		//$image_link = "http://scentsciences.files.wordpress.com/2012/04/hello-1782.jpg";
		$image_contents = file_get_contents($image_link);
		$code = $this->tmhOAuth_lib->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json',
					array(
						'media[]'  => $image_contents, //$image_link,
						'status'   => $tweet_title // Don't give up..
					),
					true, // use auth
					true,  // multipart
					array('without_bearer' => true)
				);
		
		$response = $this->tmhOAuth_lib->response['response'];
		$op_result = $this->_buildResponse($response, $code);
		
		$result['resp'] = $response;
		$result['re'] = $op_result;
		return $result;
		
		//debug($this->tmhOAuth_lib->response['response']);
		/*
		if ($code == 200) {
			tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
		} else {
			tmhUtilities::pr($tmhOAuth->response['response']);
		}
		*/
	}
	
	public function status_update_without_media($tweet_title)
	{
		$result = array('resp' => false, 're' => false);
		
		$code = $this->tmhOAuth_lib->request('POST', $this->tmhOAuth_lib->url('1.1/statuses/update'),
					array(
						'status'   => $tweet_title // Don't give up..
					),
					true, // use auth
					false,  // multipart
					array('without_bearer' => true)
				);
		
		$response = $this->tmhOAuth_lib->response['response'];
		$op_result = $this->_buildResponse($response, $code);
		
		$result['resp'] = $response;
		$result['re'] = $op_result;
		return $result;
		
		//debug($this->tmhOAuth_lib->response['response']);
		/*
		if ($code == 200) {
			tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
		} else {
			tmhUtilities::pr($tmhOAuth->response['response']);
		}
		*/
	}
	
	private function _buildResponse($response, $code)
	{
		$op_result = json_decode($response, true);
		
		if (empty($op_result))
		{
			$op_result = array('code' => $code);
		}
		
		return $op_result;
	}
	
	// returns response of verify credentials
	public function get_user()
	{
		if (false == $this->initialized)
		{
			$this->initialize_tmh_lib();
		}
		
		$op_result = $this->verify_credentials();
		return $op_result;
	}
}

?>