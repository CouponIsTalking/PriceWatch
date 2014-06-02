<?php
App::uses('FcparserInfo', 'Model');

/**
 * FcparserInfo Test Case
 *
 */
class FcparserInfoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.fcparser_info'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->FcparserInfo = ClassRegistry::init('FcparserInfo');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->FcparserInfo);

		parent::tearDown();
	}

}
