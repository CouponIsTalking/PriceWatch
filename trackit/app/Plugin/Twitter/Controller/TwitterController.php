<?php
class TwitterController extends AppController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'Twitter';

/**
 * Uses
 * 
 * @var string
 */
	public $uses = '';
	
/**
 * Consumer Secret from Twitter App
 * Set from within the Config/twitter.php file.
 * 
 * @var string
 */
	public $consumerKey = '';
	
/**
 * Consumer Secret from Twitter App
 * Set from within the Config/twitter.php file.
 * 
 * @var string
 */
	public $consumerSecret = '';
/**
 * Plugin that contains the model that saves authorization values. 
 * 
 * @var string
 */
	public $savePlugin = 'Users';
	
/**
 * Model to save authorization values to. 
 * Table must have user_id, type, and value fields
 * 
 * @var string
 */
	public $saveModel = 'UserConnect';
	
/**
 * components
 * 
 * @var string
 */
	public $components = array('Twitter.Twitter', 'UserData', 'RedirectUrl');
	
/**
 * Controller construct (loads config file)
 * 
 * @return null
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);	
		Configure::load('Twitter.twitter', 'default', false);	
		$this->consumerKey = Configure::read('Twitter.consumerKey');
		$this->consumerSecret = Configure::read('Twitter.consumerSecret');
	}
	
	public function tw_login_failed()
	{
		$this->Session->setFlash('It appears that you cancelled Twitter Authorization. We assure you that we do not use any personal info from Twitter.');
		$this->RedirectUrl->post_login_do_redirect();
	}
	
	public function tw_login()
	{
		//$this->RedirectUrl->pre_login_set_redirect();
		
		$twitter_consumer_key = Configure::read('Twitter.consumerKey');
		$twitter_consumer_secret = Configure::read('Twitter.consumerSecret');
		if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret)) 
		{
			$this->Twitter->setupApp($twitter_consumer_key, $twitter_consumer_secret); 
			$this->Twitter->connectApp(Router::url(array('action' => 'tw_authorization'), true));
		}
	}

	public function tw_authorization() { 
		if (!empty($this->request->query['oauth_token']) && !empty($this->request->query['oauth_verifier'])) {
			$this->Twitter->authorizeTwitterUser($this->request->query['oauth_token'], $this->request->query['oauth_verifier']);
			# connect the user to the application
			try {
				$user = $this->Twitter->getTwitterUser(true);
				$credentialCheck = $this->Twitter->accountVerifyCredentials();
				//debug($user);debug($credentialCheck);
				
				$profile_data = array_merge(array('token' => $this->request->query['oauth_token']), array('verifier' => $this->request->query['oauth_verifier']), array('profile_data' => $user));
				//debug($profile_data);
				//die();
				
				$this->UserData->write_intermediate_tw_login_data($profile_data);
				
				//$this->_connectUser($user, $this->request->query['oauth_verifier'], $this->request->query['oauth_token']);
				//$this->Session->setFlash('Test status message sent.');
				//$this->redirect(array('action' => 'post_tw_login_step', $profile_data));
				$this->redirect(array('plugin' => false, 'controller'=> 'users', 'action' => 'post_tw_auth_step'));
				
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->redirect(array('action' => 'tw_login_failed', null));
			}
		} else {
			$this->Session->setFlash('Invalid authorization request.');
			$this->redirect(array('action' => 'tw_login_failed', null));
		}
	}
	
	/*
	public function post_tw_login_step($profile_data)
	{
		
		$user_email = $this->UserData->getUserEmail();
				
		if(!empty($user_email))
		{
			$user_created = $this->UserData->CreateUserWithEmailAndTwitterInfo($email, $profile_data, "blogger");
			if ($user_created['success'])
			{
				$newuser = $user_created['user_data'];
				$this->UserData->setLoginData($newuser['User']);
				$this->RedirectUrl->post_login_do_redirect();
			}
			
		}
		else
		{
			
			if (!empty($profile_data))
			{
				$this->set('show_email_form', true);
			}
			else
			{
				$this->set('show_error', true);
			}	
			
		}	
		
		//$UserConnect = new UserConnect;
		//$data['UserConnect']['type'] = 'twitter';
		//$data['UserConnect']['value'] = serialize(array_merge(array('token' => $token), array('verifier' => $verifier), $profileData));
		
	}
	*/
	
