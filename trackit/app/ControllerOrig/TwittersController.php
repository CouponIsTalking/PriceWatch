<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class TwittersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'RequestHandler', 
		'UserData', 
		'RedirectUrl', 
		'TmhOAuthInf', 
		'TwitterResultProcessor'
		);

	var $uses = array('User');
	
	var $helpers = array ();
	
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);	
		//Configure::load('Twitter.twitter', 'default', false);			
	}
	
	public function request_user_auth($oauth_token)
	{
		//$this->layout = 'ajax';
		$url = $this->TmhOAuthInf->build_authorize_url($oauth_token);
		
		$this->set('url', $url);
		//header('Location: '.$url);
		//$this->response->header('Location' , $url);
	}
	
	public function close_tw_login($was_ajax_request, $login_successful)
	{
		
		if (1 == $was_ajax_request)
		{
			$this->layout = 'ajax';
			$this->set('was_ajax_request', $was_ajax_request);
			$this->set('login_successful', $login_successful);
		}
		else
		{
			$this->redirect('/');
		}
		
	}
	
	public function init_tw_login()
	{
		$result = array('success' => 0, 'msg' => "", 'pop_up_url' => "");
		
		$is_ajax = $this->RequestHandler->isAjax();
		$this->set('is_ajax', $is_ajax);
			
		$twitter_inf = $this->TmhOAuthInf;
		
		if ($is_ajax)
		{
			$this->layout = 'ajax';
		}
		
		$oauth_token = $twitter_inf->login_user($is_ajax);
			
		if ($is_ajax)
		{
			if (empty($oauth_token))
			{
				$result['success'] = 0;
				$result['msg'] = "There was an issue in communicating with Twitter.";
			}
			else
			{
				$result['success'] = 1;
				//$result['pop_up_url'] = $twitter_inf->url_to_request_user_auth($oauth_token);
				$result['pop_up_url'] = $twitter_inf->build_authorize_url($oauth_token);
			}
			
			$this->set('result', $result);
		}
		// if the result was successful, we would show a popup window leading to a twitter auth page
		// with parent window as 
		else
		{
			if (empty($oauth_token))
			{
				$result['success'] = 0;
				$result['msg'] = "There was an unknown issue, because of which we could not login with twitter. Please re-try. If the problem persists, then send us an email.";
				$this->Session->setFlash($result['msg']);
			}
			
			$this->set('result', $result);
		}
	}
	
	public function after_login_landing_page($was_request_ajax)
	{
		
		$twitter_inf = $this->TmhOAuthInf; 
		
		if (!empty($this->request->query['denied']))
		{
			$this->redirect(
				array(
					'plugin' => false,
					'controller' => 'twitters',
					'action' => 'close_tw_login',
					1,
					0
				)
			);
		}
		// get tokens from the url
		else if (!empty($this->request->query['oauth_token']) && !empty($this->request->query['oauth_verifier'])) 
		{
		
			$oauth_token = $this->request->query['oauth_token'];
			$oauth_verifier = $this->request->query['oauth_verifier'];
			$result = $twitter_inf->get_and_set_access_token($oauth_token, $oauth_verifier);
			
			// re-initialize twitter lib
			$twitter_inf->initialize_tmh_lib();
			$user = $twitter_inf->get_user();
			
			if (true == $result)
			{
				$profile_data = array_merge(array('token' => $oauth_token), array('verifier' => $oauth_verifier), array('profile_data' => $user));
				
				$this->UserData->write_intermediate_tw_login_data($profile_data);
				
				$this->redirect(array('plugin' => false, 'controller'=> 'users', 'action' => 'post_tw_auth_step', $was_request_ajax));
			}
			else
			{
				$this->UserData->delete_tw_login_step_response();
			}
			
		} else {
			$this->UserData->delete_tw_login_step_response();
		}
	}
	
	/*
	public function tw_login_failed()
	{
		$this->Session->setFlash('It appears that you cancelled Twitter Authorization. We assure you that we do not use any personal info from Twitter.');
		$this->RedirectUrl->post_login_do_redirect();
	}
	*/
	
}

?>