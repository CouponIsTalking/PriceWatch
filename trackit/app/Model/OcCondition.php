<?php
App::uses('AppModel', 'Model');
/**
 * OcCondition Model
 *
 */
class OcCondition extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'oc_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'condition_id' => array(
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
	
	public function increment_met_so_far($oc_id, $con_id)
	{
		$result = array();
		$result['could_increment'] = 0;
		$result['met_so_far'] = 0;
		$result['max_count'] = 0;
		
		$oc_con = $this->find('first', array(
			'conditions' => array (
					'id' => $con_id,
					'oc_id' => $oc_id
				),
			'recursive' => -1
		));
		
		if (empty($oc_con))
		{
			return $result;
		}
		
		if ($oc_con['OcCondition']['met_so_far'] < $oc_con['OcCondition']['max_count'])
		{
			$oc_con['OcCondition']['met_so_far'] = $oc_con['OcCondition']['met_so_far'] + 1;
			$this->id = $oc_con['OcCondition']['id'];
			if($this->save($oc_con))
			{
				$result['could_increment'] = 1;
				$result['met_so_far'] = $oc_con['OcCondition']['met_so_far'];
				$result['max_count'] = $oc_con['OcCondition']['max_count'];
			}
			
		}
		
		return $result;
	}
	
	public function findTenRunningFromGivenOCIds($oc_ids)
	{
		$occon = $this->find('all', array(
							'conditions'=>array('oc_id'=>$oc_ids, 'condition_id' => 5, 'param1 >' => date('Y-m-d')),
							'recursive >' => -1,
							'limit' => 10
							)
						);
						
		$oc_ids = array();
		if (!empty($occon))
		{
			foreach ($occon as $index => $occon)
			{
				$oc_ids[] = $occon['OcCondition']['oc_id'];
			}
		}
		return $oc_ids;
	}
	
	
	public function isValidOfferType($offer_type)
	{
		if ($offer_type == 'coupon' || $offer_type == 'dollar' || $offer_type == 'gift' || $offer_type == 'none')
		{
			return true;
		}
		
		return false;
	}
	
	public function ValidateConditionParam($condition_id, $param1, $param2, $offer_type, $offer_worth)
	{
		if (floatval($offer_worth) < 0)
		{
			return false;
		}
		if ($this->isValidOfferType($offer_type) != true)
		{
			return false;
		}
		
		if ($condition_id == 1		// minimum blog comments
			|| $condition_id == 2	// minimum fb likes
			|| $condition_id == 3	// minimum fb shares
			|| $condition_id == 4	// minimum fb comments
			|| $condition_id == 6	// minimum fb likes and comments
			|| $condition_id == 7	// minimum fb likes, share and comments
		)
		{
			if (is_numeric($param1) == false)
			{
				return false;
			}
			
			if ($offer_worth == 0)
			{
				return false;
			}
		}
		
		return true;		
		
	}
	
	public function makeValidUntilDate($year, $month, $day)
	{
		return $year . "-" . $month . "-" . $day;
	}
	public function makeValidUntilDateTime($year, $month, $day)
	{
		return $year . "-" . $month . "-" . $day . " 23:59:59";
	}
	
	public function decodeValidUntilDate($date)
	{
		$date_array = date_parse_from_format("Y-n-j", $date);
		return $date_array; 
	}
	
	public function getRawConditionsFromOCId($oc_id)
	{
		$oc_conditions = $this->find('all', array(
							'conditions'=>array('oc_id' => $oc_id),
							'recursive' => -1//,
							//'group' => 'oc_id'
						));
						
		return $oc_conditions;
	}
}
