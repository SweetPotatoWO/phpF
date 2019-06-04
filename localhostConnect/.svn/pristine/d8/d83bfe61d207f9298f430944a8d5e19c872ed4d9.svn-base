<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Exception;

/**
 * 服务器运行异常错误
 */
class InternalServerException extends BaseException {

    public function __construct($message, $code = 0) {
        parent::__construct(
                '服务器运行错误：' . $message, 5000 + $code
        );
    }

}
