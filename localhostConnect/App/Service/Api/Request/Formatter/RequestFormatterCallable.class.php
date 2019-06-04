<?php

namespace Service\Api\Request\Formatter;

use Service\Api\Exception;
use Service\Api\Request\IRequestFormatter;

/**
 * 格式化回调类型
 *
 */
class RequestFormatterCallable extends RequestFormatterBase implements IRequestFormatter {

    /**
     * 对回调类型进行格式化
     *
     * @param mixed $value 变量值
     * @param array $rule array('callback' => '回调函数', 'params' => '第三个参数')
     * @return boolean/string 格式化后的变量
     *
     */
    public function parse($value, $rule) {
        if (!isset($rule['callback']) || !is_callable($rule['callback'])) {
            throw new Exception\InternalServerException(
            $rule['name'] . '参数规则的回调函数非法'
            );
        }
        if (isset($rule['params'])) {
            return call_user_func($rule['callback'], $value, $rule, $rule['params']);
        } else {
            return call_user_func($rule['callback'], $value, $rule);
        }
    }

}
