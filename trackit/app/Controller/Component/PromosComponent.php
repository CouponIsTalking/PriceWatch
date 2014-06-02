<?php

class PromosComponent extends Component {
	
	//public $components = array('UserData');
	private $current_limits_page = "";//SITE_NAME . "current_limits.html";
	
	public function initialize(Controller $controller) {
	
		$this->controller = $controller;		
		$this->current_limits_page = "javascript:show_current_limits();"; //SITE_NAME . "current_limits.html";
	}
	
	private function get_last_promo_times($user_id, $promo_type)
	{
		
		App::uses('User', 'Model');
		$user_model = new User();
		$promo_times = $user_model->get_promo_times($user_id, $promo_type);
		//debug($promo_times);
		$pt_str = $promo_times[$promo_type];
		$pt_ary = split(',', $pt_str);
		return $pt_ary;
	}
	
	private function get_promos_since($user_id, $promo_type, $time)
	{
		$promo_times = $this->get_last_promo_times($user_id, $promo_type);
		$count = 0;
		
		foreach($promo_times as $index=>$pt)
		{
			//debug(intval($pt) - $time);
			if (intval($pt) >= $time)
			{
				$count = $count + 1;
			}
		}
		
		return $count;
	}
	
	public function is_new_promo_allowed($user_id, $promo_type)
	{
		$result = array(
			'success' => 0,
			'msg' => ""
		);
		
		$cur_time = time();
		$time_an_hour_before = $cur_time - 3600;
		$time_a_day_before = $cur_time - 3600*24;
		$promos_in_last_hour = $this->get_promos_since($user_id, $promo_type, $time_an_hour_before);
		$promos_in_last_day = $this->get_promos_since($user_id, $promo_type, $time_a_day_before);
		
		if ('fb' == $promo_type)
		{
			if ($promos_in_last_hour >= 3)
			{
				$result['success'] = 0;
				$result['msg'] = "Limits enforced on Facebook posting are reached. <a style=\"color:white;\" href=\"{$this->current_limits_page}\">Check current limits here.</a>";
			}
			else if ($promos_in_last_day >= 3)
			{
				$result['success'] = 0;
				$result['msg'] = "Limits enforced on Facebook posting are reached. <a style=\"color:white;\" href=\"{$this->current_limits_page}\">Check current limits here.</a>";
			}
			else
			{
				$result['success'] = 1;
				$result['msg'] = "";
			}
		}
		else if ('tweet' == $promo_type || 'tw' == $promo_type)
		{
			if ($promos_in_last_hour >= 3)
			{
				$result['success'] = 0;
				$result['msg'] = "Limits enforced on Twitter posting are reached. <a style=\"color:white;\" href=\"{$this->current_limits_page}\">Check current limits here.</a>";
			}
			else if ($promos_in_last_day >= 3)
			{
				$result['success'] = 0;
				$result['msg'] = "Limits enforced on Twitter posting are reached. <a style=\"color:white;\" href=\"{$this->current_limits_page}\">Check current limits here.</a>";
			}
			else
			{
				$result['success'] = 1;
				$result['msg'] = "";
			}
		}
		
		return $result;
	}
	
	public function add_new_promo_time_as_now($user_id, $promo_type)
	{
		
		$time = time();
		
		App::uses('User', 'Model');
		$user_model = new User();
		$promo_times = $user_model->get_promo_times($user_id, $promo_type);
		
		$pt_str = $promo_times[$promo_type];
		
		$pt_ary = split(',', $pt_str);
		if (count($pt_ary) > 9) // if total elements are more than 9, then get the last 9 elements
		{
			$pt_str = "";
			$l = count($pt_ary);
			$start = max($l-9, 0);
			for ($i = $start; $i < $l; $i++) // get first 9 elements
			{
				if ("" == $pt_str) {$pt_str = strval($time);}
				else { $pt_str = $pt_str . "," . strval($time);} // order is important here, 
																// because for loop is running backwards...
			}
		}
		
		if ("" == $pt_str)
		{
			$pt_str = strval($time);
		}
		else
		{
			$pt_str = $pt_str . "," . strval($time);
		}
		
		$result = $user_model->set_promo_times($user_id, $promo_type, $pt_str);
		
		return $result;
	}
	
}

?>