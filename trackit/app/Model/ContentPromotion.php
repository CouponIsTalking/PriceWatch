<?php
App::uses('AppModel', 'Model');
/**
 * ContentPromotion Model
 *
 */
class ContentPromotion extends AppModel {


	public function getType($cpr)
	{
		return $cpr['ContentPromotion']['response_type'];
	}
	
	public function mark_accepted($cpr_id)
	{
		$cpr = $this->getRawResponseByCprId($cpr_id);
		if (empty($cpr))
		{
			return false;
		}
		
		$cpr['ContentPromotion']['processed'] = 1;
		$cpr['ContentPromotion']['processing_result'] = 1;
		$this->id = $cpr['ContentPromotion']['id'];
		
		if ($this->save($cpr))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	public function mark_unaccepted($cpr_id)
	{
		$cpr = $this->getRawResponseByCprId($cpr_id);
		if (empty($cpr))
		{
			return false;
		}
		
		$cpr['ContentPromotion']['processed'] = 1;
		$cpr['ContentPromotion']['processing_result'] = 0;
		$this->id = $cpr['ContentPromotion']['id'];
		
		if ($this->save($cpr))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	public function getRawResponsesByContentId($content_id)
	{
		if (empty($content_id))
		{
			return null;
		}
		
		$cprs = $this->find('all', array(
						'conditions'=>array('content_id' => $content_id),
						'recursive' => -1
					));
		return $cprs;
	}
	
	public function getRawResponseByCprId($cpr_id)
	{
		$cpr = $this->find('first', array(
						'conditions'=>array('id' => $cpr_id),
						'recursive' => -1
					));
		return $cpr;
	}
	
	public function getRawResponsesByCprIds($cpr_ids)
	{
		$cprs = $this->find('all', array(
						'conditions'=>array('id' => $cpr_ids),
						'recursive' => -1
					));
		return $cprs;
	}


	public function getRawContentPromotionsByUserId($user_id)
	{
		
		if (empty($user_id)) return null;
	
		$cp_responses = $this->find('all', array('recursive' => -1, 'conditions' => array ('user_id' => $user_id)));
		
		return $cp_responses;	
	
	}
	
	public function findResponseByUserAndContentId($user_id, $content_id)
	{
		
		if (empty($user_id) || empty($content_id)) return null;
	
		$cp_response = $this->find('first', 
										array(
										'recursive' => -1, 
										'conditions' => 
											array (
												'user_id' => $user_id,
												'content_id' => $content_id
												)
										)
									);
		
		return $cp_response;	
	
	}
	
	public function doesUserOwnCpr($user_id, $cpr_id)
	{
		if (empty($user_id) || empty($cpr_id)) return null;
	
		$cp_response = $this->find('first', 
										array(
										'recursive' => -1, 
										'conditions' => 
											array (
												'user_id' => $user_id,
												'id' => $cpr_id
												)
										)
									);
		
		if (!empty($cp_response['ContentPromotion']))
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
		
		$cpr_entry = array ('ContentPromotion' => $cpr);
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
		$cpr['content_id'] = $params['resp_for_obj_id'];
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
	
	public function addRawTweetResponseData($user_id, $content_id, $tweet_response_data)
	{
		$cpr = array();
		
		$cpr['user_id'] = $user_id;
		$cpr['content_id'] = $content_id;
		$cpr['response_type'] = 'tweet';
		$cpr['response_data'] = json_encode($tweet_response_data);
		$cpr['processed'] = 0;
		$cpr['created'] = date("Y-m-d H:i:s");
		
		$result = $this->addRawResponseData($cpr);
		return $result;
	}
	
	public function addRawFBResponseData($user_id, $content_id, $fb_response_data)
	{
		$cpr = array();
		
		$cpr['user_id'] = $user_id;
		$cpr['content_id'] = $content_id;
		$cpr['response_type'] = 'fb_post';
		$cpr['response_data'] = json_encode($fb_response_data);
		$cpr['processed'] = 0;
		$cpr['created'] = date("Y-m-d H:i:s");
		
		$result = $this->addRawResponseData($cpr);
		return $result;
	}
	
	public function verify_verifier($verifier = null, $content_id = null)
	{
		if (empty($verifier) || empty($content_id)) { return false; }
		
		$temp = explode ('UMN', $verifier);
		if (2 != count($temp)) { return false; }
		
		$key = $temp[0];
		$user_id = $temp[1];
		
		if (empty($key) || empty($user_id)) { return false; }
		
		$found = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'id' => $key,
				'content_id' => $content_id,
				'user_id' => $user_id
			)
		));
		
		if (!empty($found['ContentPromotion']))
		{
			return $found['ContentPromotion'];
		}
		
		return false;
	}
	
	public function set_used_val($cpr_id, $used_val)
	{
		if (empty($cpr_id)){return false;}
		
		$cpr = array('ContentPromotion'=>array('id'=>$cpr_id,'used'=>$used_val));
		$this->id = $cpr_id;
		$saved = $this->save($cpr, true, array('used'));
		if (!empty($saved)){return true;}
		return false;
	}
	
}