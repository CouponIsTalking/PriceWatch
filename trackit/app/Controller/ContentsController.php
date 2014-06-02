<?php
App::uses('AppController', 'Controller');
/**
 * Contents Controller
 *
 * @property Content $Content
 * @property PaginatorComponent $Paginator
 */
class ContentsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'RequestHandler', 'UserData', 'Misc');

	var $uses = array('Content', 'Company', 'UserCoupon');
	
	var $helpers = array ('Html', 'Youtube', 'Vimeo');
	
	
	public function getcoupon_button_click()//$static_content_timenid)
	{
		
		$this->layout = 'button_popup';
		//debug($this->params);
		//debug($this->params->query);
		
		$static_content_timenid = $this->params->query['id'];
		$from_url = $this->params->query['url'];
		
		$timenid_array = split('_', $static_content_timenid);
		
		$unix_timestamp = "";
		$id = "";
		if (!empty($timenid_array))
		{
			$unix_timestamp = $timenid_array[0];
			$id = $timenid_array[1];
		}
		
		$content = $this->Content->get_active_content_by_time_n_id($unix_timestamp, $id);
		$coupon_is_open = array ('fb' => 0, 'tw' => 0, 'pinit' => 0);
		
		$user_id = $this->UserData->getUserId();
		
		if (!empty($content['Content']) && !empty($user_id))
		{
			$content_id = $content['Content']['id'];
			$company_id = $content['Content']['company_id'];
			
			$user_id_content_id_fb_coupon_code = $this->UserCoupon->create_entry_name($user_id, $company_id, $content['Content']['fb_coupon_code']); 
			$user_id_content_id_tw_coupon_code = $this->UserCoupon->create_entry_name($user_id, $company_id, $content['Content']['tw_coupon_code']); 
			$user_id_content_id_pinit_coupon_code = $this->UserCoupon->create_entry_name($user_id, $company_id, $content['Content']['pinit_coupon_code']); 
			
			$entry_names = array($user_id_content_id_fb_coupon_code, $user_id_content_id_tw_coupon_code, $user_id_content_id_pinit_coupon_code);
			$entries_result = $this->UserCoupon->is_content_coupon_userid_entry_present($entry_names);
			
			$coupon_is_open['fb'] = $entries_result[$user_id_content_id_fb_coupon_code];
			$coupon_is_open['tw'] = $entries_result[$user_id_content_id_tw_coupon_code];
			$coupon_is_open['pinit'] = $entries_result[$user_id_content_id_pinit_coupon_code];
			
		}
		
		
		//$user_id = $this->UserData->getUserId();
		if (!empty($user_id))
		{
			$this->set('is_user_logged_in', 1);
		}
		else
		{
			$this->set('is_user_logged_in', 0);
		}
		
		$this->set('from_url', $from_url);
		$this->set('coupon_is_open', $coupon_is_open);
		
		$content['Content']['unix_timestamp_and_id'] = $this->Content->build_unix_timestamp_and_id($content['Content']['unix_timestamp'], $content['Content']['id']);
		$this->set('content', $content);
		
		
	}
	
	
	public function view_by_url()
	{
		if(!empty($this->params['url']['get_random'])) 
		{
			//$content = $this->Content->get_random();
			$content = $this->Content->get_last_day_random();
			$this->set('content_id', $content['Content']['id']);
			$this->set('url', urldecode($content['Content']['link']));
		}
		elseif(!empty($this->params['url']['content_url'])) 
		{
			$encoded_url = $this->params['url']['content_url'];
			$this->set('content_id', $this->params['url']['cid']);
			$this->set('url', urldecode($encoded_url));
		}	
	}
	
		
	public function set_content_state($content_id, $state)
	{
		$company_id = $this->UserData->getCompanyId();
		$have_access = false;
		
		if ($company_id)
		{
			$have_access = $this->Content->doesCompanyOwnContent($company_id, $content_id);
		}
		else
		{
			$have_access = $this->UserData->isAdmin();
		}
		
		if ($have_access)
		{
			$this->Content->set_content_state($content_id, $state);
			$result = 1;
		}
		else
		{
			$result = 0;
		}
		
		$this->set('result', $result);
		return $result;
	}
	
	public function view_by_company($company_id=null) {
		
		$this->set('editable', false);
		
		$only_show_active = true;
		
		$logged_in_company_id = $this->UserData->getCompanyId();
		if (!empty($logged_in_company_id))
		{
			$company_id = $logged_in_company_id;
		}
		
		if (empty($company_id))
		{
			$company_id = $this->UserData->getCompanyId();			
		}
		
		if (empty($company_id))
		{
			$this->Session->setFlash("Looks like, you are trying to manage your promotional material. Please, ensure that you are logged in.");
		}
		
		if ($this->UserData->isAdmin())
		{
			$this->set('editable', true);
			$only_show_active = false;
		}
		else
		{
			$logged_in_company_id = $this->UserData->getCompanyId();
			if ($company_id == $logged_in_company_id)
			{
				$this->set('editable', true);
				$only_show_active = false;
			}
			
		}
		
		$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $company_id));
		$this->set('company', $this->Company->find('first', $options));
		
		
		$this->set('company', $this->Company->getRawCompanyInfo($company_id));
		
		if ($only_show_active)
		{
			$content_data = $this->Content->get_active_content_by_company_id($company_id);
		}
		else
		{
			$content_data = $this->Content->get_by_company_id($company_id);
		}
		
		$this->set('contents', $content_data);
		
		return $content_data;
		//$topic_data = $this->Topic->getTopicList();
		//$this->set('topic_data', $topic_data);
		//$this->set('is_blogger', $this->User->isBlogger());
		
	}
	
	public function dynamic_offer_contents_by_company($company_id=null) {
		
		$this->set('editable', false);
		
		$only_show_active = true;
		
		if (empty($company_id))
		{
			$company_id = $this->UserData->getCompanyId();			
		}
		
		if (empty($company_id))
		{
			$this->Session->setFlash("Looks like, you are trying to manage your promotional material. Please, ensure that you are logged in.");
		}
		
		if ($this->UserData->isAdmin())
		{
			$this->set('editable', true);
			$only_show_active = false;
		}
		else
		{
			$logged_in_company_id = $this->UserData->getCompanyId();
			if ($company_id == $logged_in_company_id)
			{
				$this->set('editable', true);
				$only_show_active = false;
			}
			
		}
		
		$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $company_id));
		$this->set('company', $this->Company->find('first', $options));
		
		
		$this->set('company', $this->Company->getRawCompanyInfo($company_id));
		
		if ($only_show_active)
		{
			$content_data = $this->Content->get_active_content_by_company_id($company_id);
		}
		else
		{
			$content_data = $this->Content->get_by_company_id($company_id);
		}
		
		$this->set('contents', $content_data);
		
		return $content_data;
		//$topic_data = $this->Topic->getTopicList();
		//$this->set('topic_data', $topic_data);
		//$this->set('is_blogger', $this->User->isBlogger());
		
	}
	
	public function fixed_offers_by_company($company_id=null) {
		
		$this->set('editable', false);
		
		$only_show_active = true;
		
		if (empty($company_id))
		{
			$company_id = $this->UserData->getCompanyId();			
		}
		
		if (empty($company_id))
		{
			$this->Session->setFlash("Looks like, you are trying to manage your promotional material. Please, ensure that you are logged in.");
		}
		
		if ($this->UserData->isAdmin())
		{
			$this->set('editable', true);
			$only_show_active = false;
		}
		else
		{
			$logged_in_company_id = $this->UserData->getCompanyId();
			if ($company_id == $logged_in_company_id)
			{
				$this->set('editable', true);
				$only_show_active = false;
			}
			
		}
		
		$options = array('conditions' => array('Company.' . $this->Company->primaryKey => $company_id));
		$this->set('company', $this->Company->find('first', $options));
		
		
		$this->set('company', $this->Company->getRawCompanyInfo($company_id));
		
		if ($only_show_active)
		{
			$content_data = $this->Content->get_active_fixed_offers_by_company_id($company_id);
		}
		else
		{
			$content_data = $this->Content->get_fixed_offers_by_company_id($company_id);
			//$content_data = $this->Content->get_by_company_id($company_id);
		}
		
		$this->set('contents', $content_data);
		
		return $content_data;
		//$topic_data = $this->Topic->getTopicList();
		//$this->set('topic_data', $topic_data);
		//$this->set('is_blogger', $this->User->isBlogger());
		
	}
		
	public function redirect_link($timestamp, $title_slug)
	{
		$this->layout = 'ajax';
		
		$timestamped_title_slug = $timestamp . '_' . $title_slug;
		
		$content = $this->Content->find('first', array('recursive' => -1, 'conditions' => array('timestamped_title_slug' => $timestamped_title_slug)));
		
		$company_id = $content['Content']['company_id'];
		$company = $this->Company->getRawCompanyInfoById($company_id);
		
		if (!empty($company['Company']['website']))
		{
			$redirect_to = $this->Misc->addhttp($company['Company']['website']);
		}
		else
		{
			$redirect_to = $this->Misc->addhttp(SITE_NAME);
		}
		
		$this->set('redirect_to', $redirect_to);
	}

	
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Content->recursive = 0;
		$this->set('contents', $this->Paginator->paginate());

		$product_data = $this->Product->getProductList();
		$this->set('product_data', $product_data);
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
	
		$topic_data = $this->Topic->getTopicList();
		$this->set('topic_data', $topic_data);
	}

		
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Content->exists($id)) {
			throw new NotFoundException(__('Invalid content'));
		}
		$options = array('conditions' => array('Content.' . $this->Content->primaryKey => $id));
		$this->set('content', $this->Content->find('first', $options));
	
		$product_data = $this->Product->getProductList();
		$this->set('product_data', $product_data);
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);	
		
		$topic_data = $this->Topic->getTopicList();
		$this->set('topic_data', $topic_data);
		
	}
	
	public function image_import_from_fb()
	{
		$result = array('success' => 0, 'msg'=> "");
		
		$is_ajax = $this->RequestHandler->isAjax();
		
		$data = $this->request->data;
		//debug($data);
		$company_id = $this->UserData->getCompanyId();
		$product_id = 0;
		
		if (empty($company_id))
		{
			$result = array('success' => 0, 'msg'=> "Please log in to upload content.");
		}
		else if (empty($data['images']))
		{
			$result = array('success' => 0, 'msg'=> "No new image to update!");
		}
		else
		{
			$contents = array();
			$ajax_img_data = $data['images'];
			
			foreach($ajax_img_data as $img_link => $img_info)
			{
				$img_title = $img_info['title'];
				$fbimgid = $img_info['fbimgid'];
				$fbimglink = $img_info['fbimglink'];
				
				$title_slug = $this->Misc->slugify($img_title);
				$extension = substr(strrchr($img_link,'.'),1);
				
				$image_name_starter = substr ($title_slug, 0, 20);
				do
				{
					$image_name = strval(rand(1,1000000)). date("Y_m_d_H_i_s__") . "_{$company_id}_{$product_id}__{$image_name_starter}.{$extension}";
					$full_name = CONTENT_IMG_FOLDER_LOCAL_PATH . $image_name;
				}while (file_exists ($full_name));
				
				$has_https = strpos(strtolower($img_link),"https");
				if ((FALSE !== $has_https) && (0==$has_https))
				{
					$img_link = "http" . substr($img_link, 5);
				}
				
				$ch = curl_init($img_link);
				$image_file_handle = fopen($full_name,"wb");
				
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 50);
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				curl_setopt($ch, CURLOPT_FILE, $image_file_handle);
				curl_setopt($ch, CURLOPT_HEADER, 0);
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
				curl_setopt($ch, CURLOPT_MAXREDIRS, 3 );
				
				$result_curl = curl_exec($ch);
				$errormsg_curl = curl_error($ch);
				//debug($result_curl);
				//debug($error_curl);
				curl_close($ch);
				fclose($image_file_handle);
				
				if ( "" == $errormsg_curl && $result_curl && is_readable($full_name) )
				{
					$moved = true;
				}
				
				$img_our_link = CONTENT_IMG_FOLDER. $image_name ; // /* note : this is not CONTENT_IMG_FOLDER_LOCAL_PATH */
							
				if ($moved)
				{
					$content = array();
					$content['company_id'] = $company_id;
					$content['product_id'] = $product_id;
					$content['type'] = 'image';
					$content['title'] = $img_title;
					$content['desc'] = $img_link;
					$content['fbobjectid'] = $fbimgid;
					$content['fbobjecturl'] = $fbimglink;
					$content['link'] = $img_our_link;
					$content['topic1'] = 0;			
					$content['unix_timestamp'] = time();
					$content['title_slug'] = $title_slug;
					$content['timestamped_title_slug'] = $content['unix_timestamp'] . '_'.$content['title_slug'];
					$content['state'] = 1;			
					//$data['Content']['has_fixed_offer'] = 1;
					$content['has_fixed_social_coupons'] = 0;
					$contents[] = array('Content' => $content);
				}
				
				/*
				// check permissions
				debug($tmp_name);
				debug(CONTENT_IMG_FOLDER_LOCAL_PATH . $image_name);
				debug (is_readable($tmp_name));
				debug (is_writable (CONTENT_IMG_FOLDER_ALIAS));
				debug (is_writable (CONTENT_IMG_FOLDER_LOCAL_PATH));
				*/
				
				// hack - because, move_uploaded_file is not working for some reason.
				
				
			}
			
			$saved = $this->Content->saveAll($contents);
			
			if (empty($saved))
			{
				$errors = $this->Content->validationErrors;
				$result = array('success' => 0, 'msg'=> "An error occured when saving images.");
			}
			else
			{
				$result = array('success' => 1, 'msg'=> "");
			}
		}
		
		$this->set('result', $result);
	}
	
	public function batch_obj_import_from_fb()
	{
		$result = array('success' => 0, 'msg'=> "");
		
		$is_ajax = $this->RequestHandler->isAjax();
		
		$data = $this->request->data;
		//debug($data);
		$company_id = $this->UserData->getCompanyId();
		$product_id = 0;
		
		if (empty($company_id))
		{
			$result = array('success' => 0, 'msg'=> "Please log in to upload content.");
		}
		else if (empty($data['objs']))
		{
			$result = array('success' => 0, 'msg'=> "No new object to update!");
		}
		else
		{
			$contents = array();
			$json_encoded_obj_data = $data['objs'];
			$ajax_obj_data = json_decode($json_encoded_obj_data, true);
			
			$type = $data['type'];
			
			foreach($ajax_obj_data as $fbobjid => $per_object_data)
			{
				//$json_obj_detail = $per_object_data['od'];
				$obj_detail = $per_object_data['od']; //json_decode($json_obj_detail, true);
				$json_obj_detail = json_encode($obj_detail);
				
				if (empty($obj_detail)) { continue;}
				
				if ('vdo' == $type)
				{
					$total_formats = count($obj_detail['format']);
					$format_to_pick = 0;
					for ($i = 0; $i< $total_formats; $i++)
					{
						if ($obj_detail['format'][0]['width'] < 480)
						{
							$format_to_pick = $i;
						}
						else if (480 == $obj_detail['format'][0]['width'])
						{
							$format_to_pick = $i;
							break;
						}
					}
					
					$video_embed_html 	= $obj_detail['format'][$format_to_pick]['embed_html'];
					$img_link 			= $obj_detail['format'][$format_to_pick]['picture'];
					$content_type 		= 'video';
				}
				
				$obj_title = substr($obj_detail['description'], 0, 20);
				$title_slug = $this->Misc->slugify($obj_title);
				$extension = substr(strrchr($img_link,'.'),1);
				
				$image_name_starter = $fbobjid;
				do
				{
					$image_name = strval(rand(1,1000000)). date("Y_m_d_H_i_s__") . "_{$company_id}_{$product_id}__{$image_name_starter}.{$extension}";
					$full_name = CONTENT_IMG_FOLDER_LOCAL_PATH . $image_name;
				}while (file_exists ($full_name));
				
				$has_https = strpos(strtolower($img_link),"https");
				if ((FALSE !== $has_https) && (0==$has_https))
				{
					$img_link = "http" . substr($img_link, 5);
				}
				
				$ch = curl_init($img_link);
				$image_file_handle = fopen($full_name,"wb");
				
				curl_setopt($ch, CURLOPT_TIMEOUT, 20);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20 );
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				curl_setopt($ch, CURLOPT_FILE, $image_file_handle);
				curl_setopt($ch, CURLOPT_HEADER, 0);
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
				curl_setopt($ch, CURLOPT_MAXREDIRS, 3 );
				
				$result_curl = curl_exec($ch);
				$errormsg_curl = curl_error($ch);
				//debug($result_curl);
				//debug($error_curl);
				curl_close($ch);
				fclose($image_file_handle);
				
				if ( "" == $errormsg_curl && $result_curl && is_readable($full_name) )
				{
					$moved = true;
				}
				
				$img_our_link = CONTENT_IMG_FOLDER. $image_name ; // /* note : this is not CONTENT_IMG_FOLDER_LOCAL_PATH */
							
				if ($moved)
				{
					$content = array();
					$content['company_id'] = $company_id;
					$content['product_id'] = $product_id;
					$content['type'] = $content_type;
					$content['title'] = '';
					$content['desc'] = $json_obj_detail;
					$content['fbobjectid'] = $fbobjid;
					$content['fbobjecturl'] = $video_embed_html;
					$content['link'] = $img_our_link;
					$content['topic1'] = 0;			
					$content['unix_timestamp'] = time();
					$content['title_slug'] = '';
					$content['timestamped_title_slug'] = $content['unix_timestamp'] . '_'.$content['title_slug'];
					$content['state'] = 1;			
					//$data['Content']['has_fixed_offer'] = 1;
					$content['has_fixed_social_coupons'] = 0;
					$contents[] = array('Content' => $content);
				}
				
				/*
				// check permissions
				debug($tmp_name);
				debug(CONTENT_IMG_FOLDER_LOCAL_PATH . $image_name);
				debug (is_readable($tmp_name));
				debug (is_writable (CONTENT_IMG_FOLDER_ALIAS));
				debug (is_writable (CONTENT_IMG_FOLDER_LOCAL_PATH));
				*/
				
				// hack - because, move_uploaded_file is not working for some reason.
				
				
			}
			
			$saved = $this->Content->saveAll($contents);
			
			if (empty($saved))
			{
				$errors = $this->Content->validationErrors;
				$result = array('success' => 0, 'msg'=> "An error occured when saving images.");
			}
			else
			{
				$result = array('success' => 1, 'msg'=> "");
			}
		}
		
		$this->set('result', $result);
	}
	
