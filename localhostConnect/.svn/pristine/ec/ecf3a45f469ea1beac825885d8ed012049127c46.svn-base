<?php

/**
 * 码串生成器
 * Created by PhpStorm.
 * User: zhoull
 * Date: 2018/7/6
 * Time: 13:43
 */
namespace Common\Common;
class CodeGenerator
{
    const source_string = 'WX8E5FC4DGH1BANPJ2RSTUV67M9KL3Y';
    /*
     * 生成35进制唯一码串
     */
    public function createCode($user_id) {
        $num = $user_id;
        $code = '';
        while ( $num > 0) {
            $mod = $num % 31;
            $num = ($num - $mod) / 31;
            $source = self::source_string;
            $code = $source[$mod].$code;
        }
        if(empty($code[4]))
            $code = str_pad($code,5,'Q',STR_PAD_LEFT);
        return $code;
    }
    /*
     * 反生成35进制唯一码串
     */
    public function decode($code) {
        if (strrpos($code, 'Q') !== false)
            $code = substr($code, strrpos($code, 'Q')+1);
        $len = strlen($code);
        $code = strrev($code);
        $num = 0;
        for ($i=0; $i < $len; $i++) {
            $num += strpos(self::source_string, $code[$i]) * pow(31, $i);
        }
        return $num;
    }
}