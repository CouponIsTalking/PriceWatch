<?php
App::uses('AppController', 'Controller');
/**
 * UserCoupons Controller
 *
 * @property UserCoupon $UserCoupon
 * @property PaginatorComponent $Paginator
 */
class UserCouponsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData');
	public $uses = array ('UserCoupon', 'Content');
	
	
	public function add_ajax()
	{
		$this->layout = 'ajax';
		
		$result = array('success' => true , 'msg' => "", 'added' => false);
		
		if ($this->RequestHandler->isAjax()) 
		{
			$user_id = $this->UserData->getUserId();
			if (empty($user_id))
			{
				$result['msg'] = "User is not logged in." ;
			}
			
			else
			{
				$data = $this->request->data;
				if (!empty($data['content_id']))
				{
					$content_id = $data['content_id'];
					$company_id = $this->Content->get_company_id($content_id);
				}
				else if (!empty($data['company_id']))
				{
					//$oc_id = $data['oc_id'];
					$company_id = $data['company_id'];
				}
				
				$coupon = trim($data['coupon']);
				//$content_coupon = $data['content_coupon_code'];
				
				if (empty($coupon) || empty($company_id))
				{
					$result['msg'] = "Bad POST data." ;
				}
				
				else
				{
					
					//$user_id_n_content_coupon = $user_id . "_" . $content_coupon;
					//$user_id_n_content_coupon = $this->UserCoupon->create_entry_name($user_id, $content_id, $coupon);
					
					$user_id_n_company_coupon = $this->UserCoupon->create_entry_name($user_id, $company_id, $coupon);
					
					$added = $this->UserCoupon->add_entry($user_id_n_company_coupon);
					
					if ($added)
					{
						$result['msg'] = "User Adv updated." ;
						$result['added'] = true;
					}
					else
					{
						$result['msg'] = "DB update failed." ;
						$result['added'] = false;
					}
				}
			}
		}
		
		$this->set('result', json_encode($result));
	}
	
	
	
/**
 * index method
 *
 * @return void
 */
	
	private function index() {
		$this->UserCoupon->recursive = 0;
		$this->set('userCoupons', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	private function view($id = null) {
		if (!$this->UserCoupon->exists($id)) {
			throw new NotFoundException(__('Invalid user coupon'));
		}
		$options = array('conditions' => array('UserCoupon.' . $this->UserCoupon->primaryKey => $id));
		$this->set('userCoupon', $this->UserCoupon->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	private function add() {
		if ($this->request->is('post')) {
			$this->UserCoupon->create();
			if ($this->UserCoupon->save($this->request->data)) {
				$this->Session->setFlash(__('The user coupon has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The user coupon could not be saved. Please, try again.'));
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	private function edit($id = null) {
		if (!$this->UserCoupon->exists($id)) {
			throw new NotFoundException(__('Invalid user coupon'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UserCoupon->save($this->request->data)) {
				$this->Session->setFlash(__('The user coupon has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The user coupon could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('UserCoupon.' . $this->UserCoupon->primaryKey => $id));
			$this->request->data = $this->UserCoupon->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	private function delete($id = null) {
		$this->UserCoupon->id = $id;
		if (!$this->UserCoupon->exists()) {
			throw new NotFoundException(__('Invalid user coupon'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserCoupon->delete()) {
			$this->Session->setFlash(__('User coupon deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User coupon was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
