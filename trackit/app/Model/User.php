<?php
App::uses('AppModel', 'Model');
//App::uses('Security', 'Utility');
/**
 * User Model
 *
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'maxlength' => array(
				'rule' => array('maxlength'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'role' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'username' => array(
			'email' => array(
				'rule' => array('email'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			/*'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),*/
		),
		'terms' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'active' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	
	public function set_new_passwd_by_email($user_email, $new_pass)
	{
		if (empty($user_email) || empty($new_pass))
		{
			return false;
		}
		
		$user = array('User' => array());
		$user['User'] = $this->findUserByEmail($user_email);
		
		if (empty($user['User']))
		{
			return false;
		}
		
		$user['User']['password'] = $new_pass;
		$this->id = $user['User']['id'];
		$saved = $this->save($user, true, array('password'));
		//debug($saved);
		if (!empty($saved))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
			//Security::hash($this->data[$this->alias]['password'], null, true);//
		}
		return true;
	}
	
	public function findUserByEmailAndPassword($email, $password){
	
		if (!empty($email) && !empty($password))
		{
			$encoded_password = AuthComponent::password($password); //Security::hash($password, null, true); 
			$user = $this->find('first', array(
							'conditions'=>array(
									'username' => $email,
									'password' => $encoded_password
									),
							'recursive' => -1
							)
						);
			if (!empty($user['User']))
			{
				return $user['User'];
			}
		}
		
		return null;
	}
	
	public function findUserById($user_id){
	
		if (!empty($user_id))
		{
			$user = $this->find('first', array(
							'conditions'=>array(
									'id' => $user_id
									),
							'recursive' => -1
							)
						);
			if (!empty($user['User']))
			{
				return $user['User'];
			}
		}
		
		return null;
	}
	
	public function findFirstUserByFieldVal($field_name, $val)
	{
		$user = $this->find('first', array(
				'conditions'=>array($field_name => $val),
				'recursive' => -1
				));
						
		if (!empty($user['User'])){return $user['User'];}
		else {return null;}
	}
	
	public function findUserByFbId($fb_id){
		if (empty($fb_id)){return null;}
		$user = $this->findFirstUserByFieldVal('fb_id', $fb_id);
		return $user;
	}
	
	public function findUserByEmail($email){
		
		if (empty($email)){return null;}
		$user = $this->findFirstUserByFieldVal('username', $email);
		return $user;
	}
	
	public function findUserByTwitterId($twitter_id){
	
		if (!empty($twitter_id))
		{
			$user = $this->find('first', array(
							'conditions'=>array(
									'twitter_id' => $twitter_id
									),
							'recursive' => -1
							)
						);
			if (!empty($user['User']))
			{
				return $user['User'];
			}
		}
		
		return null;
	}
	
	public function activateUserByConfirmationHash($h)
	{
		$user = $this->findEntryByConfirmationHash($h);
		if (empty($user['User']))
		{
			return false;
		}
		
		$this->id = $user['User']['id'];
		$user['User']['active'] = 1;
		
		$saved = $this->save($user, true, array('active'));
		
		if (!empty($saved))
		{
			return $user;
		}
		else
		{
			return false;
		}
	}
	
	public function findEntryByConfirmationHash($h)
	{
		if (empty($h)){return null;}
		
		$entry = $this->find('first', array('recursive'=>-1, 'conditions'=>array('confirmation_link' => $h)));
		
		if (!empty($entry['User']))
		{
			return $entry;
		}
		else
		{
			return null;
		}
	}
	
	public function UpdateAndGetNewHash($user_id, $email, $feed)
	{
		$h = $this->GenerateConfirmationHash($email, $feed);
		
		$user = array('User'=> array() );
		$user['User']['id'] = $user_id;
		$user['User']['confirmation_link'] = $h;
		
		$this->id = $user_id;
		$saved = $this->save($user, true, array('confirmation_link'));
		
		if (!empty($saved))
		{
			return $h;
		}
		else
		{
			return false;
		}
	}
	public function GenerateConfirmationHash($email, $feed)
	{
		$h = "";
		for($i =0;;$i++)
		{
			$str_to_hash = $feed . strval($i). $email;
			$h = hash('ripemd160', $str_to_hash);
			
			$h_entry = $this->findEntryByConfirmationHash($h);
			if (empty($h_entry))
			{
				return $h;
			}
		}
	}
	
	public function updateName($uid, $firstname, $fullname)
	{
		$r = array('s'=>0,'m'=>'');
		$this->id = $uid;
		$user=array('User'=>array('id'=>$uid));
		
		$fields_to_save = array();
		if (!empty($firstname)){
			$user['User']['firstname']=$firstname;
			$fields_to_save = array('firstname');
		}
		if (!empty($fullname)){
			$user['User']['fullname']=$fullname;
			$fields_to_save = array('fullname');
		}
		
		if (!empty($firstname) && !empty($fullname)){
			$fields_to_save = array('firstname', 'fullname');
		}
		
		$saved = $this->save($user, true, $fields_to_save);
		if (!empty($saved)){$r['s']=1; return $r;}
		else {return $r;}
	}
	
	public function updateFbInfo($uid, $fbid, $fb_compact_info)
	{
		$r = array('s'=>0,'m'=>'');
		$this->id = $uid;
		//$fb_compact_info['first_name'];
		//$fb_compact_info['name'];
		$user=array('User'=>array('id'=>$uid,'fb_id'=>$fbid, 'fb_compact_info'=>json_encode($fb_compact_info)));
		$saved = $this->save($user, true, array('fb_id','fb_compact_info'));
		if (!empty($saved)){$r['s']=1; return $r;}
		else {return $r;}
	}
	
	public function CreateUserWithEmailAndPassword($email, $password, $user_type, $active, $confirmation_hash_feed)
	{
		$result = array ('success' => false, 'msg' => "", 'full_errors' => "" );
		
		$this->create();
		$newuser = array('User'=>array());
		$newuser['User']['username'] = $email;
		$newuser['User']['password'] = $password;
		$newuser['User']['role'] = $user_type;
		$newuser['User']['terms'] = 1;
		
		if (1 == $active || true == $active || '1' == $active)
		{
			$newuser['User']['active'] = 1;
		}
		else if (0 == $active || false == $active || '0' == $active)
		{
			$newuser['User']['active'] = 0;
		}
		else
		{
			$newuser['User']['active'] = $active;
		}
		
		if (!empty($confirmation_hash_feed))
		{
			$confirmation_hash = $this->GenerateConfirmationHash($email, $confirmation_hash_feed);
			$newuser['User']['confirmation_link'] = $confirmation_hash;
		}
		else
		{
			$newuser['User']['confirmation_link'] = "";
		}
		
		
		$saved = $this->save($newuser);
		
		if (!empty($saved))
		{
			$result['success'] = true;
			$result['msg']  = 'User Created';
			$result['user_data'] = array();
			
			$added_user = $this->findUserByEmail($email);
			if (!empty($added_user))
			{
				$result['user_data'] = array ('User' => $added_user);
			}
			//$newuser['User']['id'] = $this->id;
			//$result['user_data'] = $newuser;
			//debug($saved);
		}
		else
		{
			$result['success'] = false;
			$result['msg']  = 'User Creation failed';
			$result['full_errors'] = $this->validationErrors;
		}
		
		return $result;
	}
	
	public function CreateUserWithEmailAndTwitterInfo($email, $twitter_info, $user_type = 'blogger')
	{
		$result = array('error' => 0, 'msg' => "");
		
		$user_from_email = array();
		$user_from_email['User'] = $this->findUserByEmail($email);
		
		$user_from_tw_id = array();
		$twitter_id = $twitter_info['profile_data']['profile_data']['id_str'];
		$user_from_tw_id['User'] = $this->findUserByTwitterId($twitter_id);
		//debug($user_from_tw_id);
		//debug($twitter_id);
		
		if (!empty($user_from_email['User']['id']) 
			&& !empty($user_from_tw_id['User']['id'])
			//&& ($user_from_email['User']['id'] == $user_from_tw_id['User']['id'])
			)
			{
				//$result['error'] = false;
				//$result['msg'] = "User already present.";
				//$result['user_data'] = $user_from_email;
				
				$user_from_email['User']['twitter_auth_token'] = $twitter_info['token'];
				$user_from_email['User']['twitter_compact_info'] = json_encode($twitter_info);
				
				$this->id = $user_from_email['User']['id'];
				//debug($user_from_email);
				$saved = $this->save($user_from_email, true, array('twitter_auth_token', 'twitter_compact_info'));
				//debug($saved);
				if (!empty($saved))
				{
					$result['error'] = false;
					$result['msg'] = "Tw info updated in existing email user.";
					$user_from_email['User']['id'] = $this->id;
					$result['user_data'] = $user_from_email;
				}
				else
				{
					$result['error'] = true;
					$result['msg'] = "Tw info update failed in existing email user. " ; 
					$result['full_error'] = $this->validationErrors;
				}
			}
		elseif ( empty($user_from_email['User']['id']) 
				&& !empty($user_from_tw_id['User']['id'])
				)
			{
				$user_from_tw_id['User']['Username'] = $email;
				$this->id = $user_from_tw_id['User']['id'];
				$saved = $this->save($user_from_tw_id);
				if (!empty($saved))
				{
					$result['error'] = false;
					$result['msg'] = "Email updated in Tw Info.";
					$user_from_tw_id['User']['id'] = $this->id;
					$result['user_data'] = $user_from_tw_id;
				}
				else
				{
					$result['error'] = true;
					$result['msg'] = "Email update failed in Tw user. " ; 
					$result['full_error'] = $this->validationErrors;
				}
			}
		elseif ( !empty($user_from_email['User']['id']) 
				&& empty($user_from_tw_id['User']['id'])
				)
			{
				$user_from_email['User']['twitter_id'] = $twitter_id;
				$user_from_email['User']['twitter_auth_token'] = $twitter_info['token'];
				$user_from_email['User']['twitter_compact_info'] = json_encode($twitter_info);
				
				$this->id = $user_from_email['User']['id'];
				//debug($user_from_email);
				$saved = $this->save($user_from_email, true, array('twitter_id', 'twitter_auth_token', 'twitter_compact_info'));
				//debug($saved);
				if (!empty($saved))
				{
					$result['error'] = false;
					$result['msg'] = "Tw info updated in existing email user.";
					$user_from_email['User']['id'] = $this->id;
					$result['user_data'] = $user_from_email;
				}
				else
				{
					$result['error'] = true;
					$result['msg'] = "Tw info update failed in existing email user. " ; 
					$result['full_error'] = $this->validationErrors;
				}
			}
		elseif ( empty($user_from_email['User']['id']) 
				 && empty($user_from_tw_id['User']['id'])
				)
			{
				
				
				$newuser = array('User'=>array());
				$newuser['User']['username'] = $email;
				$newuser['User']['password'] = "";
				$newuser['User']['role'] = $user_type;
				$newuser['User']['terms'] = 1;
				$newuser['User']['active'] = 1;
				$newuser['User']['twitter_id'] = $twitter_id;
				$newuser['User']['twitter_auth_token'] = $twitter_info['token'];
				$newuser['User']['twitter_compact_info'] = json_encode($twitter_info);
				
				$this->create();
				$saved = $this->save($newuser);
				
				if (!empty($saved))
				{
					$result['error'] = false;
					$result['msg'] = "User created with email and Tw info.";
					$newuser['User']['id'] = $this->id;
					$result['user_data'] = $newuser;
				}
				else
				{
					$result['error'] = true;
					$result['msg'] = "User creation with email and Tw info failed. " ; 
					$result['full_error'] = $this->validationErrors;
				}
			}
		
		return $result;
	}
	
	
	// handle promo strs
	public function get_promo_times($user_id, $promo_types)
	{	
		if (empty($user_id) || empty($promo_types))
		{
			return "";
		}
		
		$user = $this->findUserById($user_id);
		if (empty($user))
		{
			return "";
		}
		
		if (!is_array($promo_types))
		{
			$promo_types = array($promo_types);
		}
		
		$promo_strs = array();
		foreach($promo_types as $index => $pt)
		{
			if ('fb' == $pt)
			{
				$promo_strs[$pt] = $user['last_fb_posts_time_strs'];
			}
			else if ('tw' == $pt || 'tweet' == $pt)
			{
				$promo_strs[$pt] = $user['last_tw_posts_time_strs'];
			}
			
		}
		
		return $promo_strs;
	}
	
	public function set_promo_times($user_id, $promo_type, $pt_str)
	{
		if (empty($user_id) || empty($promo_type) || empty($pt_str))
		{
			return false;
		}
		
		$user = array('User' => array('id' => $user_id));
		
		if ('fb' == $promo_type)
		{
			$key = 'last_fb_posts_time_strs';
		}
		else if ('tw' == $promo_type || 'tweet' == $promo_type)
		{
			$key = 'last_tw_posts_time_strs';
		}
		else
		{
			return false;
		}
		
		$user['User'][$key] = $pt_str;
		
		$this->id = $user_id;
		$saved = $this->save($user, true, array($key));
		
		if (empty($saved))
		{
			return false;
		}
		
		return true;
		
	}
	
	
}