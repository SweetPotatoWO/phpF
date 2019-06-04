<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Response;

/**
 * JSON响应类
 */
class ResponseJsonp extends BaseResponse {

    protected $callback = '';

    /**
     * @param string $callback JS回调函数名
     */
    public function __construct($callback) {
        $this->callback = $this->clearXss($callback);
        $this->addHeaders('Content-Type', 'text/javascript; charset=utf-8');
    }

    /**
     * 对回调函数进行跨站清除处理
     *
     * - 可使用白名单或者黑名单方式处理，由接口开发再实现
     */
    protected function clearXss($callback) {
        return $callback;
    }

    protected function formatResult($result) {
        echo $this->callback . '(' . json_encode($result) . ')';
    }

}
