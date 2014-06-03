<?php
App::uses('AppController', 'Controller');
/**
 * CacheData Controller
 *
 * @property Discount $OpenCampaign
 * @property PaginatorComponent $Paginator
 */
class CacheDataController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $uses = array (
		'CacheData',
		'OpenCampaign', 
		'Company', 
		'Product', 
		'Condition', 
		'OcCondition', 
		'Content', 
		'UserCoupon',
		'ProdVote'
	);
	
	var $helpers = array('Html');//,'Javascript');
    var $components = array(
		'Paginator', 
		'RequestHandler', 
		'UserData'		
		);
	
	// If the request is coming from an admin or one of our python
	// scripts, then continue. Otherwise, redirect to 404-page-not-found.
	//
	private function either_admin_or_py(){
		// If no python code given, then ensure that it is the admin doing.
		if (empty($data['pcode'])){
			$this->only_admin_can_see();
		}
		// Else proceed only if python code is verified.
		else{
			$this->ProdAPI = $this->Components->load('ProdAPI');
			$has_api_access = $this->ProdAPI->has_python_access($data['pcode']);
			if (!$has_api_access){
				$this->goto_restricted_page();
			}
		}
		return true;
	}
	
	public function cache_recent_pdrop_data(){
		
		$this->either_admin_or_py();
		
		$all_products = $this->Product->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				//'lastpriceupdate_timestamp >=' =>  $since,
				'image1 !=' => null,
				'LENGTH(image1) >' => 5
			),
			'order' => array('lastpriceupdate_timestamp' => 'DESC'),
			'limit' => 300,
		));
		
		$total_prods = count($all_products);
		$has_prods = true;
		for($i=0;$i<6;$i++){
			$products = array();
			$product_ids = array();
			for($j=0;$has_prods && $j<50;$j++){
				$this_index = $i*50+$j;
				if($this_index >= $total_prods){
					$has_prods = false;
					break;
				}
				
				$products[] = $all_products[$this_index];
				$product_ids[] = $all_products[$this_index]['Product']['id'];
			}
			$company_ids = $this->Product->getUniqueFields($products,'company_id','Product');
			$companies = $this->Company->getCompanyListByIds($company_ids);
			
			$this->CacheData->set_data_by_key("prod_map_for_recent_pdrop_list".strval($i+1), $products, false, 'memcache_24hr');
			$this->CacheData->set_data_by_key("prodids_for_recent_pdrop_list".strval($i+1), $product_ids,false, 'memcache_24hr');
			//$this->CacheData->set_data_by_key("compids_for_recent_pdrop_list".strval($i+1), $company_ids,false, 'memcache_24hr');
			$this->CacheData->set_data_by_key("comp_map_for_recent_pdrop_list".strval($i+1), $companies,false, 'memcache_24hr');
		}
	}
	
	public function cache_discount_page(){
		
		$this->either_admin_or_py();
		
		$brosable_companies = $this->Company->getBrosableCompaniesList();
		
		$this->CacheData->set_data_by_key("comps_select_list_with_fast_tracker", $brosable_companies,false, 'memcache_24hr');
		$this->CacheData->set_data_by_key("condition_list", $this->Condition->getConditionList(),false, 'memcache_24hr');
	}
	
	public function cache_company_ids_with_fast_tracker(){
		
		$this->either_admin_or_py();
		
		App::uses('TrackerInfo', 'Model');
		$this->TrackerInfo = new TrackerInfo();
		$company_ids = $this->TrackerInfo->get_company_ids_with_fast_tracker();
		
		$this->CacheData->set_data_by_key("company_ids_with_fast_tracker", $company_ids,false, 'memcache_24hr');
	}
	
	public function nice(){
	}
}