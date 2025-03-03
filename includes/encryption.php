<?php

class Encryption {

	private $private_secret_key = '208d7b51449b4b04b2ec4a3de636fff4';

	public function get_private_secret_key() {
		return $this->private_secret_key;
	}

	// This function encrypts a string passed into it
	public function encrypt($message) {
		$encryption_key = $this->private_secret_key;
		$key = hex2bin($encryption_key);

		$nonceSize = openssl_cipher_iv_length('aes-256-ctr');
		$nonce = openssl_random_pseudo_bytes($nonceSize);

		$ciphertext = openssl_encrypt(
			$message, 
			'aes-256-ctr', 
			$key,
			OPENSSL_RAW_DATA,
			$nonce
		);

		return base64_encode($nonce.$ciphertext);
	}

	// This function decrypts a string passed into it
	public function decrypt($message) {
		$encryption_key = $this->private_secret_key;
		$key = hex2bin($encryption_key);
		$message = base64_decode($message);
		$nonceSize = openssl_cipher_iv_length('aes-256-ctr');
		$nonce = mb_substr($message, 0, $nonceSize, '8bit');
		$ciphertext = mb_substr($message, $nonceSize, null, '8bit');

		$plaintext = openssl_decrypt(
			$ciphertext, 
			'aes-256-ctr', 
			$key,
		  OPENSSL_RAW_DATA,
		  $nonce
		);

		return $plaintext;
	}
}

?>