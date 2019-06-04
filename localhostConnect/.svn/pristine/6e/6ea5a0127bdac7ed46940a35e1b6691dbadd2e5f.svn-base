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
class AesSecurity {

    private $key;
    protected $iv;

    public function __construct() {
        //QIANHEZI2016APP@
        $this->key = "QIANHEZI2016APP@";
        $this->iv = "QIANHEZI2016APP@";
    }

    /**
     * 加密。
     * @param type $input
     * @return type
     */
    public function encryptData($input) {
        if (is_array($input)) {
            $input = json_encode($input);
        }
        $str = base64_encode($input);
        $output = $this->encrypt($str);
        return base64_encode($output);
    }

    /**
     * 解密
     * @param type $input
     * @return type
     */
    public function decryptData($input) {
        $input = base64_decode($input);
        $output = $this->decrypt($input);
        return base64_decode($output);
    }

    protected function decrypt($string) {
        $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $string, MCRYPT_MODE_CBC, $this->iv);
        return $output;
    }

    protected function encrypt($string) {
        $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $string, MCRYPT_MODE_CBC, $this->iv);
        return $output;
    }

}
