<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Crypt;

/**
 * Description of ApiAes
 *
 * @author Administrator
 */
class ApiAes {

    /**
     * 加密
     * @param type $data
     * @param type $key 加密key （如：AESAPPCLIENT_KEY）。
     * @param type $iv 加密向量（如：AESAPPCLIENT_KEY）。
     * @return type
     */
    public function encrypt($data, $key, $iv) {
        $jsonStr = json_encode($data);
        $str = base64_encode($jsonStr);
        $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $iv);
        return base64_encode($output);
    }

    /**
     * 解密
     * @param type $data
     * @param type $key 解密key （如：AESAPPCLIENT_KEY）。
     * @param type $iv 解密向量（如：AESAPPCLIENT_KEY）。
     * @return type
     */
    public function decrypt($data, $key, $iv) {
        $data = base64_decode($data);
        $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
        return base64_decode($output);
    }

}
