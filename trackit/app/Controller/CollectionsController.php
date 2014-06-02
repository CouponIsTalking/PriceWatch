<?php
App::uses('AppController', 'Controller');
/**
 * UserProducts Controller
 *
 * @property UserProduct $UserProduct
 * @property PaginatorComponent $Paginator
 */
class CollectionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData');
	public $uses = array('UserProduct', 'Company', 'Product', 'ProdVote', 'Collection');
	
	public function shared($id,$hash){
	
		$c =$this->Collection->findby_id_and_hash($id,$hash);
		
		if (empty($c)){
			$this->set('bad_url', true);
			return;
		}else{
			$this->set('bad_url', false);
			//debug($c);
			$user_id = $c['user_id'];
			$group_name = $c['group_name'];
			
			if("All"==$group_name){
				$this->get_items_from_collection($user_id, "");
			}else{
				$this->get_items_from_collection($user_id, $group_name);
			}
			
			$this->set('filtered_group_name', $group_name);
				
			$loggedin_user_id = $this->UserData->getUserId();
			if (!empty($loggedin_user_id) 
				&&($user_id == $loggedin_user_id)
			){ 
				$this->set('self_collection', true);
			} else{
				$this->set('self_collection', false);
			}
			
			$this->set('filtered_group_name', $group_name);
		}
		
	}
	
	/*
	public function shared($user_id, $encrypted_group_name="")
	{
		$loggedin_user_id = $this->UserData->getUserId();
		if (!empty($loggedin_user_id) 
			&&($user_id == $loggedin_user_id)
		){ 
			$this->set('self_collection', true);
		} else{
			$this->set('self_collection', false);
		}
		
		$this->CollectEncrypt = $this->Components->load('CollectEncrypt');
		$group_name = $this->CollectEncrypt->decrypt_collection_name($encrypted_group_name);
		$this->get_items_from_collection($user_id, $group_name);
		$this->set('filtered_group_name', $group_name);
	}
	*/
	
	public function my()
	{
		$this->set('self_collection', true);
		$user_id = $this->UserData->getUserId();
		$user_email = $this->UserData->getUserEmail();
		
		if (!empty($this->params['named']['group_name'])){
			$group_name = $this->params['named']['group_name'];
		}
		else{
			$group_name = "";
		}
		
		$this->set('filtered_group_name', $group_name);
		
		if (empty($user_id) || empty($user_email)){return;}
		/*
		if (!empty($user_email_existing)){
			$user_email = $user_email_existing;
		}
		
		if (empty($user_email)){
			$post_data = $this->request->data;
			if (!empty($post_data))
			{
				$user_email = $post_data['user_email'];
			}
		}
		*/
		
		$this->get_items_from_collection($user_id, $group_name);
		
		$groups = $this->UserProduct->get_product_group_names($user_email);
		if (!empty($groups)){
			$result['success'] = 1;
			$result['collections'] = $groups;
		}
		$this->set('collection_names', $groups);
		
		if(empty($group_name)){
			$c = $this->Collection->findby_uid_and_grpname($user_id, "All", true);
		}else{
			$c = $this->Collection->findby_uid_and_grpname($user_id, $group_name, true);
		}
		if(!empty($c)){
			$collection_share_tag = $c['id']."/".$c['hash'];
		}else{
			$collection_share_tag="";
		}
		$this->set('collection_share_tag', $collection_share_tag);
		
	}
	
	/*
	Gets a list of products with given config conditions
	
	Type:	
		type1:
			priceupdate_before > priceupdate_time
			order desc priceupdate_time
		type2:
			priceupdate_after < priceupdate_time
			order desc priceupdate_time
			
		type3:
			add_timestamp_before > add_timestamp
			order desc add_timestamp
		type4:
			add_timestamp_after < add_timestamp
			order desc add_timestamp
		
	Limit: 
		how many max products to get ?
	
	
			
	*/
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
		
		$type = intval($data['type']);
		
		$priceupdate_before = intval($data['pub']);
		$priceupdate_after = intval($data['pua']);
		
		$added_before = intval($data['adb']);
		$added_after = intval($data['ada']);
		
		$company_id = intval($data['cid']);
		$limit = intval($data['lmt']);
		$must_have_image = intval($data['mhi']);
		
		if(!$limit){$limit=30;}
		
		$query_params =array(
			'recursive' => -1,
			'conditions' => array(),
			'order' => array(),
			'limit' => $limit,
		);
		
		if (1==$for_homepage){
			$this->set('edit_options', false);
			$this->set('track_options', true);
			$this->set('like_want_own_options', true);			
		}
		
		if(1==$must_have_image){
			$query_params['conditions']['image1 !='] = null;
			$query_params['conditions']['LENGTH(image1) >'] = 5;			
		}
		
		if(1==$type){
			if(0==$priceupdate_before){
				$priceupdate_before = time();
			}
			$query_params['conditions']['lastpriceupdate_timestamp <='] = $priceupdate_before;
			$query_params['order']['lastpriceupdate_timestamp'] = 'DESC';

		}else if(2==$type){
			if(0==$priceupdate_after){
				$priceupdate_after = time();
			}
			$query_params['conditions']['lastpriceupdate_timestamp >='] = $priceupdate_after;
			$query_params['order']['lastpriceupdate_timestamp'] = 'DESC';
			
		}else if(3==$type){
			if(0==$added_before){
				$added_before = time();
			}
			$query_params['conditions']['add_timestamp <='] = $added_before;
			$query_params['order']['add_timestamp'] = 'DESC';
			
		}else if(4==$type){
			if(0==$added_after){
				$added_after = time();
			}
			$query_params['conditions']['add_timestamp >='] = $added_after;
			$query_params['order']['add_timestamp'] = 'DESC';
		}
		
		//debug($query_params);
		$products = $this->Product->find('all',$query_params);
		
		$product_ids = array();
		foreach ($products as $index=>$product){
			$product_ids[] = $product['Product']['id'];
		}
		
		$ret['s']=1;
		if(empty($products)){
			$ret['nk'] = 0;
		}
		else if(1==$type){
			$ret['nk'] = $products[count($products)-1]['Product']['lastpriceupdate_timestamp'];
		} else if(2==$type){
			$ret['nk'] = $products[0]['Product']['lastpriceupdate_timestamp'];
		} else if(3==$type){
			$ret['nk'] = $products[count($products)-1]['Product']['add_timestamp'];
		} else if(4==$type){
			$ret['nk'] = $products[0]['Product']['add_timestamp'];
		}
		
		$this->UserProdVote = $this->Components->load('UserProdVote');
		$prod_votes = $this->UserProdVote->get_user_votes_on_prod($this,$product_ids);
		$this->set('prod_votes', $prod_votes);
		$this->set('products', $products);
		
		$company_ids = $this->Product->getUniqueFields($products,'company_id','Product');
		$companies = $this->Company->getCompanyListByIds($company_ids);
		$this->set('companies', $companies);
				
		$this->set('ret',$ret);
		return $ret;
	}
	
	private function get_items_from_collection($user_id, $group_name){
		
		if ("" == $group_name){
			$this->paginate = array('recursive'=>-1,
				'conditions'=>array('UserProduct.user_id'=>$user_id),
				'limit' => 30
				);
		}else{
			$this->paginate = array('recursive'=>-1,
				'conditions'=>array(
					'AND'=>array('UserProduct.user_id'=>$user_id, 
							'UserProduct.group_name'=>$group_name
						)),
				'limit' => 30
				);
		}
		
		$user_products = $this->Paginator->paginate('UserProduct');
		
		$this->set('ups', $user_products);
		
		$product_ids = array();
		foreach ($user_products as $index=>$up){
			$product_ids[] = $up['UserProduct']['product_id'];
		}
		
		$i = count($product_ids);
		if ($i > 1){
			$products = $this->Product->find('all', array('recursive'=> -1, 'conditions'=> array('id IN' => $product_ids)));
		}else if ($i == 1){
			$products = $this->Product->find('all', array('recursive'=> -1, 'conditions'=> array('id' => $product_ids[0])));
		}else{
			$products = array();
		}
		
		//$company_ids = $this->Product->getCompanyIdsFromProducts($products);
		//$products = $this->Product->indexOnId($products);
		
		$this->set('products', $products);
		
		$this->UserProdVote = $this->Components->load('UserProdVote');
		$prod_votes = $this->UserProdVote->get_user_votes_on_prod($this,$product_ids);
		$this->set('prod_votes', $prod_votes);
		
		$company_ids = $this->Product->getUniqueFields($products,'company_id','Product');
		$companies = $this->Company->getCompanyListByIds($company_ids);
		$this->set('companies', $companies);
		
		
	}	

}?>