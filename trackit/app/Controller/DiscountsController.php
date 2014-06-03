<?php
App::uses('AppController', 'Controller');
/**
 * Discounts Controller
 *
 * @property Discount $OpenCampaign
 * @property PaginatorComponent $Paginator
 */
class DiscountsController extends AppController {

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
	
	public function awesome()
	{
		//Configure::write('debug', 2);
		
		$logged_in_company_id = $this->UserData->getCompanyId();
		$this->set('logged_in_company_id', $logged_in_company_id);
		
		if (!empty($logged_in_company_id))
		{
			$ocs = $this->OpenCampaign->getActiveCampaignsByCompanyId($logged_in_company_id);
		}
		else if (!empty($this->request->query['c']))
		{
			$company_id = $this->request->query['c'];
			$ocs = $this->OpenCampaign->getActiveCampaignsByCompanyId($company_id);
		}
		else
		{
			$ocs = $this->OpenCampaign->getActiveCampaigns();
		}
		
		if (!empty($this->request->query['layout']) 
			&& ('popup' == $this->request->query['layout'])
			)
		{
			$this->layout='oc_popup';
			$this->set('layout_var', 'popup');
		}
		
		$ocids = array();
		
		foreach ($ocs as $index => $oc)
		{
			$ocids[] = $oc['OpenCampaign']['id'];
		}
		
		$oc_conditions = $this->OcCondition->find('all', array(
							'conditions'=>array('oc_id' => $ocids),
							'recursive' => -1//,
							//'group' => 'oc_id'
						));
		
		// sort conditions based on oc_id
		$oc_sorted_conditions = array();
		foreach ($ocids as $index => $id)
		{
			$oc_sorted_conditions[$id] = array();
		}
		
		foreach ($oc_conditions as $index => $oc_con)
		{
			$id = $oc_con['OcCondition']['oc_id'];
			$oc_sorted_conditions[$id][] = $oc_con['OcCondition'];
		}
		
		$condition_list = $this->CacheData->get_data_by_key(
						'condition_list', 
						false, 
						'memcache_24hr', 
						true);
		
		//$condition_list = $this->Condition->getConditionList();
		
		$this->set('condition_data', $condition_list);
		$this->set('ocs', $ocs);
		$this->set('ocids', $ocids);
		$this->set('oc_conditions', $oc_sorted_conditions);
		
		$brosable_companies = $this->CacheData->get_data_by_key(
						'comps_select_list_with_fast_tracker', 
						false, 
						'memcache_24hr', 
						true);
		
		//$brosable_companies = $this->Company->getBrosableCompaniesList();
		
		$this->set('companies', $brosable_companies);
		//$this->set('products', $products);
		//$this->set('products', $this->Product->getProductList());
		$this->set('title_for_layout', 'Discover Latest Discounts');
		
	}
	
	public function products(){
		
		//$products = $this->recent_price_drops(0,30);
		$products = $this->CacheData->get_data_by_key(
						'prod_map_for_recent_pdrop_list1', 
						false, 
						'memcache_24hr', 
						true);
		
		/*
		$product_ids = array();
		foreach ($products as $index=>$product){
			$product_ids[] = $product['Product']['id'];
		}
		*/
		$product_ids = $this->CacheData->get_data_by_key(
						'prodids_for_recent_pdrop_list1', 
						false, 
						'memcache_24hr', 
						true);
						
		$this->UserProdVote = $this->Components->load('UserProdVote');
		$prod_votes = $this->UserProdVote->get_user_votes_on_prod($this,$product_ids);
		$this->set('prod_votes', $prod_votes);
		
		/*
		$company_ids = $this->Product->getUniqueFields($products,'company_id','Product');
		$companies = $this->Company->getCompanyListByIds($company_ids);
		*/
		$companies = $this->CacheData->get_data_by_key(
						'comp_map_for_recent_pdrop_list1', 
						false, 
						'memcache_24hr', 
						true);
						
		$this->set('companies', $companies);
		$this->set('products', $products);
		
	}
	
	// When home page is scrolled down, get another prod list
	// page and attach to the bottom.
	//
	public function ajax_get_prod_list(){
		
		$this->layout = 'ajax';
		
		$ret = array('s'=>0,'m'=>'');
		$this->set('ret',$ret);
		
		$ispost=$this->RequestHandler->isPost();
		$isajax=$this->RequestHandler->isAjax();
		
		if(!$ispost || !$isajax){
			return $ret;
		}
		
		$data = $this->request->data;
		$for_homepage = intval($data['forhmpg']);
		$key = $data['key'];
		$from = $data['from'];
		
		$this->set('edit_options', false);
		$this->set('track_options', true);
		$this->set('like_want_own_options', true);
		$ret['s']=1;
		$ret['nk']=intval($from)+1;
		
		$products = $this->CacheData->get_data_by_key(
				"prod_map_for_recent_pdrop_list{$from}", 
				false, 
				'memcache_24hr', 
				true);
		
		$product_ids = $this->CacheData->get_data_by_key(
						"prodids_for_recent_pdrop_list{$from}", 
						false, 
						'memcache_24hr', 
						true);
		
		$this->UserProdVote = $this->Components->load('UserProdVote');
		$prod_votes = $this->UserProdVote->get_user_votes_on_prod($this,$product_ids);
		$this->set('prod_votes', $prod_votes);
				
		$companies = $this->CacheData->get_data_by_key(
						"comp_map_for_recent_pdrop_list{$from}",  
						false, 
						'memcache_24hr', 
						true);
		
		$this->set('companies', $companies);
		$this->set('products', $products);
		
		$this->set('ret',$ret);
		return $ret;
	}
	
	private function recent_price_drops_from($from,$min_return_count){
		$products = array();
		if ( ($from == 3600)
			|| ($from == 24 * 3600)
			|| ($from == 3 * 24 * 3600)
			|| ($from == 7 * 24 * 3600)
			|| ($from == 30 * 24 * 3600)
			|| ($from == 3 * 30 * 24 * 3600)
			)
		{
			$since = time() - $from;
			$products = $this->Product->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'lastpriceupdate_timestamp >=' =>  $since,
					'image1 !=' => null,
					'LENGTH(image1) >' => 5
				),
				'order' => array('id' => 'DESC'),
				'limit' => $min_return_count,
			));
		}
		return $products;
	}
	
	public function recent_price_drops($from=0, $min_return_count=30)
	{
		$products = array();
		
		if (0!=$from){
			$products = $this->recent_price_drops_from($from,$min_return_count);
		}
		else {
			$from = 3600;
			while(count($products) < $min_return_count){
				$products = $this->recent_price_drops_from($from,$min_return_count);
				if(3600==$from){
					$from = 24 * 3600;
				}else if(24*3600==$from){
					$from = 3*24 * 3600;
				}else if(3*24*3600==$from){
					$from = 7*24 * 3600;
				}else if(7*24*3600==$from){
					$from = 30*24 * 3600;
				}else if(30*24*3600==$from){
					$from = 3*30*24 * 3600;
				}else{
					break;
				}
			}
		}
		
		$this->set('from', $from);
		$this->set('products', $products);
		return $products;
	}
		
}