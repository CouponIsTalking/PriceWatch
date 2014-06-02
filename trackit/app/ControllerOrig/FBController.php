<?php
App::uses('AppController', 'Controller');
/**
 * FB Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class FBController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'RequestHandler', 
		'UserData', 
		'RedirectUrl', 
		'FBInf', 
		'FBResultProcessor'
		);

	var $uses = array('User');
	
	var $helpers = array ();
	
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);	
		Configure::load('Facebook', 'default', false);
		//Configure::load('Twitter.twitter', 'default', false);
	}
	
	public function request_user_auth()
	{
		//$this->layout = 'ajax';
		$url = $this->FBInf->build_authorize_url(0);
		
		$this->set('url', $url);
		//header('Location: '.$url);
		//$this->response->header('Location' , $url);
	}
	
	public function close_login($was_ajax_request, $login_successful)
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
	
	public function init_login()
	{
		$result = array('success' => 0, 'msg' => "", 'pop_up_url' => "");
		
		$is_ajax = $this->RequestHandler->isAjax();
		$this->set('is_ajax', $is_ajax);
			
		$fb_inf = $this->FBInf;
		
		if ($is_ajax)
		{
			$this->layout = 'ajax';
		}
		
		if ($is_ajax)
		{
			$result['success'] = 1;
			//$result['pop_up_url'] = $fb_inf->url_to_request_user_auth($oauth_token);
			$result['pop_up_url'] = $fb_inf->build_authorize_url($is_ajax);
			
			$this->set('result', $result);
		}
		// if the result was successful, we would show a popup window leading to a twitter auth page
		// with parent window as 
		else
		{
			$fb_inf->login_user($is_ajax);
		}
	}
	
	public function login_cancelled($was_request_ajax)
	{
		$this->redirect(
				array(
					'plugin' => false,
					'controller' => 'twitters',
					'action' => 'close_login',
					$was_request_ajax,
					0
				)
			);
	}
	
	public function after_login_landing_page($was_request_ajax)
	{
		
		$fb_inf = $this->FBInf; 
		$login_successful = 0;
		
		if (!empty($this->request->query['denied']))
		{
			$this->redirect(
				array(
					'plugin' => false,
					'controller' => 'fb',
					'action' => 'close_tw_login',
					$was_request_ajax,
					$login_successful
				)
			);
		}
		
		$result = $fb_inf->get_and_set_access_token($oauth_token, $oauth_verifier);
		
		if (true == $result)
		{
			// re-initialize fb lib
			$fb_inf->initialize_lib();
			$user = $fb_inf->get_user();
			
			if (!empty($user))
			{
				$this->UserData->write_intermediate_tw_login_data($profile_data);
				$profile_data = array('profile_data' => $user);
				$login_successful = 1;
				$this->redirect(array('plugin' => false, 'controller'=> 'users', 'action' => 'post_fb_auth_step', $was_request_ajax));
			}
		} 
		
		$this->redirect( 
			array(
				'plugin' => false,
				'controller' => 'fb',
				'action' => 'close_login',
				$was_request_ajax,
				$login_successful
		
		));
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