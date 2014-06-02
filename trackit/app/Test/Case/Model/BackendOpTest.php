<?php
App::uses('BackendOp', 'Model');

/**
 * BackendOp Test Case
 *
 */
class BackendOpTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.backend_op'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BackendOp = ClassRegistry::init('BackendOp');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BackendOp);

		parent::tearDown();
	}

}
