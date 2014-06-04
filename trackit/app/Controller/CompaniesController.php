<?php
App::uses('AppController', 'Controller');
/**
 * Companies Controller
 *
 * @property Company $Company
 * @property PaginatorComponent $Paginator
 */
class CompaniesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Paginator', 
		'RequestHandler',
		'Auth',
		'UserData',
		'EmailAccess'
		);
	
	public $uses = array ('Company', 'User', 'Content', 'Topic', 'OpenCampaign', 'OcCondition', 'Condition', 'Goal', 'TrackerInfo', 'Product');

	
	function beforeFilter()
	{
		$this->Auth->autoRedirect = false;
		$this->Auth->allow('add');
		$this->Auth->allow('get_open_campaigns');
		$this->Auth->allow('get_list_for_parsing');
		$this->Auth->allow('get_cinfo_for_parsing');
		$this->Auth->allow('index');
		$this->Auth->allow('search_by_website');
		$this->Auth->allow('user_view');
		$this->Auth->allow('coupons');
		$this->Auth->allow('all_view');
		$this->Auth->allow('show_shop');
		$this->Auth->allow('view');
		$this->Auth->allow('add');
		$this->Auth->allow('edit');
		$this->Auth->allow('delete');
		$this->Auth->allow('merchant_suggestions');
		parent::beforeFilter();
		
	}
	
	public function delete($id = null) {
		$this->only_admin_can_see();
		
		$this->Company->id = $id;
		if (!$this->Company->exists()) {
			throw new NotFoundException(__('Invalid Company'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Company->delete()) {
			$this->Session->setFlash(__('Company deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Company was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
	
	public function get_open_campaigns($company_id = null) {
		
		//if ( !($this->UserData->isUserLoggedIn()) || !($this->UserData->getCompanyId()) ) { return null;}
		if ( !($this->UserData->isUserLoggedIn()) ) { return null; }
		
		if (!$company_id)
		{
			$company_id = $this->UserData->getCompanyId();
		}
		

		$ocs = $this->OpenCampaign->getActiveCampaignsByCompanyId($company_id);
		$content_data = $this->Content->get_active_content_by_company_id($company_id);
		$this->set('contents', $content_data);
		
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
		
		$condition_data = $this->Condition->getConditionList();
		
		$this->set('oc_conditions', $oc_sorted_conditions);
		$this->set('condition_data', $condition_data);
		$this->set('ocs', $ocs);
		$this->set('ocids', $ocids);
		
		$this->set('company', $this->Company->getRawCompanyInfo($company_id));
		
		$products = $this->Product->getRawProductsByCompanyId($company_id);
		$product_data = $this->Product->compactProductData($products);
		$this->set('product_data', $product_data);
		
		//$this->set('goals', $this->Goal->getGoalList());
		
	}

	public function get_cinfo_for_parsing()
	{
		$this->layout = 'ajax';
		$this->set('name',"");
		$this->set('website',"");
			
		$cid = $this->request->data['cid'];
		$pcode = $this->request->data['pcode'];
		if (empty($cid)||empty($pcode)){return;}
		
		$this->ProdAPI = $this->Components->load('ProdAPI');
		$has_access = $this->ProdAPI->has_python_access($pcode);
		if (!$has_access){return;}
		
		$company = $this->Company->getRawCompanyInfo($cid);
		if (!empty($company)){
			$this->set('name',$company['Company']['name']);
			$this->set('website',$company['Company']['website']);
		}
		return;
	}
	
	public function get_list_for_parsing($python_code)
	{
		if ($python_code == Configure::read ('PYTHON_VERIFICATION_CODE'))
		{
			$product_data = $this->Product->getProductList();
			$this->set('product_data', $product_data);
			
			$company_data = $this->Company->getCompanyList();
			$this->set('company_data', $company_data);
			
			$this->set('content_data', $this->Content->get_all_content());
		}
		else
		{
			$this->set('hide', true);
		}
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->only_admin_can_see();
		
		$this->Company->recursive = 0;
		$this->set('companies', $this->Paginator->paginate());

		$topic_data = $this->Topic->getTopicList();
		$this->set('topic_data', $topic_data);
	
	}
	
	public function search_by_website(){
		
		$this->only_admin_can_see();
		if($this->request->ispost()){
			$data = $this->request->data;
			$company_website = $data['Company']['company_website'];
			
			$company = $this->Company->getRawCompanyInfoBySiteName($company_website);
			if (empty($company)){
				$this->Session->setFlash("No match found for {$company_website}.");
			}else{
				$this->set('company', $company);
			}
		}else{
			$this->set('company', false);
		}
	}
	
	public function coupons() {
		
		$this->paginate = array(
			'recursive'=>-1,
			'conditions'=>array('enabled'=>1)
		);
		
		$companies = $this->Company->getBrosableCompaniesList();
		
		$this->set('companies', $companies);
		
		$this->set('title_for_layout', 'Find coupons from awesome companies');
		
		//$topic_data = $this->Topic->getTopicList();
		//$this->set('topic_data', $topic_data);
	
	}
	
	
	public function user_view() {
		
		$this->paginate = array(
			'recursive'=>-1,
			'conditions'=>array('enabled'=>1)
		);
		
		$company_ids = $this->TrackerInfo->get_company_ids_with_fast_tracker();
		/*
		$trackers = $this->TrackerInfo->find('all', array('recursive' => -1, 'conditions' => array ('urllib2_pimg_xpath1 !=' => "")));
		$company_ids = array();
		foreach ($trackers as $index => $tracker)
		{
			$company_ids[] = $tracker['TrackerInfo']['company_id'];
		}
		*/
		$this->set('companies', $this->Company->getBrosableCompaniesList($company_ids));//$this->Paginator->paginate());

		$topic_data = $this->Topic->getTopicList();
		$this->set('topic_data', $topic_data);
	
	}
	
	public function all_view() {
		
		$this->paginate = array(
			'recursive'=>-1,
			'conditions'=>array('enabled'=>1)
		);
		$this->set('companies', $this->Company->getBrosableCompaniesList());//$this->Paginator->paginate());

		$topic_data = $this->Topic->getTopicList();
		$this->set('topic_data', $topic_data);
	
	}
	
	public function show_shop() {
		
		if(!empty($this->params['url']['shop'])) 
		{
			$encoded_url = $this->params['url']['shop'];
			$this->set('shopurl', urldecode($encoded_url));
		}
	}
	
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($company_id = null) {
		
		$this->only_admin_can_see();
		
		if (!$this->Company->exists($company_id)) {
			throw new NotFoundException(__('Invalid company'));
		}
		
		$this->set('company', $this->Company->getRawCompanyInfo($company_id));
		
		$products = $this->Product->getRawProductsByCompanyId($company_id);
		$product_data = $this->Product->compactProductData($products);
		$this->set('product_data', $product_data);
				
		$content_data = $this->Content->get_by_company_id($company_id);
		$this->set('content_data', $content_data);
		
		$topic_data = $this->Topic->getTopicList();
		$this->set('topic_data', $topic_data);
		
	}
	
	
/**
 * add method
 *
 * @return void
 */
	public function add() 
	{
		
		$result = array ('success' => 0, 'msg' => 0);
		
		$is_ajax = $this->RequestHandler->isAjax();
		$this->set('is_ajax' , $is_ajax);
		
		if ($is_ajax)
		{
			$this->layout = 'ajax';
		}
		
		$company_id = $this->UserData->getCompanyId();
		if ($company_id)
		{
			$campaign_add_url = SITE_NAME . "open_campaigns/add";
			if ($is_ajax)
			{
				$company = $this->UserData->getCompanyData();
				$company_name = $company['Company']['name'];
				$result['success'] = 0;
				$result['msg'] = "You are already logged in as <b>{$company_name}</b>. <a style='color:white;' href=\"{$campaign_add_url}\"> Proceed to create your campaign ?</a>";
				$this->set('result', $result);
			}
			else
			{
				$this->redirect($campaign_add_url);
				//$this->redirect(array('controller'=>'open_campaigns', 'action' => 'add'));
			}
			return null;
		}
		
		if ($this->UserData->isUserLoggedIn()) 
		{ 
			$add_url = SITE_NAME . "companies/add";
			$logout_and_add_url = SITE_NAME . "users/logout_ajax?next=".urlencode($add_url);
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = "You are already logged in. <a style='color:white;' href=\"{$logout_and_add_url}\"> Logout before Add company.</a>";
				$this->set('result', $result);
			}
			else
			{
				$this->redirect($logout_and_add_url);
			}
			return null;
		}
		
		
		$error_msg = "";
		
		if ($this->request->is('post')) 
		{
	
			$error_msg = null;
			
			$this->Company->create();
			$company = $this->request->data;
			if (!$is_ajax)
			{
				$company_name = $company['Company']['name'];
				$website = $company['Company']['website'];
				$phone = $company['Company']['phone'];
				$email = $company['Company']['email'];
				$password1 = $company['Company']['password1'];
				$password2 = $company['Company']['password2'];
			}
			else
			{
				$company_name = $company['company_name'];
				$website = $company['website'];
				$phone = $company['phone'];
				$email = $company['email'];
				$password1 = $company['password1'];
				$password2 = $company['password2'];
				$company = array (
						'Company' => array(
							'name' => $company_name,
							'website' => $website,
							'phone' => $phone,
							'email' => $email,
							'password1' => $password1,
							'password2' => $password2
						)
					);
			}
			//debug($company);
						
			if (empty($company['Company']['name']))
			{
				$error_msg = 'Please provide name of your company.';
			}
			
			if (empty($company['Company']['website']))
			{
				$error_msg = 'Please provide website of your company.';
			}
			
			if (empty($company['Company']['phone']))
			{
				$error_msg = 'Please provide a phone number for your company.';
			}
			
			$company['Company']['email'] = filter_var($company['Company']['email'], FILTER_VALIDATE_EMAIL);
			if (empty($company['Company']['email']))
			{
				$error_msg = 'Please check your email address.';
			}
			
			else if (empty($company['Company']['password1']))
			{
				$error_msg = 'Please enter your password.';
			}
			
			else if (strlen($company['Company']['password1']) < 6)
			{
				$error_msg = 'Password is too small. It must be at least 6 characters.';
			}
			
			else if (empty($company['Company']['password2']))
			{
				$error_msg = "Please re-type passwords.";
			}
			
			else if ($company['Company']['password1'] != $company['Company']['password2'])
			{
				$error_msg = 'Your passwords do not match. Please re-enter your passwords.';
			}
			else 
			{
				$already_present_user = $this->User->findUserByEmail($email);
				if (!empty($already_present_user['id']))
				{
					$error_msg = "This email is already in use.";
				}
			}
			
			
			if ($error_msg != "")
			{
				$result['success'] = 0;
				$result['msg'] = $error_msg;
			}
			else
			{
				$user_created = $this->User->CreateUserWithEmailAndPassword($company['Company']['email'], $company['Company']['password1'], 'company', 1, "");
				if (!$user_created['success'])
				{
					$result['success'] = 0;
					$result['msg'] = $user_created['msg'];
				}
				else
				{
					//debug($user_created);
					$newuser = $user_created['user_data'];
					$this->UserData->setLoginData($newuser['User']);
					
					$company_info_linked = $this->Company->GetOrAddCompany($company['Company']['name'], $company['Company']['website'], $company['Company']['email'], $company['Company']['phone']);
					//debug($company_info_linked);
					
					
					$company = $this->Company->getRawCompanyInfoByEmail($company['Company']['email']);
					$this->UserData->setCompanyData($company);
					
					$campaign_add_url = SITE_NAME . "open_campaigns/add";
					$result['success'] = 1;
					$result['msg'] = "Thank you for registering with us. For any questions regarding how to use this platform of social coupons, feel free to drop us an email or call at anytime. <a style='color:white;' href=\"{$campaign_add_url}\"> Create your first campaign.</a>";
					
				}
			}
			
			//debug ($company);
			if (0 == $result['success'])
			{
				if (!$is_ajax)
				{
					$this->Session->setFlash($result['msg']);
				}
			}
			else
			{
				if (!$is_ajax)
				{
					$this->redirect(array('controller'=>'contents', 'action' => 'add'));
					return null;
				}
				
			}
			
		}

		$this->set('result', $result);
		return null;
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		
		$is_admin = $this->UserData->isAdmin();
		$this->set('is_admin', $is_admin);
		
		if (empty($is_admin)){
			$id = $this->UserData->getCompanyId();
		}
		// If admin, then take ID from the post request.
		else{
			if(!empty($this->request->data['Company']['id'])){
				$id=$this->request->data['Company']['id'];
			}
		}
		
		if(empty($id)){
			$this->Session->setFlash(__('Please login as a business to edit its information on file.'));
		}
		
		if (!$this->Company->exists($id)) {
			throw new NotFoundException(__('Invalid company'));
			return;
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if (!$is_admin){$this->request->data['Company']['id'] = $id;}
			
			if ($this->Company->save($this->request->data)) {
				$this->Session->setFlash(__('Your company profile is updated.'));
				return $this->redirect(array('action' => 'edit', $id));
			}
			$this->Session->setFlash(__('The company could not be saved. Please, try again.'));
		} else {
			$options = array('recursive' => -1, 'conditions' => array('Company.' . $this->Company->primaryKey => $id));
			$this->request->data = $this->Company->find('first', $options);
		}
		
		$topic_data = $this->Topic->getTopicList();
		$this->set('topic_data', $topic_data);
		
	}
	
	public function merchant_suggestions()
	{
	
		$is_ajax = $this->RequestHandler->isAjax();
		$is_post = $this->RequestHandler->isPost();
		
		if ($is_ajax && $is_post)
		{
			$this->layout='ajax';
			$result = array('success' => 0, 'msg' => "");
			
			$this->set('show_form', false);
			if (!empty($this->request->data['name']))
			{
				$name = $this->request->data['name'];
				$info = "";
				$website = "";
				$user_email = "";
				
				if (!empty($this->request->data['info']))
				{
					$info = $this->request->data['info'];
				}
				if (!empty($this->request->data['website']))
				{
					$website = $this->request->data['website'];
				}
				
				if ($this->UserData->isUserLoggedIn())
				{
					$user_email = $this->UserData->getUserEmail();
					if (empty($user_email))
					{
						$user_email = "";
					}
				}
				
				$email_sent = $this->EmailAccess->shoot_email_for_merchant_suggestion($name, $website, $info, $user_email);
				if (!empty($email_sent))
				{
					$result['success'] = 1;
					$result['msg'] = 'Thank you very much for your suggestion. We will get in touch with the merchant for coupons.';
				}
			}
			
			$this->set('result', $result);
		}
		else
		{
			$this->set('show_form', true);
		}
		
		$this->set('title_for_layout', 'Suggest us a merchant that you like and want coupon of.');
		
	}
	
}