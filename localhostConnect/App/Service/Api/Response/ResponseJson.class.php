<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Response;

/**
 *  JSON响应类
 */
class ResponseJson extends BaseResponse {

    public function __construct() {
        $this->addHeaders('Content-Type', 'application/json;charset=utf-8');
    }

    protected function formatResult($result) {
        return json_encode($result);
    }

}
