<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class TestsController extends AppController {

/**
 * Components
 *
 * @var array
 */

	public $components = array('Session', 'UserData');
	var $uses = false;
	

	public function test_fb_resp_update_and_show_coupon_one()
	{
	}
	
	public function test_fb_resp_update_and_show_coupon_two()
	{
	}
	
	public function session_write_test()
	{
		$this->Session->write('test_val', '1');
	}
	
	public function session_read_test()
	{
		$val = $this->Session->read('test_val');
		$re = ((1 == $val) || ('1' == $val));
		$this->set('result', $re);
		
	}
}

?>