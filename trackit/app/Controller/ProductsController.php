<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 *
 * @property Product $Product
 * @property PaginatorComponent $Paginator
 */
class ProductsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData', 'PythonInf');
	
	var $uses = array('Product', 'Company', 'Topic', 'UserProduct', 'BackendOp', 'TrackerInfo');

	public function enable_trigger_for_pending_jobs()
	{
		$this->layout = 'ajax';
		$this->PythonInf->set_trigger_for_pending_jobs(1);
	}
	
	public function disable_trigger_for_pending_jobs()
	{
		$this->layout = 'ajax';
		$this->PythonInf->set_trigger_for_pending_jobs(0);
	}
	
	private function recent_price_drops_from($from){
		$products = array();
		if ( ($from == 3600)
			|| ($from == 24 * 3600)
			|| ($from == 7 * 24 * 3600)
			|| ($from == 30 * 24 * 3600)
			)
		{
			$since = time() - $from;
			$products = $this->Product->find('all', array(
				'recursive' => -1,
				'conditions' => array('lastpriceupdate_timestamp >=' =>  $since),
				'limit' => 100,
			));
		}
		return $products;
	}
	
	public function recent_price_drops($from=0)
	{
		$products = array();
		
		if (0!=$from){
			$products = $this->recent_price_drops_from($from);
		}
		else {
			$from = 3600;
			while(count($products) < 20){
				$products = $this->recent_price_drops_from($from);
				if(3600==$from){
					$from = 24 * 3600;
				}else if(24*3600==$from){
					$from = 3*24 * 3600;
				}else if(3*24*3600==$from){
					$from = 7*24 * 3600;
				}else{
					break;
				}
			}
		}
		
		$this->set('from', $from);
		$this->set('products', $products);
		
		$companies = $this->Company->getCompanyList();
		$this->set('companies', $companies);		
		
	}
	
	public function plist_for_price_check($python_code)
	{
		$this->layout = 'ajax';
		
		$products = array();
		
		$this->ProdAPI = $this->Components->load('ProdAPI');
		$has_access = $this->ProdAPI->has_api_or_python_access($python_code);
		if ($has_access)
		{
			App::uses('ConfigConst','Model');
			$this->ConfigConst = new ConfigConst();
			$last_pid = $this->ConfigConst->get_val_by_key('last_plist_end_pid');
			
			$seconds_in_an_hour = 60*60;
			
			$products = $this->Product->find('all', array(
					'recursive' => -1,
					'conditions' => array(
						'id >'=>$last_pid,
						'lastpriceupdate_timestamp' < strval(time()- $seconds_in_an_hour)
						),
					'fields' => array('id', 'purl'),
					'limit' => 100,
				));
			
			if (count($products) > 0){
				$tp = count($products);
				$last_pid = $products[$tp-1]['Product']['id'];				
			}else{
				$last_pid = 0;
			}
			$last_pid = $this->ConfigConst->update_val_by_key('last_plist_end_pid', $last_pid);
			
		}
		
		$this->set('products', $products);
	}
	
	
	public function best_stuff($company_id = null)
	{
		
		if (empty($company_id))
		{
			$this->set('show_company_list', true);
			$company_ids = $this->TrackerInfo->get_company_ids_with_fast_tracker();
			
			$this->set('companies', $this->Company->getBrosableCompaniesList($company_ids));			
		}
		else
		{
			$this->set('show_company_list', false);
			
			$prods = $this->Product->getRawProductsByCompanyId($company_id);
			
			$this->set('prods', $prods);
			
			$companies = $this->Company->getCompanyList();
			$this->set('companies', $companies);
		}
		
	}
	
	public function api_get_prod_info($api_key){
		
		$this->layout = 'ajax';
		
		$this->ProdAPI = $this->Components->load('ProdAPI');
		$has_api_access = $this->ProdAPI->has_api_access($api_key);
		if (!$has_api_access)
		{
			$this->set('output', 'No API permission');
			return;
		}
		
		$output = "";
		$this->set('output', $output);
		
		$purl = $this->request->data['url'];
		
		$max_exec_time = ini_get('max_execution_time');
		ini_set('max_execution_time', 120);
		
		$command = PYTHON_SCRIPTS_FOLDER."get_features.py \"{$purl}\"";
		$output = array();
		//debug ($command);
		exec($command, $output);
		
		$this->set('output', $output);
	}
	
	public function update_price_from_script($python_code)
	{
		$this->layout = 'ajax';
		
		$result = false;
		
		$this->ProdAPI = $this->Components->load('ProdAPI');
		$has_api_access = $this->ProdAPI->has_api_or_python_access($python_code);
		if (!$has_api_access)
		{
			$this->set('result', 'No API permission. Request Validation Failed.');
			return;
		}
		else //if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			$post_data = $this->request->data;
			$pid = $post_data['prod_id'];
			$new_price = $post_data['new_price'];
			$time = time();
			
			$product = $this->Product->findRawProductInfoById($pid);
			
			if (empty($product['Product']))
			{
				$result = 'Product not found';
			}
			else if (empty($new_price) || !$new_price)
			{
				$result = "New price is empty or zero";
			}
			else if (strval($new_price) == strval($product['Product']['cur_price']))
			{
				$result = "New price is same as current price ". strval($product['Product']['cur_price']);
			}
			else
			{
				$cur_time = time();
				$cur_price = $product['Product']['cur_price'];
				$dir = 0;
				if (!empty($cur_price) && !empty($new_price)){
					// if the new price is less, set the last price drop timestamp to now
					if (floatval($cur_price) > floatval($new_price)){
						$product['Product']['lastpricedrop_timestamp'] = $cur_time;
						$dir = -1;
					} // else if new price is more, set the last price up timestamp to now
					else if (floatval($cur_price) < floatval($new_price)){
						$product['Product']['lastpriceup_timestamp'] = $cur_time;
						$dir =  1;
					}
				}
				
				$product['Product']['cur_price'] = $new_price;
				if ($product['Product']['cur_price'] > $product['Product']['high_price'])
				{
					$product['Product']['high_price'] = $product['Product']['cur_price'];
				}
				else if (  (0 == $product['Product']['low_price'])
						|| ($product['Product']['cur_price'] < $product['Product']['low_price'])
					)
				{
					$product['Product']['low_price'] = $product['Product']['cur_price'];
				}
				
				$product['Product']['lastpriceupdate_timestamp'] = $cur_time;
				if (!empty($product['Product']['price_date_history']))
				{
					$product['Product']['price_date_history'] = $product['Product']['price_date_history'] . "," . strval($product['Product']['cur_price']) . "," . strval($product['Product']['lastpriceupdate_timestamp']);
				}
				else
				{
					$product['Product']['price_date_history'] = strval($product['Product']['cur_price']) . "," . strval($product['Product']['lastpriceupdate_timestamp']);
				}
				
				$saved = $this->Product->save($product, true, array('cur_price', 'high_price', 'low_price', 'lastpriceupdate_timestamp', 'lastpriceup_timestamp', 'lastpricedrop_timestamp', 'price_date_history'));
				
				if (!empty($saved))
				{
					$result = true;
					// if price dropped, then add a price notification job
					if(-1 == $dir)
					{
						$this->BackendOp->add_job(Configure::read('GEN_PRICE_NOTF'), array('pid' => $pid));
					}
				}
				else
				{
					$result = $this->Product->validationErrors;
				}
			}
			
		}
		
		$this->set('result', $result);
		return $result;
			
	}
	
	public function update_prod_info_from_script($python_code)
	{
		$retval = 0;
		$this->set('retval', 0);
		
		if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			$post_data = $this->request->data;
			
			$prod_url = $post_data['prod_link'];
			$product = $this->Product->findRawProductInfoByUrl($prod_url);
			
			$new_product = false;
			
			if (empty($product['Product']))
			{
				$company = $this->Company->getRawCompanyInfoBySiteName($post_data['company_website']);
				
				if (!empty($company['Company']['id']))
				{
					$company_id = $company['Company']['id'];
				}
				else
				{
					return null;
				}
				
				$product = array('Product' => array());
				$this->Product->create();
				$product['Product']['purl'] = $prod_url;
				$product['Product']['company_id'] = $company_id;
				$new_product = true;
			}
			
			if (empty($product['Product']['name']))
			{
				$product['Product']['name'] = $post_data['title'];
			}
			
			
			$price_updated = false;
			$price_change_dir = 'none';
			if ($new_product || (!empty($post_data['price']) && ($product['Product']['cur_price'] != $post_data['price'])))
			{
				$price_updated = true;
				if (!empty($product['Product']['cur_price']))
				{
					$cur_price_float = floatval($product['Product']['cur_price']);
					$new_price_float = floatval($post_data['price']);
					if ($new_price_float < $cur_price_float){
						$price_change_dir = 'down';
					}else if ($new_price_float > $cur_price_float){
						$price_change_dir = 'up';
					}
				}
				$product['Product']['cur_price'] = $post_data['price'];
			}
			
			//if (empty($product['Product']['image1']) || ($product['Product']['image1'] == 'NULL') || ($product['Product']['image1'] == NULL))
			//{
				$product['Product']['image1'] = $post_data['pimg'];
			//}
			
			foreach ($post_data as $key => $val)
			{
				if ((strpos($key, "pimg") === 0)  && ($val != $post_data['pimg']))
				{
					$product['Product']['image2'] = $val;
				}
			}
			
			$product['Product']['high_price'] = $product['Product']['cur_price'];
			$product['Product']['low_price'] = $product['Product']['cur_price'];
			$product['Product']['start_price'] = $product['Product']['cur_price'];
			if ($price_updated){
				$product['Product']['price_date_history'] = strval($product['Product']['cur_price']) . "," . strval(time());
				if ('down' == $price_change_dir){
					$product['Product']['lastpricedrop_timestamp'] = time();
				}else if ('up' == $price_change_dir){
					$product['Product']['lastpriceup_timestamp'] = time();
				}
			}
			if ($new_product){
				$product['Product']['add_timestamp'] = time();
				$product['Product']['lastpricedrop_timestamp'] = -1;
			}
			$product['Product']['lastpriceupdate_timestamp'] = time(); // this is more like last price check-n-update timestamp
			
			$result = $this->Product->save($product);
			
			
		}
		
		if (!empty($result))
		{
			$this->set('retval', 1);
		}
		
	}
	
	public function test_saveit_for_coupon()
	{
		$this->request->data['purl'] = "http://www.express.com/clothing/minus+the+leather+pieced+a+line+dress/pro/9347235C/saleWomen";
		$result = $this->saveit_for_coupon();
		$this->set('result', $result);
	}
	
	public function saveit_for_coupon()
	{
		$this->layout = 'ajax';
		
		//debug($this->request);
		$post_data = $this->request->data;
		$purl = $post_data['purl'];
		$user_email = $this->UserData->getUserEmail();
		$user_id = $this->UserData->getUserId();
		//debug($user_email);debug($user_id);
		
		$result = array ('success' => 0, 'msg' => '');
		
		if (empty($user_email) || empty($user_id))
		{
			$result['success'] = 0;
			$result['msg'] = 'Please login to save the item.';
		}
		else if (empty($purl))
		{
			$result['success'] = 0;
			$result['msg'] = 'URL to save not provided.';
		}
		else
		{
		
			$this->request->data['url'] = $purl;
			$this->request->data['decode_title'] = 1;
			$add_item_part2_result = $this->add_item_part2_internal();
			if ($add_item_part2_result && $add_item_part2_result['success']) 
			{ 
				$result['success'] = 1;
			}
			else
			{
				$result['msg'] = $add_item_part2_result['msg'];
			}
		}
		
		$this->set('result', $result);
		
		return $result;
		
	}
	
	public function trackit_mainpage_quick_auto()
	{
		$this->layout = 'ajax';
		$purl = $this->params->query['purl'];
		//debug($this->params->query);
		$user_email = $this->UserData->getUserEmail();
		
		$already_added = 0;
		
		if ($user_email && $purl)
		{
			$this->request->data['url'] = $purl;
			$this->request->data['decode_title'] = 1;
			//debug($this->request);
			$result = $this->add_item_part2_internal();
			//debug($result);
			if ($result && $result['success']) 
			{ 
				$already_added = 1;
			}
			//debug($result);
		}
		
		$this->set('user_email', $user_email);
		$this->set('already_added', $already_added);
	}
	
	public function update_group()
	{
		$this->layout = 'ajax';
		if(!($this->RequestHandler->isAjax()))
		{
			return null;
		}
		
		$post_data = $this->request->data;
		$itemid = $post_data['itemid'];
		$group_name = trim($post_data['group_name']);
		
		/*$itemid = 43;
		$group_name = 'watches';*/
		$group_name_now = $this->UserProduct->update_group($itemid, $group_name);
		$result = array();
		if ($group_name_now && ($group_name_now == $group_name))
		{
			$result['success'] = 1;
			$result['msg'] = "Thanks, we have moved the item to '". $group_name_now. "' collection.";
		}
		else
		{
			$result['success'] = 0;
			if ($group_name_now)
			{
				$result['msg'] = "Oops, looks like we couldn't switch item to '". $group_name. "' collection. You'll find it in your '". $group_name_now . "' collection at " . SITE_NAME;
			}
			else
			{
				$result['msg'] = "Oops, looks like we couldn't switch item to '". $group_name. "' collection. You'll find it in your saved collection at " . SITE_NAME;
			}
		}
		$this->set('result', json_encode($result));
		return $result;
	}
	
	public function add_item_part2()
	{
		if(!($this->RequestHandler->isAjax()))
		{
			return null;
		}
		$this->layout = 'ajax';
		
		$result = $this->add_item_part2_internal();
		$this->set('result', json_encode($result));
		return $result;
	}
	
	private function add_item_part2_internal()
	{
		$result = array ('success' => 0, 'msg'=>"");
		$this->set('result', json_encode($result));
		
		$post_data = $this->request->data;
		$purl = $post_data['url'];
		
		
		if (!empty($post_data['decode_title']))// && $post_data['decode_title'] == 1)
		{
			//document.referrer
			$command = PYTHON_SCRIPTS_FOLDER."get_title.py \"{$purl}\"";
			$output = array();
			//debug ($command);
			exec($command, $output);
			//debug($output);
			//$output = array("{\"points\": 2062, \"comments\": 211, \"views\": 162929}"	);
			//debug($output);
			if (!empty($output[0]) && $output[0] != 'Exception')
			{
				$output = json_decode($output[0]);
				//debug($output);
				if (!empty($output->title))
				{
					$pname = $output->title;
				}
				else
				{
					$pname = "";
				}
			}
		}
		else
		{
			$pname = $post_data['name'];
		}
		$pname = trim($pname);
		
		if (!empty($post_data['wait_price']))
		{
			$wait_price = $post_data['wait_price'];
		}
		else
		{
			$wait_price = 0;
		}
		
		$user_email = $this->UserData->getUserEmail();
		if (!empty($user_email))
		{
			$this->set('user_email', $user_email);
		}
		else
		{
			$user_email = $post_data['email'];
			// filter email data
			$user_email = filter_var($user_email, FILTER_VALIDATE_EMAIL);
		}
		
		
		/*
		if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) || $user_email == "" ) 
		{
			$user_email = null;
		}
		*/
		
		$product = array('Product'=>array());
		$product['Product']['purl'] = $purl;
		$product['Product']['name'] = $pname;
		
		$new_product = true;
		
		$product_id = 0;
		$product_info = $this->Product->findRawProductInfoByUrl($purl);
		if (empty($product_info))
		{
			$company_id = 0;
			$command = PYTHON_SCRIPTS_FOLDER."get_base_url.py \"{$purl}\"";
			#$command = PYTHON_SCRIPTS_FOLDER."get_features.py {$purl}";
			$output = array();
			//debug ($command);
			exec($command, $output);
			//debug($output);
			//$output = array("{\"points\": 2062, \"comments\": 211, \"views\": 162929}"	);
			//debug($output);
			if (!empty($output[0]) && $output[0] != 'Exception')
			{
				$output = json_decode($output[0]);
				//debug($output);
				if (!empty($output->url))
				{
					$company_website = $output->url;
					//debug($company_website);
					if (!$fp = curl_init($company_website)) // check if url is valid
					{
						return null;
					}
					
					$company = $this->Company->getRawCompanyInfoBySiteName($company_website);
					//debug($company);
					if (empty($company))
					{
						$added = $this->Company->AddFromWebsiteName($company_website);
						//debug($added);
						$company = $this->Company->getRawCompanyInfoBySiteName($company_website);
					}
					if (!empty($company['Company']['id']))
					{
						$company_id = $company['Company']['id'];
					}
				}
			}
			$product['Product']['company_id'] = $company_id;
			
			$product['Product']['cur_price'] = 0;
			$product['Product']['high_price'] = $product['Product']['cur_price'];
			$product['Product']['low_price'] = $product['Product']['cur_price'];
			$product['Product']['start_price'] = $product['Product']['cur_price'];
			$product['Product']['price_date_history'] = strval($product['Product']['cur_price']) . "," . strval(time());
			$product['Product']['add_timestamp'] = time();
			$product['Product']['lastpriceupdate_timestamp'] = time();
			
			$this->Product->create();
			if ($this->Product->save($product))
			{
				$product_id = $this->Product->id;
			}
		}
		else
		{
			$new_product = false;
			$product_id = $product_info['Product']['id'];
		}
		
		$user_id = $this->UserData->getUserId();
		if (empty($user_id) && empty($user_email))
		{
			return null;
		}
		
		if (!empty($product_id))
		{
			$user_product = null;
			if (empty($user_product) && !empty($user_id))
			{
				$user_product = $this->UserProduct->getFromUserIdProductId($user_id, $product_id);
			}
			
			if (empty($user_product) && !empty($user_email))
			{
				$user_product = $this->UserProduct->getFromUserEmailProductId($user_email, $product_id);
			}
			
			if (empty($user_product['UserProduct']))
			{
				$this->UserProduct->create();
				$user_product = array('UserProduct' => array());
				$user_product['UserProduct']['product_id'] = $product_id;
				$user_product['UserProduct']['uid_pid'] = $this->UserProduct->build_uid_pid_field($user_id, $product_id);
				$user_product['UserProduct']['group_name'] = 'Newly Added';				
			}
			else
			{
				$this->UserProduct->id = $user_product['UserProduct']['id'];
			}
			
			if (!empty($user_id))
			{
				$user_product['UserProduct']['user_id'] = $user_id;
			}
			if (!empty($user_email))
			{
				$user_product['UserProduct']['user_email'] = $user_email;					
			}
			$user_product['UserProduct']['wait_price'] = $wait_price;
			$user_product['UserProduct']['user_product_name']= $pname;
			//debug($user_product);
			$saved = $this->UserProduct->save($user_product);
			//debug($saved);
			if($saved)
			{
				$result['itemid'] = $this->UserProduct->id;
				$result['success'] = 1;
				$groups = $this->UserProduct->get_product_group_names($user_email);
				$result['groups'] = $groups;
				
				$this->BackendOp->add_job(Configure::read('GET_PROD_DETAIL'), array('url' => $purl));
				$this->BackendOp->add_job(Configure::read('GET_RELATED_PROD'), array('url' => $purl));				
			}
			else
			{
				$result['success'] = 0;
				$result['msg'] = "There was an issue with saving data. We don't expect this problem to show up. However, if this error persists, lets us know and we will fix things for better experience of all.";
			}
		}
		
		if ($new_product == true)
		{
			$max_exec_time = ini_get('max_execution_time');
			ini_set('max_execution_time', 120);
			
			$command = PYTHON_SCRIPTS_FOLDER."get_features.py \"{$purl}\"";
			$output = array();
			//debug ($command);
			exec($command, $output);
			//debug($output);
			//$output = array("{\"points\": 2062, \"comments\": 211, \"views\": 162929}"	);
			//debug($output);
			if (!empty($output[0]) && $output[0] != 'Exception')
			{
				//debug($output[0]);
				$output = json_decode($output[0]);
				//debug($output);
			}
			if ($output)
			{
				$product_info['Product']['image1'] = "";
				$product_info['Product']['cur_price'] = 0;
				$product_info['Product']['name'] = "";
				
				try{
					$product_info['Product']['image1'] = $output->pimg;	
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
				
				try{
					$product_info['Product']['cur_price'] = $output->price;
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
				
				try{
					$product_info['Product']['name'] = $output->title;
				} catch (Exception $e) {
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
				
				$product_info['Product']['high_price'] = $product_info['Product']['cur_price'];
				$product_info['Product']['low_price'] = $product_info['Product']['cur_price'];
				$product_info['Product']['start_price'] = $product_info['Product']['cur_price'];
				$product_info['Product']['price_date_history'] = strval($product_info['Product']['cur_price']) . "," . strval(time());
				$product_info['Product']['lastpriceupdate_timestamp'] = time();
					
				$product_saved = $this->Product->save($product_info);			
				
			}
			
			ini_set('max_execution_time', $max_exec_time);
			
			$this->enable_trigger_for_pending_jobs();
			
			/*			
			$command = PYTHON_SCRIPTS_FOLDER."trigger_pending_job_processing.py > phpout_trigger_pending_job_processing.txt 2>&1 & echo $!";
			$output = array();
			//debug ($command);
			exec($command, $output);
			*/
			
		}
		
		if (strpos($purl, "demopage.html") !== FALSE)
		{
			$result['success'] = 1;
		}
		
		$this->set('result', json_encode($result));
		
		return $result;
	}
	
	public function add_item_part1()
	{
		$this->layout = 'ajax';
		
		$title = " ";
		
		$user_email = $this->UserData->getUserEmail();
		if (!empty($user_email))
		{
			$info_for_second_part = array('title' => $title, 'user_email' => $user_email);
		}
		else
		{
			$info_for_second_part = array('title' => $title, 'user_email' => "");
		}
		
		$this->set('info_for_second_part', $info_for_second_part);
		
		
		/*if(!($this->RequestHandler->isAjax()))
		{
			return null;
		}*/
		
		$post_data = $this->request->data;
		//debug($post_data);
		
		$url = $post_data['url'];
		//debug($url);
		//return;
		
		/*
		$str = file_get_contents($url);
		//debug($str);
		if(strlen($str)>0){
			preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
			//debug($title);
			//debug($title[1]);
			$this->set('title', $title[1]);
		}
		*/
		
		/*$html = new simple_html_dom();
		$html->load_file($url); //put url or filename  
		if ($html)
		{
			$title = $html->find('title');
			if (!empty($title))
			{
				$this->set('title', $title);
			}
		}*/
		
		$command = PYTHON_SCRIPTS_FOLDER."get_title.py \"{$url}\"";
		$output = array();
			
		//debug ($command);
		exec($command, $output);
		//debug($output);
		//$output = array("{\"points\": 2062, \"comments\": 211, \"views\": 162929}"	);
		//debug($output);
		if (!empty($output[0]) && $output[0] != 'Exception')
		{
			$output = json_decode($output[0]);
			//debug($output);
			if (!empty($output->title))
			{
				$this->set('title', $output->title);
				$info_for_second_part['title'] = $output->title;
			}
		}
		
		$this->set('info_for_second_part', $info_for_second_part);
		
	}

/**
 * index method
 *
 * @return void
 */
	/*
	public function index() {
		$this->Product->recursive = 0;
		$this->set('products', $this->Paginator->paginate());
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
	}
	*/

	public function view_by_company($company_id = null) {
		if (!$this->Company->exists($company_id)) {
			throw new NotFoundException(__('Invalid company'));
		}
		
		$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $company_id));
		$this->set('company', $this->Company->find('first', $options));
		
		$this->set('products', $this->Product->getRawProductsByCompanyId($company_id));
		
		$topic_data = $this->Topic->getTopicList();
		$this->set('topic_data', $topic_data);
	}
	

}