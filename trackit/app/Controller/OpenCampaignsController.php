<?php
App::uses('AppController', 'Controller');
/**
 * OpenCampaigns Controller
 *
 * @property OpenCampaign $OpenCampaign
 * @property PaginatorComponent $Paginator
 */
class OpenCampaignsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $uses = array ('OpenCampaign', 'Company', 'Product', 'Condition', 'OcCondition', 'Content', 'UserCoupon');
	
	var $helpers = array('Html');//,'Javascript');
    var $components = array(
		'Paginator', 
		'RequestHandler', 
		'UserData'		
		);
	
	public function company_has_coupon($coupon_code)
	{
		$this->layout = 'ajax';
		
		$result = array('success'=>0, 'msg'=>'', 'is_present'=>0, 'where'=>'');
		$this->set('result', $result);
		
		$isajax = $this->RequestHandler->isAjax();
		
		//if (!$isajax){return $result;}
		
		$logged_in_company_id = $this->UserData->getCompanyId();
		if (empty($logged_in_company_id))
		{
			$result['msg'] = 'Please login as a business to continue.';
		}
		else
		{
			$is_present = $this->OpenCampaign->coupon_exists($coupon_code, $logged_in_company_id);
			if ($is_present)
			{
				$result['is_present'] = $is_present;
				$result['where'] = 'oc';
			}
			else
			{
				$is_present = $this->Content->coupon_exists($coupon_code, $logged_in_company_id);
				if ($is_present)
				{
					$result['is_present'] = $is_present;
					$result['where'] = 'content';
				}
			}
			
			if ($is_present)
			{
				$result['msg'] = "This coupon code ({$coupon_code}) is already used by you.";
			}
			else
			{
				$result['msg'] = 'Coupon code not used in the past.';
			}
		
		}
		
		$this->set('result', $result);
	}
	
	public function running_campaigns()
	{
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
		$this->set('companies', $this->Company->getCompanyList());
		//$this->set('products', $this->Product->getProductList());
		$this->set('title_for_layout', 'Discover Awesome Coupons');
	}
	
	public function get_open_campaign($oc_id)
	{
		
		$userid = $this->UserData->getUserId();

		if (!empty($this->request->query['layout']) 
			&& 'popup' == $this->request->query['layout']
			)
		{
			$this->layout='oc_popup';
			$this->set('layout_var', 'popup');
		}
			
		if (!empty($userid))
		{
			$this->set('is_user_logged_in', 1);
		}
		else
		{
			$this->set('is_user_logged_in', 0);
		}
		
		//if ( !($this->UserData->isUserLoggedIn()) || !($this->UserData->getCompanyId()) ) { return null;}
		//if ( !($this->UserData->isUserLoggedIn()) ) { return null; }
		
		$ocs = $this->OpenCampaign->getCampaignsByOCIds(array($oc_id));
		if (empty($ocs[0]['OpenCampaign']))
		{
			return null;
		}
		$company_id = $ocs[0]['OpenCampaign']['company_id'];
		$coupon_code= $ocs[0]['OpenCampaign']['coupon_code'];
		
		$approved_content_ids_ary = split(',', trim($ocs[0]['OpenCampaign']['approved_content_ids']));
		
		//debug($approved_content_ids_ary);
		//$ocs = $this->OpenCampaign->getActiveCampaignsByCompanyId($company_id);
		//$content_data = $this->Content->get_active_content_by_company_id($company_id);
		$content_data = $this->Content->get_active_content_by_ids($approved_content_ids_ary);
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
		
		/*
		$products = $this->Product->getRawProductsByCompanyId($company_id);
		$product_data = $this->Product->compactProductData($products);
		$this->set('product_data', $product_data);
		*/
		
		$userid_company_id_coupon_code = $this->UserCoupon->create_entry_name($userid, $company_id, $coupon_code);
		
		$is_coupon_open = $this->UserCoupon->is_userid_company_coupon_present($userid_company_id_coupon_code);

		$this->set('is_coupon_open', $is_coupon_open);
		$this->set('title_for_layout', 'Unlock Awesome Coupons');
		//$this->set('goals', $this->Goal->getGoalList());
		
		$plat_type = $ocs[0]['OpenCampaign']['type'];
		if ('single_email_ns_signup' == $plat_type
			|| 'dual_email_ns_signup' == $plat_type
			)
		{
			$user_email = $this->UserData->getUserEmail();
			if (empty($user_email))
			{
				$user_email = "";
			}
			$this->set('user_email', $user_email);
		}
		
		if ('fb_post_video'==$plat_type){
			$vid_url = $ocs[0]['OpenCampaign']['default_link'];
			$this->Vid = $this->Components->load('Vid');
			$vid_type = $this->Vid->vid_type($vid_url);
			$this->set('vid_type', $vid_type);
			
			if ('youtube' == $vid_type){
				$this->helpers[] = 'Youtube';
			}else if ('vimeo' == $vid_type){
				$this->helpers[] = 'Vimeo';
			}
		}
	}
	
	public function create_resp($oc_id)
	{
		$ocdata	= $this->OpenCampaign->getCampaignsByOCIds($oc_id);
		$company_id = $ocdata[0]['OpenCampaign']['company_id'];
		$company = $this->Company->getRawCompanyInfo($company_id);
		$contents = $this->Content->get_by_company_id($company_id);
		
		$this->set('ocdata', $ocdata[0]);
		$this->set('company', $company);
		$this->set('contents', $contents);
		return;
	}
	
	public function details ($oc_id)
	{
		if ($this->RequestHandler->isAjax())
		{
			$this->layout = 'ajax';
		}
		
		$ocdata	= $this->OpenCampaign->getCampaignsByOCIds($oc_id);
		$company_id = $ocdata[0]['OpenCampaign']['company_id'];
		$product_id = $ocdata[0]['OpenCampaign']['product_id'];
		$company = $this->Company->getRawCompanyInfo($company_id);
		
		$resp = array();
		$resp['oc_id'] = $ocdata[0]['OpenCampaign']['id'];
		$resp['active'] = $ocdata[0]['OpenCampaign']['active'];
		$resp['start_date'] = $ocdata[0]['OpenCampaign']['start_date'];
		$resp['type'] = $ocdata[0]['OpenCampaign']['type'];
		$resp['company_name'] = $company['Company']['name'];
		
		$product = null;
		if ($product_id != 0)
		{
			$product = $this->Product->getRawProductInfo($product_id);
			$resp['product_name'] = $product['Product']['name'];
			$resp['is_for_company'] = 0;
		}
		else
		{
			$resp['product_name'] = null;
			$resp['is_for_company'] = 1;
		}
		
		$occons = $this->OcCondition->getRawConditionsFromOCId($oc_id);
		$condition_data = $this->Condition->getConditionList();
		
		$resp['oc_data'] = $ocdata[0]['OpenCampaign'];
		
		$resp['conditions'] = array();
		foreach ($occons as $index => $occon)
		{
			$con_id = $occon['OcCondition']['condition_id'];
			$param1 = $occon['OcCondition']['param1'];
			$param2 = $occon['OcCondition']['param2'];
			$offer_type = $occon['OcCondition']['offer_type'];
			$offer_worth = $occon['OcCondition']['offer_worth'];
			$max_count = $occon['OcCondition']['max_count'];
			$met_so_far = $occon['OcCondition']['met_so_far'];
			if (empty($con_id) || $con_id == 0)
			{
				continue;
			}
			$resp['conditions'][] = array(
									'con_name' => $condition_data[$con_id]['name'], 
									'param1' => $param1, 'param2' => $param2, 
									'offer_type' => $offer_type, 
									'offer_worth' => $offer_worth,
									'max_count' => $max_count,
									'met_so_far' => $met_so_far
									);
		}
		
		$this->set('encoded_resp', json_encode($resp));
		return $resp;
	
	}
	
	/*
	public function activate($oc_id)
	{
	
		if ($this->RequestHandler->isAjax())
		{
			$this->layout = 'ajax';
		}
		
		$result = $this->OpenCampaign->activate($oc_id, true);
		$this->set('result', $result);
		return $result;
	}
	*/
	
	function edit_activation()
	{
		$result = 0;
			
		$this->layout = 'ajax';
				
		$new_val = $this->request->data['new_val'];
		$oc_id = $this->request->data['oc_id'];
		//$new_val = '1';
		//$oc_id = '39';
		
		$company_id = $this->UserData->getCompanyId();
		$is_admin = $this->UserData->isAdmin();
		
		$this->set('result', $result);
		
		if (empty($company_id) && !$is_admin)
		{
			return $result;
		}
		
		if (1 == intval($new_val))
		{
			$result = $this->OpenCampaign->activate($oc_id, true, $company_id, $is_admin);
		}
		else if (0 == intval($new_val))
		{
			$result = $this->OpenCampaign->activate($oc_id, false, $company_id, $is_admin);
		}
		
		
		if ($result)
		{
			$this->set('result', 1);
		}
		else
		{
			$this->set('result', 0);
		}
		return $result;
	}
	
