<?php
App::uses('AppModel', 'Model');
/**
 * UserProduct Model
 *
 */
class UserProduct extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'user_product_name';
	public $primaryKey = 'id';
	
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'product_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_product_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'group_name' => array(
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
	
	function build_uid_pid_field($uid, $pid){
		
		return "{$uid}u{$pid}p";
	}
	
	function getFromItemId($itemid)
	{
		if (empty($itemid)) return null;
	
		$user_product = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $itemid)));
		
		return $user_product;
	}
	
	function getFromItemIdAndUserEmail($itemid, $user_email)
	{
		if (empty($itemid) || empty($user_email)) return null;
	
		$user_product = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $itemid, 'user_email' => $user_email)));
		
		return $user_product;
	}
	
	
	function getFromUserEmail($user_email)
	{
		if (empty($user_email)) return null;
	
		$user_products = $this->find('all', array('recursive' => -1, 'conditions' => array ('user_email' => $user_email)));
		
		return $user_products;
	}
	
	function getFromUserEmailAndGroupName($user_email, $group_name)
	{
		if (empty($user_email)) return null;
	
		$user_products = $this->find('all', array('recursive' => -1, 'conditions' => array ('user_email' => $user_email, 'group_name' => $group_name)));
		
		return $user_products;
	}
	
	
	function getFromUserIdProductId($user_id, $product_id)
	{
		if (empty($user_id) || empty($product_id)) return null;
		
		$uid_pid = $this->build_uid_pid_field($user_id, $product_id);
		
		//$user_product = $this->find('first', array('recursive' => -1, 'conditions' => array ('user_id' => $user_id, 'product_id' => $product_id)));
		$user_product = $this->find('first', array('recursive' => -1, 'conditions' => array ('uid_pid' => $uid_pid)));
		return $user_product;
	}
	
	function user_tracked_products($user_id, $product_ids){
		
		$user_tracked_prods = array();
		if (empty($user_id) || empty($product_ids)) return $user_tracked_prods;
		$uid_pids = array();
		$i=0;
		$final_i = count($product_ids);
		for($i=0;$i<$final_i;$i++){
			$uid_pids[] = $this->build_uid_pid_field($user_id, $product_ids[$i]);
		}
		
		$user_products = $this->find('list', 
			array('recursive' => -1, 
				'conditions' => array ('uid_pid IN' => $uid_pids),
				'fields' => array('UserProduct.uid_pid','UserProduct.id')
				));
		return $user_products;
	}
	
	function getFromUserEmailProductId($user_email, $product_id)
	{
		if (empty($user_email) || empty($product_id)) return null;
	
		$user_product = $this->find('first', array('recursive' => -1, 'conditions' => array ('user_email' => $user_email, 'product_id' => $product_id)));
		
		return $user_product;
	}
	
	function getAllUserIdFromProductId($product_id){
		
		$user_products = array();
		if (empty($product_id)) {return $user_products;}
		$user_products = $this->find('all',array(
			'recursive'=>-1,
			'conditions'=>array('product_id'=>$product_id),
			'fields' => array('user_id')
		));
		return $user_products;
	}
	
	function update_group($itemid, $group_name)
	{
		if (empty($itemid) or empty($group_name)) return false;
		if ($group_name == "") return false;
		
		$up = $this->getFromItemId($itemid);
		//debug($up);
		$prev_group = $up['UserProduct']['group_name'];
		// this is important, because user_id can be null (default entered by database),
		// which is not allowed by this model's validation rules.
		$up_to_save = array(
				'UserProduct' => array( 'id' => $up['UserProduct']['id'], 'group_name' => $group_name)
				);
		$this->id = $up['UserProduct']['id'];
		$could_save = $this->save($up_to_save);
		//debug($could_save);
		
		if ($could_save)
		{
			return $group_name;
		}
		else
		{
			return $prev_group;
		}
		
	}
	
	function get_product_group_names($user_email)
	{
		if (empty($user_email)) return null;
	
		$user_product = $this->find('all', 
				array(
					'recursive' => -1, 
					'conditions' => array ('user_email' => $user_email), 
					'fields' => 'DISTINCT group_name',
					)
			);
		
		if (empty($user_product))
		{
			return null;
		}
		
		$group_names = array();
		foreach ($user_product as $index => $up)
		{
			$group_names[] = $up['UserProduct']['group_name'];
		}
		return $group_names;
	}
	
}