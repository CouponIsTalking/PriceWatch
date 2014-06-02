<?php
App::uses('AppModel', 'Model');
/**
 * OcResponse Model
 *
 */
class OcResponse extends AppModel {


	public function getType($ocr)
	{
		return $ocr['OcResponse']['response_type'];
	}
	
	public function mark_accepted($ocr_id)
	{
		$ocr = $this->getRawResponseByOcrId($ocr_id);
		if (empty($ocr))
		{
			return false;
		}
		
		$ocr['OcResponse']['processed'] = 1;
		$ocr['OcResponse']['processing_result'] = 1;
		$this->id = $ocr['OcResponse']['id'];
		
		if ($this->save($ocr))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	public function mark_unaccepted($ocr_id)
	{
		$ocr = $this->getRawResponseByOcrId($ocr_id);
		if (empty($ocr))
		{
			return false;
		}
		
		$ocr['OcResponse']['processed'] = 1;
		$ocr['OcResponse']['processing_result'] = 0;
		$this->id = $ocr['OcResponse']['id'];
		
		if ($this->save($ocr))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	public function getRawResponsesByOCId($oc_id)
	{
		$ocrs = $this->find('all', array(
						'conditions'=>array('oc_id' => $oc_id),
						'recursive' => -1
					));
		return $ocrs;
	}
	
	public function getRawResponseByOcrId($ocr_id)
	{
		$ocr = $this->find('first', array(
						'conditions'=>array('id' => $ocr_id),
						'recursive' => -1
					));
		return $ocr;
	}
	
	public function getRawResponsesByOcrIds($ocr_ids)
	{
		$ocrs = $this->find('all', array(
						'conditions'=>array('id' => $ocr_ids),
						'recursive' => -1
					));
		return $ocrs;
	}


	public function getRawOcResponsesByBloggerId($blogger_id)
	{
		
		if (empty($blogger_id)) return null;
	
		$oc_responses = $this->find('all', array('recursive' => -1, 'conditions' => array ('blogger_id' => $blogger_id)));
		
		return $oc_responses;	
	
	}
	
	public function findResponseByBloggerAndOcId($blogger_id, $oc_id)
	{
		
		if (empty($blogger_id) || empty($oc_id)) return null;
	
		$oc_response = $this->find('first', 
										array(
										'recursive' => -1, 
										'conditions' => 
											array (
												'blogger_id' => $blogger_id,
												'oc_id' => $oc_id
												)
										)
									);
		
		return $oc_response;	
	
	}
	
	public function doesBloggerOwnOcr($blogger_id, $ocr_id)
	{
		if (empty($blogger_id) || empty($ocr_id)) return null;
	
		$oc_response = $this->find('first', 
										array(
										'recursive' => -1, 
										'conditions' => 
											array (
												'blogger_id' => $blogger_id,
												'id' => $ocr_id
												)
										)
									);
		
		if (!empty($oc_response['OcResponse']))
		{
			return true;
		}
		else
		{
			return false;
		}			
	}
	
	private function addRawResponseData($cpr)
	{
		$result = array ('success' => false, 'data' => array());
		
		$cpr_entry = array ('OcResponse' => $cpr);
		$this->create();
		$save_response = $this->save($cpr_entry);
		
		if (!empty($save_response))
		{
			$result['success'] = true;
			$result['data'] = $save_response;
			$result['saveid'] = $this->id;
		}
		else
		{
			$result['success'] = false;
			$result['data'] = $save_response;
		}
		
		return $result;
		
	}
	
	public function verify_verifier($verifier = null, $oc_id = null)
	{
		if (empty($verifier) || empty($oc_id)) { return false; }
		
		$temp = explode ('UMN', $verifier);
		if (2 != count($temp)) { return false; }
		
		$key = $temp[0];
		$user_id = $temp[1];
		
		if (empty($key) || empty($user_id)) { return false; }
		
		$found = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'id' => $key,
				'oc_id' => $oc_id,
				'user_id' => $user_id
			)
		));
		
		if (!empty($found['OcResponse']))
		{
			return $found['OcResponse'];
		}
		
