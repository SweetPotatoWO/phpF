<?php

namespace Service\Api\Request\Formatter;

use Service\Api\Request\IRequestFormatter;

/**
 * 格式化浮点类型
 */
class RequestFormatterFloat extends RequestFormatterBase implements IRequestFormatter {

    /**
     * 对浮点型进行格式化
     *
     * @param mixed $value 变量值
     * @param array $rule array('min' => '最小值', 'max' => '最大值')
     * @return float/string 格式化后的变量
     *
     */
    public function parse($value, $rule) {
        return floatval($this->filterByRange(floatval($value), $rule));
    }

}
