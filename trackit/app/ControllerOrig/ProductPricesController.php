<?php
App::uses('AppController', 'Controller');
/**
 * ProductPrices Controller
 *
 * @property ProductPrice $ProductPrice
 * @property PaginatorComponent $Paginator
 */
class ProductPricesController extends AppController {

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
		$this->ProductPrice->recursive = 0;
		$this->set('productPrices', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ProductPrice->exists($id)) {
			throw new NotFoundException(__('Invalid product price'));
		}
		$options = array('conditions' => array('ProductPrice.' . $this->ProductPrice->primaryKey => $id));
		$this->set('productPrice', $this->ProductPrice->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ProductPrice->create();
			if ($this->ProductPrice->save($this->request->data)) {
				$this->Session->setFlash(__('The product price has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The product price could not be saved. Please, try again.'));
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
		if (!$this->ProductPrice->exists($id)) {
			throw new NotFoundException(__('Invalid product price'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ProductPrice->save($this->request->data)) {
				$this->Session->setFlash(__('The product price has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The product price could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('ProductPrice.' . $this->ProductPrice->primaryKey => $id));
			$this->request->data = $this->ProductPrice->find('first', $options);
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
		$this->ProductPrice->id = $id;
		if (!$this->ProductPrice->exists()) {
			throw new NotFoundException(__('Invalid product price'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProductPrice->delete()) {
			$this->Session->setFlash(__('Product price deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Product price was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->ProductPrice->recursive = 0;
		$this->set('productPrices', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->ProductPrice->exists($id)) {
			throw new NotFoundException(__('Invalid product price'));
		}
		$options = array('conditions' => array('ProductPrice.' . $this->ProductPrice->primaryKey => $id));
		$this->set('productPrice', $this->ProductPrice->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->ProductPrice->create();
			if ($this->ProductPrice->save($this->request->data)) {
				$this->Session->setFlash(__('The product price has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The product price could not be saved. Please, try again.'));
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
		if (!$this->ProductPrice->exists($id)) {
			throw new NotFoundException(__('Invalid product price'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ProductPrice->save($this->request->data)) {
				$this->Session->setFlash(__('The product price has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The product price could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('ProductPrice.' . $this->ProductPrice->primaryKey => $id));
			$this->request->data = $this->ProductPrice->find('first', $options);
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
		$this->ProductPrice->id = $id;
		if (!$this->ProductPrice->exists()) {
			throw new NotFoundException(__('Invalid product price'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProductPrice->delete()) {
			$this->Session->setFlash(__('Product price deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Product price was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
