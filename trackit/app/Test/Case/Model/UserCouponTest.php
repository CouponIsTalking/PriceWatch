<?php
App::uses('UserCoupon', 'Model');

/**
 * UserCoupon Test Case
 *
 */
class UserCouponTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user_coupon'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserCoupon = ClassRegistry::init('UserCoupon');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserCoupon);

		parent::tearDown();
	}

}
