<?php
App::uses('AppModel', 'Model');
/**
 * BackendOp Model
 *
 */
class BackendOp extends AppModel {

	public $primaryKey = 'id';
	
	
	public function is_job_type_valid($type)
	{
		$GET_RELATED_PROD = Configure::read('GET_RELATED_PROD');
		$GET_PROD_DETAIL = Configure::read('GET_PROD_DETAIL');
		$CLASSIFY_PAGE = Configure::read('CLASSIFY_PAGE');
		$GEN_PRICE_NOTF = Configure::read('GEN_PRICE_NOTF');
		return (($type == $GEN_PRICE_NOTF) || ($type == $GET_RELATED_PROD) || ($type == $GET_PROD_DETAIL) || ($type == $CLASSIFY_PAGE));
	
	}
	
	public function add_job($type, $data)
	{
		
		if (!($this->is_job_type_valid($type)))
		{
			return false;
		}
		
		$data = array_merge(array('url'=>'','pid'=>0, 'status'=>0), $data);
		
		$bop = array('type' => $type, 
			'url' => $data['url'], 
			'pid' => $data['pid'], 
			'status' => $data['status']);
		
		$this->create();
		$result = $this->save($bop);
		
		return $result;
		
	}
	
	public function update_job_status($id, $new_status)
	{
		$this->id = $id;
		$bop = array('id' => $id, 'status' => $new_status);
		$result = $this->save($bop);
		return $result;
	}
	
	public function get_pending_jobs($job_types)
	{
		$num_job_types = count($job_types);
		
		if (0 == $num_job_types)
		{
			return null;
		}
		else if (1 == $num_job_types)
		{
			$jobs = $this->find('all', array(
					'recursive' => -1,
					'conditions' => array ('status' => 0, 'type' => $job_types[0])
				));			
		}
		else
		{
			$jobs = $this->find('all', array(
					'recursive' => -1,
					'conditions' => array ('status' => 0, 'type IN' => $job_types)
				));
		}
		
		return $jobs;
	}
	
}
