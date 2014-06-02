<?php
App::uses('BloggerPrice', 'Model');

/**
 * BloggerPrice Test Case
 *
 */
class BloggerPriceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.blogger_price'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BloggerPrice = ClassRegistry::init('BloggerPrice');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BloggerPrice);

		parent::tearDown();
	}

}
