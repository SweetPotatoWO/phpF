<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Common\Common;

use Common\Common\Jpush\PushPayload;

class Jpush {

    private static $instance;
    private $appKey;
    private $masterSecret;
    private $retryTimes;

    const app_tender = "tender"; //投标页面
    const app_index = "index"; //首页
    const app_account = "account"; //账户中心页面
    const app_shop = "shop"; //积分商城页面
    const app_shopdetails = "shopdetails"; //积分商城商品详情页面
    const app_ticket = "ticket"; //跳转到我的卡券页面
    const app_htmlpage = "htmlpage"; //H5活动页面

    private function __construct() {
        $this->appKey = JpushKey;
        $this->masterSecret = JpushSecret;
        $this->retryTimes = 3;
    }

    /**
     * 利用单例模式不用多次实例化对象
     * @return type
     */
    public static function GetInstance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // $content 发送的内容     $data 发送的客户端    
    //  $action 属于那个页面   $number 参数   $reutnUrl H5页面跳转的URL地址
    public function push($content, $data, $action = Jpush::app_index, $number = "", $reutnUrl = "") {
        if (empty($number)) {
            $number = "";
        }
        if(empty($reutnUrl)){
            $reutnUrl="";
        }
        $pusher = new PushPayload($this);
        $pusher->setPlatform(array('android', "IOS"));
        if (is_string($data) && $data == "all") {
            $pusher->setAudience("all");
        } elseif (is_array($data)) {
            $pusher->addRegistrationId(array($data["jphRegId"]));
        } else {
            logger_wge("data 无数据", "request", "crl", "jpush");
            return;
        }
        $where["extras"] = array("extmsg" => json_encode(array('action' => $action, "number" => $number, 'url' => $reutnUrl)));
        $pusher->iosNotification($content, $where);
        $pusher->androidNotification($content, $where);
        return $pusher->send();
    }

    public function getAuthStr() {
        return $this->appKey . ":" . $this->masterSecret;
    }

    public function getRetryTimes() {
        return $this->retryTimes;
    }

    public function getLogFile() {
        return $this->logFile;
    }

}
