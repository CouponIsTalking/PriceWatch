<?php
App::uses('AppModel', 'Model');
/**
 * UserCoupon Model
 *
 */
class UserCoupon extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'userid_content_coupon_code' => array(
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
	
	public function build_verifier($key, $user_id)
	{
		$verifier = $key . 'UMN' . $user_id;
		return $verifier;
	}
	
	public function create_entry_name($user_id, $company_id, $coupon)
	{
		//return $user_id . "_" . $content_id . "_" . $coupon;
		return $user_id . "_" . $company_id . "_" . $coupon;
	}
	
	public function add_entry($user_id_n_company_coupon)
	{
		
		$this->create();
		$data = array ('UserCoupon' => array());
		$data['userid_content_coupon_code'] = $user_id_n_company_coupon;
		
		$saved = $this->save($data);
		
		if (!empty($saved))
		{
			return true;
		}
		
		return false;
	}
	
	public function is_userid_company_coupon_present($userid_company_coupon)
	{
		if (empty($userid_company_coupon))
		{
			return false;
		}
		
		$entry = $this->find('first', array(
					'recursive'=>-1, 
					'conditions'=> array('userid_content_coupon_code' => $userid_company_coupon)
				));
		
		if (empty($entry['UserCoupon']))
		{
			return false;
		}
		
		return true;
	}
	
	public function is_content_coupon_userid_entry_present($user_id_n_content_coupon_s)
	{
		$entries = array();
	
		$result = array();
		foreach($user_id_n_content_coupon_s as $index => $key)
		{
			$result[$key] = 0;
		}
		
		if (!empty($user_id_n_content_coupon_s))
		{
			
			if (1 == count($user_id_n_content_coupon_s))
			{
				$entries = $this->find('all', array(
					'recursive'=>-1, 
					'conditions'=> array('userid_content_coupon_code' => $user_id_n_content_coupon_s[0])
				));
			}
			else
			{
				$entries = $this->find('all', array(
					'recursive'=>-1, 
					'conditions'=> array('userid_content_coupon_code IN' => $user_id_n_content_coupon_s)
				));
			}
		}
		
		foreach ($entries as $key => $entry)
		{
			$result[$entry['UserCoupon']['userid_content_coupon_code']] = 1;
		}
		
		return $result;
		
	}
	
}
