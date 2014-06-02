<?php

class RedirectUrlComponent extends Component {
	
	public $components = array('Session');

	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}
	
/*	public function set_current_url_as_redirect_url()
	{
		//$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		//$this->set_redirect_url($url);
		$url = "";
		if ($this->controller->referer() != '/') 
		{
            $url = $this->controller->referer();
        }
		else
        {
			$url = '/';
		}
		$this->set_redirect_url($url);
		
		return;
	}
*/	
	public function pre_login_set_redirect()
	{
		$this->set_current_referrer_as_redirect_url();
	}
	
	public function post_login_do_redirect()
	{
		$this->do_redirect();
	}
	
	public function set_current_referrer_as_redirect_url()
	{
		//$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		//$this->set_redirect_url($url);
		$url = "";
		if ($this->controller->referer() != '/') 
		{
            $url = $this->controller->referer();
        }
		else
        {
			$url = '/';
		}
		//debug($url);
		$this->set_redirect_url($url);
		
		return;
	}
	
	public function set_redirect_url($url)
	{
		$encoded_url = urlencode($url);
		$this->Session->write('redirect_url.encoded_url', $encoded_url);
		
		return;
	}
	
	public function set_request_data($request_data)
	{
		$this->Session->write('redirect_url.request_data', $request_data);
		
		return;
	}
	
	public function clear_request_data()
	{
		$this->Session->delete('redirect_url.request_data');
		
		return;
	}
	
	public function set_use_internal_param()
	{
		$this->Session->write('redirect_url.use_internal_param', true);
	}
	
	public function clear_use_internal_param()
	{
		$this->Session->write('redirect_url.use_internal_param', false);
	}
	
	public function should_use_internal_param()
	{
		$use_internal_param = $this->Session->read('redirect_url.use_internal_param');
		return (!empty($use_internal_param) && (true == $use_internal_param));
	}
	
	public function del_redirect_url_data() {
		$this->Session->delete('redirect_url.encoded_url');
		$this->clear_request_data();
		$this->clear_use_internal_param();
	}
	
	public function get_request_data()
	{
		$request_data = $this->Session->read('redirect_url.request_data');
		return $request_data;
	}
	
	public function do_redirect()
	{
		$encoded_url = $this->Session->read('redirect_url.encoded_url');
		if (empty($encoded_url))
		{
			return;
		}
		
		$url = urldecode($encoded_url);
		
		//$this->Session->delete('redirect_url.encoded_url');
		$this->controller->redirect($url);
		
		return;
	}
	
	public function delete_and_do_redirect()
	{
		$encoded_url = $this->Session->read('redirect_url.encoded_url');
		if (empty($encoded_url))
		{
			return;
		}
		
		$this->del_redirect_url_data();
		
		$url = urldecode($encoded_url);
		
		//$this->Session->delete('redirect_url.encoded_url');
		$this->controller->redirect($url);
		
		return;
	}
	
}

?>