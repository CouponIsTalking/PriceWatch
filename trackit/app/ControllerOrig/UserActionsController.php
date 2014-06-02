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

	var $uses = array('Content', 'Company', 'OpenCampaign', 'Company', 'UserCoupon', 'ContentPromotion', 'OcResponse', 'Bitly.BitlyAccess');
	
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
			if (!empty($full_resp['permalink']))
			{
				$live_link = $full_resp['permalink'];
			}
			
			$user_id_n_company_id_coupon = $this->UserCoupon->create_entry_name($user_id, $company_id, $coupon_code);
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
			else if ('tweet' == $promo_method || 'tw' == $promo_method)
			{
				$params['promo_method'] = 'tweet';
			}
			
			if ('oc' == $promo_for)
			{
				$this->OcResponse->addRawGenResponseData($params);
				/*
				if ('fb_post' == $promo_method)
				{
					$this->OcResponse->addRawFBResponseData($user_id, $oc_id, $json_promo_response, $company_id, $coupon_code, $user_id_n_company_id_coupon);
				}
				else if ('tweet' == $promo_method || 'tw' == $promo_method)
				{
					$this->OcResponse->addRawTweetResponseData($user_id, $oc_id, $json_promo_response, $company_id, $coupon_code);
				}
				*/
			}
			else if ('content' == $promo_for)
			{
				$this->ContentPromotion->addRawGenResponseData($params);
				/*
				if ('fb_post' == $promo_method)
				{
					$this->ContentPromotion->addRawFBResponseData($user_id, $content_id, $json_promo_response, $company_id, $coupon_code);
				}
				else if ('tweet' == $promo_method || 'tw' == $promo_method)
				{
					$this->ContentPromotion->addRawTweetResponseData($user_id, $content_id, $json_promo_response, $company_id, $coupon_code);
				}
				*/
			}
			
			// mark twitter coupon code as earned by the user
			$added = $this->UserCoupon->add_entry($user_id_n_company_id_coupon);
			
			if ('fb_post' == $promo_method)
			{
				$this->Promos->add_new_promo_time_as_now($user_id, 'fb');
			}
			else if ('tweet' == $promo_method || 'tw' == $promo_method)
			{
				$this->Promos->add_new_promo_time_as_now($user_id, 'tw');
			}
			
			$company_name = $this->Company->getCompanyNameById($company_id);
			$this->EmailAccess->send_coupon($user_email, "", $company_name, $coupon_code, $coupon_line, $coupon_details, $coupon_valid_until_date);
			
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
				
				//$title = $post_data['tw_title'];
				//$desc = $post_data['tw_desc'];
				(!empty($post_data['tw_title'])) ? $title = $post_data['tw_title']: $title = $post_data['default_title'];
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
						
						if (!empty($oc_id))
						{
							$params['resp_for_obj_id'] = $oc_id;
							$this->OcResponse->addRawGenResponseData($params);
							//$this->OcResponse->addRawTweetResponseData($user_id, $oc_id, $tweet_successful['resp']);
						}
						else if (!empty($content_id))
						{
							$params['resp_for_obj_id'] = $content_id;
							$this->ContentPromotion->addRawGenResponseData($params);
							//$this->ContentPromotion->addRawTweetResponseData($user_id, $content_id, $tweet_successful['resp']);
						}
						
						// mark twitter coupon code as earned by the user
						$added = $this->UserCoupon->add_entry($user_id_n_company_id_coupon);
						$this->Promos->add_new_promo_time_as_now($user_id, 'tw');
						
						$company_name = $this->Company->getCompanyNameById($company_id);
						$this->EmailAccess->send_coupon($user_email, "", $company_name, $tw_coupon_code, $coupon_line, $coupon_details, $coupon_valid_until_date);
						
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