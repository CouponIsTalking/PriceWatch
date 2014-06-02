<?php
App::uses('AppController', 'Controller');
/**
 * OcConditions Controller
 *
 * @property OcCondition $OcCondition
 * @property PaginatorComponent $Paginator
 */
class OcConditionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->OcCondition->recursive = 0;
		$this->set('ocConditions', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OcCondition->exists($id)) {
			throw new NotFoundException(__('Invalid oc condition'));
		}
		$options = array('conditions' => array('OcCondition.' . $this->OcCondition->primaryKey => $id));
		$this->set('ocCondition', $this->OcCondition->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->OcCondition->create();
			if ($this->OcCondition->save($this->request->data)) {
				$this->Session->setFlash(__('The oc condition has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The oc condition could not be saved. Please, try again.'));
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
		if (!$this->OcCondition->exists($id)) {
			throw new NotFoundException(__('Invalid oc condition'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->OcCondition->save($this->request->data)) {
				$this->Session->setFlash(__('The oc condition has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The oc condition could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('OcCondition.' . $this->OcCondition->primaryKey => $id));
			$this->request->data = $this->OcCondition->find('first', $options);
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
		$this->OcCondition->id = $id;
		if (!$this->OcCondition->exists()) {
			throw new NotFoundException(__('Invalid oc condition'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->OcCondition->delete()) {
			$this->Session->setFlash(__('Oc condition deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Oc condition was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->OcCondition->recursive = 0;
		$this->set('ocConditions', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->OcCondition->exists($id)) {
			throw new NotFoundException(__('Invalid oc condition'));
		}
		$options = array('conditions' => array('OcCondition.' . $this->OcCondition->primaryKey => $id));
		$this->set('ocCondition', $this->OcCondition->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->OcCondition->create();
			if ($this->OcCondition->save($this->request->data)) {
				$this->Session->setFlash(__('The oc condition has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The oc condition could not be saved. Please, try again.'));
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
		if (!$this->OcCondition->exists($id)) {
			throw new NotFoundException(__('Invalid oc condition'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->OcCondition->save($this->request->data)) {
				$this->Session->setFlash(__('The oc condition has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The oc condition could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('OcCondition.' . $this->OcCondition->primaryKey => $id));
			$this->request->data = $this->OcCondition->find('first', $options);
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
		$this->OcCondition->id = $id;
		if (!$this->OcCondition->exists()) {
			throw new NotFoundException(__('Invalid oc condition'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->OcCondition->delete()) {
			$this->Session->setFlash(__('Oc condition deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Oc condition was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
