<?php

namespace Service\Api\Request\Formatter;

use Service\Api\Exception;
use Service\Api\Request\IRequestFormatter;

/**
 * 格式化字符串
 */
class RequestFormatterString extends RequestFormatterBase implements IRequestFormatter {

    /**
     * 对字符串进行格式化
     *
     * @param mixed $value 变量值
     * @@param array $rule array('len' => ‘最长长度’)
     * @return string 格式化后的变量
     */
    public function parse($value, $rule) {
        $rs = strval($this->filterByStrLen(strval($value), $rule));
        $this->filterByRegex($rs, $rule);
        if ((isset($rule['require']) && $rule['require']) && strlen($value) <= 0) {
            return null;
        }
        return $rs;
    }

    /**
     * 根据字符串长度进行截取
     */
    protected function filterByStrLen($value, $rule) {
        $lenRule = $rule;
        $lenRule['name'] = $lenRule['name'] . '.len';
        $lenValue = !empty($lenRule['format']) ? mb_strlen($value, $lenRule['format']) : strlen($value);
        $this->filterByRange($lenValue, $lenRule);
        return $value;
    }

    /**
     * 进行正则匹配
     */
    protected function filterByRegex($value, $rule) {
        if (!isset($rule['regex']) || empty($rule['regex'])) {
            return;
        }
        //如果你看到此行报错，说明提供的正则表达式不合法
        if (preg_match($rule['regex'], $value) <= 0) {
            throw new Exception\BadRequestException(empty($rule['desc']) ? $rule['name'] : $rule['desc'] . '格式不正确', 5);
        }
    }

}
