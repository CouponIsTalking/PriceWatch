<?php
App::uses('TrackerInfo', 'Model');

/**
 * TrackerInfo Test Case
 *
 */
class TrackerInfoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tracker_info'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->TrackerInfo = ClassRegistry::init('TrackerInfo');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->TrackerInfo);

		parent::tearDown();
	}

}
