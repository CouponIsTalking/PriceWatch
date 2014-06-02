<?php
App::uses('UserProduct', 'Model');

/**
 * UserProduct Test Case
 *
 */
class UserProductTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user_product'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserProduct = ClassRegistry::init('UserProduct');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserProduct);

		parent::tearDown();
	}

}
