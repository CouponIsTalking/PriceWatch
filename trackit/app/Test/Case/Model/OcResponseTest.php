<?php
App::uses('OcResponse', 'Model');

/**
 * OcResponse Test Case
 *
 */
class OcResponseTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.oc_response'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OcResponse = ClassRegistry::init('OcResponse');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OcResponse);

		parent::tearDown();
	}

}