/**
 * add method
 *
 * @return void
 */
	public function add() {
		$company_id = $this->UserData->getCompanyId();
		
		if (!$company_id)
		{
			$msg = 'Please login as a company to add advertising contents.';
			$msg_type = 'error_msg';
			$this->Session->setFlash(__($msg));
			//$this->show_modal_msg_on_blank_page($msg, $msg_type);
			$this->redirect('/');
			return null;
		}
		
		//$raw_products = $this->Product->getRawProductsByCompanyId($company_id);
		//$product_data = $this->Product->compactProductData($raw_products);
		//$this->set('product_data', $product_data);
		
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$data['Content']['company_id'] = $company_id;
			$data['Content']['topic1'] = 0;
			
			//$product_id = $data['Content']['product_id'];
			$type = $data['Content']['type'];
			$title = $data['Content']['title'];
			$desc = $data['Content']['desc'];
			
			// media 
			/*
			$news_link = $data['Content']['news_link'];
			$video_link = $data['Content']['video_link'];
			$image = $data['Content']['image'];
			*/
			
			/*
			$allowed = $this->Product->doesCompanyOwnsProduct($company_id, $product_id);
			
			if (!$allowed)
			{
				$msg = 'This error would occur if you are trying to add content for a product you dont own. We don\'t expect this error to occur, however if it persists, please contact us and we\'ll resolve it immediately.'; 
				$msg_type = 'error_msg';
				$this->Session->setFlash(__($msg));
				return null;	
			}
			*/
			
			if (empty($title))
			{
				$msg = 'Please provide a title(name) for this content. The title may be used by promoters as it is given, so choose an attractive one.'; 
				$msg_type = 'error_msg';
				$this->Session->setFlash(__($msg));
				return null;	
			}
			
			if (empty($desc))
			{
				$msg = 'Please provide a little description for this content. The description may be used by promoters as it is given, so choose an attractive one.'; 
				$msg_type = 'error_msg';
				$this->Session->setFlash(__($msg));
				return null;	
			}
			
			$valid_type = $this->Content->isValidContentType($type);
			if (!$valid_type)
			{
				$msg = 'This error would occur if you are trying to add content of type other than news, image or video. We don\'t expect this error to occur, however if it persists, please contact us and we\'ll resolve it immediately.'; 
				$msg_type = 'error_msg';
				$this->Session->setFlash(__($msg));
				return null;	
			}
			else
			{
				$link = "";
				$error = true;
				$msg = "";
				
				if ($type == 'news') 
				{
					if(!empty($data['Content']['news_link']))
					{
						$data['Content']['link'] = $data['Content']['news_link'];
						$error = false;
					}
					if ($error)
					{
						$msg = 'Please provide the web link of news or blog.'; 
					}
					
				}
				else if ($type == 'video')
				{
					$error = true;
					if(!empty($data['Content']['video_link']))
					{
						$video_link = $data['Content']['video_link'];
						$add_it = ((strpos($video_link, 'youtube.com') > 0) || (strpos($video_link, 'vimeo.com') > 0));
						if ($add_it)
						{
							$data['Content']['link'] = $data['Content']['video_link'];
							$error = false;
						}
					}
					if ($error)
					{
						$msg = 'Please provide a youtube or vimeo link of the video.'; 
					}
				}
				else if ($type == 'image')
				{
					//debug($this->request);
					if (!empty($data['Content']['image']))
					{
						$image_name = "";
						$full_name = "";
						$image = $data['Content']['image'];
						if (strlen($image['name']) > 100)
						{
							$msg = 'Image name is too long. It can be upto 100 characters long. Please rename the image to a smaller name and then upload.';
						}
						else
						{
							do
							{
								$image_name = strval(rand(1,10000)). date("Y_m_d_H_i_s__") . "_{$company_id}_{$product_id}__{$image['name']}";
								$full_name = CONTENT_IMG_FOLDER_LOCAL_PATH . $image_name;
							}while (file_exists ($full_name));
							
							$tmp_name = str_replace ("\\", "\\\\", $image['tmp_name']);
							
							/*
							// check permissions
							debug($tmp_name);
							debug(CONTENT_IMG_FOLDER_LOCAL_PATH . $image_name);
							debug (is_readable($tmp_name));
							debug (is_writable (CONTENT_IMG_FOLDER_ALIAS));
							debug (is_writable (CONTENT_IMG_FOLDER_LOCAL_PATH));
							*/
							
							// hack - because, move_uploaded_file is not working for some reason.
							$image_file_handle = fopen($full_name,"wb");
							$tmp_handle = fopen($tmp_name, "r");
							$contents = fread($tmp_handle, filesize($tmp_name));
							fwrite($image_file_handle, $contents, filesize($tmp_name));
							fclose($tmp_handle);
							fclose($image_file_handle);
							if ( is_readable($full_name) )
							{
								$moved = true;
							}
							
							/*$moved = move_uploaded_file($tmp_name, CONTENT_IMG_FOLDER_LOCAL_PATH . 'imagedingdong.png'); //$image_name);
							if (!$moved)
								$moved = move_uploaded_file($tmp_name, CONTENT_IMG_FOLDER_ALIAS . "/" . $image_name);
							*/
						}
						if ($moved)
						{
							// store the filename in the array to be saved to the db
							$link = CONTENT_IMG_FOLDER. $image_name ; // /* note : this is not CONTENT_IMG_FOLDER_LOCAL_PATH */
							$data['Content']['link'] = $link;
							$error = false;
						}
						else
						{
							$msg = 'There was an error in uploading image. Please ensure that you are picking right image. If the problem persists, let us know and we\'ll resolve it immediately.'; 
							$msg_type = 'error_msg';
							$this->Session->setFlash(__($msg));
							return null;	
						}
					}
					if ($error)
					{
						$msg = 'This error would occur if you are trying to add content of type other than news, image or video. We don\'t expect this error to occur, however if it persists, please contact us and we\'ll resolve it immediately.'; 
					}
				}
				
				// If couldn't find the content matching with the content type, or there was
				// any issue in uploading then show error message.
				if ($error)
				{
					$this->Session->setFlash($msg);
					return null;
				}
				
			}
			
			if ($type == 'news')
			{
				$msg = 'The news/blog promotional content is now saved.';
			}
			else if ($type == 'image')
			{
				$msg = 'The image for promotions is now uploaded.';
			}
			else if ($type == 'video')
			{
				$msg = 'The promotinal video is now uploaded.';
			}
			
			$data['Content']['unix_timestamp'] = time();
			$data['Content']['title_slug'] = $this->Misc->slugify($data['Content']['title']);
			$data['Content']['timestamped_title_slug'] = $data['Content']['unix_timestamp'] . '_'.$data['Content']['title_slug'];
			//$data['Content']['has_fixed_offer'] = 1;
			$data['Content']['has_fixed_social_coupons'] = 0;
			
			$this->Content->create();
			$content_stuff = array('Content' => $data['Content']);
			if ($this->Content->save($content_stuff)) {
				
				$this->Session->setFlash("Sharing Content Uploaded.");
				
				return $this->redirect(array('action' => 'view_by_company'));
			}
			else
			{
				$this->Session->setFlash(__('The content could not be saved for promotions. Please, try again. If the problem persists, let us know and we\'ll resolve it immediately.'));
			}
			
		}
		
		
		//$topic_data = $this->Topic->getTopicList();
		//$this->set('topic_data', $topic_data);
	}

