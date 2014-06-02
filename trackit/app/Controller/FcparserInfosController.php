<?php
App::uses('AppController', 'Controller');
/**
 * FcparserInfos Controller
 *
 * @property FcparserInfo $FcparserInfo
 * @property PaginatorComponent $Paginator
 // FC STANDS for FOREIGN COUPON, i.e., Coupon obtained from Foreign Sources
 */
class FcparserInfosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'UserData');
	
	public $uses = array('FcparserInfo','Company');
	
	
	public function test_get_fcp_info(){
		$this->set('cid', 31);
		$this->set('pcode', Configure::read('PYTHON_VERIFICATION_CODE'));
	}
	
	public function update_fcp_info(){
		
		$this->layout = 'ajax';
		$this->set('result', 0);
		
		$cid = $this->request->data['cid'];
		$pcode = $this->request->data['pcode'];
		$store_name = $this->request->data['store_name'];
		$coupon_page_link = $this->request->data['cpl'];
		
		if(empty($pcode) || empty($cid) || empty($store_name) || empty($coupon_page_link)){return;}
		
		$this->ProdAPI = $this->Components->load('ProdAPI');
		$has_access = $this->ProdAPI->has_python_access($pcode);
		if (!$has_access){
			return;
		}
		
		//$cid = 31; $store_name = 'retailmenot.com'; $coupon_page_link = 'efgh';
		
		$fcparser = $this->FcparserInfo->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'company_id' => $cid,
				'store_name' => $store_name
			)
		));
		
		if (!empty($fcparser)){
			$fcparser['FcparserInfo']['coupon_page_link'] = $coupon_page_link;
		}
		else{
			$fcparser = array(
				'FcparserInfo'=>array(
					'company_id' => $cid,
					'store_name' => $store_name,
					'coupon_page_link' => $coupon_page_link
				)
			);
			$this->FcparserInfo->create();
		}
		
		$saved = $this->FcparserInfo->save($fcparser);
		if (!empty($saved)){
			$result = 1;
		}
		
		$this->set('result', $result);
		return $result;
	}
	
	public function get_fcp_info(){
		
		$cid = $this->request->data['cid'];
		$pcode = $this->request->data['pcode'];
		
		if(empty($cid)){return;}
		
		$this->ProdAPI = $this->Components->load('ProdAPI');
		$has_access = $this->ProdAPI->has_python_access($pcode);
		if (!$has_access){
			return;
		}
		
		$this->layout='ajax';
		
		$fcparsers = $this->FcparserInfo->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'company_id' => $cid
			)
		));
		$this->set('fcparsers', $fcparsers);
		
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->only_admin_can_see();
		
		$this->FcparserInfo->recursive = 0;
		$this->set('fcparserInfos', $this->Paginator->paginate());
		
		$companies = $this->Company->getCompanyList();
		$this->set('company_data', $companies);
		
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->only_admin_can_see();
		
		if (!$this->FcparserInfo->exists($id)) {
			throw new NotFoundException(__('Invalid fcparser info'));
		}
		$options = array('conditions' => array('FcparserInfo.' . $this->FcparserInfo->primaryKey => $id));
		$fcparser = $this->FcparserInfo->find('first', $options);
		$this->set('fcparserInfo', $fcparser);
		
		$company = $this->Company->getRawCompanyInfo($fcparser['FcparserInfo']['company_id']);
		$this->set('company',$company);
		
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->only_admin_can_see();
		
		if ($this->request->is('post')) {
			$this->FcparserInfo->create();
			if ($this->FcparserInfo->save($this->request->data)) {
				$this->Session->setFlash(__('The fcparser info has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The fcparser info could not be saved. Please, try again.'));
		}
		
		$companies = $this->Company->getCompanyList();
		$this->set('company_data', $companies);
		
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->only_admin_can_see();
		
		if (!$this->FcparserInfo->exists($id)) {
			throw new NotFoundException(__('Invalid fcparser info'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->FcparserInfo->save($this->request->data)) {
				$this->Session->setFlash(__('The fcparser info has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The fcparser info could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('FcparserInfo.' . $this->FcparserInfo->primaryKey => $id));
			$this->request->data = $this->FcparserInfo->find('first', $options);
		}
		
		$company = $this->Company->getRawCompanyInfo($fcparser['FcparserInfo']['company_id']);
		$this->set('company',$company);
		
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->only_admin_can_see();
		
		$this->FcparserInfo->id = $id;
		if (!$this->FcparserInfo->exists()) {
			throw new NotFoundException(__('Invalid fcparser info'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FcparserInfo->delete()) {
			$this->Session->setFlash(__('Fcparser info deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Fcparser info was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
