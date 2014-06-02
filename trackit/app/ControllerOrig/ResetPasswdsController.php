<?php
App::uses('AppController', 'Controller');
/**
 * ResetPasswds Controller
 *
 * @property ResetPasswd $ResetPasswd
 * @property PaginatorComponent $Paginator
 */
class ResetPasswdsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Auth' => array(),
		'TimeManagement', 'UserData', 'EmailAccess', 'RequestHandler');
	
	public $uses = array ('User', 'ResetPasswd');
	
	
	public function beforeFilter() {
        parent::beforeFilter();
		$this->Auth->allow('v_n_c');
		$this->Auth->allow('get_reset_link');
    }
	
	
	public function v_n_c($h = null)
	{
		
		$result = array ('success' => 0, 'msg' => "");
		
		if (empty($h))
		{
			$this->layout='ajax';
			return ;
		}
		
		$this->set('show_form', true);
		
		$is_post = $this->RequestHandler->isPost();
		if ($is_post)
		{
			$data = $this->request->data;
			$sub_email = $data['User']['email'];
			$pass1 = $data['User']['pass1'];
			$pass2 = $data['User']['pass2'];
			
			if (empty($sub_email) || empty($pass1) || empty($pass2))
			{
				$msg = "Please fill in all fields.";
				$result['msg'] = $msg;
				$this->set('result', $result);
				$this->Session->setFlash($msg);
			}
			elseif($pass1 != $pass2)
			{
				$msg = "Password1 and password2 do not matach. Please reenter passwords.";
				$result['msg'] = $msg;
				$this->set('result', $result);
				$this->Session->setFlash($msg);
				return;
			}
			
			$entry = $this->ResetPasswd->findEntryByHash($h);
			if (empty($entry))
			{
				$new_link = SITE_NAME."reset_passwds/get_reset_link";
				
				$msg = "This link has expired. Obtain a new link from here - <a href=\"{$new_link}\" style='color:white;'>Get Reset Password link.</a>";
				$result['msg'] = $msg;
				$this->set('result', $result);
				$this->Session->setFlash($msg);
				
				$this->set('show_form', false);
				return;
			}
			
			$email = $entry['ResetPasswd']['email'];
			$add_time = $entry['ResetPasswd']['add_time'];
			
			if ($sub_email != $email)
			{
				$msg = "Please check the email address you entered.";
				$result['msg'] = $msg;
				$this->set('result', $result);
				$this->Session->setFlash($msg);
				return;
			}
			
			$now_time = TimeManagement::TSNowTime();
			$time_diff = TimeManagement::TimeDifference($add_time, $now_time);
			if ($time_diff[0] > 0 || $time_diff[1] > 2)
			{
				$msg = "This link has expired.";
				$result['msg'] = $msg;
				$this->set('result', $result);
				$this->Session->setFlash($msg);
				
				$this->set('show_form', false);
				return;
			}
			
			$saved = $this->User->set_new_passwd_by_email($email, $pass1);
			
			if(!empty($saved))
			{
				$msg = "Your password is updated. Please login with your new password.";
				$result['msg'] = $msg;
				$this->set('result', $result);
				$this->Session->setFlash($msg);
				
				//$this->Session->setFlash("Your password is updated. Please login with your new password.");
				$this->ResetPasswd->deleteByEmail($email);
				$this->set('show_form', false);
			}
			else
			{
				$msg = "An Unknown error occured. If this problem persists, then please drop us an email.";
				$result['msg'] = $msg;
				$this->set('result', $result);
				$this->Session->setFlash($msg);
			}
			
		}
	}
	
	public function get_reset_link()
	{
		$result = array ('success' => 0, 'msg' => "");
		
		$ispost = $this->RequestHandler->isPost();
		$this->set('ispost', $ispost);
		$is_ajax = $this->RequestHandler->isAjax();
		$this->set('is_ajax', $is_ajax);	
			
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
		
		
		if ($ispost)
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
			
			if (!empty($email))
			{
				$user = array('User' => array());
				$user['User'] = $user = $this->User->findUserByEmail($email);
				if (!empty($user['User']))
				{
					$add_time = TimeManagement::TSNowTime();
					$result = $this->ResetPasswd->create_new_hash($email, $add_time);
					if ($result['success'])
					{
						$reset_password_link = SITE_NAME. "reset_passwds/v_n_c/".$result['hash'];
						
						$email_sent = $this->EmailAccess->shoot_email_for_reset_password($email, "", $reset_password_link);
						
						if ($email_sent)
						{
							$result['msg'] = "We have sent a link to your email '$email'. Please check you email and follow the link to reset your password.";
						}
						else
						{
							$result['msg'] = 'There was an issue sending reset password link to your email. If this problem persists, send us an email.';
						}
					}
				}
				else
				{
					$result['msg'] = "There is no user with this email.";
				}
			}
			else
			{
				$result['msg'] = 'Bad data.';
			}
			
			$this->set('result', $result);
			
		}
		
	}
}