/**
 * add_static_coupon method
 *
 * @return void
 */
	public function add_static_coupon() {
		
		$company_id = $this->UserData->getCompanyId();
		$is_admin = $this->UserData->isAdmin();
		
		if (!$is_admin && !$company_id)
		{
			$msg = 'Please login as a company to add advertising contents.';
			$msg_type = 'error_msg';
			$this->Session->setFlash(__($msg));
			//$this->show_modal_msg_on_blank_page($msg, $msg_type);
			$this->redirect('/');
			return null;
		}
		
		
		if ($this->request->is('post')) {
			$data = $this->request->data;
			if (!empty($company_id))
			{
				$data['Content']['company_id'] = $company_id;
			}
			
			$data['Content']['topic1'] = 0;
			
			$type = $data['Content']['type'];
			$title = $data['Content']['title'];
			$desc = $data['Content']['desc'];
			
			$fb_offer = $data['Content']['fb_offer'];
			$tw_offer = $data['Content']['tw_offer'];
			
			$fb_coupon_code = $data['Content']['fb_coupon_code'];
			$tw_coupon_code = $data['Content']['tw_coupon_code'];
			
			// media 
			/*
			$news_link = $data['Content']['news_link'];
			$video_link = $data['Content']['video_link'];
			$image = $data['Content']['image'];
			*/
			
			
			if (empty($title))
			{
				$msg = 'Please provide a title(name) or tag line for this offer.'; 
				$msg_type = 'error_msg';
				$this->Session->setFlash(__($msg));
				return null;	
			}
			
			if (empty($desc))
			{
				$msg = 'Please provide a little description for this offer.'; 
				$msg_type = 'error_msg';
				$this->Session->setFlash(__($msg));
				return null;	
			}
			
			$valid_type = $this->Content->isValidContentType($type);
			if (!$valid_type)
			{
				$msg = 'This error would occur if you are trying to add content of type other than news, image or video. We don\'t expect this error to occur, however if it persists, please contact us and we\'ll resolve it immediately.'; 
				$msg_type = 'error_msg';
				$this->Session->setFlash(__($msg));
				return null;	
			}
			else
			{
				$link = "";
				$error = true;
				$msg = "";
				
				if ($type == 'news') 
				{
					if(!empty($data['Content']['news_link']))
					{
						$data['Content']['link'] = $data['Content']['news_link'];
						$error = false;
					}
					if ($error)
					{
						$msg = 'Please provide the web link of news or blog.'; 
					}
					
				}
				else if ($type == 'video')
				{
					$error = true;
					if(!empty($data['Content']['video_link']))
					{
						$video_link = $data['Content']['video_link'];
						$add_it = ((strpos($video_link, 'youtube.com') > 0) || (strpos($video_link, 'vimeo.com') > 0));
						if ($add_it)
						{
							$data['Content']['link'] = $data['Content']['video_link'];
							$error = false;
						}
					}
					if ($error)
					{
						$msg = 'Please provide a youtube or vimeo link of the video.'; 
					}
				}
				else if ($type == 'image')
				{
					//debug($this->request);
					if (!empty($data['Content']['image']))
					{
						$image_name = "";
						$full_name = "";
						$image = $data['Content']['image'];
						if (empty($image['name']))
						{
							$msg = 'Please pick an image for sharing.';
						}
						else if (strlen($image['name']) > 100)
						{
							$msg = 'Image name is too long. It can be upto 100 characters long. Please rename the image to a smaller name and then upload.';
						}
						else
						{
							do
							{
								$image_name = strval(rand(1,1000000)). date("Y_m_d_H_i_s__") . "_{$company_id}_{$image['name']}";
								$full_name = CONTENT_IMG_FOLDER_LOCAL_PATH . $image_name;
							}while (file_exists ($full_name));
							
							$tmp_name = str_replace ("\\", "\\\\", $image['tmp_name']);
							
							/*
							// check permissions
							debug($tmp_name);
							debug(CONTENT_IMG_FOLDER_LOCAL_PATH . $image_name);
							debug (is_readable($tmp_name));
							debug (is_writable (CONTENT_IMG_FOLDER_ALIAS));
							debug (is_writable (CONTENT_IMG_FOLDER_LOCAL_PATH));
							*/
							
							// hack - because, move_uploaded_file is not working for some reason.
							$image_file_handle = fopen($full_name,"wb");
							$tmp_handle = fopen($tmp_name, "r");
							$contents = fread($tmp_handle, filesize($tmp_name));
							fwrite($image_file_handle, $contents, filesize($tmp_name));
							fclose($tmp_handle);
							fclose($image_file_handle);
							
							$moved = false;
							if ( is_readable($full_name) )
							{
								$moved = true;
							}
							
							/*$moved = move_uploaded_file($tmp_name, CONTENT_IMG_FOLDER_LOCAL_PATH . 'imagedingdong.png'); //$image_name);
							if (!$moved)
								$moved = move_uploaded_file($tmp_name, CONTENT_IMG_FOLDER_ALIAS . "/" . $image_name);
							*/
						}
						if ($moved)
						{
							// store the filename in the array to be saved to the db
							$link = CONTENT_IMG_FOLDER. $image_name ; // /* note : this is not CONTENT_IMG_FOLDER_LOCAL_PATH */
							$data['Content']['link'] = $link;
							$error = false;
						}
						else
						{
							$msg = 'There was an error in uploading image. Please ensure that you are picking right image. If the problem persists, let us know and we\'ll resolve it immediately.'; 
							$msg_type = 'error_msg';
							$this->Session->setFlash(__($msg));
							return null;	
						}
					}
					if ($error)
					{
						$msg = 'This error would occur if you are trying to add content of type other than news, image or video. We don\'t expect this error to occur, however if it persists, please contact us and we\'ll resolve it immediately.'; 
					}
				}
				
				// If couldn't find the content matching with the content type, or there was
				// any issue in uploading then show error message.
				if ($error)
				{
					$this->Session->setFlash($msg);
					return null;
				}
				
			}
			
			
			$data['Content']['unix_timestamp'] = time();
			$data['Content']['title_slug'] = $this->Misc->slugify($data['Content']['title']);
			$data['Content']['timestamped_title_slug'] = $data['Content']['unix_timestamp'] . '_'.$data['Content']['title_slug'];
			//$data['Content']['has_fixed_offer'] = 1;
			$data['Content']['has_fixed_social_coupons'] = 1;
			$this->Content->create();
			//$this->set('content_stuff', $data);
			if ($this->Content->save($data)) {
				
				$this->Session->setFlash("Your Simple SocialCoupon is successfully created.");
				
				return $this->redirect(array('action' => 'fixed_offers_by_company'));
			}
			else
			{
				$this->Session->setFlash(__('The content could not be saved for promotions. Please, try again. If the problem persists, let us know and we\'ll resolve it immediately.'));
			}
			
		}
		
		
		//$topic_data = $this->Topic->getTopicList();
		//$this->set('topic_data', $topic_data);
	}

	public function like_an_item($item_id)
	{
		$result = 0;
		if (!empty($item_id))
		{
			$result = $this->Content->increment_like($item_id);
		}
		
		$this->set('result', $result);
		return $result;
	}
	
		
	public function share_item_from_user() {
		
		$error = false;
		$msg = "";
		
		$user_id = $this->UserData->getUserId();
		
		if ($this->RequestHandler->isAjax()) {
			
			$this->layout = 'ajax';
			
			$data = $this->request->data;
			$type = $data['linktype'];
			$goal = $data['goal'];
			$link = $data['link'];
			
			if(!empty($link))
			{
				$data['Content']['link'] = $link;
			}
			else
			{
				$error = true;
				$msg = 'Please provide the web link of the item you are trying to share.'; 
			}

			$valid_type = $this->Content->isValidContentType($type);
			if (!$valid_type) 
			{
				$error = true;
				$msg = 'We currently support a news, image or video article only.'; 
				
			}
			
			if ('video' == $type)
			{
				$add_it = (strpos($link, 'youtube.com') > 0);
				if (empty($add_it))
				{
					$error = true;
					$msg = 'We currently support youtube videos only. Please provide a link of youtube video.'; 
				}
			}
			
			// If couldn't find the content matching with the content type, or there was
			// any issue in uploading then show error message.
			if ($error)
			{
				$result['success'] = 0;
				$result['msg'] = $msg;
			}
			else
			{
				$content_stuff = array('Content'=>array());
				$content_stuff['Content']['company_id'] = -1;
				$content_stuff['Content']['product_id'] = 0;
				$content_stuff['Content']['title'] = 'SharedByUser';
				$content_stuff['Content']['desc'] = 'SharedByUser';
				$content_stuff['Content']['link'] = $link;
				$content_stuff['Content']['topic1'] = 0;
				$content_stuff['Content']['state'] = 1;
				$content_stuff['Content']['type'] = $type;
				if (!empty($user_id))
				{
					$content_stuff['Content']['shared_by'] = $user_id;
				}
				else
				{
					$content_stuff['Content']['shared_by'] = 0;
				}
				$content_stuff['Content']['likes'] = 1;
				$content_stuff['Content']['un_goal_id'] = $goal;
				
				$this->Content->create();
				if ($this->Content->save($content_stuff)) {
					
					$result['success'] = 1;
					$result['msg'] = "";
				}
				else
				{
					$result['success'] = 0;
					$result['msg'] = "";
				}
				
				$this->Surfer->AddSite($link);
				
			}
			
			$this->set('result', $result);
			return $result;
			
		}
		
		
		//$topic_data = $this->Topic->getTopicList();
		//$this->set('topic_data', $topic_data);
	}

	public function choose_content($company_id, $get_all = 'no')
	{
		$logged_in_company_id = $this->UserData->getCompanyId();
		if (!empty($logged_in_company_id))
		{
			$company_id = $logged_in_company_id;
		}
		
		if ('all' == $get_all)
		{
			$content_data = $this->Content->get_by_company_id($company_id);	
		}
		else
		{
			$content_data = $this->Content->get_active_content_by_company_id($company_id);	
		}
		
		$this->set('content_data', $content_data);
		return $content_data;
	}
	
	
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Content->exists($id)) {
			throw new NotFoundException(__('Invalid content'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Content->save($this->request->data)) {
				$this->Session->setFlash(__('The content has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The content could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('Content.' . $this->Content->primaryKey => $id));
			$this->request->data = $this->Content->find('first', $options);
		}
		
		$company_data = $this->Company->getCompanyList();
		$this->set('company_data', $company_data);
		
		$product_data = $this->Product->getProductList();
		$this->set('product_data', $product_data);
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Content->id = $id;
		if (!$this->Content->exists()) {
			throw new NotFoundException(__('Invalid content'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Content->delete()) {
			$this->Session->setFlash(__('Content deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Content was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Content->recursive = 0;
		$this->set('contents', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Content->exists($id)) {
			throw new NotFoundException(__('Invalid content'));
		}
		$options = array('conditions' => array('Content.' . $this->Content->primaryKey => $id));
		$this->set('content', $this->Content->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Content->create();
			if ($this->Content->save($this->request->data)) {
				$this->Session->setFlash(__('The content has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The content could not be saved. Please, try again.'));
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
		if (!$this->Content->exists($id)) {
			throw new NotFoundException(__('Invalid content'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Content->save($this->request->data)) {
				$this->Session->setFlash(__('The content has been saved'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('The content could not be saved. Please, try again.'));
		} else {
			$options = array('conditions' => array('Content.' . $this->Content->primaryKey => $id));
			$this->request->data = $this->Content->find('first', $options);
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
		$this->Content->id = $id;
		if (!$this->Content->exists()) {
			throw new NotFoundException(__('Invalid content'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Content->delete()) {
			$this->Session->setFlash(__('Content deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Content was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}
}
