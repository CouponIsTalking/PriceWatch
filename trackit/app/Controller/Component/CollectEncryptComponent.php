<?php

class CollectEncryptComponent extends Component {
	
	private $collection_key = "343aASD#$34"; // IMPORTANT : change in this key will
		// change the way collection names are encrypted. AND IT WILL CAUSE
		// ALL OUTSTANDING COLLECTION LINKS TO BE BAD, SINCE THEIR ENCRYPTED
		// COLLECTION NAME WILL NOT BE DECRYPTED RIGHT.
	
	private $generic_key = "RockyMovie."; // This should only be used for
	// temporary encryption
	
	public function encrypt_collection_name($data){
		
		$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->collection_key, $data, MCRYPT_MODE_ECB);
		return $encrypted;
	}
	public function decrypt_collection_name($encrypted){

		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->collection_key, $encrypted, MCRYPT_MODE_ECB);
		return $decrypted;
	}
	
	public function encrypt_str($to_encrypt){
		//$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->generic_key, $to_encrypt, MCRYPT_MODE_ECB);
		$encrypted = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->generic_key, $to_encrypt, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
		return $encrypted;
	}
	public function decrypt_str($encrypted){
		//$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->generic_key, $encrypted, MCRYPT_MODE_ECB);
		$decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->generic_key, base64_decode($encrypted), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		return $decrypted;
	}
}

?>