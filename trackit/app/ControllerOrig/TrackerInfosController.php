<?php
App::uses('AppController', 'Controller');
/**
 * TrackerInfos Controller
 *
 * @property TrackerInfo $TrackerInfo
 * @property PaginatorComponent $Paginator
 */
class TrackerInfosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	public $uses = array('TrackerInfo', 'Company');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->TrackerInfo->recursive = 0;
		$this->set('trackerInfos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->TrackerInfo->exists($id)) {
			throw new NotFoundException(__('Invalid tracker info'));
		}
		$options = array('conditions' => array('TrackerInfo.' . $this->TrackerInfo->primaryKey => $id));
		$this->set('trackerInfo', $this->TrackerInfo->find('first', $options));
	}

	
/**
 * add method
 *
 * @return void
 */
	function get_tracker_info_from_script($python_code=null)
	{
		$result = 0;
		$this->layout = 'ajax';
		//debug($python_code);
		//debug(Configure::read ('PYTHON_VERIFICATION_CODE'));
		if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			$post_data = $this->request->data;
			//debug($post_data);
			//return null;
			$company_website = $post_data['website'];
			//debug($company_website);
			$company = $this->Company->getRawCompanyInfoBySiteName($company_website);
			$company_id = $company['Company']['id'];
			$tracker_info = $this->TrackerInfo->getRawTrackerInfoByCompanyId($company_id);
			
			unset($tracker_info['TrackerInfo']['id']);
			$this->set('tracker_info', $tracker_info);
		}
	}
 	function add_tracker_info_from_script($python_code=null)
	{
		$result = 0;
		//$this->layout = 'ajax';
		//debug($python_code);
		//debug(Configure::read ('PYTHON_VERIFICATION_CODE'));
		if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			$post_data = $this->request->data;
			//debug($post_data);
			//return null;
			$company_website = $post_data['website'];
			
			$company = $this->Company->getRawCompanyInfoBySiteName($company_website);
			if (empty($company))
			{
				$added = $this->Company->AddFromWebsiteName($company_website);
				if (empty($added))
				{
					return null;
				}
				//debug($added);
				//$this->set('added', $added);
				$company = $this->Company->getRawCompanyInfoBySiteName($company_website);
			}
			//$this->set('company', $company);
			
			$company_id = $company['Company']['id'];
			$tracker_info = $this->TrackerInfo->getRawTrackerInfoByCompanyId($company_id);
			if (empty($tracker_info))
			{
				$tracker_info = array('TrackerInfo' => array());
				$tracker_info['TrackerInfo']['company_id'] = $company_id;
			}
			
			$valid_keys = array();
			$valid_keys['titlexpath'] = 1;
			$valid_keys['pricexpath'] = 1;
			$valid_keys['title_price_xpath'] = 1;
			$valid_keys['oldpricexpath'] = 1;
			$valid_keys['price_cur_code'] = 1;
			$valid_keys['old_price_cur_code'] = 1;
			$valid_keys['pimg_xpath'] = 1;
			$valid_keys['pimg_xpath1'] = 1;
			$valid_keys['pimg_xpath2'] = 1;
			$valid_keys['pimg_xpath3'] = 1;
			$valid_keys['pimg_xpath4'] = 1;
			$valid_keys['pimg_xpath5'] = 1;
			$valid_keys['urllib2_pimg_xpath1'] = 1;
			$valid_keys['urllib2_pimg_xpath2'] = 1;
			$valid_keys['urllib2_pimg_xpath3'] = 1;
			$valid_keys['urllib2_pimg_xpath4'] = 1;
			$valid_keys['urllib2_pimg_xpath5'] = 1;
			$valid_keys['image_and_title_parent_xpath'] = 1;
			$valid_keys['image_and_details_container_xpath'] = 1;
			$valid_keys['details_xpath'] = 1;
			$valid_keys['title_price_css'] = 1;
			$valid_keys['is_image_in_og_image_meta_tag'] = 1;
			$valid_keys['pinterest_position'] = 1;
			
			$new_data=false;
			foreach ($post_data as $key=>$value)
			{
				//debug($key);
				//debug($post_data[$key]);
				if (!empty($post_data[$key]) && (array_key_exists ($key, $valid_keys))) 
				{
					$tracker_info['TrackerInfo'][$key] = $value;
					$new_data=true;
				}
			}
			
			if($new_data)
			{
				if ($this->TrackerInfo->save($tracker_info))
				{
					$result = 1;
				}
			}
		}
		
		$this->set('result', $result);
		
	}
	
	
 	function add_regex_info_from_script($python_code=null)
	{
		$result = 0;
		//$this->layout = 'ajax';
		//debug($python_code);
		//debug(Configure::read ('PYTHON_VERIFICATION_CODE'));
		if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			//debug($this->request);
			$post_data = $this->request->data;
			//debug($post_data);
			//return null;
			$company_website = $post_data['website'];
			//debug ($company_website);
			
			$company = $this->Company->getRawCompanyInfoBySiteName($company_website);
			//debug($company);
			if (empty($company))
			{
				return null;
			}
			//$this->set('company', $company);
			
			$company_id = $company['Company']['id'];
			$tracker_info = $this->TrackerInfo->getRawTrackerInfoByCompanyId($company_id);
			//debug($company_id);
			//debug($tracker_info);
			if (empty($tracker_info))
			{
				return null;
			}
			
			$valid_regex_keys = array();
			$valid_regex_keys['titlexpath_regex'] = 1;
			$valid_regex_keys['pricexpath_regex'] = 1;
			$valid_regex_keys['title_price_xpath_regex'] = 1;
			$valid_regex_keys['oldpricexpath_regex'] = 1;
			$valid_regex_keys['pimg_xpath_regex'] = 1;
			$valid_regex_keys['pimg_xpath1_regex'] = 1;
			$valid_regex_keys['pimg_xpath2_regex'] = 1;
			$valid_regex_keys['pimg_xpath3_regex'] = 1;
			$valid_regex_keys['pimg_xpath4_regex'] = 1;
			$valid_regex_keys['pimg_xpath5_regex'] = 1;
			$valid_regex_keys['urllib2_pimg_xpath1_regex'] = 1;
			$valid_regex_keys['urllib2_pimg_xpath2_regex'] = 1;
			$valid_regex_keys['urllib2_pimg_xpath3_regex'] = 1;
			$valid_regex_keys['urllib2_pimg_xpath4_regex'] = 1;
			$valid_regex_keys['urllib2_pimg_xpath5_regex'] = 1;
			$valid_regex_keys['image_and_title_parent_xpath_regex'] = 1;
			$valid_regex_keys['image_and_details_container_xpath_regex'] = 1;
			$valid_regex_keys['pimg_xpath1_regex'] = 1;
			//debug($valid_regex_keys);
			$new_data=false;
			foreach ($post_data as $key=>$value)
			{
				//debug($key);
				//debug($post_data[$key]);
				if (!empty($post_data[$key]) && (array_key_exists ($key, $valid_regex_keys))) 
				{
					$tracker_info['TrackerInfo'][$key] = $value;
					$new_data=true;
				}
			}
			//debug($tracker_info);
			if($new_data)
			{
				$this->TrackerInfo->id = $tracker_info['id'];
				if ($this->TrackerInfo->save($tracker_info))
				{
					$result = 1;
				}
				else
				{
					//debug($this->TrackerInfo->validationErrors);
				}
			}
		}
		
		$this->set('result', $result);
		
	}
		
	
	
	public function add() {
		if ($this->request->is('post')) {
			$this->TrackerInfo->create();
			if ($this->TrackerInfo->save($this->request->data)) {
				$this->Session->setFlash(__('The tracker info has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The tracker info could not be saved. Please, try again.'));
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
		if (!$this->TrackerInfo->exists($id)) {
			throw new NotFoundException(__('Invalid tracker info'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->TrackerInfo->save($this->request->data)) {
				$this->Session->setFlash(__('The tracker info has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The tracker info could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('TrackerInfo.' . $this->TrackerInfo->primaryKey => $id));
			$this->request->data = $this->TrackerInfo->find('first', $options);
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
		$this->TrackerInfo->id = $id;
		if (!$this->TrackerInfo->exists()) {
			throw new NotFoundException(__('Invalid tracker info'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TrackerInfo->delete()) {
			$this->Session->setFlash(__('Tracker info deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Tracker info was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->TrackerInfo->recursive = 0;
		$this->set('trackerInfos', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->TrackerInfo->exists($id)) {
			throw new NotFoundException(__('Invalid tracker info'));
		}
		$options = array('conditions' => array('TrackerInfo.' . $this->TrackerInfo->primaryKey => $id));
		$this->set('trackerInfo', $this->TrackerInfo->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->TrackerInfo->create();
			if ($this->TrackerInfo->save($this->request->data)) {
				$this->Session->setFlash(__('The tracker info has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The tracker info could not be saved. Please, try again.'));
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
		if (!$this->TrackerInfo->exists($id)) {
			throw new NotFoundException(__('Invalid tracker info'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->TrackerInfo->save($this->request->data)) {
				$this->Session->setFlash(__('The tracker info has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The tracker info could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('TrackerInfo.' . $this->TrackerInfo->primaryKey => $id));
			$this->request->data = $this->TrackerInfo->find('first', $options);
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
		$this->TrackerInfo->id = $id;
		if (!$this->TrackerInfo->exists()) {
			throw new NotFoundException(__('Invalid tracker info'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->TrackerInfo->delete()) {
			$this->Session->setFlash(__('Tracker info deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Tracker info was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
