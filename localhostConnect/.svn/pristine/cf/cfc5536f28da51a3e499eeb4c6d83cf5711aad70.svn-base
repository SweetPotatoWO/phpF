<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Request\Formatter;

use Service\Api\Exception;
use Service\Api\Request\IRequestFormatter;

/**
 * Description of RequestFormatterPhone
 *
 * @author Administrator
 */
class RequestFormatterPhone extends RequestFormatterBase implements IRequestFormatter {

    /**
     * 对手机号码进行格式化
     *
     * @param mixed $value 变量值
     * @@param array $rule array('len' => ‘最长长度’)
     * @return string 格式化后的变量
     */
    public function parse($value, $rule) {
        $rs = strval($this->filterByStrLen(strval($value), $rule));
        if (!verify_phone($rs)) {
            throw new Exception\BadRequestException('请输入正确的手机号码', 5);
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

}
