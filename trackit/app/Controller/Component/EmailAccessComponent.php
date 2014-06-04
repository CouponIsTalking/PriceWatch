<?php
/**
 *
 * Requires PHP5 (simple xml) and the cURL library 
 * 
 * @author    Rahul
 * @version 1.0
 * @category Components 
 */
 
App::uses('email', 'Config');
App::uses('EmailOptionConfig', 'Config');

class EmailAccessComponent extends Component {
	
	//static TEAM_EMAIL; //"team@usemenot.com";
	//static NO_REPLY_EMAIL; //"team@usemenot.com";
	
	var $email_addresses = array();
	var $name = 'EmailAccess'; // the name of your component
	/*
	var $components = array('Email',
							'Postmark'
							); // the other component your component uses
	*/
	var $my_controller = null;
	
	var $test_mode=false;
	var $format_only=false;
	
	private $email;
	
	function set_test_mode(){
		$this->test_mode = true;
	}
	function clear_test_mode(){
		$this->test_mode = false;
	}
	function is_test_mode(){
		return $this->test_mode;
	}
	
	function set_format_only(){
		$this->format_only = true;
	}
	function clear_format_only(){
		$this->format_only = false;
	}
	function is_format_only(){
		return $this->format_only;
	}
	
	function shoot_price_notifications($params)
	{
		$subject = "Good News! Prices have dropped !";
		$template = "price_drop_group";
		// Prepare data_for_email, i.e., dfe
		$dfe = array();
		$dfe['to'] = $params['user_email'];
		$dfe['to_firstname'] = $params['user_name'];
		$dfe['subject'] = $subject;
		
		$dfe['formatting_vars']['user_name'] = $params['user_name'];
		$dfe['formatting_vars']['product_list'] = $params['product_list'];
		$dfe['formatting_vars']['subject'] = $subject;		
		
		$successfully_mail_sent = $this->shoot_email($template, $dfe);
		
		return $successfully_mail_sent;
		
	}
	function shoot_contact_request($params)
	{
		$subject = 'Contact Request - ' . $params['reason'];  
		$template = 'contact_initiated';
		
		$data_for_email['to'] = 'hemant456@gmail.com';
		$data_for_email['to_firstname'] = 'hemant';
		$data_for_email['subject'] = $subject;
		
		$data_for_email['formatting_vars']['from_name'] = $params['from_name'];
		$data_for_email['formatting_vars']['from_email'] = $params['from_email'];
		$data_for_email['formatting_vars']['reason'] = $params['reason'];
		$data_for_email['formatting_vars']['subject'] = $params['subject'];
		$data_for_email['formatting_vars']['message'] = $params['message'];
		
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	function send_contact_confirmation_email($from_name, $from_email)
	{
		$template = 'confirmation_for_contact_initiation';
		
		$data_for_email['to'] = $from_email;
		$data_for_email['to_firstname'] = $from_name;
		
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	function shoot_email_for_merchant_suggestion($comp_name, $website, $info, $user_email)
	{
		//return true;
		$subject = 'Merchant Suggested ';  
		$template = 'merchant_suggested';
		//$replyTo = $this->data['Contact']['email'];
		
		$data_for_email['to'] = 'hemant456@gmail.com';
		$data_for_email['to_firstname'] = 'hemant';
		$data_for_email['formatting_vars']['comp_name'] = $comp_name;
		$data_for_email['formatting_vars']['website'] = $website;
		$data_for_email['formatting_vars']['info'] = $info;
		$data_for_email['formatting_vars']['user_who_suggested'] = $user_email;
		//$data_for_email['formatting_vars']['message'] = $message;
		
		$data_for_email['formatting_vars']['subject'] = $subject;
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	function send_coupon($user_email, $user_name, $company_name, $coupon_code, $coupon_line, $coupon_details, $coupon_valid_until_date)
	{
	
		$email_params = array(
				'user_email' => $user_email,
				'user_name' => $user_name,
				'company_name' => $company_name,
				'coupon_code' => $coupon_code,
				'coupon_line' => $coupon_line,
				'coupon_details' => $coupon_details,
				'coupon_valid_until_date' => $coupon_valid_until_date,
				'verifier' => 'N/A'
			);	
		
		$this->send_coupon_ary($email_params);
	}
	
	function send_deferred_coupon_ary($email_params){
	
		$user_email 				= $email_params['user_email'];
		$user_name 					= $email_params['user_name'];
		$company_name 				= $email_params['company_name'];
		$next_step_msg 				= $email_params['next_step_msg'];
		$subject					= $email_params['subject'];
		
		$template = 'send_deferred_coupon';
		//$replyTo = $this->data['Contact']['email'];
		
		$data_for_email['to'] = $user_email;
		$data_for_email['to_firstname'] = $user_name;
		$data_for_email['formatting_vars']['user_name'] = $user_name;
		$data_for_email['formatting_vars']['from_name'] = GENERIC_APPNAME;
		
		$data_for_email['formatting_vars']['company_name'] = $company_name;
		$data_for_email['formatting_vars']['next_step_msg'] = $next_step_msg;
		
		$data_for_email['subject'] = $subject;
		$data_for_email['formatting_vars']['subject'] = $subject;
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	function send_coupon_ary($email_params)
	{
		$user_email 				= $email_params['user_email'];
		$user_name 					= $email_params['user_name'];
		$company_name 				= $email_params['company_name'];
		$coupon_code 				= $email_params['coupon_code'];
		$coupon_line				= $email_params['coupon_line'];
		$coupon_details 			= $email_params['coupon_details'];
		$coupon_valid_until_date 	= $email_params['coupon_valid_until_date'];
		$verifier 					= $email_params['verifier'];
		
		$enabled = true;
		if (!$enabled)
		{
			return true;
		}
		
		$subject = 'Here is Your Coupon '. $coupon_code;  
		$template = 'send_coupon';
		//$replyTo = $this->data['Contact']['email'];
		
		$data_for_email['to'] = $user_email;
		$data_for_email['to_firstname'] = $user_name;
		$data_for_email['formatting_vars']['user_name'] = $user_name;
		$data_for_email['formatting_vars']['from_name'] = GENERIC_APPNAME;
		//$data_for_email['formatting_vars']['message'] = $message;
		
		$data_for_email['formatting_vars']['company_name'] = $company_name;
		$data_for_email['formatting_vars']['coupon_code'] = $coupon_code;
		$data_for_email['formatting_vars']['coupon_line'] = $coupon_line;
		$data_for_email['formatting_vars']['coupon_details'] = $coupon_details;
		$data_for_email['formatting_vars']['verifier'] = $verifier;
		
		$data_for_email['subject'] = $subject;
		
		if (empty($coupon_valid_until_date))
		{
			$data_for_email['formatting_vars']['coupon_valid_until_date'] = "";
		}
		else
		{
			$data_for_email['formatting_vars']['coupon_valid_until_date'] = "Valid until " . $coupon_valid_until_date;
		}
		
		$data_for_email['formatting_vars']['subject'] = $subject;
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	function shoot_email_after_registration($user_email, $user_name)
	{
		$subject = 'Welcome From '. GENERIC_APPNAME;  
		$template = 'welcome_after_registration';
		//$replyTo = $this->data['Contact']['email'];
		
		$data_for_email['to'] = $user_email;
		$data_for_email['to_firstname'] = $user_name;
		$data_for_email['formatting_vars']['user_name'] = $user_name;
		$data_for_email['formatting_vars']['from_name'] = GENERIC_APPNAME;
		//$data_for_email['formatting_vars']['message'] = $message;
		$data_for_email['formatting_vars']['subject'] = $subject;
		$data_for_email['formatting_vars']['merchant_suggestion_link'] = SITE_NAME . "companies/merchant_suggestions";
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	function shoot_email_for_confirm_extra_email($user_email, $user_name, $confirmation_link)
	{
		$subject = 'Confirm your email for '. GENERIC_APPNAME;  
		$template = 'confirm_email_extra_email';
		//$replyTo = $this->data['Contact']['email'];
		
		$data_for_email['to'] = $user_email;
		$data_for_email['to_firstname'] = $user_name;
		$data_for_email['formatting_vars']['user_name'] = $user_name;
		$data_for_email['formatting_vars']['from_name'] = GENERIC_APPNAME;
		$data_for_email['formatting_vars']['confirmation_link'] = $confirmation_link;
		$data_for_email['formatting_vars']['subject'] = $subject;
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	function shoot_email_for_confirm_email($user_email, $user_name, $confirmation_link)
	{
		$subject = 'Confirm your email for '. GENERIC_APPNAME;  
		$template = 'confirm_email';
		//$replyTo = $this->data['Contact']['email'];
		
		$data_for_email['to'] = $user_email;
		$data_for_email['to_firstname'] = $user_name;
		$data_for_email['formatting_vars']['user_name'] = $user_name;
		$data_for_email['formatting_vars']['from_name'] = GENERIC_APPNAME;
		$data_for_email['formatting_vars']['confirmation_link'] = $confirmation_link;
		$data_for_email['formatting_vars']['subject'] = $subject;
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	function shoot_email_for_reset_password($user_email, $user_name, $reset_password_link)
	{
		$subject = 'Reset password for '. GENERIC_APPNAME;  
		$template = 'reset_password';
		//$replyTo = $this->data['Contact']['email'];
		
		$data_for_email['to'] = $user_email;
		$data_for_email['to_firstname'] = $user_name;
		$data_for_email['formatting_vars']['user_name'] = $user_name;
		$data_for_email['formatting_vars']['from_name'] = GENERIC_APPNAME;
		$data_for_email['formatting_vars']['reset_password_link'] = $reset_password_link;
		$data_for_email['formatting_vars']['subject'] = $subject;
		$successfully_mail_sent = $this->shoot_email($template, $data_for_email);
		
		return $successfully_mail_sent;
	}
	
	
	function shoot_email($reason = null, $data, $method = 'postmark') {
		
		if(empty($data['to'])) {
			return null;
		}
		if(empty($data['to_firstname'])) {
			$data['to_firstname'] = '';
		}
		
		$data['tag_or_reason'] = $reason;
		
		if(empty($data['formatting_vars'])) 
			$data['formatting_vars'] = null;
			
		if(empty($data['formatting_vars']['firstname'])) {
			$data['formatting_vars']['firstname'] = $data['to_firstname'];
		}
		
		// If the first name is still empty, then pull the email username
		// as the first name
		if(empty($data['formatting_vars']['firstname'])) {
			$at_position = strpos($data['to'], '@');
			$data['formatting_vars']['firstname'] = substr($data['to'], 0, $at_position);
		}
			
		// Formatting var to set the footer message 
		$data['formatting_vars']['_email_to'] = $data['to'];
		
		if ('contact_initiated' == $reason)
		{
			$data['subject'] = $data['subject'];
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		else if ('confirmation_for_contact_initiation' == $reason)
		{
			$data['subject'] = 'Thank you for reaching out.';
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		else if($reason == 'confirmation_email_to_user_from_contact_us_page') {
			
			$data['subject'] = 'Thank you for contacting us';
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		else if($reason == 'contact_request') {
			
			$data['subject'] = 'Contact Request';
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		else if($reason == 'thankyou') {
		
			$data['subject'] = 'Thank you from '.GENERIC_APPNAME;
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		else if($reason == 'welcome_after_registration') {
		
			$data['subject'] = 'Welcome from '.GENERIC_APPNAME;
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		else if($reason == 'reset_password') {
		
			$data['subject'] = 'Reset Password for '.GENERIC_APPNAME;
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		else if($reason == 'confirm_email') {
		
			$data['subject'] = 'Confirm Your Email for '.GENERIC_APPNAME;
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		else if($reason == 'confirm_email_extra_email') {
		
			$data['subject'] = 'Confirm Your Email for '.GENERIC_APPNAME;
			$data['from'] = $this->email_addresses['team_email'];
			$data['fromName'] = GENERIC_APPNAME;
			$data['replyTo'] = $this->email_addresses['team_email'];
			$data['replyToName'] = GENERIC_APPNAME;
		}
		
		if(!empty($data['override'])) {
			foreach($data['override'] as $key => $override_value) {
				$data[$key] = $override_value;
			}
			$data['override'] = null;
		}
		
		$data['formatted_messages'] = EmailAccess::get_usual_formatted_messages($reason, $data['formatting_vars']);
		
		$result = $this->format_email_options_and_send_email($method, $data);
		
		return $result;
		
	}

	function format_email_options_and_send_email($method = null, $data_for_email = null) {
			
			//debug($data_for_email);
			
			if(empty($data_for_email)) {
				return null;
			}
			
			//$this->Postmark->reset();
	
			if(!empty($data_for_email['from'])) {
				$from = $data_for_email['from'];
			}
			else {
				$from = $this->email_addresses['no_reply_email'];
			}
			
			if(!empty($data_for_email['fromName'])) {
				$fromName = $data_for_email['fromName'];
			}
			else {
				$fromName = GENERIC_APPNAME;
			}
			
			if(!empty($data_for_email['replyTo'])) {
				$replyTo = $data_for_email['replyTo'];
			}
			else {
				$replyTo = $this->email_addresses['no_reply_email'];
			}
			
			if(!empty($data_for_email['replyToName'])) {
				$replyToName = $data_for_email['replyToName'];
			}
			else {
				$replyToName = GENERIC_APPNAME;
			}
			
			// format from and reply to
			//$from = $fromName .' <'.$from.'>';
			//$replyTo = $replyToName .' <'.$replyTo.'>';
			
			if(empty($data_for_email['to'])) {
				$to = '';
			}
			else
				$to = $data_for_email['to'];
			
			if(empty($data_for_email['cc'])) {
				$cc = array();
			}
			else
				$cc = $data_for_email['cc'];
				
			if(empty($data_for_email['bcc'])) {
				$bcc = array();
			}
			else
				$bcc = $data_for_email['bcc'];

			// Disable BCC for the timebeing.
			// $bcc[] = 'emailarchives@jodhpuriconsultant.com';
			
			if(empty($data_for_email['subject'])) {
				$subject = 'From '.GENERIC_APPNAME;
			}
			else
				$subject = $data_for_email['subject'];

			if(empty($data_for_email['template'])) {
				$template = 'default';
			}
			else {
				$template = $data_for_email['template'];
			}
			
			$attachments = null;
			if (!empty($data_for_email['attachments']))
			{
				$attachments = $data_for_email['attachments'];
			}
			
			$tag = $data_for_email['tag_or_reason'];
			//$this->tag = $tag;
			
			if(!empty($data_for_email['message']))
				$message = $data_for_email['message'];
			else
				$message = '123';
			
			$text_message = $data_for_email['formatted_messages']['text'];
			$html_message = $data_for_email['formatted_messages']['html'];
			
			
			$this->controller->set('data_for_email', $data_for_email);
			
			
			$result = false;
			
			$this->email->template($template);
			$this->email->emailFormat('html');
			$this->email->from(array($from => $fromName));
			$this->email->replyTo(array($replyTo => $replyToName));
			$this->email->to($to);
			$this->email->cc($cc);
			$this->email->bcc($bcc);
			$this->email->subject($subject);
			$this->email->addHeaders(array('Tag' => $tag));
			$this->email->attachments($attachments);
			
			if($this->is_format_only()){
				$stuff = array(
					'template' => $template,
					'from' => $from,
					'fromName' => $fromName,
					'replyTo' => $replyTo,
					'replyToName' => $replyToName,
					'to' => $to,
					'cc' => $cc,
					'bcc' => $bcc,
					'subject' => $subject,
					'attachments' => $attachments//,
					//'html_message' => $html_message
				);
				return $stuff;
			}
			else if ($method == 'postmark')
			{
				/*
				$this->Postmark->delivery = 'mail'; // 
				$this->Postmark->to = $to;
				$this->Postmark->cc = $cc;
				$this->Postmark->bcc = $bcc;
				$this->Postmark->subject = $subject;
				$this->Postmark->from = $from;
				$this->Postmark->replyTo = $replyTo;
				$this->Postmark->attachments = $attachments;
				
				//Send as 'html', 'text' or 'both' (default is 'text')
				//$this->Postmark->sendAs = 'both';
				$this->Postmark->sendAs = 'html';
				
				//debug ($this->Postmark);
				$this->Postmark->template = $template;
				*/

				$this->email->config('postmark');
				$sendReturn =  $this->email->send($html_message);
				$headers = $this->email->getHeaders(array('to'));
				//debug($sendReturn); debug($headers);
				if (
					//($sendReturn['To'] == $headers['To']) ||
					(empty($sendReturn['ErrorCode']) || $sendReturn['ErrorCode'] == 0) ||
					(empty($sendReturn['Message']) || $sendReturn['Message'] == 'OK')
					)
				{
					$result = true;
				}
				else
				{
					$result = false;
				}
				
				//debug($sendReturn);debug($headers);
				
			}
			else
			{
				/*
				$this->Email->delivery = 'mail'; // 
				$this->Email->to = $to;
				$this->Email->cc = $cc;
				$this->Email->bcc = $bcc;
				$this->Email->subject = $subject;
				$this->Email->from = $from;
				$this->Email->replyTo = $replyTo;
				$this->Email->attachments = $attachments;
				
				//Send as 'html', 'text' or 'both' (default is 'text')
				$this->Email->sendAs = 'both';
				
				$this->Email->template = $template;
				*/
				
				$sendReturn =  $this->email->send($html_message);
				$headers = $this->email->getHeaders(array('to'));
				
				if (empty($sendReturn))
				{
					$result = false;
				}
				else
				{
					$result = true;
				}
			}
			
			/*
			//debug($html_message);
			if ($method == 'postmark')
			{
				$this->Postmark->htmlMessage = $html_message; //'html message';
				$this->Postmark->textMessage = $text_message; //'text message';
				$result = $this->Postmark->send_via_postmark($message);
				
				if ($result['ErrorCode'] == 0) {
					return true;
				} else {
					//debug($result);
					return false;
				}
			}
			else
			{
				$this->Email->htmlMessage = $html_message; //'html message';
				$this->Email->textMessage = $text_message; //'text message';
				$result = $this->Email->send($message);
				
				if (empty($result)) {
					//debug($result);
					return false;
				} else {
					return true;
				}
			}
			*/
			
			//debug($result);
			
			return $result;
			
	}
	
	//called after Controller::beforeFilter()	
	/*
	function startup(&$controller) {
		//$this->Email->foo();
		$this->controller = &$controller;
	}
	*/
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		
		App::uses('CakeEmail', 'Network/Email');
		$this->email = new CakeEmail();
		$this->email_addresses = EmailOptionConfig::$addresses;
	}
	
	
}
class EmailAccess{    
    protected $sample_variable = 'something';

    static function get_usual_formatted_messages($template, $data_for_formatting) {
    	
    	$result = array('text' => 'empty', 'html' => 'empty');
    	
    	//$tpl_file = $template.'.ctp';
    	
		$template_contents = EmailAccess::get_template_file_contents($template);
		
		$arr_tpl_vars = array();
		$arr_tpl_data = array();
		
		foreach ($data_for_formatting as $key => $replacement_string) {
			$arr_tpl_vars[] = '{'.$key.'}';
			$arr_tpl_data[] = $replacement_string;
		}
		
		$text_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['text']);
		$html_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['html']);
		
		// Okay, now put the html message into the html layout
		// 1. Read the layout file contents.
		// 2. Replace {MeSsAge} by the html message formatted so far 
		//
		$add_html_layout_only = true;
				
		if($add_html_layout_only == true || !empty($html_msg))
		{
			$html_layout_content = EmailAccess::get_html_layout_file_contents();
			$html_msg = str_replace('{MeSsAge}', $html_msg, $html_layout_content);
			// Replace the _email_to in the footer of email.
			// This would form proper message , the one that says, You are receiving this email at {_email_to} .. blah ... 
			$html_msg = str_replace('{_email_to}', $data_for_formatting['_email_to'], $html_msg);
		}
		
		$result['text'] = $html_msg; //text_msg;
		$result['html'] = $html_msg;	
	//	debug($result);
		return $result;
		
    }    

    // Return contents of html layout file.
    // null if the layout file not found.
    //
    static function get_html_layout_file_contents() {
    	
    	//$html_layout_file = 'email_templates/html_layout.html';
    	$html_layout_file = 'email_templates/coupon_layout.html';
		
    	if (file_exists($html_layout_file)) {
		    $result = file_get_contents($html_layout_file);	
		    return $result;
    	}
    	else {
    		//TODO: log severe error message that email template file $tpl_file not found
    		return null;
    	}
    }
    
    static function get_template_file_contents($tpl_file){
    	
    	$result = array('text' => '', 'html' => '');
    	
    	$text_tpl_file = 'email_templates/text/'.$tpl_file .".ctp";
    	$html_tpl_file = 'email_templates/html/'.$tpl_file .".html";
    	
    	if (file_exists($text_tpl_file)) {
		    $result['text'] = file_get_contents($text_tpl_file);	
    	}
    	else {
    		//TODO: log severe error message that email template file $tpl_file not found
    	}
		
    	if (file_exists($html_tpl_file)) {
		    $result['html'] = file_get_contents($html_tpl_file);	
    	}
    	else {
    		//TODO: log severe error message that email template file $tpl_file not found
    	}
    	
    	return $result;
    }
    
    static function get_formatted_messages($reason, $data_for_formatting) {
    	
    	$result = array('text' => 'empty', 'html' => 'empty');
    	
    	switch($reason) {
    		case 'confirmation_email_to_user_from_contact_us_page' :
    			
    			$tpl_file = 'confirmation_email_to_user_from_contact_us_page.ctp';
				$template_contents = EmailAccess::get_template_file_contents($tpl_file);
				$arr_tpl_vars = array('{firstname}');
				$arr_tpl_data = array($data_for_formatting['firstname']);
				$text_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['text']);
				$html_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['html']);
				$result['text'] = $text_msg;
				$result['html'] = $html_msg;	
				return $result;
				
    			break;
    		case 'contact_request' :
    			
    			$tpl_file = 'contact_request.ctp';
				$template_contents = EmailAccess::get_template_file_contents($tpl_file);
				$arr_tpl_vars = array('{user_type}', '{from_name}', '{from_email}' , '{subject}', '{message}' );
				$arr_tpl_data = array(
								$data_for_formatting['user_type'],
								$data_for_formatting['from_name'], $data_for_formatting['from_email'], 
								$data_for_formatting['subject'], $data_for_formatting['message']
								);
				$text_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['text']);
				$html_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['html']);
				$result['text'] = $text_msg;
				$result['html'] = $html_msg;	
				return $result;
				
    			break;
    		case 'change_password' :
    			
    			$tpl_file = 'change_password.ctp';
				$template_contents = EmailAccess::get_template_file_contents($tpl_file);
				$arr_tpl_vars = array('{firstname}');
				$arr_tpl_data = array($data_for_formatting['firstname']);
				$text_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['text']);
				$html_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['html']);
				$result['text'] = $text_msg;
				$result['html'] = $html_msg;	
				return $result;
				
    			break;
   			case 'reset_password' :
    			
    			$tpl_file = 'reset_password.ctp';
				$template_contents = EmailAccess::get_template_file_contents($tpl_file);
				$arr_tpl_vars = array('{firstname}', '{new_password}');
				$arr_tpl_data = array($data_for_formatting['firstname'], $data_for_formatting['new_password']);
				$text_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['text']);
				$html_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['html']);
				$result['text'] = $text_msg;
				$result['html'] = $html_msg;	
				return $result;
				
    			break;
   			case 'resend_registration_email_verification_link':
   				$tpl_file = 'resend_registration_email_verification_link.ctp';
				$template_contents = EmailAccess::get_template_file_contents($tpl_file);
				$arr_tpl_vars = array('{firstname}', '{reg_url}');
				$arr_tpl_data = array($data_for_formatting['firstname'], $data_for_formatting['reg_url']);
				$text_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['text']);
				$html_msg = str_replace($arr_tpl_vars, $arr_tpl_data, $template_contents['html']);
				$result['text'] = $text_msg;
				$result['html'] = $html_msg;	
				return $result;
				
    			break;
    		case 'cause_registration' :
    			break;
    		case 'admin_cause_pending_approval' :
    			break;
    		case 'cause_welcome_after_email_confirmation' :
    			break;
    		case 'company_registration' :
    			break;
    		case 'admin_company_pending_approval' :
    			break;
    		case 'company_welcome_after_email_confirmation' :
    			break;
    		case 'thankyou_after_deal_buy' :
    			break;
    	}
    	
    }
    
    
}
?>