/**
 * index method
 *
 * @return void
 */
	public function index($company_id = null) {
		
		if ( !($this->UserData->isUserLoggedIn()) )
		{
			$msg = 'Looks like you are not logged in. Please login as a company to see list of your campaigns.';
			$msg_type = 'error_msg';
			$this->Session->setFlash(__($msg));
			$this->show_modal_msg_on_blank_page($msg, $msg_type);
			return null;
		}
		
		$is_admin = false;		
		
		$is_admin = $this->UserData->isAdmin();
		$logged_in_company_id = $this->UserData->getCompanyId();
		
		if (empty($logged_in_company_id))
		{
			$this->set('show_comp_name', true);
			$this->set('comp_name', false);
		}
		else
		{
			$this->set('show_comp_name', false);
			$company = $this->UserData->getCompanyData();
			$this->set('comp_name', $company['Company']['name']);
			$company_id = $logged_in_company_id;		
		}		
		
		if (!$is_admin && !$company_id)
		{
			$msg = 'Looks like you are not logged in. Please login as a company to see list of your campaigns.';
			$msg_type = 'error_msg';
			$this->Session->setFlash(__($msg));
			$this->show_modal_msg_on_blank_page($msg, $msg_type);
			return null;
		}
	
		$this->OpenCampaign->recursive = 0;
		
		$company_data = array();
		
		if ($is_admin)
		{
			$company_data = $this->Company->getCompanyList();
		}
		
		if (!$company_id)
		{
			//$product_data = $this->Product->getProductList();			
			$oc_paginated = $this->Paginator->paginate();
		}
		else
		{
			//$raw_product_data = $this->Product->getRawProductsByCompanyId($company_id);
			//$product_data = $this->Product->compactProductData($raw_product_data);
			if (empty($company_data))
			{
				$raw_company_data = $this->Company->getRawCompanyInfo($company_id);
				$company_data = $this->Company->compactData(array($raw_company_data));
			}
			
			$paginate = array(
				'conditions' => array(
					'OpenCampaign.company_id' => $company_id
				),
				'recursive' => -1
			);
			$pre_settings = $this->Paginator->settings;
			$this->Paginator->settings = $paginate;
			$oc_paginated = $this->Paginator->paginate();
			$this->Paginator->settings = $pre_settings;
			
		}
		
		//$this->set('product_data', $product_data);
		$this->set('company_data', $company_data);
		$this->set('openCampaigns', $oc_paginated);	
		$this->set('is_admin', $is_admin);
	}

	
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->OpenCampaign->exists($id)) {
			throw new NotFoundException(__('Invalid open campaign'));
		}
		$product_data = $this->Product->getProductList();
		$this->set('product_data', $product_data);
			
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
			
		$options = array('conditions' => array('OpenCampaign.' . $this->OpenCampaign->primaryKey => $id));
		$this->set('openCampaign', $this->OpenCampaign->find('first', $options));
	}
	

