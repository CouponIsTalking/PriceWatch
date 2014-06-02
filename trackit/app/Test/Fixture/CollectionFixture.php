<?php
/**
 * CollectionFixture
 *
 */
class CollectionFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
		'user_id' => array('type' => 'biginteger', 'null' => false, 'default' => '0', 'length' => 22, 'key' => 'index'),
		'group_name' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'uid_grpname' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'share_times' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => array('user_id', 'group_name', 'uid_grpname'), 'unique' => 0)
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
			'id' => '',
			'user_id' => '',
			'group_name' => 'Lorem ipsum dolor sit amet',
			'uid_grpname' => 'Lorem ipsum dolor sit amet',
			'views' => 1,
			'share_times' => 1
		),
	);

}
