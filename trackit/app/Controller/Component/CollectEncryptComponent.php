<?php

class CollectEncryptComponent extends Component {
	
	private $key = "343aASD#$34";
	
	public function encrypt_collection_name($data){
		
		$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $data, MCRYPT_MODE_ECB);
		return $encrypted;
	}
	public function decrypt_collection_name($encrypted){

		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, $encrypted, MCRYPT_MODE_ECB);
		return $decrypted;
	}
	
}

?>