/**
 * add method
 *
 * @return void
 */
	public function add() {
		
		if ( !($this->UserData->isUserLoggedIn()) || !($this->UserData->getCompanyId()) ) 
		{
			$msg = 'Please login as a company to create an advertising campaign.';
			$msg_type = 'error_msg';
			$this->Session->setFlash(__($msg));
			$this->show_modal_msg_on_blank_page($msg, $msg_type);
			return null;
		}
		
		$company_id = $this->UserData->getCompanyId();
		
		if ($this->request->is('post')) {
			$this->OpenCampaign->create();
			//debug($this->request->data);
			//return null;
			$safe = true;
			
			if (empty($this->request->data['OpenCampaign']))
			{
				$this->Session->setFlash(__('Please fill all the details.'));
				$safe = false;
			}
			
			$ocdata = $this->request->data['OpenCampaign'];
			$ocdata['company_id'] = $company_id;
			$start_now_aswell = $ocdata['start_now_aswell'];
			$approved_content_ids_str = $ocdata['approved_content_ids'];
			
			$default_title = $ocdata['default_title'];
			$default_link = $ocdata['default_share_link'];
			$default_desc = $ocdata['default_share_desc'];
			$default_fbpageurl = $ocdata['default_fbpageurl'];
			$default_fbeventurl = $ocdata['default_fbeventurl'];
			$default_linkforpost = $ocdata['default_linkforpost'];
			$email_signup_ways = $ocdata['email_signup_ways'];
				
			$ocdata['coupon_worth'] = str_replace(array(" ", "$"), array("",""), $ocdata['coupon_worth']);
			
			$ocdata['condition1_offer_worth'] = trim($ocdata['coupon_worth']); //intval($ocdata['condition1_offer_worth']);
			
			$ocdata['condition1'] = trim($ocdata['condition1']);
			
			if ((11 == $ocdata['condition1']) && empty($default_title))
			{
				$this->Session->setFlash(__('Please enter default Facebook post title.'));
				$safe = false;
			}
			
			if ((11 == $ocdata['condition1']) && empty($default_link))
			{
				$this->Session->setFlash(__('Please enter the link that you would like to share with Facebook Post.'));
				$safe = false;
			}
			
			if ((11 == $ocdata['condition1']) && empty($default_desc))
			{
				$this->Session->setFlash(__('Please enter 2-3 lines of description that you would like to share on Facebook.'));
				$safe = false;
			}
			
			if ((12 == $ocdata['condition1']) && empty($default_desc))
			{
				$this->Session->setFlash(__('Please enter the tweet that you want people to share share.'));
				$safe = false;
			}
			
			if ((11 == $ocdata['condition1'] || 12 == $ocdata['condition1'] || 13 == $ocdata['condition1']) && empty($approved_content_ids_str))
			{
				$this->Session->setFlash(__('Please choose allowed images, news or press material that people should promote.'));
				$safe = false;
			}
			
			if (14 == $ocdata['condition1'])
			{
				if (!empty($default_fbpageurl))
				{
					//App::uses('FBInfComponent', 'Controller/Component');
					//$fb_inf = new FBInfComponent();//$this->FBInf;
					$this->FBInf = $this->Components->load('FBInf'); 
					$page_props = $this->FBInf->get_fb_page_props($default_fbpageurl);
					//debug($page_props);
					
					if (empty($page_props['id']))
					{
						$this->Session->setFlash(__('Please check your facebook page url.'));
						$safe = false;
					}
					else
					{
						$default_link = $default_fbpageurl;
						$default_desc = array('id' => $page_props['id']);
						$default_desc = json_encode($default_desc);
					}
				}
				else
				{
					$this->Session->setFlash(__('Please enter facebook page url to like.'));
					$safe = false;
				}
			}
			
			if (16 == $ocdata['condition1'])
			{
				if (!empty($default_linkforpost))
				{
					$this->Vid = $this->Components->load('Vid');
						
					$link_breakdown = parse_url($default_linkforpost);
					$host = $link_breakdown['host'];
					$host = strtolower($host);
					
					$has_youtube = $this->Vid->is_youtube($default_linkforpost);//strpos($host, 'youtube.com');
					$has_vimeo = $this->Vid->is_vimeo($default_linkforpost);//strpos($host, 'vimeo.com');
					
					// NOTE: === is important here
					/*if ( FALSE === $has_youtube 
						&& FALSE === $has_vimeo
						)*/
					if (!($has_youtube||$has_vimeo))
					{
						$this->Session->setFlash(__('Please enter a Youtube or Vimeo Url.'));
						$safe = false;
					}
					else
					{
						$default_link = $default_linkforpost;
						$thumbnail = $this->Vid->get_large_thumbmail_src($default_link);
						$desc = array(
							'link' => $default_linkforpost,
							'thumbnail' => $thumbnail
						);
						$default_desc = json_encode($desc);//$default_linkforpost;
					}
				}
				else
				{
					$this->Session->setFlash(__('Please enter Youtube or Vimeo video URL that people should post on Facebook to earn coupon.'));
					$safe = false;
				}
			}
			
			if (17 == $ocdata['condition1'])
			{
				//debug($email_signup_ways);
				if ('single_email_ns_signup' != $email_signup_ways
					&& 'dual_email_ns_signup' != $email_signup_ways
					)
				{
					$this->Session->setFlash(__('Please choose a sign up method.'));
					$safe = false;
				}
			}
			
			if (18 == $ocdata['condition1'] || 19 == $ocdata['condition1'])
			{
				if (!empty($default_fbeventurl))
				{
					$default_link = $default_fbeventurl;
					$default_desc = $default_fbeventurl;
					/*
					$this->FBInf = $this->Components->load('FBInf'); 
					$page_props = $this->FBInf->get_fb_page_props($default_fbpageurl);
					//debug($page_props);
					
					if (empty($page_props['id']))
					{
						$this->Session->setFlash(__('Please check your facebook page url.'));
						$safe = false;
					}
					else
					{
						$default_link = $default_fbpageurl;
						$default_desc = array('id' => $page_props['id']);
						$default_desc = json_encode($default_desc);
					}
					*/
				}
				else
				{
					$this->Session->setFlash(__('Please enter your facebook event url.'));
					$safe = false;
				}
			}
			
			if (20 == $ocdata['condition1'])
			{
				// if it is a give-away coupon, then pass everything
			}
			
			if (21 == $ocdata['condition1'])
			{
				// if it is a yelp review coupon
			}
			
			if (empty($ocdata['company_id']) 
				|| empty($ocdata['plat'])
				)
			{
				$this->Session->setFlash(__('Please fill all the details.'));
				$safe = false;
			}
			
			$plat = $ocdata['plat'];
			if ( $plat != 'blog' 
				&& $plat != 'tweet' 
				&& $plat != 'fb_post' 
				&& $plat != 'reddit' 
				&& $plat != 'imgur'
				&& $plat != 'signup'
				&& $plat != 'review'
				&& $plat != 'giveaway')
			{
				$this->Session->setFlash(__('We currently support promotions via Facebook, Twitter, Newsletter or Give-away only.'));
				$safe = false;
			}
			else
			{
				$con1 = $ocdata['condition1'];
				if (11 == $con1)
				{
					$ocdata['type'] = 'fb_post';
				}
				else if (12 == $con1)
				{
					$ocdata['type'] = 'tweet';
				}
				else if (13 == $con1)
				{
					$ocdata['type'] = 'fb_like_pic';
				}
				else if (14 == $con1)
				{
					$ocdata['type'] = 'fb_like_page';
				}
				else if (15 == $con1)
				{
					$ocdata['type'] = 'fb_like_video';
				}
				else if (16 == $con1)
				{
					$ocdata['type'] = 'fb_post_video';
				}
				else if (17 == $con1 && 'single_email_ns_signup' == $email_signup_ways)
				{
					$ocdata['type'] = 'single_email_ns_signup'; // note the _ns_ in between means newsletter
				}
				else if (17 == $con1 && 'dual_email_ns_signup' == $email_signup_ways)
				{
					$ocdata['type'] = 'dual_email_ns_signup'; // note the _ns_ in between means newsletter
				}
				else if (18 == $con1)
				{
					$ocdata['type'] = 'fb_event_share';
				}				
				else if (19 == $con1)
				{
					$ocdata['type'] = 'fb_event_join';
				}
				else if (20 == $con1)
				{
					$ocdata['type'] = 'giveaway';
				}
				else if (21 == $con1)
				{
					$ocdata['type'] = 'yelp_review';
				}
			}
			
			if ($ocdata['condition1'] == 0)
			{
				$this->Session->setFlash(__('Please provide at least one condition for campaign. These conditions also serve as targets for promoters.'));
				$safe = false;
			}
			
			if (!empty($ocdata['condition1_offer_type']))
			{
				$offer_type = $ocdata['condition1_offer_type'];
				if ($offer_type == 'coupon')
				{
					$coupon_valid_until_date = $this->OcCondition->makeValidUntilDate($ocdata['condition3_param1']['year'] , $ocdata['condition3_param1']['month'], $ocdata['condition3_param1']['day']);
					$coupon_worth = $ocdata['coupon_worth'];
					$coupon_worth_cur = $ocdata['coupon_worth_cur'];
					$coupon_code = $ocdata['coupon_code'];
					$coupon_line = $ocdata['coupon_line'];
					$coupon_type = $ocdata['coupon_type'];
					$coupon_details = $ocdata['coupon_details'];
					
					if (empty($coupon_valid_until_date) || empty($coupon_worth) || empty($coupon_worth_cur) || empty($coupon_code) || empty($coupon_line) || empty($coupon_type))
					{
						$safe = false;
						$this->Session->setFlash(__('Please enter all the coupon information.'));
					}
					else if ($coupon_type != 'percent_off' && $coupon_type != 'dollar_off' && $coupon_type != 'sale')
					{
						$this->Session->setFlash(__('Please enter valid coupon type.'));
					}
					else if (!(is_numeric($coupon_worth)) || floatval($coupon_worth) < 0)
					{
						$this->Session->setFlash(__('Coupon worth should be numeric, and positive'));
					}
					
					$ocdata['coupon_valid_until_date'] = $coupon_valid_until_date;
					
				}
			}
			
			if ($safe)
			{
				if (is_numeric($ocdata['condition1_param1']))
				{
					$ocdata['condition1_param1'] = intval($ocdata['condition1_param1']);
				}
				else if ($ocdata['condition1'] > 0)
				{
					$this->Session->setFlash(__('Please enter a numeric target value that fulfills first condition.'));
					$safe = false;
				}
			}
			
			if ($safe)
			{
				if ($ocdata['condition1'] > 0 && floatval($ocdata['condition1_offer_worth']) <= 0)
				{
					$this->Session->setFlash(__('Please provide a valid dollar amount for first target condition(in place of promo offer worth)'));
					$safe = false;
				}
			}
			
			
			if ($safe)
			{
				if (empty($ocdata['max_count1']))
				{
					$ocdata['max_count1'] = 0;
				}
				
				if ($ocdata['condition1'] > 0)
				{
					if (is_numeric($ocdata['max_count1']) && $ocdata['max_count1'] >= 0)
					{
						$ocdata['max_count1'] = intval($ocdata['max_count1']);
					}
					else
					{
						$this->Session->setFlash(__('Please provide a valid value of maximum offer count for first condition.'));
						$safe = false;
					}
				}
			}
			
			if ($safe)
			{
				$no_error = $this->OcCondition->ValidateConditionParam($ocdata['condition1'], $ocdata['condition1_param1'], -1, $ocdata['condition1_offer_type'], $ocdata['condition1_offer_worth']);
				
				if (!$no_error)
				{
					$this->Session->setFlash(__($no_error));
					$safe = false;
				}
				
				$max_days = 0;
				$year = intval($ocdata['condition3_param1']['year']);
				$month = intval($ocdata['condition3_param1']['month']);
				$days  = intval ($ocdata['condition3_param1']['day']);
				if ($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12)
				{
					$max_days = 31;
				}
				else if ($month == 4 || $month == 6 || $month == 9 || $month == 11)
				{
					$max_days = 30;
				}
				else if ($month == 2 && ($year % 4) > 0)
				{
					$max_days = 28;
				}
				else if ($month == 2 && ($year % 4) == 0)
				{
					$max_days = 29;
				}
				if ($days > $max_days)
				{
					$this->Session->setFlash(__("Please check the date."));
					$safe = false;
				}
			}
			
			if ($safe)
			{
				$valid_until_datetime = $this->OcCondition->makeValidUntilDateTime($ocdata['condition3_param1']['year'] , $ocdata['condition3_param1']['month'], $ocdata['condition3_param1']['day']);				
				$ocdata_tosave = array();
				$ocdata_tosave['OpenCampaign'] = array();
				$ocdata_tosave['OpenCampaign']['company_id'] = $ocdata['company_id'];
				$ocdata_tosave['OpenCampaign']['product_id'] = $ocdata['product_id'];
				$ocdata_tosave['OpenCampaign']['type'] = $ocdata['type'];
				$ocdata_tosave['OpenCampaign']['end_date'] = $valid_until_datetime; //date("Y-m-d H:i:s", $valid_until_datetime);
				$ocdata_tosave['OpenCampaign']['active'] = 0;
				$ocdata_tosave['OpenCampaign']['coupon_code'] = $coupon_code;
				$ocdata_tosave['OpenCampaign']['coupon_worth'] = $coupon_worth;
				$ocdata_tosave['OpenCampaign']['coupon_line'] = $coupon_line;
				$ocdata_tosave['OpenCampaign']['coupon_details'] = $coupon_details;
				$ocdata_tosave['OpenCampaign']['coupon_valid_until_date'] = $coupon_valid_until_date;
				$ocdata_tosave['OpenCampaign']['coupon_worth_cur'] = $coupon_worth_cur;
				$ocdata_tosave['OpenCampaign']['coupon_type'] = $coupon_type;
				$ocdata_tosave['OpenCampaign']['approved_content_ids'] = $approved_content_ids_str;
				$ocdata_tosave['OpenCampaign']['default_title'] = $default_title;
				$ocdata_tosave['OpenCampaign']['default_link'] = $default_link;
				$ocdata_tosave['OpenCampaign']['default_desc'] = $default_desc;
				//debug($ocdata_tosave);
				//die();
				$saved = $this->OpenCampaign->save($ocdata_tosave);
				if ($saved) {
					$oc_id = $this->OpenCampaign->id;
					
					$oc_conditiondata_tosave = array();
					$oc_conditiondata_tosave['OcCondition'] = array();
					$oc_conditiondata_tosave['OcCondition']['oc_id'] = $oc_id; 
					$oc_conditiondata_tosave['OcCondition']['condition_id'] = $ocdata['condition1'];
					$oc_conditiondata_tosave['OcCondition']['prerequisite_condition1_id'] = 0;
					$oc_conditiondata_tosave['OcCondition']['prerequisite_condition2_id'] = 0;
					$oc_conditiondata_tosave['OcCondition']['param1'] = $ocdata['condition1_param1'];
					$oc_conditiondata_tosave['OcCondition']['param2'] = -1;
					$oc_conditiondata_tosave['OcCondition']['offer_type'] = $ocdata['condition1_offer_type'];
					$oc_conditiondata_tosave['OcCondition']['offer_worth'] = $ocdata['condition1_offer_worth'];
					$oc_conditiondata_tosave['OcCondition']['max_count'] = $ocdata['max_count1'];
					$oc_conditiondata_tosave['OcCondition']['met_so_far'] = 0;					
					$this->OcCondition->create();
					$first_save = $this->OcCondition->save($oc_conditiondata_tosave);
					$first_save_id = $this->OcCondition->id;
					
					$oc_conditiondata_tosave['OcCondition']['condition_id'] = 5; // valid until condition $ocdata['condition3'];
					$oc_conditiondata_tosave['OcCondition']['prerequisite_condition1_id'] = 0;
					$oc_conditiondata_tosave['OcCondition']['prerequisite_condition2_id'] = 0;
					$valid_until_date = $this->OcCondition->makeValidUntilDate($ocdata['condition3_param1']['year'] , $ocdata['condition3_param1']['month'], $ocdata['condition3_param1']['day']);
					$oc_conditiondata_tosave['OcCondition']['param1'] = $valid_until_date;
					$oc_conditiondata_tosave['OcCondition']['param2'] = -1;
					$oc_conditiondata_tosave['OcCondition']['offer_type'] = 'none';
					$oc_conditiondata_tosave['OcCondition']['offer_worth'] = 0; 
					$oc_conditiondata_tosave['OcCondition']['max_count'] = 0;
					$oc_conditiondata_tosave['OcCondition']['met_so_far'] = 0;					
					
					$this->OcCondition->create();
					$second_save = $this->OcCondition->save($oc_conditiondata_tosave);
					$third_save_id = $this->OcCondition->id;
					
					//if (empty($first_save_id) || empty($second_save_id) || empty($third_save_id))
					if (empty($first_save_id) || empty($third_save_id))
					{
						$this->Session->setFlash(__('Please check the promo conditions and offers made'));
						$safe = false;
					}
					if ($safe)
					{
						$this->Session->setFlash(__('The coupon has been saved. Check the details and start it now to make it visible to the public:)'));
						
						$oc_activated = $this->OpenCampaign->activate($oc_id, true);
						if ($oc_activated)
						{
							$this->Session->setFlash(__('The coupon is saved and is visible to the public now.:)'));
						}
						return $this->redirect(array('action' => 'index'));
					}
				}
				$this->Session->setFlash(__('The campaign could not be saved. Please, try again.'));
			}
		}
		
		$company_data = $this->UserData->getCompanyData();//$this->Company->getCompanyList();
		$company_id = $this->UserData->getCompanyId();
		$this->set('company_data', $company_data);
		$raw_products = $this->Product->getRawProductsByCompanyId($company_id);
		$product_data = $this->Product->compactProductData($raw_products);
		$this->set('product_data', $product_data);

		$conditions = $this->Condition->getConditionList();
		$this->set('conditions', $conditions);
		
		$content_data = $this->Content->get_by_company_id($company_id);	
		$this->set('content_data', $content_data);
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit_remove($id = null) {
		if (!$this->OpenCampaign->exists($id)) {
			throw new NotFoundException(__('Invalid open campaign'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->OpenCampaign->save($this->request->data)) {
				$this->Session->setFlash(__('The open campaign has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The open campaign could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('OpenCampaign.' . $this->OpenCampaign->primaryKey => $id));
			$this->request->data = $this->OpenCampaign->find('first', $options);
		}
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
		$product_data = $this->Product->getProductList();
		$this->set('product_data', $product_data);

		$conditions = $this->Condition->getConditionList();
		$this->set('conditions', $conditions);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->OpenCampaign->id = $id;
		if (!$this->OpenCampaign->exists()) {
			throw new NotFoundException(__('Invalid open campaign'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->OpenCampaign->delete()) {
			$this->Session->setFlash(__('Open campaign deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Open campaign was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->OpenCampaign->recursive = 0;
		$this->set('openCampaigns', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->OpenCampaign->exists($id)) {
			throw new NotFoundException(__('Invalid open campaign'));
		}
		$options = array('conditions' => array('OpenCampaign.' . $this->OpenCampaign->primaryKey => $id));
		$this->set('openCampaign', $this->OpenCampaign->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->OpenCampaign->create();
			if ($this->OpenCampaign->save($this->request->data)) {
				$this->Session->setFlash(__('The open campaign has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The open campaign could not be saved. Please, try again.'));
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
		if (!$this->OpenCampaign->exists($id)) {
			throw new NotFoundException(__('Invalid open campaign'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->OpenCampaign->save($this->request->data)) {
				$this->Session->setFlash(__('The open campaign has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The open campaign could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('OpenCampaign.' . $this->OpenCampaign->primaryKey => $id));
			$this->request->data = $this->OpenCampaign->find('first', $options);
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
		$this->OpenCampaign->id = $id;
		if (!$this->OpenCampaign->exists()) {
			throw new NotFoundException(__('Invalid open campaign'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->OpenCampaign->delete()) {
			$this->Session->setFlash(__('Open campaign deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Open campaign was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
