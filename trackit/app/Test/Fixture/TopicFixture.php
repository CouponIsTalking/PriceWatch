<?php
/**
 * TopicFixture
 *
 */
class TopicFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'subtopic1' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'subtopic2' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'subtopic3' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'subtopic4' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'subtopic5' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'id' => array('column' => 'id', 'unique' => 1)
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
			'name' => 'Lorem ipsum dolor ',
			'subtopic1' => 1,
			'subtopic2' => 1,
			'subtopic3' => 1,
			'subtopic4' => 1,
			'subtopic5' => 1
		),
	);

}
