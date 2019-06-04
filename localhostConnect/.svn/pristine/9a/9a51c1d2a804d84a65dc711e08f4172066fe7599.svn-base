<?php

namespace Service\Api\Exception;

/**
 * 自定义异常类
 *
 * - 可以继承此框架异常类，定义项目的业务异常类，以便被框架自动捕捉处理
 *
 */
class BaseException extends \Exception {

    private $url;

    public function __construct($message = '', $code = 0, $url = '') {
        parent::__construct(
                $message, $code
        );
        $this->url = $url;
    }

    /**
     * 获取一个跳转URL。
     * @return type
     */
    public function getJmpUrl() {
        return $this->url;
    }

}
