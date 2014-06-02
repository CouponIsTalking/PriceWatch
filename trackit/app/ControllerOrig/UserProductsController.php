<?php
App::uses('AppController', 'Controller');
/**
 * UserProducts Controller
 *
 * @property UserProduct $UserProduct
 * @property PaginatorComponent $Paginator
 */
class UserProductsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData');
	public $uses = array('UserProduct', 'Company', 'Product');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->UserProduct->recursive = 0;
		$this->set('userProducts', $this->Paginator->paginate());
	}
	
	public function remove_user_product_from_group()
	{
		$this->layout = 'ajax';
		
		$result = array();
		
		if(!($this->RequestHandler->isAjax()))
		{
			return null;
		}
		
		
		$user_email = $this->UserData->getUserEmail();
		
		if (empty($user_email) || !$user_email)
		{
			$result['success'] = 0;
			$result['msg'] = "Please log in to remove an item.";
		}
		else
		{
			$post_data = $this->request->data;
			$itemid = $post_data['itemid'];
			
			$user_product = $this->UserProduct->getFromItemIdAndUserEmail($itemid, $user_email);
			if (empty($user_product['UserProduct']['id']))
			{
				$result['success'] = 0;
				$result['msg'] = "We couldn't remove this item because it is not in your profile.";			
			}
			else
			{
				$delete_result = $this->UserProduct->delete($itemid);
				if (!empty($delete_result))
				{
					$result['success'] = 1;
					$result['msg'] = "Item removed.";
				}
				else
				{
					$result['success'] = 0;
					$result['msg'] = "There was an error in remove this item.";
				}
			}
		}
		
		$this->set('result', json_encode($result));
		return $result;
	}
	
	public function copy_user_product_to_group()
	{
		$result = array();
		
		$this->layout = 'ajax';
		if(!($this->RequestHandler->isAjax()))
		{
			return null;
		}
		
		$user_email = $this->UserData->getUserEmail();
		$user_id = $this->UserData->getUserId();
		
		if (empty($user_email) || !$user_email)
		{
			$result['success'] = 0;
			$result['msg'] = "Please log in to copy an item.";
		}
		else
		{
			$post_data = $this->request->data;
			$itemid = $post_data['itemid'];
			$new_group_name_given = trim($post_data['group_name']);
			
			$item = $this->UserProduct->getFromItemIdAndUserEmail($itemid, $user_email);
			if (empty($item['UserProduct']['id']))
			{
				$result['success'] = 0;
				$result['msg'] = "The item must be in your profile to do this.";
			}
			else if(empty($new_group_name_given) || "" == $new_group_name_given)
			{
				$result['success'] = 0;
				$result['msg'] = "Collection(group) name must not be empty. Which collection to copy this item to ? Please specify a valid name.";
			}
			else
			{
			
				unset($item['UserProduct']['id']);
				$item['UserProduct']['group_name'] = $new_group_name_given;
				$item['UserProduct']['user_email'] = $user_email;
				if (empty($user_id))
				{
					$user_id = 0;
				}
				$item['UserProduct']['user_id'] = $user_id;
				
				//$group_name_now = $this->UserProduct->update_group($itemid, $group_name);
				$this->UserProduct->create();
				$saved = $this->UserProduct->save($item);
				
				if (!empty($saved))
				{
					$result['success'] = 1;
					$result['msg'] = "Thanks, we have copied the item to '". $new_group_name_given. "' collection.";
				}
				else
				{
					$result['success'] = 0;
					$result['msg'] = "Oops, looks like we couldn't copy this item to '". $new_group_name_given. "' collection.";
				}
			}
		}
		$this->set('result', json_encode($result));
		return $result;
	}
	
	public function move_user_product_to_group()
	{
		$result = array();
		
		$this->layout = 'ajax';
		if(!($this->RequestHandler->isAjax()))
		{
			return null;
		}
		
		$post_data = $this->request->data;
		$itemid = $post_data['itemid'];
		$new_group_name_given = trim($post_data['group_name']);
		
		//debug($itemid);
		//debug($new_group_name);
		//debug($post_data);
		
		$user_email = $this->UserData->getUserEmail();
		$user_id = $this->UserData->getUserId();
		
		
		if (empty($user_email) || !$user_email)
		{
			$result['success'] = 0;
			$result['msg'] = "Please log in to move an item.";
		}
		else
		{
			$post_data = $this->request->data;
			$itemid = $post_data['itemid'];
			$new_group_name_given = trim($post_data['group_name']);
			
			$item = $this->UserProduct->getFromItemIdAndUserEmail($itemid, $user_email);
			if (empty($item['UserProduct']['id']))
			{
				$result['success'] = 0;
				$result['msg'] = "The item must be in your profile to move it.";
			}
			else if(empty($new_group_name_given) || "" == $new_group_name_given)
			{
				$result['success'] = 0;
				$result['msg'] = "Collection(group) name must not be empty. Which collection to move this item to ? Please specify a valid name.";
			}
			else
			{
				//$item = $this->UserProduct->getFromItemId($itemid);
				unset($item['UserProduct']['id']);
				$item['UserProduct']['group_name'] = $new_group_name_given;
				$item['UserProduct']['user_email'] = $user_email;
				if (empty($user_id))
				{
					$user_id = 0;
				}
				$item['UserProduct']['user_id'] = $user_id;
						
				$group_name_now = $this->UserProduct->update_group($itemid, $new_group_name_given);
				
				if ($group_name_now && ($group_name_now == $new_group_name_given))
				{
					$result['success'] = 1;
					$result['msg'] = "Thanks, we have moved the item to '". $group_name_now. "' collection.";
				}
				else
				{
					$result['success'] = 0;
					$result['msg'] = "Oops, looks like we couldn't move item to '". $group_name_now. "' collection.";
				}
			}
		}	
		
		$this->set('result', json_encode($result));
		return $result;
	}
	
	public function get_collection_names()
	{
		if ($this->RequestHandler->isAjax())
		{
			$this->layout='ajax';
			$user_email = $this->request->data['user_email'];
		}
		
		$groups = $this->UserProduct->get_product_group_names($user_email);
		if (!empty($groups))
		{
			$result['success'] = 1;
			$result['collections'] = $groups;
		}
		else
		{	
			$result['success'] = 0;
			$result['success'] = "Hmm.. we couldn't find any collection by this email. Check our page listing all items.";
		}
		$this->set('result', $result);
	}
	
	public function my_collection($user_email = null)
	{
	
		$user_id = $this->UserData->getUserId();
		$user_email_existing = $this->UserData->getUserEmail();
		
		if (!empty($this->params['named']['group_name']))
		{
			$group_name = $this->params['named']['group_name'];
		}
		else
		{
			$group_name = "";
		}
		
		if (!empty($user_email_existing))
		{
			$user_email = $user_email_existing;
		}
		
		if (empty($user_email))
		{
			$post_data = $this->request->data;
			if (!empty($post_data))
			{
				$user_email = $post_data['user_email'];
			}
		}
		
		if (empty($user_email))
		{
			return;
		}
		
		if ("" == $group_name)
		{
			$user_products = $this->UserProduct->getFromUserEmail($user_email);
		}
		else
		{
			$user_products = $this->UserProduct->getFromUserEmailAndGroupName($user_email, $group_name);
		}
		
		$this->set('ups', $user_products);
		
		$product_ids = array();
		$i =0;
		foreach ($user_products as $index=>$up)
		{
			$product_ids[] = $up['UserProduct']['product_id'];
			$i=$i+1;
		}
		
		if ($i > 1)
		{
			$products = $this->Product->find('all', array('recursive'=> -1, 'conditions'=> array('id IN' => $product_ids)));
		}
		else if ($i == 1)
		{
			$products = $this->Product->find('all', array('recursive'=> -1, 'conditions'=> array('id' => $product_ids[0])));
		}
		else
		{
			$products = array();
		}
		
		$products = $this->Product->indexOnId($products);
		
		$this->set('products', $products);
		
		$companies = $this->Company->getCompanyList();
		$this->set('companies', $companies);
		
		$groups = $this->UserProduct->get_product_group_names($user_email);
		if (!empty($groups))
		{
			$result['success'] = 1;
			$result['collections'] = $groups;
		}
		$this->set('collection_names', $groups);
		
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->UserProduct->exists($id)) {
			throw new NotFoundException(__('Invalid user product'));
		}
		$options = array('conditions' => array('UserProduct.' . $this->UserProduct->primaryKey => $id));
		$this->set('userProduct', $this->UserProduct->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->UserProduct->create();
			if ($this->UserProduct->save($this->request->data)) {
				$this->Session->setFlash(__('The user product has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The user product could not be saved. Please, try again.'));
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
		if (!$this->UserProduct->exists($id)) {
			throw new NotFoundException(__('Invalid user product'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UserProduct->save($this->request->data)) {
				$this->Session->setFlash(__('The user product has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The user product could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('UserProduct.' . $this->UserProduct->primaryKey => $id));
			$this->request->data = $this->UserProduct->find('first', $options);
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
		$this->UserProduct->id = $id;
		if (!$this->UserProduct->exists()) {
			throw new NotFoundException(__('Invalid user product'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserProduct->delete()) {
			$this->Session->setFlash(__('User product deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User product was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->UserProduct->recursive = 0;
		$this->set('userProducts', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->UserProduct->exists($id)) {
			throw new NotFoundException(__('Invalid user product'));
		}
		$options = array('conditions' => array('UserProduct.' . $this->UserProduct->primaryKey => $id));
		$this->set('userProduct', $this->UserProduct->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->UserProduct->create();
			if ($this->UserProduct->save($this->request->data)) {
				$this->Session->setFlash(__('The user product has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The user product could not be saved. Please, try again.'));
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
		if (!$this->UserProduct->exists($id)) {
			throw new NotFoundException(__('Invalid user product'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UserProduct->save($this->request->data)) {
				$this->Session->setFlash(__('The user product has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The user product could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('UserProduct.' . $this->UserProduct->primaryKey => $id));
			$this->request->data = $this->UserProduct->find('first', $options);
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
		$this->UserProduct->id = $id;
		if (!$this->UserProduct->exists()) {
			throw new NotFoundException(__('Invalid user product'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserProduct->delete()) {
			$this->Session->setFlash(__('User product deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User product was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
