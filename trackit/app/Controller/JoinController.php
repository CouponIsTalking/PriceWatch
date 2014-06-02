<?php
App::uses('AppController', 'Controller');
/**
 * Join Controller
 *
 * @property ResetPasswd $ResetPasswd
 * @property PaginatorComponent $Paginator
 */
class JoinController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'TimeManagement', 'UserData', 'EmailAccess', 'RequestHandler'
		);
	
	public $uses = array ('ResetPasswd');
	
	public function us(){
		$user_id = $this->UserData->getUserId();
		$this->set('user_id',$user_id);
		
		if (!empty($user_id)){
			$this->redirect(SITE_NAME);
		}
	}
	
}
?>