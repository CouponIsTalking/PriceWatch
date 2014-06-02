<?php
/**
 * OcResponseFixture
 *
 */
class OcResponseFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'created' => array('type' => 'timestamp', 'null' => false, 'default' => 'CURRENT_TIMESTAMP'),
		'oc_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'index'),
		'blogger_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'response_blog_link' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'response_data' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 100),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'oc_id' => array('column' => array('oc_id', 'blogger_id'), 'unique' => 0)
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
			'created' => 1379397803,
			'oc_id' => 1,
			'blogger_id' => 1,
			'response_blog_link' => 'Lorem ipsum dolor sit amet',
			'response_data' => 1
		),
	);

}
