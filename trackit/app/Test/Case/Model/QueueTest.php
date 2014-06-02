<?php
App::uses('Queue', 'Model');

/**
 * Queue Test Case
 *
 */
class QueueTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.queue'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Queue = ClassRegistry::init('Queue');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Queue);

		parent::tearDown();
	}

}