		return false;
	}
	
	public function addRawGenResponseData($params)
	{
		$result = array ('success' => false, 'data' => array());
		$cpr = array();
		
		if (empty($params['user_id'])
			|| empty($params['company_id'])
			|| empty($params['promo_method'])
			)
		{
			return $result;
		}
		
		$cpr['user_id'] = $params['user_id'];
		$cpr['oc_id'] = $params['resp_for_obj_id'];
		$cpr['response_type'] = $params['promo_method'];
		$cpr['response_blog_link'] = $params['live_link'];
		$cpr['response_data'] = $params['json_promo_response'];
		$cpr['response_live_id'] = $params['postid'];
		
		// coupon code, company info
		$cpr['company_id'] = $params['company_id'];
		$cpr['coupon_code'] = $params['coupon_code'];
		$cpr['uid_cid_coupon_code'] = $params['uid_cid_coupon_code'];
		
		//share info
		$cpr['share_title'] = $params['share_title'];
		$cpr['share_desc'] = $params['share_desc'];
		$cpr['share_image_link'] = $params['share_image_link'];
		$cpr['share_news_title'] = $params['share_news_title'];
		$cpr['share_news_link'] = $params['share_news_link'];
		
		// key share metrics
		if (!empty($params['update_like_count']) 
			&& 1==$params['update_like_count'])
		{
			$cpr['like_count'] = $params['like_count'];
		}
		if (!empty($params['update_comment_count']) 
			&& 1==$params['update_comment_count'])
		{
			$cpr['comment_count'] = $params['comment_count'];
		}
		if (!empty($params['update_share_count']) 
			&& 1==$params['update_share_count'])
		{
			$cpr['share_count'] = $params['share_count'];
		}
		if (!empty($params['update_retweet_count']) 
			&& 1==$params['update_retweet_count'])
		{
			$cpr['retweet_count'] = $params['retweet_count'];
		}
		
		$cpr['processed'] = 0;
		$cpr['created'] = date("Y-m-d H:i:s");
		
		$result = $this->addRawResponseData($cpr);
		return $result;
	}
	
	public function addRawTweetResponseData($user_id, $oc_id, $tweet_response_data)
	{
		$cpr = array();
		
		$cpr['user_id'] = $user_id;
		$cpr['oc_id'] = $oc_id;
		$cpr['response_type'] = 'tweet';
		$cpr['response_data'] = json_encode($tweet_response_data);
		$cpr['processed'] = 0;
		$cpr['created'] = date("Y-m-d H:i:s");
		
		$result = $this->addRawResponseData($cpr);
		return $result;
	}
	
	public function addRawFBResponseData($user_id, $oc_id, $fb_response_data)
	{
		$cpr = array();
		
		$cpr['user_id'] = $user_id;
		$cpr['oc_id'] = $oc_id;
		$cpr['response_type'] = 'fb_post';
		$cpr['response_data'] = json_encode($fb_response_data);
		$cpr['processed'] = 0;
		$cpr['created'] = date("Y-m-d H:i:s");
		
		$result = $this->addRawResponseData($cpr);
		return $result;
	}
	
	public function set_used_val($oc_id, $used_val)
	{
		if (empty($oc_id)){return false;}
		
		$ocr = array('OcResponse'=>array('id'=>$oc_id,'used'=>$used_val));
		$this->id = $oc_id;
		$saved = $this->save($ocr, true, array('used'));
		if (!empty($saved)){return true;}
		return false;
	}
	
	public function getEmails($oc_id)
	{
		if (empty($oc_id)){return false;}
		
		$emails = $this->find('all', array(
			'recursive' => -1, 'conditions' => array (
					'response_type IN' => array('single_email_ns_signup', 'dual_email_ns_signup'),
					'oc_id' => $oc_id
					),
			'fields'=>array('response_data'))
		);
		return $emails;
		
	}
	
	public function update_coupon_code($ocr_id, $coupon_code, $user_id_n_company_id_coupon){
		if (empty($ocr_id)){return false;}
		
		$to_update = array('OcResponse'=>array('id'=>$ocr_id, 'coupon_code' => $coupon_code, 
						'uid_cid_coupon_code' => $user_id_n_company_id_coupon));
		$this->id = $ocr_id;
		$saved = $this->save($to_update, true, array('coupon_code', 'uid_cid_coupon_code'));
		if (!empty($saved)){return true;}
		else{ return false;}
	}
}