<?php

class ProdAPIComponent extends Component {
	
	var $api_key = "";
	var $configured_python_ver_code = "";
	
	private function configure_keys(){
		$this->configured_python_ver_code = Configure::read('PYTHON_VERIFICATION_CODE');
		$this->api_key = "adadad348jqdj2~!@!!@";
	}
	
	public function get_python_ver_code(){
		$this->configure_keys();
		return $this->configured_python_ver_code;
	}
	
	public function get_api_key(){
		$this->configure_keys();
		return $this->api_key;
	}
	
	public function has_api_access($user_key){
		$this->configure_keys();
		
		if (empty($user_key)){
			return false;
		}else if($user_key == $this->api_key){
			return true;
		}else{
			return false;
		}
	}
	
	public function has_python_access($user_key){
		$this->configure_keys();
		
		if (empty($user_key)){
			return false;
		}else if($user_key == $this->configured_python_ver_code){
			return true;
		}else{
			return false;
		}
	}
	
	public function has_api_or_python_access($user_key){
		$this->configure_keys();
		
		if (empty($user_key)){
			return false;
		}else if($user_key == $this->api_key){
			return true;
		}else if($user_key == $this->configured_python_ver_code){
			return true;
		}else{
			return false;
		}
	}
	
}

?>