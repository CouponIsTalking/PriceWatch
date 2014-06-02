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
	
	public function update_prod_info_from_script($python_code)
	{
		$retval = 0;
		$this->set('retval', 0);
		
		if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			$post_data = $this->request->data;
			
			$prod_url = $post_data['prod_link'];
			$product = $this->Product->findRawProductInfoByUrl($prod_url);
			
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
			}
			
			if (empty($product['Product']['name']))
			{
				$product['Product']['name'] = $post_data['title'];
			}
			$product['Product']['cur_price'] = $post_data['price'];
			
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
			debug($this->request);
			$result = $this->add_item_part2_internal();
			debug($result);
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
		debug($str);
		if(strlen($str)>0){
			preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
			debug($title);
			debug($title[1]);
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
	public function view_by_company($company_id = null) {
	/*
		$this->Product->recursive = 0;
		$this->set('products', $this->Paginator->paginate());
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
	*/
		if (!empty($company_id))
		{
			$products = $this->Product->getRawProductsByCompanyId($company_id);
			$this->set('raw_products', $products);
			$raw_comp_info = $this->Company->getRawCompanyInfo($company_id);
			$this->set('raw_comp_info', $raw_comp_info);
		}
		else
		{
			$companies = $this->Company->getCompanyList();
			$this->set('companies', $companies);
		}
		
		
	}

/*
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
*/	

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Product->exists($id)) {
			throw new NotFoundException(__('Invalid product'));
		}
		$options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
		$this->set('product', $this->Product->find('first', $options));
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Product->create();
			if ($this->Product->save($this->request->data)) {
				$this->Session->setFlash(__('The product has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The product could not be saved. Please, try again.'));
		}
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Product->exists($id)) {
			throw new NotFoundException(__('Invalid product'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Product->save($this->request->data)) {
				$this->Session->setFlash(__('The product has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The product could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
			$this->request->data = $this->Product->find('first', $options);
		}
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Product->delete()) {
			$this->Session->setFlash(__('Product deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Product was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Product->recursive = 0;
		$this->set('products', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Product->exists($id)) {
			throw new NotFoundException(__('Invalid product'));
		}
		$options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
		$this->set('product', $this->Product->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Product->create();
			if ($this->Product->save($this->request->data)) {
				$this->Session->setFlash(__('The product has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The product could not be saved. Please, try again.'));
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
		if (!$this->Product->exists($id)) {
			throw new NotFoundException(__('Invalid product'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Product->save($this->request->data)) {
				$this->Session->setFlash(__('The product has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The product could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
			$this->request->data = $this->Product->find('first', $options);
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
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Product->delete()) {
			$this->Session->setFlash(__('Product deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Product was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}