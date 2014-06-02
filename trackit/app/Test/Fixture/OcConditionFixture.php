<?php
/**
 * OcConditionFixture
 *
 */
class OcConditionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'oc_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'condition_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'prerequisite_condition1_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'prerequisite_condition2_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'param1' => array('type' => 'integer', 'null' => true, 'default' => null),
		'param2' => array('type' => 'integer', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'oc_id' => array('column' => 'oc_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'oc_id' => 1,
			'condition_id' => 1,
			'prerequisite_condition1_id' => 1,
			'prerequisite_condition2_id' => 1,
			'param1' => 1,
			'param2' => 1
		),
	);

}
