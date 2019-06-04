<?php

namespace Service\Api\Exception;

/**
 * 客户端非法请求
 */
class BadRequestException extends BaseException {

    public function __construct($message, $code = 0) {
        parent::__construct(
                '您的请求出错了：' . $message, 4000 + $code
        );
    }

}
