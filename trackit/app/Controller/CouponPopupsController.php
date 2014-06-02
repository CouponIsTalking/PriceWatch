<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class CouponPopupsController extends AppController {

/**
 * Components
 *
 * @var array
 */

	//public $components = array('RequestHandler', 'UserData');
	public $components = array(
		'Paginator',
		'RequestHandler',
		'Session',
		'UserData',
		'TimeManagement',
		'EmailAccess'
		);
		
	var $helpers = array('Html');//,'Javascript');
    
	public $uses = array ('Pcard', 'User','Company', 'PcardCust', 'PcardCvisit');
	
	public function create(){
	}
}

?>