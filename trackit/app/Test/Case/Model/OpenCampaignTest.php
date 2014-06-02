<?php
App::uses('OpenCampaign', 'Model');

/**
 * OpenCampaign Test Case
 *
 */
class OpenCampaignTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.open_campaign'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OpenCampaign = ClassRegistry::init('OpenCampaign');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OpenCampaign);

		parent::tearDown();
	}

}
