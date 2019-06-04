<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Crypt;

/**
 * ApiRsaKeyGenerator 生成器
 * 
 * RSA私钥或公钥的生成器
 *
 */
class ApiRsaKeyGenerator {

    protected $privkey;
    protected $pubkey;

    public function __construct() {
        $res = openssl_pkey_new();
        openssl_pkey_export($res, $privkey);
        $this->privkey = $privkey;

        $pubkey = openssl_pkey_get_details($res);
        $this->pubkey = $pubkey['key'];
    }

    public function getPriKey() {
        return $this->privkey;
    }

    public function getPubKey() {
        return $this->pubkey;
    }

}
