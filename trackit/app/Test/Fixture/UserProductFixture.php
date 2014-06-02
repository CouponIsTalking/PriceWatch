<?php
/**
 * UserProductFixture
 *
 */
class UserProductFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 22),
		'product_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 22),
		'wait_price' => array('type' => 'float', 'null' => false, 'default' => null),
		'user_product_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'group_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			
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
			'user_id' => 1,
			'product_id' => 1,
			'wait_price' => 1,
			'user_product_name' => 'Lorem ipsum dolor sit amet',
			'group_name' => 'Lorem ipsum dolor sit amet'
		),
	);

}
