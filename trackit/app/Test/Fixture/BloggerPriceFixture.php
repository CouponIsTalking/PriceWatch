<?php
/**
 * BloggerPriceFixture
 *
 */
class BloggerPriceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'blogger_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'publishing_type' => array('type' => 'integer', 'null' => false, 'default' => null),
		'min_price' => array('type' => 'float', 'null' => false, 'default' => null),
		'giveaway_goods' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'blogger_id' => 1,
			'publishing_type' => 1,
			'min_price' => 1,
			'giveaway_goods' => 1
		),
	);

}
