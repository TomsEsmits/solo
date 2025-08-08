<?php
class Varien_Crypt_Mcrypt
{
    protected $_key;
    protected $_cipher = 'AES-256-CBC';
    protected $_iv;

    public function init($cipher = MCRYPT_BLOWFISH, $mode = MCRYPT_MODE_ECB, $rand = null)
    {
        // No-op method to prevent fatal errors — kept for compatibility.
        return $this;
    }

    public function setKey($key)
    {
        $this->_key = hash('sha256', $key, true);
        return $this;
    }

    public function getKey()
    {
        return $this->_key;
    }

    public function encrypt($data)
    {
        $this->_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->_cipher));
        $encrypted = openssl_encrypt($data, $this->_cipher, $this->_key, OPENSSL_RAW_DATA, $this->_iv);
        return base64_encode($this->_iv . $encrypted);
    }

    public function decrypt($data)
    {
        $data = base64_decode($data);
        $ivLength = openssl_cipher_iv_length($this->_cipher);
        $this->_iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, $this->_cipher, $this->_key, OPENSSL_RAW_DATA, $this->_iv);
    }
}
