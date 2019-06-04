<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MobileApi\Common;

use Service\Api\Common\BaseApi;
use Service\Api\Exception;
use Common\Common\Redis;
use Common\Common\AesSecurity;

/**
 * Description of MobileApiBase
 *
 * @author Administrator
 */
abstract class MobileApiBase extends BaseApi {

    const MAX_EXPIRE_TIME_FOR_SESSION = 2592000;    //7天

    /**
     * 验证用户身份。
     * @param type $isExit 身份验证不通过时是否抛出异常。
     */

    protected function userCheck($isExit = true) {
        $token = DI()->request->get('token');
        if (empty($token)) {
            if ($isExit) {
                throw new Exception\BadRequestException('未登录', 8);
            }
            return false;
        }
        $userInfo = $this->getLoginInfoByToken($token);
        if (empty($userInfo) || $userInfo['expiresTime'] <= $_SERVER['REQUEST_TIME']) {
            if ($isExit) {
                throw new Exception\BadRequestException('登录已过期，需要重新登录', 9);
            }
            return false;
        }

        $Redis = Redis::GetInstance();
        $Redis->select(1);
        $key = "activity_".$userInfo['userID'];
        $Redis->set($key,time(),300);
        return true;

    }

    /**
     * 获取去用户身份。
     */
    protected function initUserInfo() {
        $token = DI()->request->get('token');
        $userInfo = $this->getLoginInfoByToken($token);

        if (!empty($userInfo)) {
            $this->userID = $userInfo['userID'];
            $this->userPhone = $userInfo['phone'];
            $this->userDeviceID = $userInfo['deviceID'];
            $this->userToken = $userInfo['token'];
            $this->loginTime = $userInfo['loginTime'];
            $this->expiresTime = $userInfo['expiresTime'];
            return;
        }
        $this->userID = 0;
        $this->userPhone = '';
        $this->userDeviceID = '';
        $this->userToken = '';
        $this->loginTime = null;
        $this->expiresTime = null;
    }

    /**
     * 设置用户登录信息。
     * @param type $entity
     * @param type $callback 登录成功回调方法。
     */
    protected function setUserInfo($entity, callable $callback = null) {
        if (!empty($callback)) {
            register_shutdown_function($callback, $entity);
        }
        $redis = Redis::GetInstance();
        $userID = $entity['userID'];
        $searchKey = $this->getLoginCaheKey('*', $userID);
        $redis->dels($redis->getKeys($searchKey));
        $loginKey = $this->getLoginCaheKey($entity['token'], $userID);
        $redis->set($loginKey, $entity, self::MAX_EXPIRE_TIME_FOR_SESSION);
        $this->setAppJmpCookie($entity['token']);
        session_regenerate_id(true); //重置SESSION_ID
    }

    /**
     * 设置APP与H5交互的Cookie信息。
     * @param type $token
     */
    protected function setAppJmpCookie($token = '', $expire = '') {
        $domain = COOKIE_DOMAIN;
        if (empty($expire))
            $expire = time() +2592000+3600;
        if (!empty($this->deviceID) && strlen($this->deviceID) > 5) {
            $aes = new AesSecurity();
            setcookie('sid', $aes->encryptData($this->deviceID), $expire, '/', $domain);
            setcookie('terminal', $this->terminal, $expire, '/', $domain);
            setcookie('version', $this->version, $expire, '/', $domain);
            if (!empty($token) && strlen($token) > 5)
                setcookie('token', $aes->encryptData($token), $expire, '/', $domain);
        }
    }

    /**
     * 获取登录缓存key
     * @param type $token
     * @param type $userID
     * @return type
     */
    private function getLoginCaheKey($token, $userID = '') {
        return sprintf('APP_Login_%s_%s', $token, $userID);
    }

    /**
     * 根据Token获取用户登录信息。
     * @param type $token
     * @return boolean
     */
    private function getLoginInfoByToken($token) {
        if (!empty($token)) {
            $redis = Redis::GetInstance();
            $searchKey = $this->getLoginCaheKey($token);
            $keys = $redis->getKeys($searchKey);
            if ($keys) {
                if (count($keys) > 1) {
                    logger_sys("Token重复", serialize($keys));
                }
                $userInfo = $redis->get($keys[0]);
                if ($userInfo) {
                    return $userInfo;
                }
            }
        }
        return false;
    }

    /**
     * 获取分页的limit
     * @param type $pageindex
     * @param type $pagesize
     */
    protected function getLimit($pageindex = 1, $pagesize = 15) {
        $pagesize = is_numeric($pagesize) ? $pagesize : C("PAGE_SIZE");
        $pageindex = $pageindex * 1 > 0 ? ($pageindex - 1) * $pagesize : 0;
        return $pageindex . "," . $pagesize;
    }

    /**
     * 获取Token
     * @return type
     */
    protected function getToken() {
        return strtoupper(substr(sha1(uniqid(null, true)) . sha1(uniqid(null, true)), 0, 64));
    }

}
