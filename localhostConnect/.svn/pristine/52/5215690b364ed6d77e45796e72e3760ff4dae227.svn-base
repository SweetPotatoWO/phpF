<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Common;

/**
 * Description of AesSecurity
 *
 * @author Administrator
 */
class DesSecurity {

    private $key;

    public function __construct() {
        $this->key = "@Wt^2)V#";
    }

    public function decrypt($encrypted) {
        $encrypted = base64_decode($encrypted);
        $key = $this->key;
        $td = mcrypt_module_open('des', '', 'ecb', ''); //使用MCRYPT_DES算法,cbc模式
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);       //初始处理
        $decrypted = mdecrypt_generic($td, $encrypted);       //解密
        mcrypt_generic_deinit($td);       //结束
        mcrypt_module_close($td);
        $y = $this->pkcs5_unpad($decrypted);
        return $y;
    }

    /*
     * 借款临时用下解密 
     * 
     */

    private function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

}
