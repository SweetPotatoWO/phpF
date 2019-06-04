<?php

namespace Service\Api\Crypt;

/**
 * Description of ApiRsa
 *
 * @author Administrator
 */
class ApiRsa {

    /**
     * private key
     */
    private $_privKey;

    /**
     * public key
     */
    private $_pubKey;

    /**
     * setup the private key
     */
    private function setupPrivKey($keypath) {
        if (is_resource($this->_privKey)) {
            return true;
        }
        $prk = file_get_contents($keypath);
        $this->_privKey = openssl_pkey_get_private($prk);
        return true;
    }

    /**
     * setup the public key
     */
    private function setupPubKey($keypath) {
        if (is_resource($this->_pubKey)) {
            return true;
        }
        $puk = file_get_contents($keypath);
        $this->_pubKey = openssl_pkey_get_public($puk);
        return true;
    }

    /**
     * 加密。
     * @param type $data
     * @param type $prikey 私钥
     * @return type
     */
    public function encrypt($data, $keypath) {
        if (!is_string($data) || empty($keypath)) {
            return null;
        }
        $this->setupPrivKey($keypath);
        $result = openssl_private_encrypt($data, $encrypted, $this->_privKey);
//        openssl_free_key($this->_privKey);
        if ($result) {
            return strtoupper(bin2hex($encrypted));
        }
        return null;
    }

    /**
     * 解密。
     * @param type $data
     * @param type $pubkey 公钥
     * @return type
     */
    public function decrypt($crypted, $keypath) {
        if (!is_string($crypted) || empty($keypath)) {
            return null;
        }
        $crypted = pack("H*", $crypted);
        $this->setupPubKey($keypath);
        $result = openssl_public_decrypt($crypted, $decrypted, $this->_pubKey);
//        openssl_free_key($this->_pubKey);
        if ($result) {
            return $decrypted;
        }
        return null;
    }

}
