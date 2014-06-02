<?php
App::uses('AppModel', 'Model');
/**
 * Condition Model
 *
 */
class Condition extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

	
	public function compactConditionData($conditions)
	{
		$conditiondata = array();
		
		foreach ($conditions as $k => $condition)
		{
			$conditiondata[$condition['Condition']['id']] = array ('name' => $condition['Condition']['name'], 'id' => $condition['Condition']['id']);
		}
		
		return $conditiondata;
	}
	
	public function getConditionList()
	{
		$products = $this->find('all');
		
		$productdata = $this->compactConditionData($products);
		return $productdata;
	
	}
}
