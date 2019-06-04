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
class HashCode {

    /**
     * 对应JAVA中的hashcode
     * @param type $s
     * @return type
     * 
     */
    public   function hashCode($s) {
        $arr_str = str_split($s);
        $len = count($arr_str);
        $hash = 0;
        for ($i = 0; $i < $len; $i++) {
            if (ord($arr_str[$i]) > 127) {
                $ac_str = $arr_str[$i] . $arr_str[$i + 1] . $arr_str[$i + 2];
                $i+=2;
            } else {
                $ac_str = $arr_str[$i];
            }

            $hash = (int) ($hash * 31 + $this->asc_encode($ac_str));
            //64bit下判断符号位
            if (($hash & 0x80000000) == 0) {
                //正数取前31位即可
                $hash &= 0x7fffffff;
            } else {
                //负数取前31位后要根据最小负数值转换下
                $hash = ($hash & 0x7fffffff) - 2147483648;
            }
        }
        return $hash;
    }

    public function asc_encode($c) {
        $len = strlen($c);
        $a = 0;
        while ($a < $len) {
            $ud = 0;
            if (ord($c{$a}) >= 0 && ord($c{$a}) <= 127) {
                $ud = ord($c{$a});
                $a += 1;
            } else if (ord($c{$a}) >= 192 && ord($c{$a}) <= 223) {
                $ud = (ord($c{$a}) - 192) * 64 + (ord($c{$a + 1}) - 128);
                $a += 2;
            } else if (ord($c{$a}) >= 224 && ord($c{$a}) <= 239) {
                $ud = (ord($c{$a}) - 224) * 4096 + (ord($c{$a + 1}) - 128) * 64 + (ord($c{$a + 2}) - 128);
                $a += 3;
            } else if (ord($c{$a}) >= 240 && ord($c{$a}) <= 247) {
                $ud = (ord($c{$a}) - 240) * 262144 + (ord($c{$a + 1}) - 128) * 4096 + (ord($c{$a + 2}) - 128) * 64 + (ord($c{$a + 3}) - 128);
                $a += 4;
            } else if (ord($c{$a}) >= 248 && ord($c{$a}) <= 251) {
                $ud = (ord($c{$a}) - 248) * 16777216 + (ord($c{$a + 1}) - 128) * 262144 + (ord($c{$a + 2}) - 128) * 4096 + (ord($c{$a + 3}) - 128) * 64 + (ord($c{$a + 4}) - 128);
                $a += 5;
            } else if (ord($c{$a}) >= 252 && ord($c{$a}) <= 253) {
                $ud = (ord($c{$a}) - 252) * 1073741824 + (ord($c{$a + 1}) - 128) * 16777216 + (ord($c{$a + 2}) - 128) * 262144 + (ord($c{$a + 3}) - 128) * 4096 + (ord($c{$a + 4}) - 128) * 64 + (ord($c{$a + 5}) - 128);
                $a += 6;
            } else if (ord($c{$a}) >= 254 && ord($c{$a}) <= 255) { //error
                $ud = 0;
                $a++;
            } else {
                $ud = 0;
                $a++;
            }
            $scill .= "$ud";
        }
        return $scill;
    }

}
