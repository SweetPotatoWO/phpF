<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MobileApi\Common;

use Service\Api\Response\BaseResponse;

/**
 * Description of ResponseHtml
 *
 * @author Administrator
 */
class ResponseHtml extends BaseResponse {

    private $Codes = array(
        60000 => array('url' => 'closeWebView', 'msg' => '非法请求'),
        60001 => array('url' => 'User/Login', 'msg' => '您还未登录，请登录后再操作！'),
        60002 => array('url' => 'Huax/RealName', 'msg' => '您还未开通华兴存管！'),
        60003 => array('url' => 'closeWebView', 'msg' => '您输入的金额格式错误！'),
        60004 => array('url' => 'closeWebView', 'msg' => '您输入的金额大于最大可提现额！'),
        60005 => array('url' => 'closeWebView', 'msg' => '您已开通华兴存管账户！'),
        60007 => array('url' => 'closeWebView', 'msg' => '您输入的姓名格式有误，请重新输入！'),
        60008 => array('url' => 'closeWebView', 'msg' => '请输入有效的身份证号码！'),
        60099 => array('url' => 'closeWebView', 'msg' => '未知错误'),
    );
    private $error_url = 'Common/error/par/';

    public function __construct($callback) {
        $this->addHeaders('Content-Type', ' text/html; charset=utf-8');
    }

    /**
     * 设置返回数据
     * @param array/string $data 待返回给客户端的数据，建议使用数组，方便扩展升级
     * @return BaseResponse
     */
    public function setData($data) {
        
    }

    public function output() {
        $this->handleHeaders($this->headers);
        $rs = $this->getResult();
        echo $this->formatResult($rs);
    }

    public function getResult() {
        $result = array();
        if (array_key_exists($this->ret, $this->Codes)) {
            $result = $this->Codes[$this->ret];
            if (!empty($this->jmpurl) && strlen($this->jmpurl) > 2) {
                $result['url'] = $this->jmpurl;
            }
            if (!empty($this->error) && strlen($this->error) > 2) {
                $result['msg'] = $this->error;
            }
        } else {
            $result = array('url' => 'closeWebView', 'msg' => (empty($this->error) ? '未知错误' : $this->error));
        }
        return $result;
    }

    protected function formatResult($result) {
        $par = urlencode(base64_encode(json_encode($result)));
        $url = sprintf('Location:%s%s%s', WECHAT, $this->error_url, $par);
        header($url);
    }

}
