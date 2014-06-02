<?php


 class TwitterResultProcessorComponent extends Component {

/**
 * Controller instance
 *
 * @var Controller
 */
	protected $_controller = null;
	
	public function has_errors($result)
	{
		if (!empty($result['errors']))
		{
			return true;
		}
		
		if (!empty($result['code']))
		{
			$code = $result['code'];
			if ($this->is_error_code($code))
			{
				return true;
			}
		}
		
		return false;
		//return ( 0 != count($result['errors']));
	}
	
	public function is_error_code($code)
	{
		
		if (empty($code)) return false;
		
		if (200 == $code || 304 == $code)
		{
			return false;
		}
		
		return true;
	}
	
	public function is_bad_auth($result)
	{
		if (!empty($result['errors']))
		{
			foreach ($result['errors'] as $key => $error)
			{
				if (32 == $error['code'] || 215 == $error['code'] || 210 == $error['code'])
				{
					return true;
				}
				else if ("Bad Authentication data" == $error['message'])
				{
					return true;
				}
			}
		}
		
		return false;
		
	}
	
	public function is_dup_status($result)
	{
		if (!empty($result['errors']))
		{
			foreach ($result['errors'] as $key => $error)
			{
				if (187 == $error['code'])
				{
					return true;
				}
				else if ("Status is a duplicate." == $error['message'])
				{
					return true;
				}
			}
		}
		
		return false;
		
	}
	
	public function are_tokens_bad($result)
	{
		if (!empty($result['errors']))
		{
			foreach ($result['errors'] as $key => $error)
			{
				if (64 == $error['code'] || 89 == $error['code'] || 135 == $error['code'])
				{
					return true;
				}
				else if ("Invalid or expired token" == $error['message'])
				{
					return true;
				}
			}
		}
		
		return false;
		
	}
	
	public function is_user_rate_limit_reached($result)
	{
		if (!empty($result['errors']))
		{
			foreach ($result['errors'] as $key => $error)
			{
				if (403 == $error['code'] || 88 == $error['code'] || 185 == $error['code'])
				{
					return true;
				}
			}
		}
		
		if (!empty($result['code']))
		{
			if (403 == $result['code'] || 88 == $result['code'] || 185 == $result['code'])
			{
				return true;
			}
		}
		return false;
	}
	
	
/**
 * Controller before setup - Initialized before the controllers beforeFilter()
 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->_controller = $controller;
	}

}