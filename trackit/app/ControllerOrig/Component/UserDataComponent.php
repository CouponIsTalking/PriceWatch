<?php
App::uses('Component', 'Controller');

class UserDataComponent extends Component {
	
	public $components = array('Session');

	
	public function initialize(Controller $controller)
	{
		$this->controller = $controller;
		//$this->controller->set('user_data_component', $this);
		//$this->Session = $this->_Collection->__get('Session');
	}
	
	/*
	public function __construct(ComponentCollection $collection) {
		//$this->_Collection = $collection;
		$this->Session = $collection->__get('Session');
	}
	*/
	
	public function load_session()
	{
		//$this->Session = $this->Components->load('Session');
		$this->_Collection->enable('Session');
		$a=1;
	}
	
	public function test_write()
	{
		$this->Session->write('test.write', '101001');
	}
	
	public function test_read()
	{
		$val = $this->Session->read('test.write');
		return $val;
	}
	
	public function isUserLoggedIn()
	{
		$login_data = $this->getLoginData();
		if (!empty($login_data))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
    public function setCompanyData($company) {
        return $this->Session->write('user.data.company', $company);
    }

    public function setBloggerData($blogger) {
        return $this->Session->write('user.data.blogger', $blogger);
    }
	
    public function setLoginData($user) {
        $this->Session->write('user.data.login', $user);
		
		$twitter_profile_data = $user['twitter_compact_info'];
		
		if (!empty($twitter_profile_data))
		{
			$twitter_profile_data = json_decode($twitter_profile_data);
			$oauth_token = null;
			$oauth_token_secret = null;
			
			if (!empty($twitter_profile_data->profile_data->oauth_token))
			{
				$oauth_token = $twitter_profile_data->profile_data->oauth_token;
			}
			if (!empty($twitter_profile_data->profile_data->oauth_token_secret))
			{
				$oauth_token_secret = $twitter_profile_data->profile_data->oauth_token_secret;
			}
			
			if (!empty($oauth_token) && !empty($oauth_token_secret))
			{
				$this->setTwitterUserTokens($oauth_token, $oauth_token_secret);
			}
		}
    }
	
	
	public function cleanLoginData($user) {
        //App::import('Component', 'Twitter.Twitter');
		//$twitter_componenet = new Twitter();
		//$twitter_component->logoutTwitterUser();
		
		// Delete user logged in twitter component
		$this->Session->delete('Twitter.User');
		$this->Session->delete('user.data');
		
		$this->clear_intermediate_tw_login_data();
		$this->delete_tw_login_step_response();
		
		return;
    }
	
	public function getLoginData() {
        $user = $this->Session->read('user.data.login');
		if (empty($user))
		{
			return null;
		}
		return $user;
    }
	
	public function getWelcomeName() {
		$user = $this->getLoginData();
		if ($user)
		{
			return $user['username'];
		}
		else
		{
			return null;
		}
	
	}
	
	public function getUserType() {
		$user = $this->getLoginData();
		if ($user)
		{
			return $user['role'];
		}
		else
		{
			return null;
		}
	
	}
	
	public function getUserId() {
		$user = $this->getLoginData();
		if ($user)
		{
			return $user['id'];
		}
		else
		{
			return null;
		}
    }
	
	public function isBlogger() {
	
		$user_type = $this->getUserType();
		if ( $user_type == 'blogger')
		{
			return true;
		}
		return false;
	}
	
	public function isCompany() {
	
		$user_type = $this->getUserType();
		if ( $user_type == 'company')
		{
			return true;
		}
		return false;
	}
	
	
	public function isAdmin() {
	
		$user_type = $this->getUserType();
		if ( $user_type == 'admin')
		{
			return true;
		}
		return false;
	}
	
	public function getCompanyData() {
		$user = $this->getLoginData();
		if ($user)
		{
			$company = $this->Session->read('user.data.company');
			if (!empty($company))
			{
				return $company;
			}
		}
		return null;	
	}

	public function getBloggerData() {
		$user = $this->getLoginData();
		if ($user)
		{
			$blogger = $this->Session->read('user.data.blogger');
			if (!empty($blogger))
			{
				return $blogger;
			}
		}
		return null;	
	}
	
	public function userHasCompanyAccess($company_id)
	{
		$company = $this->getCompanyData();
		if (!empty($company) && $company['Company']['id'] == $company_id)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function userHasBloggerAccess($blogger_id)
	{
		$blogger = $this->getBloggerData();
		if (!empty($blogger) && $blogger['Blogger']['id'] == $blogger_id)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function getUserEmail() {
		$user = $this->getLoginData();
		if ($user)
		{
			return $user['username'];
		}
		return null;	
	}
	
	public function getCompanyId() {
		$company = $this->getCompanyData();
		if ($company)
		{
			return $company['Company']['id'];
		}
		return null;	
	}
	
	public function getBloggerId() {
		$blogger = $this->getBloggerData();
		if ($blogger)
		{
			return $blogger['Blogger']['id'];
		}
		return null;	
	}
	
	public function getBloggerField($field_name) {
		$blogger = $this->getBloggerData();
		if ($blogger)
		{
			return $blogger['Blogger'][$field_name];
		}
		return null;	
	}
	
	
	public function CleanRedditData()
	{
		$this->Session->write('user.data.reddit', null);
	}
	
	public function GetRedditData()
	{
		return $this->Session->read('user.data.reddit');
	}
	
	public function GetRedditModHash()
	{
		return $this->GetRedditDataField('modHash');
	}
	
	public function GetRedditSessionCookie()
	{
		return $this->GetRedditDataField('sessionCookie');
	}
	
	public function GetRedditDataField($key)
	{
		$data= $this->Session->read('user.data.reddit');
		if (!empty($data[$key]))
		{
			return $data[$key];
		}
		
		return null;
	}
	
	public function SetRedditDataField($key, $value)
	{
		$data = array();
		$data[$key] = $value;
		$this->UpdateRedditData($data);
		
		return null;
	}
	
	public function UpdateRedditData($reddit_data_toupdate)
	{
		$reddit_data_existing = $this->GetRedditData();
		if (!empty($reddit_data_existing))
		{
			$reddit_data_toupdate = array_merge($reddit_data_existing, $reddit_data_toupdate);
		}
		
		return $this->Session->write('user.data.reddit', $reddit_data_toupdate);
	}
	
	public function clear_intermediate_tw_login_data()
	{
		//return $this->Session->write('twitter.intermediate.login.data', null);
		$this->Session->delete('twitter.intermediate.login.data');
	}
	
	public function write_intermediate_tw_login_data($profile_data)
	{
		$this->Session->write('twitter.intermediate.login.data', $profile_data);
		return;
	}
	
	public function read_intermediate_tw_login_data()
	{
		$profile_data = $this->Session->read('twitter.intermediate.login.data');
		if (empty($profile_data))
		{
			return null;
		}
		else
		{
			return $profile_data;
		}
		
	}
	
	public function getFBUserTokens()
	{
		$oauth_token = $this->Session->read('user.data.FB.User.oauth_token');
		$fb_user_id = $this->Session->read('user.data.FB.User.fb_user_id');
		if (empty($oauth_token) || empty($fb_user_id))
		{
			return null;
		}
		return array ('oauth_token' => $oauth_token, 'fb_user_id' => $fb_user_id);
	}
	
	public function setFBUserTokens($oauth_token, $fb_user_id)
	{
		$this->Session->write('user.data.FB.User.oauth_token', $oauth_token);
		$this->Session->write('user.data.FB.User.fb_user_id', $fb_user_id);
	}
	
	public function deleteFBUserTokens()
	{
		//$this->Session->delete('user.data.Twitter');
		$this->Session->write('user.data.FB.User.oauth_token', null);
		$this->Session->write('user.data.FB.User.fb_user_id', null);
	}
	
	
	public function getTwitterUserTokens()
	{
		$token = $this->Session->read('user.data.Twitter.User.oauth_token');
		$secret = $this->Session->read('user.data.Twitter.User.oauth_token_secret');
		if (empty($token) || empty($secret))
		{
			return null;
		}
		return array ('oauth_token' => $token, 'oauth_token_secret' => $secret);
	}
	
	public function setTwitterUserTokens($twitter_user_token, $twitter_user_token_secret)
	{
		$this->Session->write('user.data.Twitter.User.oauth_token', $twitter_user_token);
		$this->Session->write('user.data.Twitter.User.oauth_token_secret', $twitter_user_token_secret);
	}
	
	public function deleteTwitterUserTokens()
	{
		//$this->Session->delete('user.data.Twitter');
		$this->Session->write('user.data.Twitter.User.oauth_token', null);
		$this->Session->write('user.data.Twitter.User.oauth_token_secret', null);
		$this->delete_tw_login_step_response();
	}
	
	
	public function read_tw_login_step_response($step_name)
	{
		$response = $this->Session->read('tw.login_response_data.'. $step_name .'.response');
		return $response;
	}
	
	public function write_tw_login_step_response($step_name, $response)
	{
		//debug('tw.login_response_data.'. $step_name .'.response');
		$this->Session->write('tw.login_response_data.'. $step_name .'.response', $response);
	}
	
	public function delete_tw_login_step_response()
	{
		$this->Session->write('tw.login_response_data', null);
	}
	
}

?>