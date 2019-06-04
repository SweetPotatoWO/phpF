<?php

namespace Common\Common;

use Think\Cache;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Authorized {

    /**
     * 获取Cookie
     * @return type
     */
    static function getAuthorized($terminal) {
        return self::getPCAuthorized();
    }

    static function getPCAuthorized() {
        $authcode = Authcode::singleton();
        $key = C("SHORT_CODE") . "_Login_";
        $value = base64_decode(cookie($key));
        $authCookie = $authcode->authcode($value); //解密    $cookiestr = $userID . ',' . $lasttime . ',' . $phone . ',' . $cookieID;
        if (empty($authCookie))
            return false;
        $cookArray = explode(',', $authCookie);
        $userID = $cookArray['0'];
        $expire = $cookArray['1'];
        $phone = $cookArray['2'];
        $cookieID = $cookArray['3'];
        $short = $expire - time();
        $redis = Cache::getInstance('Redis');
        $mem = $redis->get($key . $userID);
        $str = $phone . ',' . $cookieID;
        if ($mem == $str) {
            $cache["userID"] = $userID;
            $cache["phone"] = $phone;
            $cache["time"] = $expire;
            $yourmin = (int) ($short / 60);
            if ($yourmin < 5) {
                $lasttime = time() + 10 * 60;
                $cookiestr = $userID . ',' . $lasttime . ',' . $phone . ',' . $cookieID;
                $str = $authcode->authcode($cookiestr, "ECODE"); // 密
                $arr = array();
                $arr["expire"] = 10 * 60;
                $arr["domain"] = COOKIE_DOMAIN;
                cookie($key, $str, $arr);
                $redis->set($key . $userID, $phone . ',' . $cookieID, 10 * 60);
            }
            return $cache;
        }
        return false;
    }

    /**
     * 设置Cookie        $d3 = setcookie("user", "Alex Porter", time() + 3600);
      $df = $_COOKIE["user"];
     * @param type $userID
     * @param type $phone
     * @param type $terminal
     * @param type $expire
     * @return boolean
     */
    static function setAuthorized($userID, $phone, $terminal, $expire = 10) {
        $key = C("SHORT_CODE") . "_Login_";
        $authcode = Authcode::singleton();
        $time = $expire * 60;
        $lasttime = time() + $time;
        $cookieID = md5(uniqid(rand(), TRUE));
        $cookiestr = $userID . ',' . $lasttime . ',' . $phone . ',' . $cookieID;
        $str = $authcode->authcode($cookiestr, "ECODE");
        $str = base64_encode($str);
        $redis = Cache::getInstance('Redis');
        $redis->delete($key . $userID);
        $redis->set($key . $userID, $phone . ',' . $cookieID, $time);
        $arr = array();
        $arr["expire"] = $time;
        $arr["domain"] = COOKIE_DOMAIN;
        cookie($key, $str, $arr);
        return true;
    }

    /**
     * 后台登录判断获取
     */
    static function getEndAuth($key) {
        $key = $key . "_";
        $authcode = Authcode::singleton();
        $value = cookie($key);
        $authcodeCookie = $authcode->authcode($value); //解密
        $cookArray = explode(',', $authcodeCookie);
        $userID = $cookArray['0'];
        $expire = $cookArray['1'];
        $realName = $cookArray['2'];
        $userName = $cookArray['3'];
        $cookieID = $cookArray['4'];
        $short = $expire - time();
        $redis = Cache::getInstance('Redis');
        $mem = $redis->get($key . $userID);
        $str = $userName . ',' . $cookieID;
        if ($mem == $str) {
            $cache["userID"] = $userID;
            $cache["userName"] = $userName;
            $cache["realName"] = $realName;
            $cache["time"] = $expire;
            $yourmin = (int) ($short / 60);
            if ($yourmin < 5) {
                $lasttime = time() + 10 * 60;
                $cookiestr = $userID . ',' . $lasttime . ',' . $realName . ',' . $userName . ',' . $cookieID;
                $str = $authcode->authcode($cookiestr, "ECODE"); // 密
                cookie($key, $str, 10 * 60);
                $redis->set($key . $userID, $userName . ',' . $cookieID, 10 * 60);
            }
            return $cache;
        }
        return false;
    }

    /**
     * 后台登录判断设置
     */
    static function setEndAuth($userID, $userName, $realName, $key) {
        $key = $key . "_";
        $authcode = Authcode::singleton();
        $time = 60 * 60; //60分钟
        $cookieID = md5(uniqid(rand(), TRUE));
        $lasttime = time() + $time;
        $cookiestr = $userID . ',' . $lasttime . ',' . $realName . ',' . $userName . ',' . $cookieID;
        $str = $authcode->authcode($cookiestr, "ECODE");
        $redis = Cache::getInstance('Redis');
        $redis->delete($key . $userID);
        $redis->set($key . $userID, $userName . ',' . $cookieID, $time);
        cookie($key, $str, $time);
        return true;
    }

}
