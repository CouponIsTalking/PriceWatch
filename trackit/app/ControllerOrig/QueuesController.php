<?php
App::uses('AppController', 'Controller');
/**
 * Queues Controller
 *
 * @property Queue $Queue
 * @property PaginatorComponent $Paginator
 */
class QueuesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData');
	
	public $uses = array ('Queue', 'OcResponse');
	
/**
 * index method
 *
 * @return void
 */
 
	public function queueit($ocr_id) {
	
		if ( !($this->RequestHandler->isAjax()) )
		{
			return null;
		}
		
		$this->layout = 'ajax';
		
		$blogger_owns = false;
		$is_admin = $this->UserData->isAdmin();
		
		if (!$is_admin)
		{
			$blogger_id = $this->UserData->getBloggerId();
			$blogger_owns = $this->OcResponse->doesBloggerOwnOcr($blogger_id, $ocr_id);
		}
		
		if ($is_admin || $blogger_owns)
		{
			$queued_item = $this->Queue->queueit($ocr_id);
			
			if (!empty($queued_item))
			{
				$this->set('result', $queued_item['Queue']);
			}
			else
			{
				$this->set('result', null);
			}
		}
		
		return;
	}
	
	public function clearit($ocr_id) {
	
		if ( !($this->RequestHandler->isAjax()) )
		{
			return null;
		}
		
		$this->layout = 'ajax';
		$blogger_owns = false;
		$is_admin = $this->UserData->isAdmin();
		
		if (!$is_admin)
		{
			$blogger_id = $this->UserData->getBloggerId();
			$blogger_owns = $this->OcResponse->doesBloggerOwnOcr($blogger_id, $ocr_id);
		}
		
		if ($is_admin || $blogger_owns)
		{
			$cleared = $this->Queue->clearit($ocr_id);
			
			$this->set('result', $cleared);
		}
		
		return;
	
	}	
	
	
	public function index() {
		$this->Queue->recursive = 0;
		$this->set('queues', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Queue->exists($id)) {
			throw new NotFoundException(__('Invalid queue'));
		}
		$options = array('conditions' => array('Queue.' . $this->Queue->primaryKey => $id));
		$this->set('queue', $this->Queue->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Queue->create();
			if ($this->Queue->save($this->request->data)) {
				$this->Session->setFlash(__('The queue has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The queue could not be saved. Please, try again.'));
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Queue->exists($id)) {
			throw new NotFoundException(__('Invalid queue'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Queue->save($this->request->data)) {
				$this->Session->setFlash(__('The queue has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The queue could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('Queue.' . $this->Queue->primaryKey => $id));
			$this->request->data = $this->Queue->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Queue->id = $id;
		if (!$this->Queue->exists()) {
			throw new NotFoundException(__('Invalid queue'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Queue->delete()) {
			$this->Session->setFlash(__('Queue deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Queue was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Queue->recursive = 0;
		$this->set('queues', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Queue->exists($id)) {
			throw new NotFoundException(__('Invalid queue'));
		}
		$options = array('conditions' => array('Queue.' . $this->Queue->primaryKey => $id));
		$this->set('queue', $this->Queue->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Queue->create();
			if ($this->Queue->save($this->request->data)) {
				$this->Session->setFlash(__('The queue has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The queue could not be saved. Please, try again.'));
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Queue->exists($id)) {
			throw new NotFoundException(__('Invalid queue'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Queue->save($this->request->data)) {
				$this->Session->setFlash(__('The queue has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The queue could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('Queue.' . $this->Queue->primaryKey => $id));
			$this->request->data = $this->Queue->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Queue->id = $id;
		if (!$this->Queue->exists()) {
			throw new NotFoundException(__('Invalid queue'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Queue->delete()) {
			$this->Session->setFlash(__('Queue deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Queue was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
