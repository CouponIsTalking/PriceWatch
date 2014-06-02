<?php
App::uses('AppModel', 'Model');
/**
 * Content Model
 *
 */
class Content extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	
	
	public function isValidContentType($type)
	{
		if ( !empty($type) && ($type == 'news' || $type == 'image' || $type == 'video') )
		{
			return true;
		}
		
		return false;
	
	}
	
	public function compactContentData($contents)
	{
		$contentdata = array();
		
		foreach ($contents as $k => $content)
		{
			$contentdata[$content['Content']['id']] = array ('title' => $content['Content']['title'], 'link' => $content['Content']['link']);
		}
		
		return $contentdata;
	}
	
	public function get_all_content()
	{
		$contents = $this->find('all', array ( 'recursive' => -1, ));
		$content_data = $this->compactContentData($contents);
		
		return $content_data;
	}
	
	
	
	public function get_company_id($content_id)
	{
		$content = $this->getRawContentInfo($content_id);
		if (!empty($content['Content']))
		{
			return $content['Content']['company_id'];
		}
		return null;
	}
	
	public function getRawContentInfo($content_id)
	{
		if (empty($content_id)) {return null;}
		
		$content = $this->find('first', array ( 'recursive' => -1, 'conditions'=>array('id'=>$content_id) ));
		
		return $content;
	}
	
	
	public function get_random()
	{
		$content = $this->find('first', array('conditions' => array('type'=>'news', 'state'=>1), 'order' => 'RAND()')); 
		return $content;
	}
	
	public function get_last_day_random()
	{
		//unix_timestamp
		$one_day_timestamp_diff = 24*60*60;
		$content = $this->find('first', 
			array(
				'conditions' => array('type'=>'news', 'state'=>1, 'unix_timestamp >=' =>  time() - $one_day_timestamp_diff), 
				'order' => 'RAND()'
				)
			); 
		return $content;
	}
	
	public function get_by_product_id($product_id = null)
	{
	
		if (empty($product_id))
		{
			return null;
		}
		
		$content_data = $this->find('all', array ( 'recursive' => -1, 'conditions' => array ('product_id' => $product_id)));
		
		return $content_data;
	}
	
	public function get_by_company_id($company_id = null)
	{
	
		if (empty($company_id))
		{
			return null;
		}
		
		$content_data = $this->find('all', array ( 'recursive' => -1, 'conditions' => array ('company_id' => $company_id)));
		
		return $content_data;
	}

	public function get_by_link($link = null)
	{
	
		if (empty($link))
		{
			return null;
		}
		
		$content_data = $this->find('first', array ( 'recursive' => -1, 'conditions' => array ('link' => $link)));
		
		return $content_data;
	}
	
	public function get_active_content_by_company_id($company_id = null)
	{
	
		if (empty($company_id))
		{
			return null;
		}
		
		$content_data = $this->find('all', array ( 'recursive' => -1, 'conditions' => array ('company_id' => $company_id, 'state'=>1)));
		
		return $content_data;
	}
	
	public function get_active_fixed_offers_by_company_id($company_id = null)
	{
		$content_data = $this->get_active_content_by_company_id($company_id);
		
		$filtered_content_data = array();
		
		foreach($content_data as $index => $content)
		{
			if ( !empty($content_data['Content']['fb_coupon_code']) 
			  || !empty($content_data['Content']['tw_coupon_code']) 
			  || !empty($content_data['Content']['pinit_coupon_code'])
			  )
			{
				$filtered_content_data[] = $content;
			}
		}
		
		return $filtered_content_data;
	}
	
	public function get_fixed_offers_by_company_id($company_id = null)
	{
		//has_fixed_social_coupons
		if (empty($company_id))
		{
			return null;
		}
		
		$content_data = $this->find('all', array ( 'recursive' => -1, 'conditions' => array ('company_id' => $company_id, 'has_fixed_social_coupons'=>1)));
		
		return $content_data;
	}
	
	public function get_active_content_by_product_id($product_id = null)
	{
	
		if (empty($product_id))
		{
			return null;
		}
		
		$content_data = $this->find('all', array ( 'recursive' => -1, 'conditions' => array ('product_id' => $product_id, 'state'=>1)));
		
		return $content_data;
	}
	
	public function get_active_content_by_time_n_id_str($unix_timestamp_and_id_str)
	{
		$unix_timestamp_and_id = $this->split_timestamp_and_id($unix_timestamp_and_id_str);
		
		if (empty($unix_timestamp_and_id))
		{
			return null;
		}
		
		$content = $this->get_active_content_by_time_n_id($unix_timestamp_and_id['unix_timestamp'], $unix_timestamp_and_id['id']);
		
		return $content;
	}
	
	public function get_active_content_by_time_n_id($unix_timestamp, $id)
	{
		if (empty($id) || empty($unix_timestamp))
		{
			return null;
		}
		
		$content_data = $this->find('first', array ( 'recursive' => -1, 'conditions' => array ('unix_timestamp' => $unix_timestamp, 'id'=>$id)));
		
		return $content_data;
	}
	
	public function get_active_content_by_ids($ids)
	{
		if (empty($ids))
		{
			return null;
		}
		
		if (is_array($ids) && count($ids) > 1)
		{
			$single_key = false;
		}
		else
		{
			$single_key = true;
			if (is_array($ids)) {$ids = $ids[0];}
		}
		
		if (!$single_key)
		{
			$content_data = $this->find('all', 
				array ( 
				'recursive' => -1, 
				'conditions' => array ('id IN'=>$ids)
				));
		}
		else
		{
			$one_content_data = $this->find('first', 
				array ( 
				'recursive' => -1, 
				'conditions' => array ('id'=>$ids)
				));
			$content_data = array ($one_content_data);
		}
		return $content_data;
	}
	
	public function get_active_content_by_goal_id($un_goal_id = null)
	{
	
		if (empty($un_goal_id))
		{
			return null;
		}
		
		$content_data = $this->find('all', array ( 'recursive' => -1, 'conditions' => array ('un_goal_id' => $un_goal_id, 'state'=>1)));
		
		return $content_data;
	}
	
	public function increment_like($item_id)
	{
		if (empty($item_id))
		{
			return 0;
		}
		
		$content_data = $this->find('first', array ( 'recursive' => -1, 'conditions' => array ('id'=>$item_id)));
		if ( !empty($content_data['Content']) )
		{
			$content_data['Content']['likes'] = $content_data['Content']['likes'] + 1;
			if ($this->save($content_data))
			{
				return $content_data['Content']['likes'];
			}
		}		
		
		return 0;
	}
	
	public function doesCompanyOwnContent($company_id, $content_id)
	{
	
		if (empty($company_id) || empty($content_id)) return null;
	
		$content = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $content_id, 'company_id'=>$company_id)));
		
		if (!empty($content['Content']))
		{
			return true;
		}
		else
		{
			return false;
		}		
	
	}
	
	public function set_content_state($content_id, $state)
	{
	
		$content = $this->find('first', array('recursive' => -1, 'conditions' => array ('id' => $content_id)));
		
		if (empty($content['Content']))
		{
			return false;			
		}
		
		if(1 == $state)
		{
			$content['Content']['state'] = 1;
			
		}
		else if(0 == $state)
		{
			$content['Content']['state'] = 0;
		}

		$this->id = $content['Content']['id'];
		$result = $this->save($content);
		if (!empty($result))
		{
			return true;
		}
		
		return false;
	}
	
	public function build_unix_timestamp_and_id ($unix_timestamp, $id)
	{
		return strval($unix_timestamp) . "_" . strval($id);
	}
	
	public function split_timestamp_and_id($unix_timestamp_and_id)
	{
		if (empty($unix_timestamp_and_id))
		{
			return null;
		}
		
		$timenid_array = split('_', $unix_timestamp_and_id);
		
		$unix_timestamp = "";
		$id = "";
		
		if (!empty($timenid_array[0]) && !empty($timenid_array[1]))
		{
			$unix_timestamp = $timenid_array[0];
			$id = $timenid_array[1];
		}
		else
		{
			return null;
		}
	
		return array ('unix_timestamp' => $unix_timestamp, 'id'=>$id);
	}
	
	public function getTweetData($unix_timestamp_and_id)
	{
		$unix_timestamp_and_id = $this->split_timestamp_and_id($unix_timestamp_and_id);
		
		if (empty($unix_timestamp_and_id))
		{
			return null;
		}
		
		$content = $this->get_active_content_by_time_n_id($unix_timestamp_and_id['unix_timestamp'], $unix_timestamp_and_id['id']);
		
		if (empty($content['Content']))
		{
			return null;
		}
		
		$tweet_data = array(
			'title' => $content['Content']['title'],
			'desc' => $content['Content']['desc'],
			'image_link' => $content['Content']['link'],
			'tw_coupon_code' => $content['Content']['tw_coupon_code'],
			);
			
		return $tweet_data;	
		
	}
	
	public function coupon_exists($coupon_code, $company_id)
	{
		if (empty($coupon_code) || empty($company_id)){return 0;}
		
		$entry = $this->find('first', array(
			'recursive' => -1,
			'conditions'=>array(
				'fb_coupon_code'=>$coupon_code,
				'company_id' => $company_id
				)
			));
		if (!empty($entry)){return $entry['Content']['id'];}
		
		$entry = $this->find('first', array(
			'recursive' => -1,
			'conditions'=>array(
				'tw_coupon_code'=>$coupon_code,
				'company_id' => $company_id
				)
			));
		
		if (!empty($entry)){return $entry['Content']['id'];}
		
		return 0;
	}
	
}