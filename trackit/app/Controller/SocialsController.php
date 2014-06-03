<?php
class SocialsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData');
	
	public $uses = false;
	
	public function saveme_button()
	{
		$this->layout = 'ajax';
	}
	
	public function saveme_button_quicker()
	{
		$this->layout = 'ajax';
	}
	
	public function saveme_button_quick_auto()
	{
		$this->layout = 'ajax';
	}
	
	public function trackit_mainpage()
	{
		$this->layout = 'ajax';
		$user_email = $this->UserData->getUserEmail();
		$this->set('user_email', $user_email);
	}
	
	public function trackit_mainpage_quicker()
	{
		$this->layout = 'ajax';
		$user_email = $this->UserData->getUserEmail();
		$this->set('user_email', $user_email);
	}
	
	public function share_n_get_coupon_button($unix_timestamp_and_id)
	{
		$this->layout = 'button_in_iframe';
		$this->set('unix_timestamp_and_id', $unix_timestamp_and_id);
	}
	
	public function extra_coupons($cid)
	{
		$this->layout = 'button_in_iframe';
		$this->set('cid', $cid);
	}
	
	public function coupon_btns()
	{
	}
	
	
	public function get_page_info()
	{
		$result = array('success'=>0,'msg'=>0);
		$is_ajax = $this->RequestHandler->isAjax();
		if (!$is_ajax){return;}
		$this->layout = 'ajax';
		
		//$page_url = $this->data['purl'];
		$page_url = "http://graph.facebook.com/loft";
		if (!empty($page_url))
		{
			$ch = curl_init($page_url);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true); // return webpage
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20 );
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			//curl_setopt($ch, CURLOPT_FILE, $image_file_handle);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
			curl_setopt($ch, CURLOPT_MAXREDIRS, 3 );
			
			$page_info = curl_exec($ch);
			$errormsg_curl = curl_error($ch);
			if (empty($errormsg_curl)){
				$result['success'] = 1;
				$result['page_info'] = json_decode($page_info, true);
			}
			else{
				$result['msg'] = $errormsg_curl;
			}
			//debug($result_curl);
			//debug($error_curl);
			curl_close($ch);
			
		}
		
		$this->set('result', $result);
	}
	
	function trackproduct(){
		$user_id = $this->UserData->getUserId();
		if (!empty($user_id)){
			$user_logged_in = true;
		}else{
			$user_logged_in = false;
		}
		
		App::uses('TrackerInfo', 'Model');
		App::uses('Company', 'Model');
		App::uses('CacheData', 'Model');
		$this->TrackerInfo = new TrackerInfo();
		$this->Company = new Company();
		$this->CacheData = new CacheData();
		
		$company_ids = $this->CacheData->get_data_by_key(
						'company_ids_with_fast_tracker', 
						false, 
						'memcache_24hr', 
						true);
		//$company_ids = $this->TrackerInfo->get_company_ids_with_fast_tracker();
				
		$brosable_companies = $this->CacheData->get_data_by_key(
						'comps_select_list_with_fast_tracker', 
						false, 
						'memcache_24hr', 
						true);
		//$brosable_companies = $this->Company->getBrosableCompaniesList($company_ids);
		
		$this->set('companies', $brosable_companies);
		
		$this->set('user_logged_in', $user_logged_in);
	}
	
	/*
	// Collect bookmarklet
	*/
	function collectlet(){
		
		$this->layout = 'default_bookmarklet';
		
		$user_id = $this->UserData->getUserId();
		if (!empty($user_id)){
			$user_logged_in = true;
		}else{
			$user_logged_in = false;
		}
		
		//debug($this->params);
		//debug($this->query);
		//debug($this->request);
		//debug($this->params->query);
		$prodlink = false;
		$title = false;
		if(!empty($this->request->query['u'])){
			$prodlink=$this->request->query['u'];
		}
		
		if(!empty($this->request->query['t'])){
			$title=$this->request->query['t'];
		}
		
		//debug($prodlink);
		//debug($title);
		$this->set('prodlink', $prodlink);
		$this->set('title', $title);
		$this->set('user_logged_in', $user_logged_in);
	}
	
	function add_collectit_btn($element_only=0){
		
		$this->set('element_only', intval($element_only));
		
		$tile_images = array(
			SITE_NAME."screenshots/440_280/price_chart_1.png",
			SITE_NAME."screenshots/440_280/share_options.png",
			SITE_NAME."screenshots/440_280/price_chart_2.png",
			SITE_NAME."screenshots/440_280/collections.png",
			SITE_NAME."screenshots/440_280/price_chart_3.png",
		);
		
		$full_images = array(
			SITE_NAME."screenshots/1280_800/price_chart_1.png",
			SITE_NAME."screenshots/1280_800/share_options.png",
			SITE_NAME."screenshots/1280_800/price_chart_2.png",
			SITE_NAME."screenshots/1280_800/collections.png",
			SITE_NAME."screenshots/1280_800/price_chart_3.png",
		);
		
		$this->set('tile_images', $tile_images);
		$this->set('full_images', $full_images);
	}
	
}