<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Paginator',
		'RequestHandler',
		'Auth' => array(
            'loginRedirect' => array('controller' => 'companies', 'action' => 'user_view'),
            'logoutRedirect' => array('controller' => 'companies', 'action' => 'user_view')
			),
		'Session',
		'UserData',
		'RedirectUrl',
		'TimeManagement',
		'EmailAccess',
		'TwitterResultProcessor'
		);
	
	var $helpers = array('Html');//,'Javascript');
    
	public $uses = array ('User', 'Company', 'Product');
	
    public function beforeFilter() {
    /*    parent::beforeFilter();
		App::import('Vendor', 'facebook-php-sdk-master/src/facebook');
		$this->Facebook = new Facebook(array(
			'appId'     =>  FBAPPID,
			'secret'    =>  FBAPPSECRET
		));
		//$this->Auth->allow('index', 'view');
	*/
        $this->Auth->allow('add');
		$this->Auth->allow('add_ajax');
		$this->Auth->allow('update_fb_ajax');
		$this->Auth->allow('fb_login_ajax');
		$this->Auth->allow('login_ajax');
		$this->Auth->allow('logout_ajax');
		$this->Auth->allow('reddit_login');
		$this->Auth->allow('is_user_reddit_loggedin');
		$this->Auth->allow('post_tw_auth_step');
		$this->Auth->allow('action_tw_email_reg');
		
		$this->Auth->allow('confirm_email');
		$this->Auth->allow('resend_confirmation_link');
		
		// Admin helper routines
		$this->Auth->allow('find_by_email');
		$this->Auth->allow('out_and_in_as');
		$this->Auth->allow('view_all');
		
    }
	
	public function initiate_fb_login($json_data)
	{
		$fb_login_url = $this->Facebook->getLoginUrl(array('redirect_uri' => Router::url(array('controller' => 'users', 'action' => 'receive_fb_login_response', $json_data), true)));
		echo $fb_login_url;
	}
	
	public function receive_fb_login_response()
	{
	
	}	
	
	public function resend_confirmation_link()
	{
		$this->layout = 'ajax';
			
		$result = array ('success' => 0, 'msg' => "");
		
		$ispost = $this->RequestHandler->isPost();
		$this->set('ispost', $ispost);
		$is_ajax = $this->RequestHandler->isAjax();
		$this->set('is_ajax', $is_ajax);	
		
		if (!$is_ajax)
		{
			$result['msg'] = 'Bad request.';
			$this->set('result', $result);
			return;
		}
		
		$loggedin_user_id = $this->UserData->getUserId();
		
		if (!empty($loggedin_user_id))
		{
			if (!$is_ajax)
			{
				$this->Session->setFlash("You are already logged in.");
			}
			$result['msg'] = 'You are already logged in.';
			$this->set('result', $result);
			return;
		}
		
		if (!$ispost)
		{
			$result['msg'] = 'Bad request.';			
		}
		else
		{
			$data = $this->request->data;
			
			$email = null;
			
			if ($is_ajax)
			{
				if (!empty($data['email']))
				{
					$email = trim($data['email']);
				}
			}
			else
			{
				if (!empty($data['User']['email']))
				{
					$email = trim($data['User']['email']);
				}
			}
			
			$next_loc="";
			if(!empty($data['nl'])){$next_loc=$data['nl'];}
			$next_loc = urlencode($next_loc);
		
			if (!empty($email))
			{
				$user = array();
				$user['User'] = $this->User->findUserByEmail($email);
				if (empty($user['User']))
				{
					$result['msg'] = "Please check the email you entered. No user found with given email.";
				}
				else if (1 == $user['User']['active'])
				{
					$result['msg'] = "Your email is already confirmed. If you are having issues logging in, please follow the reset password link.";
				}
				else if (-1 == $user['User']['active'])
				{
					$result['msg'] = "Sorry, your account is temporarily suspended.";
				}
				else if (0 == $user['User']['active'])
				{
					
					$email = $user['User']['username'];
					$user_id = $user['User']['id'];
					$confirmation_feed = TimeManagement::TSNowTime();
					$new_hash = $this->User->UpdateAndGetNewHash($user_id, $email, $confirmation_feed);
					if (!empty($new_hash))
					{
						$confirmation_link = SITE_NAME."users/confirm_email/{$new_hash}?nl={$next_loc}";
						$email_sent = $this->EmailAccess->shoot_email_for_confirm_email(
							$email, "", $confirmation_link);
							
						if ($email_sent)
						{
							$result['success'] = 1;
							$result['msg'] = "We have sent a confirmation link to your email. Please check your email and follow the link to confirm your email.";
						}
						else
						{
							$result['msg'] = 'There was an issue in sending link to your email. If this problem persists, send us an email.';
						}
					}
					else
					{
						$result['msg'] = 'Unknown error occured in updating your records.';
					}
				}
			}
			else
			{
				$result['msg'] = 'Bad data.';
			}
		}
		
		$this->set('result', $result);
		
	}
    
	public function confirm_email($h)
	{
		$result = array ('success' => 0, 'msg' => "", 'nl'=>SITE_NAME);
		
		if(!empty($this->params->query['nl']))
		{
			$result['nl']=$this->params->query['nl'];
		}
		
		$user = $this->User->activateUserByConfirmationHash($h);
		if (!empty($user['User']))
		{
			$this->UserData->setLoginData($user['User']);
			$result['msg'] = 'A warm welcome from our team. Thank you for confirming your email.';
			$result['msg'] = 'A warm welcome from our team. Thank you for confirming your email.';
			$email_sent = $this->EmailAccess->shoot_email_after_registration($user['User']['username'], "");
			$this->set('email_sent', $email_sent);
		}
		else
		{
			$result['msg'] = 'Page does not exist.';
		}
		$this->set('result', $result);
		//$this->redirect('/');
	}
	
	public function add_ajax() {
		
		$this->layout = 'ajax';
		
		$data = array();
		if ($this->RequestHandler->isAjax())
		{
			//$this->layout = 'ajax';
			
			$data = $this->request->data;
			
		}
		
		$msg = "";
		$errors = 0;
		if (empty($data))
		{
			$msg=$msg."Please fill in form<br/>";
			$errors = $errors + 1;
		}
		//debug($data);
		
		if (empty($data['data_username']))
		{
			$msg=$msg."Please fill in email<br/>";
			$errors = $errors + 1;
		}
		if (empty($data['data_password']) || $data['data_password'] == "")
		{
			$msg=$msg."Please fill in password<br/>";
			$errors = $errors + 1;
		}
		if (empty($data['data_password2']) || $data['data_password2'] == "")
		{
			$msg=$msg."Please retype password<br/>";
			$errors = $errors + 1;
		}
		
		if (!empty($data['data_password']) && !empty($data['data_password2']) && ($data['data_password'] != $data['data_password2']) )
		{
			$msg=$msg."Passwords don't match. Please retype passwords<br/>";
			$errors = $errors + 1;
		}
		
		if (!empty($data['data_username']))
		{
			$data['data_username'] = filter_var($data['data_username'], FILTER_VALIDATE_EMAIL);
			if (empty($data['data_username']))
			{
				$msg=$msg."Please enter a valid email address<br/>";
				$errors = $errors + 1;
			}
		}
		
		$next_loc="";
		if(!empty($data['nl'])){$next_loc=$data['nl'];}
		$next_loc = urlencode($next_loc);
		
        if (0 == $errors) {
            
			$already_present_user = $this->User->findUserByEmail($data['data_username']);
			if (!empty($already_present_user['id']))
			{
				$msg = "You are already registered with us. If you are trying to confirm your email, please follow 'email confirmation link'.";
				$errors = $errors + 1;
			}
			else
			{
				$confirmation_feed = TimeManagement::TSNowTime();
				
				$user_created = $this->User->CreateUserWithEmailAndPassword(
				$data['data_username'], $data['data_password'], 'blogger', 0, $confirmation_feed);
						
				if ($user_created['success'])
				{
					$newuser = $user_created['user_data'];
					//$msg = "Congrats, you are now registered user.";
					#$msg = "Congrats, you are now registered user. Please login to continue.";
					$msg = "Please check your email to confirm your email address.";
					
					$email = $newuser['User']['username'];
					$confirmation_link = SITE_NAME."users/confirm_email/{$newuser['User']['confirmation_link']}?nl={$next_loc}";
					$email_sent = $this->EmailAccess->shoot_email_for_confirm_email(
						$email, "", $confirmation_link);
					$this->set('email_sent', $email_sent);
							
				}
				else {
					$msg = "There was some technical problem while saving the data. Please give try one more time. ";
					$msg = $msg. "If the problem persists, give us a call or email and we will resolve it immeditately";
					$errors = $errors + 1;
				}
            }
        }
		
		$result = array('errors' => $errors, 'msg' => $msg);
		$this->set('result', $result);
		return $result;
    }
	
	//
	public function post_tw_auth_step($was_request_ajax)
	{
		
		$result = array('success' =>0, 'msg' =>"");
		
		$this->set('was_request_ajax', $was_request_ajax);
		$this->set('result', $result);
		
		$user_email = $this->UserData->getUserEmail();
		$profile_data = $this->UserData->read_intermediate_tw_login_data();
		//debug($profile_data);
		
		if(!empty($user_email))
		{
			$tw_result_processor = $this->TwitterResultProcessor; //new TwitterResultProcessorComponent(new ComponentCollection());
			if (true == $tw_result_processor->has_errors($profile_data['profile_data']))
			{
				$this->UserData->clear_intermediate_tw_login_data();
				
				if (!$was_request_ajax)
				{
					$this->RedirectUrl->post_login_do_redirect();
					$this->redirect('/');
				}
				else
				{
					$result['success'] = 0;
					$result['msg'] = "There was an error in communicating with twitter.";
				}
			}
			
			$user_created = $this->User->CreateUserWithEmailAndTwitterInfo($user_email, $profile_data, "blogger");
			
			if (!($user_created['error']))
			{
				$newuser = $user_created['user_data'];
				
				//$twitter_user_token = $profile_data['oauth_token'];
				//$twitter_user_token_secret = $profile_data['oauth_token_secret'];
				//$this->UserData->setTwitterUserTokens($twitter_user_token, $twitter_user_token_secret);
				
				$this->UserData->setLoginData($newuser['User']);
				
				$this->UserData->clear_intermediate_tw_login_data();
				
				if (!$was_request_ajax)
				{
					$this->RedirectUrl->post_login_do_redirect();
					$this->redirect('/');
				}
				else
				{
					$result['success'] = 1;
					$result['msg'] = "";
				}
			}
			else
			{
				if (!$was_request_ajax)
				{
					$this->set('show_error', true);
				}
				else
				{
					$result['success'] = 0;
					$result['msg'] = "Oops. There was an error in communicating with twitter.";
				}
			}
			
		}
		/*else
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
		*/
		//$UserConnect = new UserConnect;
		//$data['UserConnect']['type'] = 'twitter';
		//$data['UserConnect']['value'] = serialize(array_merge(array('token' => $token), array('verifier' => $verifier), $profileData));
		if ($was_request_ajax)
		{
			$this->redirect(
				array(
					'plugin' => false,
					'controller' => 'twitters',
					'action' => 'close_tw_login',
					1,
					$result['success']
				)
			);
		}
		
		$this->set('result', $result);
		
	}
	
	public function action_tw_email_reg()
	{
		$this->layout = 'ajax';
		
		$msg = "";
		$errors = 0;
		
		
		if ($this->RequestHandler->isAjax())
		{
			$data = $this->request->data;
			$data['data_username'] = filter_var($data['data_username'], FILTER_VALIDATE_EMAIL);
			
			$tw_profile_data = $this->UserData->read_intermediate_tw_login_data();
			if (empty($tw_profile_data['user_personal_data']['id_str']))
			{
				$msg=$msg."Looks like you didn't completed Twitter authentication :( .<br/>";
				$errors = $errors + 1;
			}
			else if (empty($data['data_username']))
			{
				$msg=$msg."Please enter a valid email address<br/>";
				$errors = $errors + 1;
			}
			
			if (0 == $errors) 
			{
				$email = $data['data_username'];
				$user_created = $this->User->CreateUserWithEmailAndTwitterInfo($email, $tw_profile_data, 'blogger');
				
				
				if ($user_created['success']) {
					
					$newuser = $user_created['user_data'];
					$this->UserData->setLoginData($newuser['User']);
						
					//$msg = "Congrats, you are now registered user.";
					$msg = "Congrats, you are now registered user. Please login to continue.";
					
					$this->RedirectUrl->post_login_do_redirect();
					
				}
				else {
					$msg = "There was some technical problem while saving the data. Please give try one more time. ";
					$msg = $msg. "If the problem persists, give us a call or email and we will resolve it immeditately";
					$errors = $errors + 1;
				}
				
			}
		}
		
		$result = array('errors' => $errors, 'msg' => $msg);
		$this->set('result', $result);
		return $result;
	
	}
	
	// when user is already logged in
	public function update_fb_ajax(){
	
		$session_field_update = array();
		$re = array('s'=>0,'m'=>'','nstep'=>'');
		$user_id = $this->UserData->getUserId();
		if(empty($user_id))
		{
			$re['m'] = 'Please login to add a facebook account on your file.';
			$this->set('result', $re);
			return $re;
		}
		
		$fb_id_re = $this->UserData->getUserField('fb_id');
		$fb_id_now = 0;
		if ($fb_id_re['s'])
		{
			$fb_id_now = $fb_id_re['val'];
		}
		
		if ($this->RequestHandler->isAjax())
		{
			$data = $this->request->data;
			$fb_id_new = 0;
			if (!empty($data['fb_resp']))
			{
				$fb_resp = $data['fb_resp']; 
				$fb_id_new = $fb_resp['id'];
				$fb_name_new = $fb_resp['name'];
			}
			
			if (!empty($data['f'])) // if force update
			{
				$add_re = $this->User->updateFbInfo($user_id, $fb_id_new, $fb_resp);
				if ($add_re['s']){
					$re['s'] = 1;
					$session_field_update = array('fb_id'=>$fb_id_new,'fb_auth_token'=>'',
					'fb_compact_info'=>json_encode($fb_resp)
					);
				}
				else {$re['m'] = $add_re['m'];}
			}
			else if (!$fb_id_new)
			{
				$re['m'] = 'Bad request';
			}
			else if(empty($fb_id_now))
			{
				$add_re = $this->User->updateFbInfo($user_id, $fb_id_new, $fb_resp);
				
				if ($add_re['s']){
					$re['s'] = 1;
					$session_field_update = array('fb_id'=>$fb_id_new,'fb_auth_token'=>'',
					'fb_compact_info'=>json_encode($fb_resp)
					);					
				}
				else {$re['m'] = $add_re['m'];}
			}
			else if ($fb_id_now == $fb_id_new)
			{
				// already added
				// pass
				$re['s'] = 1;
			}
			else if ($fb_id_now != $fb_id_new)
			{
				// ask for update
				$old_fb_name = '';
				$fb_cinfo_re = $this->UserData->getUserField('fb_compact_info');
				$json_fb_info_now = $fb_cinfo_re['val'];
				$fb_info_now = json_decode($json_fb_info_now, true);
				if (is_array($fb_info_now) && array_key_exists('name', $fb_info_now))
				{
					$old_fb_name = "({$fb_info_now['name']})";
				}
				$re['s'] = 0;
				$re['nstep'] = 'ask-to-replace';
				$re['m'] = "Our records show that a different facebook user{$old_fb_name} is on file with your account with us.<br/><br/>Ignore and connect as {$fb_name_new} instead? ";
			}
		}
		
		if (!empty($session_field_update))
		{
			$this->UserData->updateFieldsInLoginData($session_field_update);
		}
		$this->set('result', $re);
		return $re;
	}
	
	public function fb_login_ajax()
	{
		$this->layout = 'ajax';
		
		$result = array('s' => 0, 'm' => '','nstep'=>'');
		$this->set('result', $result);
		
		$isajax = $this->RequestHandler->isAjax();
		if (!$isajax){return $result;}
		
		$uid = $this->UserData->getUserId();
		$data = $this->request->data;
		$fb_response = $data['fb_resp'];
		
		
		// if there is a user logged in, then simply update fb info
		if (!empty($uid))
		{
			$update_re = $this->update_fb_ajax();
			$result['m'] = $update_re['m'];
			$result['s'] = $update_re['s'];
			$result['nstep']=$update_re['nstep'];
		}
		else
		{
			//debug($this->request);
			//debug($data);
			$email = $fb_response['email'];
			
			if (!empty($email))
			{
				$fb_id = $fb_response['id'];
				$user = $this->User->findUserByFbId($fb_id);
				if(empty($user)){
					$user = $this->User->findUserByEmail($email);
				}
				
				// if user is present with fbid or fbemail, then set user as loggedin
				if (!empty($user)) {
					//$msg = "Congrats, you are now registered user.";
					$this->UserData->setLoginData($user);
					$result['s'] = 1;
					$user_type = $this->UserData->getUserType();
					if (!empty($user_type))
					{
						if ('blogger' == $user_type){
							$result['m'] = "A warm welcome from our team. :). We highly appreciate your feedbacks and merchant suggestions. Keep them sending our way.";
						}
						else if ('company' == $user_type){
							$result['m'] = "If you have any issue creating or managing your coupons or understanding them, please call/email us and let us know.";
						}
					}
					
				}
				// otherwise create user and set logged in
				else
				{
					$user_created = $this->User->CreateUserWithEmailAndPassword($email, "", 'blogger', 1, null);
					
					if ($user_created['success'])
					{
						$newuser = $user_created['user_data'];
						
						$firstname = $fb_response['first_name'];
						$fullname = $fb_response['name'];
						$name_update_re = $this->User->updateName($newuser['User']['id'],$firstname, $fullname);
						
						if ($name_update_re['s']){
							if (!empty($firstname)){$newuser['User']['firstname'] = $firstname;}
							if (!empty($fullname)){$newuser['User']['fullname'] = $fullname;}
						}
						
						//debug("user created");
						$this->UserData->setLoginData($newuser['User']);
						$msg = "A warm welcome from our team. :). We highly appreciate your feedbacks and merchant suggestions. Keep them sending our way.";
						
						if (!empty($fb_response['name'])) $name = $fb_response['name'];
						else $name = "";
						
						$email_sent = $this->EmailAccess->shoot_email_after_registration($email, $name);
						$this->set('email_sent', $email_sent);
						
						//set result
						$result['s'] = 1;
						$result['m'] = $msg;
					}
					else{
						$result['s']=0;
					}
				}
				
				// if fbid is not prsent in user login data,
				// means user created account with email and used fb with same email to login
				//, then update fb info
				if (1==$result['s']){
					$user = $this->UserData->getLoginData();
					if (empty($user['fb_id'])){
						$this->request->data['f'] = 1;
						$update_re = $this->update_fb_ajax();
					}
				}
			}
			else{
				$result['s']=0;
			}
		}
		
		$this->set('result', $result);
		return $result;
	}
    
	public function login_ajax() {
		
		$data = array();
		if ($this->RequestHandler->isAjax())
		{
			//$this->layout = 'ajax';
			
			$data = $this->request->data;
			
		}
		
		$msg = "";
		$errors = 0;
		if (empty($data))
		{
			$msg=$msg."Please fill in form<br/>";
			$errors = $errors + 1;
		}
		//debug($data);
		
		if (empty($data['data_username']))
		{
			$msg=$msg."Please fill in email<br/>";
			$errors = $errors + 1;
		}
		if (empty($data['data_password']) || $data['data_password'] == "")
		{
			$msg=$msg."Please fill in password<br/>";
			$errors = $errors + 1;
		}

		
        if (0 == $errors) {
            $email = $data['data_username'];
			$password = $data['data_password'];
			$user = $this->User->findUserByEmailAndPassword($email, $password);
			
			if (!empty($user)) 
			{
                $is_active = $user['active'];
				$confirmation_link = $user['confirmation_link'];
				
				if (1 == $is_active)
				{
					//$msg = "Congrats, you are now registered user.";
					
					$this->UserData->setLoginData($user);
					$role = $user['role'];
					if ($role == 'company')
					{
						$msg = "If you have any issue creating or managing your coupons or understanding them, please call/email us and let us know.";
						$company = $this->Company->getRawCompanyInfoByEmail($email);
						$this->UserData->setCompanyData($company);
						
					}
					else if ($role == 'blogger')
					{
						$msg = "A warm welcome from our team. :). We highly appreciate your feedbacks and merchant suggestions. Keep them sending our way.";
						//$blogger = $this->Blogger->getRawBloggerInfoByEmail($email);
						//$this->UserData->setBloggerData($blogger);
						
					}
					else if ($role == 'admin')
					{
						// blank
					}
					else
					{
						$errors = 1;
						$msg = 'Our system does not have all the info about you.';
					}
				}
				else if ( (0==$is_active) && !empty($confirmation_link))
				{
					$msg = "Please check you email for an email confirmation link. Visit that link to confirm your email and activate your account.";
					//$msg = $msg. "If the problem persists, give us a call or email and we will resolve it immeditately";
					$errors = $errors + 1;
				}
				else if (-1 == $is_active)
				{
					$msg = "Your account is temporarily suspended.";
					$errors = $errors + 1;
				}
            }
			else 
			{
				$msg = "Oops, looks like the email, password doesn't match.";
				//$msg = $msg. "If the problem persists, give us a call or email and we will resolve it immeditately";
				$errors = $errors + 1;
			}
            
        }
		
		$result = array('errors' => $errors, 'msg' => $msg);
		$this->set('result', $result);
		return $result;
    }
	
	// Admin routine
	public function view_all(){
	
		$this->only_admin_can_see(); // IMP
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());

	}
	
	// Admin routine
	public function find_by_email(){
		
		$this->only_admin_can_see(); // IMP
		
		$r=array('s'=>true,'m'=>'', 'u'=>false);
		$this->set('r', $r);
		
		$is_post = $this->RequestHandler->isPost();
		if (!$is_post){
			$this->Session->setFlash("Enter email to lookup a user.");
			return $r;
		}
		
		$data = $this->request->data;
		if(empty($data['User']['email'])){
			$this->Session->setFlash("Bad request.");
			return $r;
		}
		
		$email = $data['User']['email'];
		$user = $this->User->findUserByEmail($email);
		if (empty($user)){
			$r['m']= "No user not found with email {$email} .";
			$this->Session->setFlash($r['m']);
			$this->set('r',$r);
			return $r;
		}
		
		unset($user['password']);
		$r['u'] = $user;
		
		$this->CollectEncrypt = $this->Components->load('CollectEncrypt');
		$encrypted_uid = $this->CollectEncrypt->encrypt_str($user['id']);
		$r['eu'] = $encrypted_uid;
		
		$this->set('r', $r);
		return $r;
		
	}
	
	// Admin routine
	public function out_and_in_as($encrypted_uid){
		
		$this->only_admin_can_see(); // IMP
		
		$r=array('s'=>true,'m'=>'');
		
		if(empty($encrypted_uid)){
			$r['m']="Bad request.";
			$this->Session->setFlash($r['m']);
			$this->set('r', $r);
			return $r;
		}
		
		$this->CollectEncrypt = $this->Components->load('CollectEncrypt');
		$user_id = $this->CollectEncrypt->decrypt_str($encrypted_uid);
		
		$user = $this->User->findUserById($user_id);
		if (empty($user)){
			$r['m']='User not found.';
			$this->Session->setFlash($r['m']);
			$this->set('r',$r);
			return $r;
		}
		
		$r['s'] = true;
		
		// Clear current login data
		$this->UserData->cleanLoginData();
		
		// Set new login data 
		$this->UserData->setLoginData($user);
		$role = $user['role'];
		if ($role == 'company')
		{
			$email = $user['username'];
			$r['m'] = "If you have any issue creating or managing your coupons or understanding them, please call/email us and let us know.";
			$company = $this->Company->getRawCompanyInfoByEmail($email);
			$this->UserData->setCompanyData($company);			
		}
		else if ($role == 'blogger')
		{
			$r['m'] = "A warm welcome from our team. :). We highly appreciate your feedbacks and merchant suggestions. Keep them sending our way.";
		}
		else if ($role == 'admin')
		{
			// blank
		}
		else
		{
			$errors = 1;
			$r['m'] = 'Our system does not have all the info about you.';
		}
		
		$this->Session->setFlash($r['m']);
		$this->set('r',$r);
		return $r;
	}
	
	public function logout_ajax() 
	{
		$is_ajax = $this->RequestHandler->isAjax();
		$this->UserData->cleanLoginData();
		
		if (!$is_ajax)
		{
			$next = false;
			if (!empty($this->request->query['next']))
			{
				$next = $this->request->query['next'];
			}
			if ($next)
			{
				$this->redirect(urldecode($next));
			}
		}		
		
		$result = array('errors' => 0, 'msg' => "Thanks for visiting us. Please come back again as and when you like.");
		$this->set('result', $result);
		return $result;
	}	
	
	###### Reddit functions ############33
	public function reddit_login()
    {
		$result = 0;
		
		$this->layout = 'ajax';
		if ($this->RequestHandler->isAjax())
		{
			$data = $this->request->data;
			$username = $data['user'];
			$password = $data['passwd'];
			
			App::import('Vendor', 'RedditApiClient/HttpRequest');
			App::import('Vendor', 'RedditApiClient/HttpResponse');
			App::import('Vendor', 'RedditApiClient/Reddit');
			$reddit = new RedditApiClient\Reddit;
			$could_login = $reddit->login($username, $password);
			
			if ($could_login)
			{
				$result = 1;
				$reddit->updateModHashAndSessionInUDC($this->UserData);
				//debug($this->UserData->GetRedditData());
			}
			
		}
		
		$this->set('result', $result);
        return $result;
    }
	
	public function is_user_reddit_loggedin()
	{
		$result = 0;
		
		$this->layout = 'ajax';
		if ($this->RequestHandler->isAjax())
		{
			
			App::import('Vendor', 'RedditApiClient/HttpRequest');
			App::import('Vendor', 'RedditApiClient/HttpResponse');
			App::import('Vendor', 'RedditApiClient/Reddit');
			$reddit = new RedditApiClient\Reddit;
			
			
			$reddit->setModHashAndSessionFromUDCVals($this->UserData);
			$could_login = $reddit->isLoggedIn();
			$reddit->updateModHashAndSessionInUDC($this->UserData);
			
			if ($could_login)
			{
				$result = 1;
			}
			
		}
		
		$this->set('result', $result);
        return $result;
	}
}

?>