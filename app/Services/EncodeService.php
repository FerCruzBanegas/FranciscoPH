<?php

namespace App\Services;

class EncodeService
{
	private $KEY = 'MySecretKeyForEncryptionAndDecry'; // 32 chars
	private $IV = 'ivcodespanchoapp'; // 16 chars
	private $METHOD = 'aes-256-cbc';

	public function decryp($text){
	  return openssl_decrypt($text, $this->METHOD, $this->KEY, 0, $this->IV);
	}

	public function encryp($text){
	  return openssl_encrypt($text, $this->METHOD, $this->KEY, 0, $this->IV);
	}
}