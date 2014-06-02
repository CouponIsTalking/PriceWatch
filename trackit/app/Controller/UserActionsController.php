<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class UserActionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array(
			'Paginator', 
			'RequestHandler', 
			'UserData', 
			'Misc', 
			'RedirectUrl', 
			'TmhOAuthInf', 
			'TwitterResultProcessor', 
			'FBInf', 
			'FBResultProcessor', 
			'Promos',
			'EmailAccess'
			);

	var $uses = array(
		'Content', 
		'Company', 
		'OpenCampaign', 
		'UserCoupon', 
		'ContentPromotion', 
		'OcResponse', 
		'NewsletterSignup',
		'UserEmail',
		'Bitly.BitlyAccess'
		);
	
	var $helpers = array ('Html', 'Youtube', 'Vimeo');
	
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);	
		//Configure::load('Twitter.twitter', 'default', false);			
	}
	
	public function test_tweetit_for_coupon()
	{
		$this->request->data['tw_title'] = 'A Title is always a title.';
		$this->request->data['tw_desc'] = 'A Desc is always a desc.';
		$this->request->data['tw_image_link'] = "http://cdn.pocketnow.com/html/portal/news/0000009268/sample.jpg";
			
		$result = $this->tweetit_for_coupon();
		$this->set('result', $result);
	}
	
	public function is_new_promo_allowed($promo_type = "")
	{
		$success = 0;
		$msg = "";
		$is_ajax = $this->RequestHandler->isAjax();
		$this->set('is_ajax', $is_ajax);
		if ($is_ajax)
		{
			$this->layout = 'ajax';
		}
		
		$user_id = $this->UserData->getUserId();
		if (empty($user_id))
		{
			$success = 0;
			$msg = "Please <a style=\"cursor:pointer; color:white;\" onclick=\"show_user_login_form();\">login</a> to proceed.";
		}
		else if (empty($promo_type))
		{
			$success = 0;
			$msg = 'Bad Request.';
		}
		else 
		{
			if ('fb' == $promo_type)
			{
				$is_allowed = $this->Promos->is_new_promo_allowed($user_id, 'fb');
				
				if (!$is_allowed['success'])
				{
					$success = 0;
					$msg = $is_allowed['msg'];
				}
				else
				{
					$success = 1;
				}
			}
			else if ('tweet' == $promo_type)
			{
				$is_allowed = $this->Promos->is_new_promo_allowed($user_id, 'tweet');
				if (!$is_allowed['success'])
				{
					$success = 0;
					$msg = $is_allowed['msg'];
				}
				else
				{
					$success = 1;
				}
			}
			else
			{
				$success = 0;
				$msg = "Bad Request.";
			}
		}
		
		$result = array ('success' => $success, 'msg' => $msg);
		$this->set('result', $result);
		return $result;
	}
	
	public function mark_used()
	{
		$result = array ('success' => false, 'msg' =>'', 'used' => 0);
		
		$is_ajax = $this->RequestHandler->isAjax();
		$data = $this->request->data;
		
		if (empty($is_ajax)) 
		{
			$result['msg'] = 'Bad request.';
		}
		else if (empty($data['xid']) 
			|| empty($data['type'])
			|| empty($data['verifier'])
			|| !array_key_exists('used_val', $data)
		)
		{
			$result['msg'] = 'Bad data.';
		}
		else if ('oc' != $data['type'] 
				&& 'content' != $data['type']
				)
		{
			$result['msg'] = 'Bad Data.';
		}
		else
		{
			$xid = $data['xid'];
			$verifier = $data['verifier'];
			$type = $data['type'];
			$used_val = $data['used_val'];
			$loggedin_company = $this->UserData->getCompanyId();
				
			if('oc' == $type){
				$ocr = $this->OcResponse->verify_verifier($verifier, $xid);
				if(empty($ocr)){
					$result['msg'] = 'Missing promotion data';
				}
				else{
					$ocr_cid = $ocr['company_id'];
					$ocr_id = $ocr['id'];
					if($ocr_cid == $loggedin_company){
						$set = $this->OcResponse->set_used_val($ocr_id, $used_val);
						if($set){$result['success']=true;
								$result['used']=$used_val;
						}else{$result['success']=false;
							$result['used']=$ocr['OcResponse']['used'];
						}
					}
					else{
						$result['msg'] = 'Please login as business/company.';
					}
				}
			}
			else if('content' == $type){
				$cpr = $this->ContentPromotion->verify_verifier($verifier, $xid);
				if(empty($cpr)){
					$result['msg'] = 'Missing promotion data';
				}
				else{
					$cpr_cid = $cpr['company_id'];
					$cpr_id = $cpr['id'];
					if($cpr_cid == $loggedin_company){
						$set = $this->ContentPromotion->set_used_val($cpr_id, $used_val);
						if($set){$result['success']=true;
								$result['used']=$used_val;
						}else{$result['success']=false;
							$result['used']=$cpr['ContentPromotion']['used'];
						}
					}
					else{
						$result['msg'] = 'Please login as business/company.';
					}
				}
			}
			
			
		}
		
		$this->set('result', $result);
	}
	
	public function verify_verifier()
	{
		$result = array ('success' => 0, 'msg' =>'', 'used' => 0);
		
		$is_ajax = $this->RequestHandler->isAjax();
		$data = $this->request->data;
		
		if (empty($is_ajax)) 
		{
			$result['msg'] = 'Bad request.';
		}
		else if (empty($data['xid']) || empty($data['verifier']) || empty($data['type']))
		{
			$result['msg'] = 'Bad data.';
		}
		else if ('oc' != $data['type'] 
				&& 'content' != $data['type']
				)
		{
			$result['msg'] = 'Bad Data.';
		}
		else
		{
			$verifier = $data['verifier'];
			$xid = $data['xid'];
			$type = $data['type'];
			if('oc' == $type){
				$resp = $this->OcResponse->verify_verifier($verifier, $xid);				
			}
			else if('content' == $type){
				$resp = $this->ContentPromotion->verify_verifier($verifier, $xid);
			}
			
			if(!empty($resp)){
				$result['success'] = true;
				$result['used'] = $resp['used'];
			}
			else{
				$result['success'] = false;
				$result['used'] = 0;
			}
		}
		
		$this->set('result', $result);
	}
	
	public function send_deferred_coupon()
	{
		$r = array ('s' => 0, 'm' =>'');
		$is_ajax = $this->RequestHandler->isAjax();
		$data = $this->request->data;
		$loggedin_company_id = $this->UserData->getCompanyId();
		
		if (!$is_ajax){
			$r['m'] = 'Bad request';
		}
		else if (!$loggedin_company_id){
			$r['m'] = 'Please login as a business to send coupons.';
		}else if(empty($data['ocr_id'])){
			$r['m'] = 'Bad parameters';
		}else{
			$ocr_id = $data['ocr_id'];
			$ocr = $this->OcResponse->getRawResponseByOcrId($ocr_id);
			if(empty($ocr)){
				$r['m'] = 'Promotion not found.';
			}else{
				$oc_id = $ocr['OcResponse']['oc_id'];
				$user_id = $ocr['OcResponse']['user_id'];
				$ocs_details = $this->OpenCampaign->getCampaignsByOCIds(array($oc_id));
				if (empty($ocs_details[0]['OpenCampaign'])){
					$r['m'] = 'Campaign not found.';
				}else{
					$oc_detail = $ocs_details[0];
					$company_id = $oc_detail['OpenCampaign']['company_id'];
					$coupon_code = trim($oc_detail['OpenCampaign']['coupon_code']);
					$coupon_line = trim($oc_detail['OpenCampaign']['coupon_line']);
					$coupon_details = trim($oc_detail['OpenCampaign']['coupon_details']);
					$coupon_valid_until_date = trim($oc_detail['OpenCampaign']['coupon_valid_until_date']);
					$promo_method_oc = trim($oc_detail['OpenCampaign']['type']);
					
					$resp_for_obj_id = $oc_id;
					$bad_id = false;
					App::uses('User', 'Model');
					$this->User = new User();
					$user = $this->User->findUserById($user_id);
					if (empty($user)){
						$r['m'] = 'User who created this promotion not found.';
					}
					else if ($loggedin_company_id!=$company_id){
						$r['m'] = "It appears that you are not logged in as a business that created this Campaign.<br/>If you have multiple business accounts, then please login with right business account. Let(call/email) us know if it seems wrong.";
					}else{
						$user_id_n_company_id_coupon = $this->UserCoupon->create_entry_name($user_id, $company_id, $coupon_code);
						$this->OcResponse->update_coupon_code($ocr_id,$coupon_code, $user_id_n_company_id_coupon);
						$added = $this->UserCoupon->add_entry($user_id_n_company_id_coupon);
						$verifier = $this->UserCoupon->build_verifier($ocr_id, $user_id); 
						
						$company_name = $this->UserData->getCompanyName();
						
						$user_email = $user['username'];
						$user_name = $user['firstname'];
						$email_params = array(
							'user_email' => $user_email,
							'user_name' => $user_name,
							'company_name' => $company_name,
							'coupon_code' => $coupon_code,
							'coupon_line' => $coupon_line,
							'coupon_details' => $coupon_details,
							'coupon_valid_until_date' => $coupon_valid_until_date,
							'verifier' => $verifier
							);
						
						$this->EmailAccess->send_coupon_ary($email_params);
						$r['s']=true;
						$r['ccode']=$coupon_code;
					}
				}
			}
		}
		
		$this->set('r', $r);
	}
	
	public function post_promo_updates()
	{
		$result = array ('success' => 0, 'msg' =>'');
		
		$is_ajax = $this->RequestHandler->isAjax();
		$data = $this->request->data;
		$user_id = $this->UserData->getUserId();
		$user_email = $this->UserData->getUserEmail();
		
		$json_promo_response = $data['json_promo_response'];
		$promo_for = $data['objtype'];
		$promo_method = $data['promo_method'];
		
		$postid = $data['postid'];
		$live_link = "";
		
		$share_title 		= $data['share_title'];
		$share_desc 		= $data['share_desc'];
		$share_image_link 	= $data['share_image_link'];
		$share_news_title 	= $data['share_news_title'];
		$share_news_link 	= $data['share_news_link'];
		
		$bad_id = true;
		if ('content' == $promo_for)
		{
			$unix_timestamp_and_id = $data['objid'];
		
			$content = $this->Content->get_active_content_by_time_n_id_str($unix_timestamp_and_id);
			if (!empty($content['Content']))
			{
				//$tweet_data = $this->Content->getTweetData($unix_timestamp_and_id);			
				$content_id = $content['Content']['id'];
				$title = trim($content['Content']['title']);
				$desc = trim($content['Content']['desc']);
				$image_link = trim($content['Content']['link']);
				$coupon_code = trim($content['Content']['tw_coupon_code']);
				$coupon_line = trim($content['Content']['tw_offer']);
				$coupon_details = "";
				$coupon_valid_until_date = "";
				
				$company_id = $content['Content']['company_id'];
				
				$resp_for_obj_id = $content_id;
				$bad_id = false;
			}
		}
		else
		{
			$oc_id = $data['objid'];
			
			$ocs_details = $this->OpenCampaign->getCampaignsByOCIds(array($oc_id));
			if (!empty($ocs_details[0]['OpenCampaign']))
			{
				$oc_detail = $ocs_details[0];
				$company_id = $oc_detail['OpenCampaign']['company_id'];
				$coupon_code = trim($oc_detail['OpenCampaign']['coupon_code']);
				$coupon_line = trim($oc_detail['OpenCampaign']['coupon_line']);
				$coupon_details = trim($oc_detail['OpenCampaign']['coupon_details']);
				$coupon_valid_until_date = trim($oc_detail['OpenCampaign']['coupon_valid_until_date']);
				$promo_method_oc = trim($oc_detail['OpenCampaign']['type']);
				
				$resp_for_obj_id = $oc_id;
				$bad_id = false;
			}
			
		}
		
		if ($bad_id)
		{
			$result['msg'] = 'Bad Request Data.';
		}
		else if (!$is_ajax)
		{
			$result['msg'] = 'Bad request.';
		}
		else if (empty($data))
		{
			$result['msg'] = 'Bad request.';
		}
		else if (empty($user_id) || empty($user_email))
		{
			$result['msg'] = "Please <a onclick='show_user_register_login_form();'>Login</a> to proceed.";
		}
		else if ($promo_method != $promo_method_oc)
		{
			$result['msg'] = "Promo method not recognized.";
		}
		else 
		{
			/*$promo_for = $data['promo_for'];
			$company_id = $data['compid'];
			$content_or_oc_id = $data['content_or_oc_id'];
			$coupon_code = $data['coupon_code'];
			$promo_method = $data['promo_method'];
			$coupon_line = $data['coupon_line'];
			$coupon_details = $data['coupon_details'];
			$coupon_valid_until_data = $data['coupon_valid_until_data'];
			$json_promo_response = $data['json_promo_response'];
			*/
			$full_resp = json_decode($json_promo_response, true);
			if ('fb_event_share' == $promo_method){
				$live_link = "https://www.facebook.com/{$full_resp['id']}/";
			}
			else{
				if (!empty($full_resp['permalink'])){
					$live_link = $full_resp['permalink'];
				}
			}
			
			$deferred_coupon = false;
			if ('yelp_review' == $promo_method){
				$deferred_coupon = true;
			}
			
			// if it is a deferred coupon, then clear coupon code and clear userid/companyid/coupon pair,
			// otherwise, build this pair 
			if ($deferred_coupon){
				$user_id_n_company_id_coupon = "";
				$coupon_code = "";
			}else{
				$user_id_n_company_id_coupon = $this->UserCoupon->create_entry_name($user_id, $company_id, $coupon_code);
			}
			
			$params = array('user_id' => $user_id, 
					'resp_for_obj_id' => $resp_for_obj_id, 
					'json_promo_response' => $json_promo_response, 
					'postid' => $postid,
					'live_link' => $live_link,
					'company_id' => $company_id, 
					'coupon_code' => $coupon_code, 
					'uid_cid_coupon_code' => $user_id_n_company_id_coupon,
					'promo_method' => $promo_method,
					'share_title' => $share_title,
					'share_desc' => $share_desc,
					'share_image_link' => $share_image_link,
					'share_news_title' => $share_news_title,
					'share_news_link' => $share_news_link,
					'update_like_count' => 0,
					'update_comment_count' => 0,
					'update_share_count' => 0,
					'update_retweet_count' => 0,
				);
			
			if ('fb_post' == $promo_method)
			{
				$params['promo_method'] = 'fb_post';
				
				if ("" != $json_promo_response)
				{
					$ary_resp = json_decode($json_promo_response, true);
					if (!empty($ary_resp['like_info']['like_count']))
					{
						$params['like_count'] = $ary_resp['like_info']['like_count'];
						$params['update_like_count'] = 1;
					}
					if (!empty($ary_resp['comment_info']['comment_count']))
					{
						$params['comment_count'] = $ary_resp['comment_info']['comment_count'];
						$params['update_comment_count'] = 1;
					}
					if (!empty($ary_resp['share_info']['share_count']))
					{
						$params['share_count'] = $ary_resp['share_info']['share_count'];
						$params['update_share_count'] = 1;
					}
				}
			}
			else if ('fb_like_page' == $promo_method || 'fb_like_photo' == $promo_method)
			{
				$params['promo_method'] = $promo_method;
			}
			else if ('tweet' == $promo_method || 'tw' == $promo_method)
			{
				$params['promo_method'] = 'tweet';
			}
			
			$add_result = array();
			if ('oc' == $promo_for)
			{
				$add_result = $this->OcResponse->addRawGenResponseData($params);				
			}
			else if ('content' == $promo_for)
			{
				$add_result = $this->ContentPromotion->addRawGenResponseData($params);				
			}
			
			// if not deferred coupon
			if (!$deferred_coupon){
				// mark twitter coupon code as earned by the user
				// build verifier and put coupon in user_coupons table
				$verifier = "";
				if (!empty($add_result) 
					&& $add_result['success'] 
					&& !empty($add_result['saveid'])
					)
				{
					$verifier = $this->UserCoupon->build_verifier($add_result['saveid'], $user_id); 
				}
				$added = $this->UserCoupon->add_entry($user_id_n_company_id_coupon);
			}
			
			if ('fb_post' == $promo_method)
			{
				$this->Promos->add_new_promo_time_as_now($user_id, 'fb');
			}
			else if ('tweet' == $promo_method || 'tw' == $promo_method)
			{
				$this->Promos->add_new_promo_time_as_now($user_id, 'tw');
			}
			
			$company_name = $this->Company->getCompanyNameById($company_id);
			
			$user_name = $this->UserData->getWelcomeName();
			if (!empty($user_name) && ($user_name == $user_email)){$user_name = "";}
			
				
			if($deferred_coupon){
				// build email params
				$email_params = array(
					'user_email' => $user_email,
					'user_name' => $user_name,
					'company_name' => $company_name
					);
				
				if ('yelp_review'==$promo_method){
					// send informative email
					$email_params['subject'] = $coupon_line;
					$email_params['next_step_msg'] = "Thanks for submitting your Yelp review for '{$company_name}'. We will email you if you win our coupon.
					We usually have pretty high rate of winning and very short turn around time. So, stay tuned :).
					";
					$this->EmailAccess->send_deferred_coupon_ary($email_params);	
				}
			}
			else{
				// build email params
				$email_params = array(
					'user_email' => $user_email,
					'user_name' => $user_name,
					'company_name' => $company_name,
					'coupon_code' => $coupon_code,
					'coupon_line' => $coupon_line,
					'coupon_details' => $coupon_details,
					'coupon_valid_until_date' => $coupon_valid_until_date,
					'verifier' => $verifier
					);
				
				$this->EmailAccess->send_coupon_ary($email_params);
			}
			
			
			//$this->EmailAccess->send_coupon($user_email, "", $company_name, $coupon_code, $coupon_line, $coupon_details, $coupon_valid_until_date);
			
			$result['success'] = 1;
		}		
		
		$this->set('result', $result);
		return $result;
		
	}
	
	public function fbpost_for_coupon()
	{
		
		$result = array ('success' => 0, 'msg' => '', 'next_step' => '');
		$tweet_successful = array();
		$next_step = "";
		$is_ajax = $this->RequestHandler->isAjax();
		
		if ($is_ajax)
		{
			$this->layout = 'ajax';
			$using_preset_params = false;
		}
		else
		{
			//debug($this->request);
			$using_preset_params = $this->RedirectUrl->should_use_internal_param();
		}
		
		if ($using_preset_params)
		{
			$post_data = $this->RedirectUrl->get_request_data();
			$this->RedirectUrl->clear_use_internal_param();
			$post_action_redirect = $post_data['user_actions']['post_action_redirect'];
		
		}
		else
		{
			$post_data = $this->request->data;
			//$tweet_successful = array('errors'=> array(array('code'=> (int)215)));
		}
		
		//$post_data = $post_data['user_actions'];
		
		//debug($this->request);
		//debug($this->params);
		//debug($this);
		$custom_ad = false;
		if (!empty($post_data['fb_is_custom']))
		{
			$custom_ad = true;
		}
		
		$user_email = $this->UserData->getUserEmail();
		$user_id = $this->UserData->getUserId();
		
		$is_allowed = $this->Promos->is_new_promo_allowed($user_id, 'fb');
		if (!$is_allowed['success'])
		{
			$next_step = 'user_limit_reached_msg';
			$result['msg'] = $is_allowed['msg'];
		}
		else if (!$custom_ad)
		{
			$unix_timestamp_and_id = $post_data['fb_content_id'];
		
			$content = $this->Content->get_active_content_by_time_n_id_str($unix_timestamp_and_id);
			if (!empty($content['Content']))
			{
				//$tweet_data = $this->Content->getTweetData($unix_timestamp_and_id);			
				$content_id = $content['Content']['id'];
				$title = trim($content['Content']['title']);
				$desc = trim($content['Content']['desc']);
				$image_link = trim($content['Content']['link']);
				$fb_coupon_code = trim($content['Content']['fb_coupon_code']);
				$company_id = $content['Content']['company_id'];
				
				$coupon_line = trim($content['Content']['fb_offer']);
				$coupon_details = "";
				$coupon_valid_until_date = "";
			}				
			
		}
		else
		{
			$oc_id = $post_data['fb_oc_id'];
			
			$ocs_details = $this->OpenCampaign->getCampaignsByOCIds(array($oc_id));
			if (!empty($ocs_details[0]['OpenCampaign']))
			{
				$oc_detail = $ocs_details[0];
				$company_id = $oc_detail['OpenCampaign']['company_id'];
				$fb_coupon_code = trim($oc_detail['OpenCampaign']['coupon_code']);
				$coupon_line = trim($oc_detail['OpenCampaign']['coupon_line']);
				$coupon_details = trim($oc_detail['OpenCampaign']['coupon_details']);
				$coupon_valid_until_date = trim($oc_detail['OpenCampaign']['coupon_valid_until_date']);
			
				//$title = $post_data['tw_title'];
				//$desc = $post_data['tw_desc'];
				(!empty($post_data['fb_title'])) ? $title = $post_data['fb_title']: $title = "";
				(!empty($post_data['fb_desc'])) ? $desc = $post_data['fb_desc']: $desc = "";
				(!empty($post_data['fb_image_link'])) ? $image_link = $post_data['fb_image_link']: $image_link = "";
				(!empty($post_data['fb_news_link'])) ? $news_link = $post_data['fb_news_link']: $news_link = "";
				(!empty($post_data['fb_news_title'])) ? $news_title = $post_data['fb_news_title']: $news_title = "";
				//$tw_coupon_code = trim($post_data['tw_coupon_code']);
			}
			
		}
		
		//debug($custom_ad);
		//debug($oc_id); debug($title); debug($desc); debug($image_link); debug($tw_coupon_code); debug($news_title); debug($news_link);
		//debug($user_email);debug($user_id);
		//debug($tweet_data);
		//die();
		
		if ("" != $next_step)
		{
			// Do nothing, next step is already determined...
		}
		else if (empty($user_email) || empty($user_id))
		{
			$next_step = 'user_login';
		}
		else if (empty($title) && empty($desc) && empty($image_link))
		{
			$next_step = 'nothing_to_share';
		}
		else
		{
			
			// Create Tweet 
			$allow_image_upload = true;
			
			//App::import('Component', 'Twitter.Twitter');
			//App::import('Component', 'Twitter.TwitterResultProcessor');
			
			$user_tokens = $this->UserData->getFBUserTokens();
			
			/* // test place
			if (true)
			{
				//$next_step = 'user_limit_reached_msg';
				$next_step = 'tweet_successful';
			}
			else 
			*/
			if (empty($user_tokens))
			{
				$this->UserData->deleteFBUserTokens();
				$next_step = 'fb_login';
			}
			else
			{
				$fb_inf = $this->FBInf;
				$fb_inf->initialize_lib();
				
				$fb_result_processor = $this->FBResultProcessor;
				/*
				if (false != $fb_result_processor->has_errors($verify_cred_result))
				{
					$this->UserData->deleteTwitterUserTokens();
					$next_step = 'twitter_login';
				}
				else
				{*/
					$status_data = array ();
					
					if ($allow_image_upload && !empty($image_link))
					{
						//$twitter_inf->initialize_tmh_lib();
						//$twitter_inf->verify_credentials();
						$fb_successful = $fb_inf->status_update_with_media($title, $image_link);
						//$tweet_successful = $twitter_component->updateStatusWithMedia($title, $status_data['media[]']);
					}
					else
					{
						$fb_successful = $fb_inf->status_update_without_media($title);
						//$tweet_successful = $twitter_component->updateStatus($title);
					}
				
					/*debug($post_data);
					debug($content);
					debug($tweet_successful);
					return;
					*/
					
					if ( (false == $fb_result_processor->has_errors($tweet_successful))
						|| (true == $fb_result_processor->is_dup_status($tweet_successful))
					)
					{ 
						$next_step = 'fb_successful';
						
						if (!empty($oc_id))
						{
							$this->OcResponse->addRawFBResponseData($user_id, $oc_id, $fb_successful);
						}
						else if (!empty($content_id))
						{
							$this->ContentPromotion->addRawFBResponseData($user_id, $content_id, $fb_successful);
						}
						
						// mark twitter coupon code as earned by the user
						$user_id_n_company_id_coupon = $this->UserCoupon->create_entry_name($user_id, $company_id, $fb_coupon_code);
						$added = $this->UserCoupon->add_entry($user_id_n_company_id_coupon);
						$this->Promos->add_new_promo_time_as_now($user_id, 'fb');
						
						$company_name = $this->Company->getCompanyNameById($company_id);
						$this->EmailAccess->send_coupon($user_email, "", $company_name, $fb_coupon_code, $coupon_line, $coupon_details, $coupon_valid_until_date);
					}
					else if (true == $fb_result_processor->are_tokens_bad($fb_successful))
					{ 
						//debug($this->params->url);
						$this->UserData->deleteTwitterUserTokens();
						$next_step = 'fb_login';			
					}
					else if (true == $fb_result_processor->is_bad_auth($fb_successful))
					{
						//debug($this->params->url);
						$next_step = 'fb_login';
					}
					else if (true == $fb_result_processor->is_user_rate_limit_reached($fb_successful))
					{
						$next_step = 'user_limit_reached_msg';
					}
					//debug($tweet_successful);
				/*
				}*/
			}
		}
		
		if ('fb_successful' == $next_step)
		{
			if ($is_ajax)
			{
				$result['success'] = 1;
				$result['coupon_code'] = $fb_coupon_code;
			}
			else
			{
				$this->redirect($post_action_redirect);
			}
		}
		else if('fb_login' == $next_step)
		{
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = 'Please login to Facebook.';
			}
			else
			{
				$this->RedirectUrl->set_redirect_url(SITE_NAME . $this->params->url);
				$this->RedirectUrl->set_request_data($post_data);
				$this->RedirectUrl->set_use_internal_param();
				
				//$this->redirect(array('plugin' => 'twitter', 'controller' => 'twitter', 'action' => 'tw_login'));
				$this->redirect(array('plugin' => false, 'controller' => 'fb', 'action' => 'init_login'));
			}
		}
		else if ('user_limit_reached_msg' == $next_step)
		{
			if (!empty($result['msg'])) {$msg = $result['msg'];}
			else { $msg = 'Daily post limit enforced by Facebook has reached. Please try us later.';}
			
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = $msg;
			}
			else
			{
				$this->Session->setFlash($msg);
			}
		}
		else if ('nothing_to_share' == $next_step)
		{
		
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = 'Please choose a news or an image.';
			}
			else
			{
				$this->Session->setFlash("Please pick an image or news.");
				$this->redirect($post_action_redirect);
			}
		}
		else if ('user_login' == $next_step)
		{
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = 'Please login to get coupon.';
			}
			else
			{
				$this->Session->setFlash("Please login to get coupon.");
			}
		}
		
		if ($is_ajax)
		{
			$result['next_step'] = $next_step;
			$this->set('result', $result);
			return;
		}
		
		return $result;
	}
		
	public function tweetit_for_coupon()
	{
		
		$result = array ('success' => 0, 'msg' => '', 'next_step' => '');
		$tweet_successful = array();
		$next_step = "";
		$is_ajax = $this->RequestHandler->isAjax();
		
		if ($is_ajax)
		{
			$this->layout = 'ajax';
			$using_preset_params = false;
		}
		else
		{
			//debug($this->request);
			$using_preset_params = $this->RedirectUrl->should_use_internal_param();
		}
		
		if ($using_preset_params)
		{
			$post_data = $this->RedirectUrl->get_request_data();
			$this->RedirectUrl->clear_use_internal_param();
			$post_action_redirect = $post_data['user_actions']['post_action_redirect'];
		
		}
		else
		{
			$post_data = $this->request->data;
			//$tweet_successful = array('errors'=> array(array('code'=> (int)215)));
		}
		
		//$post_data = $post_data['user_actions'];
		
		//debug($this->request);
		//debug($this->params);
		//debug($this);
		$custom_ad = false;
		if (!empty($post_data['tw_is_custom']))
		{
			$custom_ad = true;
		}
		
		$user_email = $this->UserData->getUserEmail();
		$user_id = $this->UserData->getUserId();
		
		$is_allowed = $this->Promos->is_new_promo_allowed($user_id, 'tweet');
		if (!$is_allowed['success'])
		{
			$next_step = 'user_limit_reached_msg';
			$result['msg'] = $is_allowed['msg'];
		}
		else if (!$custom_ad)
		{
			$unix_timestamp_and_id = $post_data['tw_content_id'];
		
			$content = $this->Content->get_active_content_by_time_n_id_str($unix_timestamp_and_id);
			if (!empty($content['Content']))
			{
				//$tweet_data = $this->Content->getTweetData($unix_timestamp_and_id);			
				$content_id = $content['Content']['id'];
				$title = trim($content['Content']['title']);
				$desc = trim($content['Content']['desc']);
				$image_link = trim($content['Content']['link']);
				$tw_coupon_code = trim($content['Content']['tw_coupon_code']);
				$coupon_line = trim($content['Content']['tw_offer']);
				$coupon_details = "";
				$coupon_valid_until_date = "";
				
				$company_id = $content['Content']['company_id'];
			}				
			
		}
		else
		{
			$oc_id = $post_data['tw_oc_id'];
			
			$ocs_details = $this->OpenCampaign->getCampaignsByOCIds(array($oc_id));
			if (!empty($ocs_details[0]['OpenCampaign']))
			{
				$oc_detail = $ocs_details[0];
				$company_id = $oc_detail['OpenCampaign']['company_id'];
				$tw_coupon_code = trim($oc_detail['OpenCampaign']['coupon_code']);
				$coupon_line = trim($oc_detail['OpenCampaign']['coupon_line']);
				$coupon_details = trim($oc_detail['OpenCampaign']['coupon_details']);
				$coupon_valid_until_date = trim($oc_detail['OpenCampaign']['coupon_valid_until_date']);
				
				$default_title = trim($oc_detail['OpenCampaign']['default_title']);
				
				//$title = $post_data['tw_title'];
				//$desc = $post_data['tw_desc'];
				(!empty($post_data['tw_title'])) ? $title = $post_data['tw_title']: $title = $default_title; //$post_data['default_title'];
				(!empty($post_data['tw_desc'])) ? $desc = $post_data['tw_desc']: $desc = "";
				(!empty($post_data['tw_image_link'])) ? $image_link = $post_data['tw_image_link']: $image_link = "";
				(!empty($post_data['tw_news_link'])) ? $news_link = $post_data['tw_news_link']: $news_link = "";
				(!empty($post_data['tw_news_title'])) ? $news_title = $post_data['tw_news_title']: $news_title = "";
				//$tw_coupon_code = trim($post_data['tw_coupon_code']);
			}
			
		}
		
		//debug($custom_ad);
		//debug($oc_id); debug($title); debug($desc); debug($image_link); debug($tw_coupon_code); debug($news_title); debug($news_link);
		//debug($user_email);debug($user_id);
		//debug($tweet_data);
		//die();
		
		if ("" != $next_step)
		{
			// Do nothing, next step is already determined...
		}
		else if (empty($user_email) || empty($user_id))
		{
			$next_step = 'user_login';
		}
		else if (empty($title) && empty($desc) && empty($image_link))
		{
			$next_step = 'nothing_to_share';
		}
		else
		{
			
			// Create Tweet 
			$allow_image_upload = true;
			
			//App::import('Component', 'Twitter.Twitter');
			//App::import('Component', 'Twitter.TwitterResultProcessor');
			
			$user_tokens = $this->UserData->getTwitterUserTokens();
			
			/* // test place
			if (true)
			{
				//$next_step = 'user_limit_reached_msg';
				$next_step = 'tweet_successful';
			}
			else 
			*/
			if (empty($user_tokens))
			{
				$this->UserData->deleteTwitterUserTokens();
				$next_step = 'twitter_login';
			}
			else
			{
				$twitter_consumer_key = Configure::read('Twitter.consumerKey');
				$twitter_consumer_secret = Configure::read('Twitter.consumerSecret');
				
				/*
				$twitter_component = new Twitter(array('UserTokens' => $user_tokens));
				$twitter_component->setupApp($twitter_consumer_key, $twitter_consumer_secret); 
				*/
				
				//$credentialCheck = $twitter_component->accountVerifyCredentials();
				$twitter_inf = $this->TmhOAuthInf; //new TmhOAuthInfComponent;
				$twitter_inf->initialize_tmh_lib();
				$verify_cred_result = $twitter_inf->verify_credentials();
				
				//debug($user_tokens);
				//debug($credentialCheck);
				//die();
				$tw_result_processor = $this->TwitterResultProcessor; //new TwitterResultProcessorComponent(new ComponentCollection());
				
				if (false != $tw_result_processor->has_errors($verify_cred_result))
				{
					$this->UserData->deleteTwitterUserTokens();
					$next_step = 'twitter_login';
				}
				else
				{
					$status_data = array ();
					
					/*if (true)
					{
						$tweet_successful = true;
					}
					else */
					if ($allow_image_upload && !empty($image_link))
					{
						//$twitter_inf->initialize_tmh_lib();
						//$twitter_inf->verify_credentials();
						$tweet_successful = $twitter_inf->status_update_with_media($title, $image_link);
						//$tweet_successful = $twitter_component->updateStatusWithMedia($title, $status_data['media[]']);
					}
					else
					{
						$tweet_successful = $twitter_inf->status_update_without_media($title);
						//$tweet_successful = $twitter_component->updateStatus($title);
					}
				
					/*debug($post_data);
					debug($content);
					debug($tweet_successful);
					return;
					*/
					
					if ( (false == $tw_result_processor->has_errors($tweet_successful['re']))
						|| (true == $tw_result_processor->is_dup_status($tweet_successful['re']))
					)
					{ 
						$next_step = 'tweet_successful';
						$user_id_n_company_id_coupon = $this->UserCoupon->create_entry_name($user_id, $company_id, $tw_coupon_code);
						
						$postid = "";							
						$live_link = "";
						if (!empty($tweet_successful['resp']))
						{
							$decoded_resp = json_decode($tweet_successful['resp'], true);
							if(!empty($decoded_resp['id_str'])
								&& !empty($decoded_resp['user']['screen_name'])
							)
							{
								$postid = $decoded_resp['id_str'];
								$screen_name = $decoded_resp['user']['screen_name'];
								$live_link = "https://twitter.com/{$screen_name}/status/{$postid}";
							}
						}
						
						$params = array('user_id' => $user_id, 
							'postid' => $postid,
							'live_link' => $live_link,
							'json_promo_response' => $tweet_successful['resp'], 
							'company_id' => $company_id, 
							'coupon_code' => $tw_coupon_code, 
							'uid_cid_coupon_code' => $user_id_n_company_id_coupon,
							'promo_method' => 'tweet',
							'share_title' => $title,
							'share_desc' => '',
							'share_image_link' => $image_link,
							'share_news_title' => '',
							'share_news_link' => '',
							'update_like_count' => 0,
							'update_comment_count' => 0,
							'update_share_count' => 0,
							'update_retweet_count' => 0,
						);
						
						if ($tweet_successful['resp'])
						{
							$full_resp = json_decode($tweet_successful['resp'], true);
							if (!empty($full_resp['retweet_count']))
							{
								$params['update_retweet_count'] = 1;
								$params['retweet_count'] = $full_resp['retweet_count'];
							}
							if (!empty($full_resp['favorite_count']))
							{
								$params['update_like_count'] = 1;
								$params['like_count'] = $full_resp['favorite_count'];
							}
						}
						
						$add_result = array();
						
						if (!empty($oc_id))
						{
							$params['resp_for_obj_id'] = $oc_id;
							$add_result = $this->OcResponse->addRawGenResponseData($params);
							//$this->OcResponse->addRawTweetResponseData($user_id, $oc_id, $tweet_successful['resp']);
						}
						else if (!empty($content_id))
						{
							$params['resp_for_obj_id'] = $content_id;
							$add_result = $this->ContentPromotion->addRawGenResponseData($params);
							//$this->ContentPromotion->addRawTweetResponseData($user_id, $content_id, $tweet_successful['resp']);
						}
						
						// mark twitter coupon code as earned by the user
						$added = $this->UserCoupon->add_entry($user_id_n_company_id_coupon);
						$this->Promos->add_new_promo_time_as_now($user_id, 'tw');
						
						$company_name = $this->Company->getCompanyNameById($company_id);
						
						$verifier = "";
						if (!empty($add_result) 
							&& $add_result['success'] 
							&& !empty($add_result['saveid'])
							)
						{
							$verifier = $this->UserCoupon->build_verifier($add_result['saveid'], $user_id); 
						}
						
						$email_params = array(
							'user_email' => $user_email,
							'user_name' => '',
							'company_name' => $company_name,
							'coupon_code' => $tw_coupon_code,
							'coupon_line' => $coupon_line,
							'coupon_details' => $coupon_details,
							'coupon_valid_until_date' => $coupon_valid_until_date,
							'verifier' => $verifier
						);
						
						$this->EmailAccess->send_coupon_ary($email_params);
						
						//$this->EmailAccess->send_coupon($user_email, "", $company_name, $tw_coupon_code, $coupon_line, $coupon_details, $coupon_valid_until_date);
						
					}
					else if (true == $tw_result_processor->are_tokens_bad($tweet_successful['re']))
					{ 
						//debug($this->params->url);
						$this->UserData->deleteTwitterUserTokens();
						$next_step = 'twitter_login';			
					}
					else if (true == $tw_result_processor->is_bad_auth($tweet_successful['re']))
					{
						//debug($this->params->url);
						$next_step = 'twitter_login';
					}
					else if (true == $tw_result_processor->is_user_rate_limit_reached($tweet_successful['re']))
					{
						$next_step = 'user_limit_reached_msg';
					}
					//debug($tweet_successful);
				}
			}
		}
		
		if ('tweet_successful' == $next_step)
		{
			if ($is_ajax)
			{
				$result['success'] = 1;
				$result['coupon_code'] = $tw_coupon_code;
			}
			else
			{
				$this->redirect($post_action_redirect);
			}
		}
		else if('twitter_login' == $next_step)
		{
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = 'Please login to twitter.';
			}
			else
			{
				$this->RedirectUrl->set_redirect_url(SITE_NAME . $this->params->url);
				$this->RedirectUrl->set_request_data($post_data);
				$this->RedirectUrl->set_use_internal_param();
				
				//$this->redirect(array('plugin' => 'twitter', 'controller' => 'twitter', 'action' => 'tw_login'));
				$this->redirect(array('plugin' => false, 'controller' => 'twitters', 'action' => 'init_tw_login'));
			}
		}
		else if ('user_limit_reached_msg' == $next_step)
		{
			if (!empty($result['msg'])) {$msg = $result['msg'];}
			else { $msg = 'Daily tweet limit enforced by Twitter has reached. Please try us later.';}
			
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = $msg;
			}
			else
			{
				$this->Session->setFlash($msg);
			}
		}
		else if ('nothing_to_share' == $next_step)
		{
		
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = 'Please choose a news or an image.';
			}
			else
			{
				$this->Session->setFlash("Please pick an image or news.");
				$this->redirect($post_action_redirect);
			}
		}
		else if ('user_login' == $next_step)
		{
			if ($is_ajax)
			{
				$result['success'] = 0;
				$result['msg'] = 'Please login to tweet and get coupon.';
			}
			else
			{
				$this->Session->setFlash("Please login to tweet and get coupon.");
			}
		}
		
		if ($is_ajax)
		{
			$result['next_step'] = $next_step;
			$this->set('result', $result);
			return;
		}
		
		return $result;
	}
	
	public function ns_signup_for_coupon()
	{

		$result = array ('success' => 0, 'msg' => '', 'next_step' => '');
		$errors = false;
		
		$is_ajax = $this->RequestHandler->isAjax();
		$is_post = $this->RequestHandler->isPost();
		
		if ($is_ajax)
		{
			$this->layout = 'ajax';
		}
		if (!$is_post)
		{
			return;
		}
		
		$post_data = $this->request->data;
		
		if (empty($post_data['objid'])
			|| empty($post_data['objtype'])
			|| empty($post_data['promo_method'])
			|| empty($post_data['email1'])
			|| (!array_key_exists ('email2', $post_data))
		)
		{
			$result['msg'] = 'Bad Request.';
			$errors = true;
		}
		else
		{
			$objid = $post_data['objid'];
			$objtype = $post_data['objtype'];
			$promo_method = $post_data['promo_method'];
			$email1 = $post_data['email1'];
			$email2 = $post_data['email2'];			
			$user_email = $this->UserData->getUserEmail();
			$user_id = $this->UserData->getUserId();
		}
		
		if (!$errors)
		{
			if (empty($user_email) || empty($user_id))
			{
				$result['msg'] = 'Please login to sign up and unlock coupon.';
				$errors = true;
			}
		}
		
		if (!$errors &&('content' != $objtype && 'oc' != $objtype))
		{
			$result['msg'] = 'The request is improperly formed and could not be understood.';
			$errors = true;
		}
		
		if (!$errors &&('single_email_ns_signup' != $promo_method && 'dual_email_ns_signup' != $promo_method))
		{
			$result['msg'] = 'The request could not be understood.';
			$errors = true;
		}
		
		// if we didn't already run across an error so far, now check that the given emails are formatted right
		$emails = array();
		if (!$errors)
		{
			$first_email_valid =  filter_var($email1, FILTER_VALIDATE_EMAIL);
			if (empty($first_email_valid))
			{
				$result['msg'] = "'{$email1}' does not appear as a valid email address.";
				$errors = true;
			}
			if ('dual_email_ns_signup' == $promo_method)
			{
				$second_email_valid =  filter_var($email2, FILTER_VALIDATE_EMAIL);
				if (empty($second_email_valid))
				{
					$result['msg'] = "'{$email2}' does not appear as a valid email address.";
					$errors = true;
				}
				else if (trim($email1) == trim($email2))
				{
					$result['msg'] = "Please provide different email addresses.";
					$errors = true;
				}
			}
		}
		
		
		if (!$errors)
		{	
			if ('content' == $objtype)
			{
				$unix_timestamp_and_id = $post_data['objid'];
			
				$content = $this->Content->get_active_content_by_time_n_id_str($unix_timestamp_and_id);
				if (!empty($content['Content']))
				{
					//$tweet_data = $this->Content->getTweetData($unix_timestamp_and_id);			
					$content_id = $content['Content']['id'];
					$title = trim($content['Content']['title']);
					$desc = trim($content['Content']['desc']);
					$image_link = trim($content['Content']['link']);
					$coupon_code = trim($content['Content']['signup_coupon_code']);
					$coupon_line = trim($content['Content']['signup_offer']);
					$coupon_details = "";
					$coupon_valid_until_date = "";
					
					$company_id = $content['Content']['company_id'];
				}
				else
				{
					$errors = true;
					$result['msg'] = 'No such offer found that met the given criteria.';
				}
				
			}
			else if ('oc' == $objtype)
			{
				$oc_id = $post_data['objid'];
				
				$ocs_details = $this->OpenCampaign->getCampaignsByOCIds(array($oc_id));
				if (!empty($ocs_details[0]['OpenCampaign']))
				{
					$oc_detail = $ocs_details[0];
					$company_id = $oc_detail['OpenCampaign']['company_id'];
					$coupon_code = trim($oc_detail['OpenCampaign']['coupon_code']);
					$coupon_line = trim($oc_detail['OpenCampaign']['coupon_line']);
					$coupon_details = trim($oc_detail['OpenCampaign']['coupon_details']);
					$coupon_valid_until_date = trim($oc_detail['OpenCampaign']['coupon_valid_until_date']);
					
				}
				else
				{
					$errors = true;
					$result['msg'] = 'No such offer found that met the given criteria.';
				}
				
			}
			else
			{
				// we check for this condition already
			}
			
			// If we made so far, now check if the user owns the emails.
			if (!$errors)
			{
				$user_email = $this->UserData->getUserEmail();
				if ($user_email != $email1)
				{
					$entry = $this->UserEmail->user_owns_email($email1, $user_id);
					if (empty($entry['UserEmail']))
					{
						$result['msg'] = "Please verify this email - '{$email1}'";
						$errors = true;
					}
				}
				
				if (!$errors 
					&& ('dual_email_ns_signup' == $promo_method)
					&& ($user_email != $email2)
					)
				{
					$entry = $this->UserEmail->user_owns_email($email2, $user_id);
					if (empty($entry['UserEmail']))
					{
						$result['msg'] = "Please verify this email - '{$email2}'";
						$errors = true;
					}
				}
				
			}
			
			// if we made so far, now check if any email is already signed up
			if (!$errors)
			{
				$company = $this->Company->getRawCompanyInfo($company_id);
				$company_name = $company['Company']['name'];
				
				$first_email_signed_up = $this->NewsletterSignup->is_email_signedup($company_id, $email1);
				if ($first_email_signed_up)
				{
					$result['msg'] = "{$email1} has already been signedup to {$company_name}'s mailing list. Please enter a different email address.";
					$errors = true;
				}
				else
				{
					$emails[] = $email1;
					if ('dual_email_ns_signup' == $promo_method)
					{
						$second_email_signed_up = $this->NewsletterSignup->is_email_signedup($company_id, $email2);
						if ($second_email_signed_up)
						{
							$result['msg'] = "{$email2} has already been signedup to {$company_name}'s mailing list. Please enter a different email address.";
							$errors = true;
						}
						else
						{
							$emails[] = $email2;
						}
					}
				}
			}
			
			if (!$errors)
			{
				$email_str = implode(",", $emails); 
				
				$user_id_n_company_id_coupon = $this->UserCoupon->create_entry_name($user_id, $company_id, $coupon_code);
						
				$params = array('user_id' => $user_id, 
					'postid' => 0,
					'live_link' => $email_str,
					'json_promo_response' => $email_str, 
					'company_id' => $company_id, 
					'coupon_code' => $coupon_code, 
					'uid_cid_coupon_code' => $user_id_n_company_id_coupon,
					'promo_method' => $promo_method,
					'share_title' => '',
					'share_desc' => '',
					'share_image_link' => '',
					'share_news_title' => '',
					'share_news_link' => '',
					'update_like_count' => 0,
					'update_comment_count' => 0,
					'update_share_count' => 0,
					'update_retweet_count' => 0,
				);
				
				$add_result = array();
				
				if (!empty($oc_id))
				{
					$params['resp_for_obj_id'] = $oc_id;
					$add_result = $this->OcResponse->addRawGenResponseData($params);
					//$this->OcResponse->addRawTweetResponseData($user_id, $oc_id, $tweet_successful['resp']);
				}
				else if (!empty($content_id))
				{
					$params['resp_for_obj_id'] = $content_id;
					$add_result = $this->ContentPromotion->addRawGenResponseData($params);
					//$this->ContentPromotion->addRawTweetResponseData($user_id, $content_id, $tweet_successful['resp']);
				}
				
				// mark newsletter coupon code as earned by the user
				$added = $this->UserCoupon->add_entry($user_id_n_company_id_coupon);
				
				$verifier = "";
				if (!empty($add_result) 
					&& $add_result['success'] 
					&& !empty($add_result['saveid'])
					)
				{
					$verifier = $this->UserCoupon->build_verifier($add_result['saveid'], $user_id); 
				}
				
				$send_to_email = "";
				if ('single_email_ns_signup' == $promo_method)
				{
					$send_to_email = $user_email;
				}
				else if ('dual_email_ns_signup' == $promo_method)
				{
					if ($user_email == $email1)
					{
						$send_to_email = $email2;
					}
					else
					{
						$send_to_email = $email1;
					}
				}
				
				$email_params = array(
					'user_email' => $send_to_email,
					'user_name' => '',
					'company_name' => $company_name,
					'coupon_code' => $coupon_code,
					'coupon_line' => $coupon_line,
					'coupon_details' => $coupon_details,
					'coupon_valid_until_date' => $coupon_valid_until_date,
					'verifier' => $verifier
				);
				
				$this->EmailAccess->send_coupon_ary($email_params);
				
				$this->NewsletterSignup->signup_emails($emails, $company_id, $user_id);
				
				$result['success'] = 1;
				$result['msg'] = "Thank you for signing up. We have sent you your coupon at {$send_to_email} :).";
			}
			
		}
		
		if ($is_ajax)
		{
			$this->set('result', $result);
		}
		
		return $result;
	
	}
	
	public function get_bitly_url()
	{
		//CakePlugin::load('Bitly', 'default');
		//App::uses('BitlyAccess', 'Bitly.Model');
		$bitly_access_model = $this->BitlyAccess;//new BitlyAccess;
		//debug($this->BitlyAccess);return;
		$result = $bitly_access_model->getHash("http://www.cnn.com/2014/01/05/us/colorado-plane-crash/index.html?hpt=hp_t1");
		$this->set('result', $result);
	}
	
	public function twitter_update_image_test()
	{
		//App::uses('TmhOAuthInfComponent','Component');
		$twitter_inf = $this->TmhOAuthInf; //new TmhOAuthInfComponent;
		//$twitter_inf->initialize_tmh_lib();
		$result = $twitter_inf->login_user();
		if (false == $result)
		{
			$this->Session->setFlash("Could not login with twitter");
		}
		//$twitter_inf->initialize_tmh_lib();
		//$twitter_inf->verify_credentials();
		//$twitter_inf->status_update_with_media();
	}
}

?>