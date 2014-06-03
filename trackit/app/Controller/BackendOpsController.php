<?php
App::uses('AppController', 'Controller');
/**
 * BackendOps Controller
 *
 * @property BackendOp $BackendOp
 * @property PaginatorComponent $Paginator
 */
class BackendOpsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler');
	public $uses = array ('BackendOp');
	
	public function get_pending_jobs($python_verif_code)//, $job_types_str = "")
	{
		$this->layout = 'ajax';
		
		if ($python_verif_code != Configure::read('PYTHON_VERIFICATION_CODE'))
		{
			return null;
		}
		
		//if ("" == $job_types_str)
		//{
		$post_data = $this->request->data;
		$job_types_str = $post_data['job_types_str'];
		//}
		
		if (empty($job_types_str))
		{
			return null;
		}
		
		$job_types = explode(",", $job_types_str);
		
		$jobs = $this->BackendOp->get_pending_jobs($job_types);
		$this->set('jobs' , $jobs);
	}

	public function update_backend_job_status($python_code)
	{
		$result = null;
		
		if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			$post_data = $this->request->data;
			
			$job_id = $post_data['job_id'];
			$new_status = $post_data['new_status'];
			$result = $this->BackendOp->update_job_status($job_id, $new_status);
			
		}
		
		if (!empty($result))
		{
			$this->set('retval', 1);
		}
		else
		{
			$this->set('retval', 0);
		}
	}
	
	public function add_get_prod_detail_jobs($python_code)
	{
		$retval = 0;
		if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			$post_data = $this->request->data;
			
			$retval = 1;
			foreach ($post_data as $index => $link)
			{
				$result = $this->BackendOp->add_job(Configure::read('GET_PROD_DETAIL'), array('url' => $link));
				if (empty($result['BackendOp']['id']))
				{
					$retval = 0;
				}
			}
		}
		
		$this->set('retval', $retval);
		
	}
	
	public function test_add_job()
	{
		$purl = "tempurl.html";
		$data = array('url' => $purl);
		$result = $this->BackendOp->add_job(Configure::read('GET_RELATED_PROD'), $data);
		$this->BackendOp->update_job_status($result['BackendOp']['id'], 1);
		
		$result = $this->BackendOp->add_job(Configure::read('GET_PROD_DETAIL'), $data);
		$this->BackendOp->update_job_status($result['BackendOp']['id'], 1);
		
		$result = $this->BackendOp->add_job(Configure::read('CLASSIFY_PAGE'), $data);
		$this->BackendOp->update_job_status($result['BackendOp']['id'], 1);
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->only_admin_can_see();
		
		$this->BackendOp->recursive = 0;
		$this->set('backendOps', $this->Paginator->paginate());
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
		
		if (!$this->BackendOp->exists($id)) {
			throw new NotFoundException(__('Invalid backend op'));
		}
		$options = array('conditions' => array('BackendOp.' . $this->BackendOp->primaryKey => $id));
		$this->set('backendOp', $this->BackendOp->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->only_admin_can_see();
		
		if ($this->request->is('post')) {
			$this->BackendOp->create();
			if ($this->BackendOp->save($this->request->data)) {
				$this->Session->setFlash(__('The backend op has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The backend op could not be saved. Please, try again.'));
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
		$this->only_admin_can_see();
		
		if (!$this->BackendOp->exists($id)) {
			throw new NotFoundException(__('Invalid backend op'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->BackendOp->save($this->request->data)) {
				$this->Session->setFlash(__('The backend op has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The backend op could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('BackendOp.' . $this->BackendOp->primaryKey => $id));
			$this->request->data = $this->BackendOp->find('first', $options);
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
		$this->only_admin_can_see();
		
		$this->BackendOp->id = $id;
		if (!$this->BackendOp->exists()) {
			throw new NotFoundException(__('Invalid backend op'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->BackendOp->delete()) {
			$this->Session->setFlash(__('Backend op deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Backend op was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

}