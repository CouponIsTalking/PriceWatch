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
		
		
		$this->set('condition_data', $this->Condition->getConditionList());
		$this->set('ocs', $ocs);
		$this->set('ocids', $ocids);
		$this->set('oc_conditions', $oc_sorted_conditions);
		
		$this->set('companies', $this->Company->getBrosableCompaniesList());
		//$this->set('products', $products);
		//$this->set('products', $this->Product->getProductList());
		$this->set('title_for_layout', 'Discover Latest Discounts');
		
	}
	
	public function products(){
		
		$products = $this->recent_price_drops(0,30);
		
		$product_ids = array();
		foreach ($products as $index=>$product){
			$product_ids[] = $product['Product']['id'];
		}
		
		$this->UserProdVote = $this->Components->load('UserProdVote');
		$prod_votes = $this->UserProdVote->get_user_votes_on_prod($this,$product_ids);
		$this->set('prod_votes', $prod_votes);
		
		$company_ids = $this->Product->getUniqueFields($products,'company_id','Product');
		//debug($company_ids);
		$companies = $this->Company->getCompanyListByIds($company_ids);
		$this->set('companies', $companies);
		
		$this->set('products', $products);
		
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