/**
 * connect method
 */
	public function connect() {
		CakeSession::delete('Twitter.User');
		if (!empty($this->consumerKey) && !empty($this->consumerSecret)) {
			$this->Twitter->setupApp($this->consumerKey, $this->consumerSecret); 
			$this->Twitter->connectApp(Router::url(array('action' => 'authorization'), true));
		} else {
			echo 'App key and secret key are not set';
			break;
		}
	}
	
/**
 * authorization method
 */
	public function authorization() { 
		if (!empty($this->request->query['oauth_token']) && !empty($this->request->query['oauth_verifier'])) {
			$this->Twitter->authorizeTwitterUser($this->request->query['oauth_token'], $this->request->query['oauth_verifier']);
			# connect the user to the application
			try {
				$user = $this->Twitter->getTwitterUser(true);
				$this->_connectUser($user, $this->request->query['oauth_verifier'], $this->request->query['oauth_token']);
				$this->Session->setFlash('Test status message sent.');
				$this->redirect(array('action' => 'dashboard'));
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
				$this->redirect(array('action' => 'dashboard'));
			}
		} else {
			$this->Session->setFlash('Invalid authorization request.');
			$this->redirect(array('action' => 'dashboard'));
		}
	}
	
	
/**
 * dashboard method
 * 
 */
	public function dashboard() {
		if (!empty($this->request->data['Twitter']['status'])) {
			if ($this->Twitter->updateStatus($this->request->data['Twitter']['status'])) {
				$this->Session->setFlash('Status updated.');
			} else {	
				$this->Session->setFlash('Status update failed');
			}			
		}
		
		
		$status = true;
		$reload = false;
		$credentialCheck = false;
		$user = false;
		
		if (!empty($this->saveModel)) {
			$credentialCheck = $this->Twitter->accountVerifyCredentials();
			//debug($credentialCheck);
		
			if (!empty($credentialCheck['error'])) {
				$status = false;
				
				App::uses($this->saveModel, $this->savePlugin . '.Model');
				$UserConnect = new UserConnect;
				
				$user = $UserConnect->find('first', array(
					'conditions' => array(
						'UserConnect.type' => 'twitter',
						'UserConnect.user_id' => CakeSession::read('Auth.User.id'),
						),
					));
				$twitterUser = CakeSession::read('Twitter.User');
					
				//debug($twitterUser);
				$user = $this->Twitter->getTwitterUser(true);
				//debug($user);
				
				if (!empty($user) && empty($twitterUser)) {
					$twitterUser = unserialize($user['UserConnect']['value']);
					CakeSession::write('Twitter.User.oauth_token', $twitterUser['oauth_token']);
					CakeSession::write('Twitter.User.oauth_token_secret', $twitterUser['oauth_token_secret']);
					$reload = true;
				} else if (!empty($user)) {
					$reload = false;
				}
			} else {
				$status = true;
			}
		}
		$this->set(compact('status', 'reload', 'credentialCheck', 'user')); 
	}
	
/**
 * Save the user data to the application.
 * Configure the saveModel and savePlugin at the top of this controller.
 * 
 * @return bool
 * @todo 	Make this model name variable so that anyone using this plugin can easily change the table it saves data to.
 */
	protected function _connectUser($profileData, $token, $verifier) {
		if (!empty($this->saveModel)) {
			App::uses($this->saveModel, $this->savePlugin . '.Model');
			$UserConnect = new UserConnect;
			$data['UserConnect']['type'] = 'twitter';
			$data['UserConnect']['value'] = serialize(array_merge(array('token' => $token), array('verifier' => $verifier), $profileData));
		
			if ($UserConnect->add($data)) {
				if ($this->Twitter->updateStatus('I just connected my website to Twitter.')) {
					return true;
				} else {
					throw new Exception(__('test status message failed'));
				}
			} else {
				throw new Exception(__('no user to tie this twitter account to (probably need to auto create a user on our end'));
			}
		} else {
			return true;
		}
	}
}	
