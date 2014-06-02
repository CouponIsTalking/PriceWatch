<?php
App::uses('AppModel', 'Model');
/**
 * UserEmail Model
 *
 */
class UserEmail extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
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
	
	public function build_slug($email, $user_id)
	{
		$slug = $email . '-' . $user_id;
		return $slug;
	}
	
	// NOTE: assumes that user doesn't own email already
	public function add_email($email, $user_id, $confirmation_code)
	{
		if (empty($email) || empty($user_id)) {return false;}
		
		
		$this->create();
		$slug = $this->build_slug($email, $user_id);
		$data = array ('UserEmail' => array());
		$data['UserEmail']['email'] = $email;
		$data['UserEmail']['user_id'] = $user_id;
		$data['UserEmail']['email_uid_slug'] = $slug;
		$data['UserEmail']['confirmed'] = 0;
		$data['UserEmail']['confirmation_code'] = $confirmation_code;
		
		$saved = $this->save($data);
		
		if (empty($saved))
		{
			$result = false;
			return;
		}
		
		return $this->id;
	}
	
	public function get_from_entry_id($entry_id)
	{
		if (empty($entry_id)) {return false;}
		
		$entry = $this->find('first', array(
				'conditions' => array('id' => $entry_id),
				'recursive'=> -1
			));
		if (!empty($entry['UserEmail']))
		{
			return $entry;
		}
		return false;
	}
	
	public function user_owns_email($email, $user_id)
	{
		if (empty($email) || empty($user_id)) {return false;}
		
		$slug = $this->build_slug($email, $user_id);
		
		$entry = $this->find('first', array(
				'conditions' => array('email_uid_slug' => $slug),
				'recursive'=> -1
			));
		if (!empty($entry['UserEmail']))
		{
			return $entry;
		}
		return false;
	}
	
	public function is_email_confirmed($email, $user_id)
	{
		if (empty($email) || empty($user_id)) {return false;}
		
		$entry = $this->user_owns_email($email, $user_id);
		
		if (empty($entry) || empty($entry['UserEmail'])) {return false;}
		
		if (1 == $entry['UserEmail']['confirmed'])
		{
			return true;
		}
		return false;
	}
	
	public function set_email_confirmed($email, $user_id)
	{
		if (empty($email) || empty($user_id)) {return false;}
		
		$entry = $this->user_owns_email($email, $user_id);
		
		if (empty($entry) || empty($entry['UserEmail'])) {return false;}
		
		$entry['UserEmail']['confirmed'] = 1;
		$this->id = $entry['UserEmail']['id'];
		$saved = $this->save($entry, false, array('confirmed'));
		
		if (!empty($saved))
		{
			return true;
		}
		return false;
	}
	
	public function remove_email($email, $user_id)
	{
		if (empty($email) || empty($user_id)) {return false;}
		
		$slug = $this->build_slug($email, $user_id);
		$this->deleteAll(array('email_uid_slug' => $slug), false, false);
		
		return true;
	}
	
}