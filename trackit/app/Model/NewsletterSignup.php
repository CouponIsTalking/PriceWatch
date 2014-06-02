<?php
App::uses('AppModel', 'Model');
/**
 * NewsletterSignup Model
 *
 */
class NewsletterSignup extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'company_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	public function build_slug($email, $company_id)
	{
		$slug = $email . '-' . $company_id;
		return $slug;
	}
	
	public function signup_emails($emails, $company_id, $user_id)
	{
		if (empty($emails) || empty($company_id)) {return false;}
		
		if (!is_array($emails))
		{
			$emails = array($emails);
		}
		
		foreach($emails as $i=>$email)
		{
			$this->create();
			$slug = $this->build_slug($email, $company_id);
			$data = array ('NewsletterSignup' => array());
			$data['NewsletterSignup']['email'] = $email;
			$data['NewsletterSignup']['company_id'] = $company_id;
			$data['NewsletterSignup']['email_cid_slug'] = $slug;
			
			$saved = $this->save($data);
			
			if (empty($saved))
			{
				$result = false;
				return;
			}
		}
		
		return true;
	}
	
	public function is_email_signedup($company_id, $email)
	{
		$slug = $this->build_slug($email, $company_id);
		
		$signedup = $this->find('first', array(
				'conditions' => array('email_cid_slug' => $slug),
				'recursive'=> -1
			));
		if (!empty($signedup['NewsletterSignup']))
		{
			return true;
		}
		return false;
	}
	
	public function remove_signups($emails, $company_id)
	{
		if (empty($emails) || empty($company_id)) {return false;}
		
		if (!is_array($emails))
		{
			$emails = array($emails);
		}
		
		$slugs = array();
		foreach($emails as $i=>$email)
		{
			$slugs[] = $this->build_slug($email, $company_id);
			
		}
		$this->deleteAll(array('email_cid_slug IN' => $slugs), false, false);
			
		
		return true;
	}
	
}