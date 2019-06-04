<?php

namespace Service\Api\Request\Formatter;

use Service\Api\Exception;

/**
 * RequestFormatterBase 公共基类
 *
 * - 提供基本的公共功能，便于子类重用
 *
 */
class RequestFormatterBase {

    /**
     * 根据范围进行控制
     */
    protected function filterByRange($value, $rule) {
        $this->filterRangeMinLessThanOrEqualsMax($rule);

        $this->filterRangeCheckMin($value, $rule);

        $this->filterRangeCheckMax($value, $rule);

        return $value;
    }

    protected function filterRangeMinLessThanOrEqualsMax($rule) {
        if (isset($rule['min']) && isset($rule['max']) && $rule['min'] > $rule['max']) {
            throw new Exception\BadRequestException(
            '最小值应该小于等于最大值，但现在' . $rule['name'] . '的最小值为：' . $rule['min'] . '，最大值为：' . $rule['max'], 3
            );
        }
    }

    /**
     * 范围检测最小值。
     * @param type $value
     * @param type $rule
     * @throws Exception\BadRequestException
     */
    protected function filterRangeCheckMin($value, $rule) {
        if (isset($rule['min']) && $value < $rule['min']) {
            throw new Exception\BadRequestException(
            $rule['name'] . '应该大于或等于' . $rule['min'] . ', 但现在' . $rule['name'] . ' = ' . $value, 1
            );
        }
    }

    /**
     * 范围检测最大值。
     * @param type $value
     * @param type $rule
     * @throws Exception\BadRequestException
     */
    protected function filterRangeCheckMax($value, $rule) {
        if (isset($rule['max']) && $value > $rule['max']) {
            throw new Exception\BadRequestException(
            $rule['name'] . '应该小于或等于' . $rule['max'] . ', 但现在' . $rule['name'] . ' = ' . $value
            , 2);
        }
    }

    /**
     * 格式化枚举类型
     * @param string $value 变量值
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @throws PhalApi_Exception_BadRequest
     */
    protected function formatEnumValue($value, $rule) {
        if (!in_array($value, $rule['range'])) {
            throw new Exception\BadRequestException(
            '参数' . $rule['name'] . '应该为：' . implode('/', $rule['range']) . '，但现在' . $rule['name'] . ' = ' . $value, 4
            );
        }
    }

}
