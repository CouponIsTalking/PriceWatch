<?php
/**
 * FcparserInfoFixture
 *
 */
class FcparserInfoFixture extends CakeTestFixture {

/**
 * Table name
 *
 * @var string
 */
	public $table = 'fcparser_info';

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'primary'),
		'company_id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'length' => 22, 'key' => 'index'),
		'coupon_page_link' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'company_id' => array('column' => 'company_id', 'unique' => 0)
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
			'company_id' => '',
			'coupon_page_link' => 'Lorem ipsum dolor sit amet'
		),
	);

}
