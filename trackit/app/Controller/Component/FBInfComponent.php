<?php

//App::uses('FacebookApi', 'Facebook.Lib');
//App::uses('FacebookInfo', 'Facebook.Lib');
App::uses('Facebook', 'Facebook.Vendor');

class FBInfComponent extends Component {
	
	var $fb_api = null;
	
	var $components = array('UserData', 'Misc'); 
	
	var $is_initialized = false;
	
	private $fbappid = null;
	private $fbappsecret = null;
	
	private $fb_user_id = null;
	private $fb_access_token = null;
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
				
	}
	
	public function get_graph_uri_from_page_uri($page_url)
	{
		$page_url = $this->Misc->addhttp($page_url);
		
		$graph_page_url = str_replace ("http://www.facebook.com", "http://graph.facebook.com", $page_url);
		if ($graph_page_url != $page_url)
		{
			return $graph_page_url;
		}
		
		$graph_page_url = str_replace ("https://www.facebook.com", "http://graph.facebook.com", $page_url);
		if ($graph_page_url != $page_url)
		{
			return $graph_page_url;
		}
		
		$graph_page_url = str_replace ("http://facebook.com", "http://graph.facebook.com", $page_url);
		if ($graph_page_url != $page_url)
		{
			return $graph_page_url;
		}
		
		$graph_page_url = str_replace ("https://facebook.com", "http://graph.facebook.com", $page_url);
		if ($graph_page_url != $page_url)
		{
			return $graph_page_url;
		}
		
		return false;
	}
	
	public function get_fb_page_id($page_url)
	{
		$page_props = $this->get_fb_page_props($page_url);
		if ($page_props)
		{
			if (!empty($page_props['id']))
			{
				return $page_props['id'];
			}
		}
		
		return 0;
	}
	
	public function get_fb_page_props($page_url)
	{
		$page_url = $this->get_graph_uri_from_page_uri($page_url);
		
		// use curl instead of file_get_contents
		//$page_props = file_get_contents($page_url);
		
		// Create a curl handle to a non-existing location
		$ch = curl_init($page_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$json = '';
		if( ($json = curl_exec($ch) ) === false)
		{
			return false;
		}
		
		// Close handle
		curl_close($ch);
		
		if ($json)
		{
			$page_props = json_decode($json, true);
			if (!empty($page_props['id']))
			{
				return $page_props;
			}
		}
		
		return false;
	}
	
	public function initialize_lib()
	{
		if ($this->is_initialized && $this->fb_api)
		{
			return;
		}
		
		$this->fbappid = FBAPPID; //Configure::read('FB.appid');
		$this->fbappsecret = FBAPPSECRET; //Configure::read('FB.appsecret');
		
		//App::import('Vendor', 'tmhOAuth/tmhOAuth');
		//App::uses('FacebookApi', 'Facebook.Lib');
		
		$user_tokens = $this->UserData->getFBUserTokens();
		$this->fb_api = new Facebook(array(
						'appId' => $this->fbappid,
						'secret' => $this->fbappsecret,
					));
		if (!empty($user_tokens))
		{
			$this->fb_access_token = $user_tokens['oauth_token'];
			$this->fb_user_id = $user_tokens['fb_user_id'];
		}
		
		//new FB();
		/*
		$this->tmhOAuth_lib = new tmhOAuth(array(
		  'consumer_key'    => $this->consumer_key,
		  'consumer_secret' => $this->consumer_secret
		));
		*/
		$this->is_initialized = true;
	
	}
	/*
	// verify_credentials is nothing but get_user
	public function verify_credentials()
	{
		$code = $this->tmhOAuth_lib->request('GET', $this->tmhOAuth_lib->url('1.1/account/verify_credentials'));
		
		//return $code;
		//debug($code);
		$response = $this->tmhOAuth_lib->response['response'];
		$op_result = $this->_buildResponse($response, $code);
		return $op_result;
	}
	*/
	public function get_and_set_access_token($oauth_token, $oauth_verifier)
	{
		$this->initialize_lib();
		$access_token = "";
		
		$user = $this->get_user();
		
		$access_token = $this->getAccessToken();
		
		if (empty($user['id']))
		{
			return false;
		}
		
		if (!empty($access_token))
		{
			$can_get_long_term_access_token = $this->has_offline_access_perm();
			if (!empty($can_get_long_term_access_token))
			{
				$long_term_access_token = $this->get_long_term_access_token();
				
				if (!empty($long_term_access_token))
				{
					$access_token = $long_term_access_token;
				}				
			}
		}
		
		if (empty($access_token))
		{
			return false;
		}
		
		$this->UserData->setFBUserTokens($access_token, $user['id']);
		
		return true;
	}
	
	public function get_long_term_access_token()
	{
		$this->initialize_lib();
		$user = $this->getUser(); 
		$access_token = "";
		
		if (!empty($user))
		{
			$this->fb_api->setExtendedAccessToken(); //long-live access_token 60 days
			$access_token = $this->fb_api->getAccessToken();
		}
		
		return $access_token;
	}
	
	public function build_authorize_url($oauth_token)
	{
		$this->initialize_lib();
		
		if ($is_ajax)
		{
			$redirect_uri = SITE_NAME . "/fb/after_login_landing_page/1";
		}
		else
		{
			$redirect_uri = SITE_NAME . "/fb/after_login_landing_page/0";
		}
		$params = array(
		  'scope' => 'email, read_stream, read_friendlists, publish_stream', //'read_stream, friends_likes',
		  'redirect_uri' => $redirect_uri
		);

		$url = $this->$facebook->getLoginUrl($params);
		
		//$url = $this->tmhOAuth_lib->url('oauth/authorize', '') . "?oauth_token={$oauth_token}";
		return $url;
	}
	
	public function login_user($is_ajax)
	{
		//$this->UserData->delete_tw_login_step_response();
		
		//$this->controller->redirect($url);
		if (!$is_ajax)
		{
			$this->controller->redirect(array('controller' => 'fb', 'action' => 'request_user_auth' ));
		}
		else
		{
			return null;
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
		return $op_result;
		
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
		return $op_result;
		
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
	
	public function getUserId()
	{
		$this->initialize_lib();
		$user_id = $this->fb_api->getUserId();
		return $user_id ;
	}
	
	public function getAccessToken()
	{
		$this->initialize_lib();
		$access_token = $this->fb_api->getAccessToken();
		return $access_token ;
	}
	
	public function has_publish_stream_perm()
	{
		$perms = $this->query_permissions_granted();
		
		if (!empty($perms['data'][0]))
		{
			if (array_key_exists('publish_stream', $perms['data'][0]))
			{
				return true;
			}
		}
		return false;
	}
	
	public function has_offline_access_perm()
	{
		$perms = $this->query_permissions_granted();
		
		if (!empty($perms['data'][0]))
		{
			if (array_key_exists('offline_access', $perms['data'][0]))
			{
				return true;
			}
		}
		return false;
	}
	
	public function query_permissions_granted()
	{
		$this->initialize_lib();
		
		$user_id = $this->getUserId();
		
		if(!empty($user_id)) {

		  // We have a user ID, so probably a logged in user.
		  // If not, we'll get an exception, which we handle below.
		  try {

			$response = $this->fb_api->api('/me/permissions','GET');
			//echo "Name: " . $user_profile['name'];
			return $response;

		  } catch(FacebookApiException $e) {
			//error_log($e->getType());
			//error_log($e->getMessage());
		  }
		}
		
		return null;
	}
	
	public function get_user()
	{
		$this->initialize_lib();
		
		$user_id = $this->getUserId();
		
		if(!empty($user_id)) {

		  // We have a user ID, so probably a logged in user.
		  // If not, we'll get an exception, which we handle below.
		  try {

			$user_profile = $this->fb_api->api('/me','GET');
			//echo "Name: " . $user_profile['name'];
			return $user_profile;

		  } catch(FacebookApiException $e) {
			//error_log($e->getType());
			//error_log($e->getMessage());
		  }
		}
		
		return null;
	}
	
	public function create_post($post_data)
	{
		$this->initialize_lib();
		
		$user_id = $this->getUserId();

		if($user_id) {

		  // We have a user ID, so probably a logged in user.
		  // If not, we'll get an exception, which we handle below.
		  try {
			$ret_obj = $this->fb_api->api('/me/feed', 'POST',
										$post_data
									/*	array(
										  'link' => 'www.example.com',
										  'message' => 'Posting with the PHP SDK!'
										)*/
									 );
			//echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';
			return $ret_obj;
			
			// Give the user a logout link 
			//echo '<br /><a href="' . $facebook->getLogoutUrl() . '">logout</a>';
			
		  } catch(FacebookApiException $e) {
			// If the user is logged out, you can have a 
			// user ID even though the access token is invalid.
			// In this case, we'll get an exception, so we'll
			// just ask the user to login again here.
			//error_log($e->getType());
			//error_log($e->getMessage());
		  }   
		}
		
		return null;
		
	}
}

?>