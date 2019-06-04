<?php

/* ぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇ
 *
 * Copyright (c) 2014 Darren, Inc. All Rights Reserved
 * Dream
 *
 * ぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇぇ
 * @File           BiuldNum.php
 * @Author         DREAM
 * @Date           2014-06-11 11:10:58
 * @Description
 * @Modify
 */

namespace Common\Common;

class BiuldNum {

    private static $_instance;

    private function __construct() {
        
    }

    private function __clone() {
        
    }

    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * 获取CPS申请单号。
     * @return string
     */
    public function getCpsNo() {
        $tmpnum = substr(date("ymdHis"), 0, 12) . mt_rand(1000, 9999);
        return $tmpnum;
    }

    /**
     * 获取流水号
     * @param type $prefix
     */
    public function GetSequenec($prefix = "") {
        $hashCode = crc32($this->create_uuid($prefix));
        if ($hashCode < 0) {
            $hashCode = $hashCode * -1;
        }
        $strPrefix = "";
        if (!empty($prefix)) {
            $lenght = strlen($prefix);
            if ($lenght > 4) {
                $strPrefix = substr($prefix, $lenght - 4, 4);
            } else {
                $strPrefix = str_pad($prefix, 4, '0', STR_PAD_LEFT);
            }
        }
        $orderNO = "";
        $orderNO = str_pad(mt_rand(1, 99), 2, "0", STR_PAD_LEFT) . str_pad($hashCode, 10, "0", STR_PAD_LEFT) . $strPrefix;
        return $orderNO;
    }

    /**
     * 创建GUID
     * @param type $prefix
     * @return type
     */
    function create_uuid($prefix = "") {    //可以指定前缀     
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return $prefix . $uuid;
    }

    public function orderNO() {
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $uuid = chr(123)
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12)
                . chr(125);
        return $uuid;
    }

    /**
     * 获取订单号 标编号等非个人用户相关订单。
     * @param type $prefix
     */
    public function getOrderNO($prefix = "") {
        $hashCode = crc32($this->orderNO());
        if ($hashCode < 0) {
            $hashCode = $hashCode * -1;
        }
        $orderNO = "";
        $orderNO = $prefix . str_pad($hashCode, 10, "0", STR_PAD_LEFT) . str_pad(mt_rand(1, 9999), 4, "0", STR_PAD_LEFT);
        return $orderNO;
    }

}
