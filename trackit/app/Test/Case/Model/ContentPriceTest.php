<?php
App::uses('ContentPrice', 'Model');

/**
 * ContentPrice Test Case
 *
 */
class ContentPriceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.content_price'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ContentPrice = ClassRegistry::init('ContentPrice');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ContentPrice);

		parent::tearDown();
	}

}
