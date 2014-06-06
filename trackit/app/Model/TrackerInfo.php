<?php
App::uses('AppModel', 'Model');
/**
 * TrackerInfo Model
 *
 */
class TrackerInfo extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'tracker_info';
	
	
	public function getRawTrackerInfoByCompanyId($company_id)
	{
		
		if (empty($company_id)) return null;
	
		$tracker_info = $this->find('first', array('recursive' => -1, 'conditions' => array ('company_id' => $company_id)));
		
		return $tracker_info;	
	
	}
	
	public function get_company_ids_with_fast_tracker()
	{
		$trackers = $this->find('all', array('recursive' => -1, 
			'conditions' => array (
				'urllib2_pimg_xpath1 !=' => ""
			)
		));
		$company_ids = array();
		foreach ($trackers as $index => $tracker)
		{
			$company_ids[] = $tracker['TrackerInfo']['company_id'];
		}
		
		return $company_ids;
	}

}
