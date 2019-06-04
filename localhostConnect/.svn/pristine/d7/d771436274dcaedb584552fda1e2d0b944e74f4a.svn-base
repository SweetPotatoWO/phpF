<?php
namespace Common\Common;

class Crypt3Des {

    private static function pkcsPad($text, $mode) {
        $blocksize = mcrypt_get_block_size(MCRYPT_TRIPLEDES, $mode);
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private static function pkcs5Unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

    public static function encrypt($plain_text, $key, $mode) {
        $padded = self::pkcsPad($plain_text, $mode);
        // 初始化向量来增加安全性
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES, $mode), MCRYPT_RAND);
        return mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $padded, $mode, $iv);
    }

    public static function decrypt($cipher_text, $key, $mode) {
        $iv = $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_TRIPLEDES, $mode), MCRYPT_RAND);
        $plain_text = mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $cipher_text, $mode, $iv);
        return Crypt3Des::pkcs5Unpad($plain_text);
    }

    /**
     * 3des加密
     * @param  $string 待加密的字符串
     * @param  $key 加密用的密钥
     * @return string
     */
    static function encrypt_ecb($string, $key) {
        $encrypted_string = self::encrypt($string, $key, MCRYPT_MODE_ECB);
        $des3 = bin2hex($encrypted_string); // 转化成16进制
        return $des3;
    }

}
