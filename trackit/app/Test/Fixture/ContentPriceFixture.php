<?php
/**
 * ContentPriceFixture
 *
 */
class ContentPriceFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'content_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'publishing_type' => array('type' => 'integer', 'null' => false, 'default' => null),
		'min_price' => array('type' => 'float', 'null' => false, 'default' => null),
		'max_price' => array('type' => 'float', 'null' => false, 'default' => null),
		'min_rating' => array('type' => 'float', 'null' => false, 'default' => null),
		'max_rating' => array('type' => 'float', 'null' => false, 'default' => null),
		'rate_curve' => array('type' => 'integer', 'null' => false, 'default' => null),
		'giveaway_goods' => array('type' => 'integer', 'null' => false, 'default' => null),
		'giveaway_goods_value' => array('type' => 'float', 'null' => false, 'default' => null),
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
			'content_id' => 1,
			'publishing_type' => 1,
			'min_price' => 1,
			'max_price' => 1,
			'min_rating' => 1,
			'max_rating' => 1,
			'rate_curve' => 1,
			'giveaway_goods' => 1,
			'giveaway_goods_value' => 1
		),
	);

}
