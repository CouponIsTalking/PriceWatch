<?php
App::uses('AppController', 'Controller');
/**
 * OcResponses Controller
 *
 * @property OcResponse $OcResponse
 * @property PaginatorComponent $Paginator
 */
class PromotionsController extends AppController {


	var $helpers = array('Html','Js' => array('Jquery'));//,'Javascript');
    var $components = array( 'Paginator', 'RequestHandler', 'UserData' );
	var $uses = array ('ContentPromotion', 'OcResponse', 'OpenCampaign', 'Company', 'Blogger', 'Queue', 'OcCondition', 'Condition');
	
	public function pmetric()
	{
		$this->
	}
}
?>