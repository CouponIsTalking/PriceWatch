<?php
App::uses('Blogger', 'Model');

/**
 * Blogger Test Case
 *
 */
class BloggerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.blogger'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Blogger = ClassRegistry::init('Blogger');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Blogger);

		parent::tearDown();
	}

}
