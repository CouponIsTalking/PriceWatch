<?php
App::uses('AppController', 'Controller');
/**
 * FB Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class STController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'UserData', 
		);

	var $uses = false;
	
	var $helpers = array ();
	
	public function read()
	{
		$this->layout = 'ajax';
		$val = 0;//$this->Session->read('val');
		$this->set('val', $val);
	}
	
	public function write()
	{
		$this->layout = 'ajax';
		$val = 0;//$this->Session->write('val', '2');
		$this->set('val', $val);
	}
}

?>