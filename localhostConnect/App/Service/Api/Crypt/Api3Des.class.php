<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Crypt;

/**
 * Description of Api3Des
 *
 * @author Administrator
 */
class Api3Des {

    private $key = "AGAO53D4E5FY27H8I9J0G1I3";
    private $iv = "";
    private $mode = MCRYPT_MODE_CBC;

    /**
     * 加密
     * @param <type> $data
     * @return <type>
     */
    public function encrypt($data, $key = '', $iv = null) {
        $this->setKeyAndIv($key, $iv);
        $td = mcrypt_module_open(MCRYPT_3DES, '', $this->mode, '');
        $iv = $this->mode == MCRYPT_MODE_CBC ? base64_decode($this->iv) : mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $data = json_encode($data);
        $data = $this->PaddingPKCS7($data);
        $key = base64_decode($this->key);
        mcrypt_generic_init($td, $key, $iv);
        $dec = mcrypt_generic($td, $data);
        $ret = base64_encode($dec);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return $ret;
    }

    /**
     * 解密
     * @param <type> $data
     * @return <type>
     */
    public function decrypt($data, $key = '', $iv = null) {
        $this->setKeyAndIv($key, $iv);
        $td = mcrypt_module_open(MCRYPT_3DES, '', $this->mode, '');
        $iv = $this->mode == MCRYPT_MODE_CBC ? base64_decode($this->iv) : mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $key = base64_decode($this->key);
        mcrypt_generic_init($td, $key, $iv);
        $ret = trim(mdecrypt_generic($td, base64_decode($data)));
        $ret = $this->UnPaddingPKCS7($ret);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return json_decode($ret);
    }

    /**
     * 设置加密用Key和Iv。
     * @param type $key
     * @param type $iv
     */
    private function setKeyAndIv($key = '', $iv = null) {
        if (empty($key)) {
            $key = $this->key;
        }
        $key = $this->keyBpb($key);
        if ($iv == null) {
            $iv = $key;
            $this->mode = MCRYPT_MODE_ECB;
        } else {
            $iv = $this->ivBpb($iv);
        }
        $this->key = $key;
        $this->iv = $iv;
    }

    /**
     * key字符串转为专用key
     * @param string $key
     * @return string
     */
    private function keyBpb($key) {
        $key = bin2hex($key);
        $key = pack('H48', $key); //取48字节24个字符
        $key = base64_encode($key);
        return $key;
    }

    /**
     * iv字符串转为专用iv
     * @param string $iv
     * @return string
     */
    private function ivBpb($iv) {
        $iv = bin2hex($iv);
        $iv = pack('H16', $iv);
        $iv = base64_encode($iv);
        return $iv;
    }

    /**
     * 补占位符
     */
    private function PaddingPKCS7($data) {
        $block_size = mcrypt_get_block_size('tripledes', $this->mode);
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }

    private function UnPaddingPKCS7($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }

}
