<?php
/**
 * BloggerFixture
 *
 */
class BloggerFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'blog' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'facebooklink' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'twitterlink' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'instagramlink' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'pinterestlink' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'interest1' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'insterest2' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'geo1' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'fblikes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'fbfollowers' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'fbfollowing' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'twtweets' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'twfollowers' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'twfollowing' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'instposts' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'instfollowers' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'instfollowing' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'pinboards' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'pinpins' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'pinlikes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'pinfollowers' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'pinfollowing' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'boost' => array('type' => 'float', 'null' => false, 'default' => '1'),
		'extra_points' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'overall_rating' => array('type' => 'float', 'null' => false, 'default' => '1'),
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
			'id' => '',
			'name' => 'Lorem ipsum dolor ',
			'email' => 'Lorem ipsum dolor ',
			'blog' => 'Lorem ipsum dolor sit amet',
			'facebooklink' => 'Lorem ipsum dolor sit amet',
			'twitterlink' => 'Lorem ipsum dolor sit amet',
			'instagramlink' => 'Lorem ipsum dolor sit amet',
			'pinterestlink' => 'Lorem ipsum dolor sit amet',
			'interest1' => 1,
			'insterest2' => 1,
			'geo1' => 1,
			'fblikes' => 1,
			'fbfollowers' => 1,
			'fbfollowing' => 1,
			'twtweets' => 1,
			'twfollowers' => 1,
			'twfollowing' => 1,
			'instposts' => 1,
			'instfollowers' => 1,
			'instfollowing' => 1,
			'pinboards' => 1,
			'pinpins' => 1,
			'pinlikes' => 1,
			'pinfollowers' => 1,
			'pinfollowing' => 1,
			'boost' => 1,
			'extra_points' => 1,
			'overall_rating' => 1
		),
	);

}
