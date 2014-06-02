<?php
App::uses('AppController', 'Controller');
/**
 * Conditions Controller
 *
 * @property Condition $Condition
 * @property PaginatorComponent $Paginator
 */
class ConditionsController extends AppController {

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
		$this->Condition->recursive = 0;
		$this->set('conditions', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Condition->exists($id)) {
			throw new NotFoundException(__('Invalid condition'));
		}
		$options = array('conditions' => array('Condition.' . $this->Condition->primaryKey => $id));
		$this->set('condition', $this->Condition->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Condition->create();
			if ($this->Condition->save($this->request->data)) {
				$this->Session->setFlash(__('The condition has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The condition could not be saved. Please, try again.'));
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
		if (!$this->Condition->exists($id)) {
			throw new NotFoundException(__('Invalid condition'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Condition->save($this->request->data)) {
				$this->Session->setFlash(__('The condition has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The condition could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('Condition.' . $this->Condition->primaryKey => $id));
			$this->request->data = $this->Condition->find('first', $options);
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
		$this->Condition->id = $id;
		if (!$this->Condition->exists()) {
			throw new NotFoundException(__('Invalid condition'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Condition->delete()) {
			$this->Session->setFlash(__('Condition deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Condition was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Condition->recursive = 0;
		$this->set('conditions', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Condition->exists($id)) {
			throw new NotFoundException(__('Invalid condition'));
		}
		$options = array('conditions' => array('Condition.' . $this->Condition->primaryKey => $id));
		$this->set('condition', $this->Condition->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Condition->create();
			if ($this->Condition->save($this->request->data)) {
				$this->Session->setFlash(__('The condition has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The condition could not be saved. Please, try again.'));
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
		if (!$this->Condition->exists($id)) {
			throw new NotFoundException(__('Invalid condition'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Condition->save($this->request->data)) {
				$this->Session->setFlash(__('The condition has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The condition could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('Condition.' . $this->Condition->primaryKey => $id));
			$this->request->data = $this->Condition->find('first', $options);
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
		$this->Condition->id = $id;
		if (!$this->Condition->exists()) {
			throw new NotFoundException(__('Invalid condition'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Condition->delete()) {
			$this->Session->setFlash(__('Condition deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Condition was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
