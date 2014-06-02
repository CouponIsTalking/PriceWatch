<?php
App::uses('AppModel', 'Model');
/**
 * PcardCust Model
 *
 */
class PcardCust extends AppModel {
/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'pcard_custs';

	public function add_cust($p){
	
		$r = array('s'=>false,'m'=>'');
		
		if (empty($p['card_id']) || 
			(empty($p['user_id']) && empty($p['email']))
			)
		{
			$r['m'] = 'Bad param';
			return $r;
		}
		$to_save = array('PcardCust' => array());
		
		$to_save['PcardCust']['card_id'] = $p['card_id'];
		$to_save['PcardCust']['user_id'] = $p['user_id'];
		$to_save['PcardCust']['email'] 	= $p['email'];
		$to_save['PcardCust']['firstname'] 	= $p['firstname'];
		$to_save['PcardCust']['lastname'] 	= $p['lastname'];
		$to_save['PcardCust']['phone'] 		= $p['phone'];
		$to_save['PcardCust']['company_id'] = $p['company_id'];
		
		$this->create();
		$saved = $this->save($to_save);
		if (!empty($saved)){
			$r['s'] = true;
			$r['id']=$this->id;
		}else{
			$r['m'] = $this->validationErrors;
		}
		
		return $r;
	}
	
	public function findby($search_fields_and_vals){
		
		$field_and_vals = array();
		if (empty($search_fields_and_vals)){
			return false;
		}
		
		foreach($search_fields_and_vals as $key => $val){
			if ('firstname' == $key ||
				'lastname' == $key ||
				'email' == $key ||
				'phone' == $key ||
				'user_id' == $key ||
				'company_id' == $key
				)
			{
				$field_and_vals[$key] = $val;
			}
		}
		
		if (empty($field_and_vals)){
			return false;
		}
		
		$card_cust = $this->find('first', array(
				'recursive' => -1,
				'conditions' => array($field_and_vals)
			));
		
		return $card_cust;
	}
	
	public function find_by_cardid($card_id){
		if (empty($card_id)){
			return false;
		}
		
		$card = $this->find('first',array(
			'recursive' => -1,
			'conditions'=>array('id' => $card_id)
		));
		
		return $card;
	}
}
