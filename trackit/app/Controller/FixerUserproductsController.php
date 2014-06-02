<?php
App::uses('AppController', 'Controller');
/**
 * FixerUserproducts Controller
 *
 * @property PriceNotification $PriceNotification
 * @property PaginatorComponent $Paginator
 */
class FixerUserproductsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'ProdAPI', 'UserData', 'EmailAccess', 'RequestHandler'
		);
	
	public $uses = array ('User','UserProduct');
	
	
	public function userid_useremail(){
		
		$this->only_admin_can_see();
		$results = array();
		$ups = $this->UserProduct->find('all');
		foreach($ups as $index=>$up){
			$uid = $up['UserProduct']['user_id'];
			$uemail = $up['UserProduct']['user_email'];
			if (!$uid || "NULL"==$uid){
				$user = $this->User->findUserByEmail($uemail);
				if (!empty($user)){
					$new_uid = $user['id'];
					$up['UserProduct']['user_id'] = $new_uid;
					$this->UserProduct->id = $up['UserProduct']['id'];
					$saved = $this->UserProduct->save($up, true, array('user_id'));
					if(!empty($saved)){
						$results[] = "Saved !";
					}else{
						$results[] = "Not saved !";
					}
				}
			}
			
		}
		$this->set('results',$results);
	}
	
}
?>