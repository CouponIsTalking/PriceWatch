<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class ContactsController extends AppController {

/**
 * Components
 *
 * @var array
 */

	//public $components = array('RequestHandler', 'UserData');
	public $components = array(
		'RequestHandler',
		'UserData',
		'EmailAccess'
		);
		
	var $helpers = array('Html');//,'Javascript');
    
	public $uses = false;
	
	function about_us()
	{
	}
	
	function speak_with_us(){
        
		$result = array ('msg' => '', 'success' => 0);
    	$successfully_mail_sent = 0;
    	
		$is_ajax = $this->RequestHandler->isAjax();
		
		if ($is_ajax) 
		{ 
			$this->layout = 'ajax'; 
		}
		
    	//$this->Session->setFlash('Ding Dong.');
    	$user_type = '';
    	        		
    	if ($this->RequestHandler->isPost()) 
		{
        	if(true /*|| $this->Captcha->validate()*/) 	// TODO: put captcha back, skipping captcha for a while, as it was not being happy with modal window.
 	        {	
        		//send email using the Email component
				
				if ($is_ajax)
				{
					$subject = $this->data['subject'];  
					$reason = $this->data['reason'];
					$message = $this->data['message']; 
					$from_email = $this->data['email'];
					$from_name = $this->data['name'];
				}
				else
				{
					$subject = $this->data['Contact']['subject'];  
					$reason = $this->data['Contact']['reason'];
					$message = $this->data['Contact']['message']; 
					$from_email = $this->data['Contact']['email'];
					$from_name = $this->data['Contact']['name'];
				}
				//$template = 'contact_request';
				//$replyTo = $this->data['Contact']['email'];
				
				$from_name = trim($from_name);
				$from_email = trim($from_email);
				$reason = trim($reason);
				$subject = trim($subject);
				$message = trim($message);
				
				if (empty($from_name))
				{
					$result['msg'] = 'Please provide your name.';
				}
				else if (empty($from_email))
				{
					$result['msg'] = 'Please provide your email, so we can reach you back if needed.';
				}
				else if (empty($reason))
				{
					$result['msg'] = 'Please choose a reason that is closest to your message.';
				}
				else if (empty($subject))
				{
					$result['msg'] = 'Please add a subject to your message.';
				}
				else if (empty($message))
				{
					$result['msg'] = 'Please write your message.';
				}
				else
				{
					$contact_msg = array(
						'subject' => $subject,
						'reason' => $reason,
						'message' => $message,
						'from_email' => $from_email,
						'from_name' => $from_name
					);
					
					$successfully_mail_sent = $this->EmailAccess->shoot_contact_request($contact_msg);
					
					if($successfully_mail_sent) {
						$result_of_email = $this->EmailAccess->send_contact_confirmation_email($from_name, $from_email);
						$result['msg'] = 'Thank you for reaching out. We will revert back to you as soon as possible.';
						$result['success'] = 1;
					}
					else
					{
						$result['msg'] = "Hmm.. Something didn't work right. Please drop us an email at ". EmailAccessComponent::TEAM_EMAIL;
					}
				}
			}
        	
    	}
    	
		$user_email = $this->UserData->getUserEmail();
				
		$this->set('user_email', $user_email);
    	$this->set('successfully_mail_sent', $successfully_mail_sent);
		
		$this->set('is_ajax', $is_ajax);
		$this->set('result', $result);
		
		$this->set('title_for_layout', 'Get in touch. Speak with us.');
		
		return;
    }

	
}

?>