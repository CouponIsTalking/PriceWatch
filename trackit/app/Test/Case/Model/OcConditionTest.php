<?php
App::uses('OcCondition', 'Model');

/**
 * OcCondition Test Case
 *
 */
class OcConditionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.oc_condition'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OcCondition = ClassRegistry::init('OcCondition');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OcCondition);

		parent::tearDown();
	}

}
