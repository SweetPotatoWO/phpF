<?php

namespace Service\Api\Request\Formatter;

use Service\Api\Exception;
use Service\Api\Request\IRequestFormatter;

/**
 * 格式化枚举类型
 */
class RequestFormatterEnum extends RequestFormatterBase implements IRequestFormatter {

    /**
     * 检测枚举类型
     * @param string $value 变量值
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @return 当不符合时返回$rule
     */
    public function parse($value, $rule) {
        $this->formatEnumRule($rule);

        $this->formatEnumValue($value, $rule);

        return $value;
    }

    /**
     * 检测枚举规则的合法性
     * @param array $rule array('name' => '', 'type' => 'enum', 'default' => '', 'range' => array(...))
     * @throws InternalServerException
     */
    protected function formatEnumRule($rule) {
        if (!isset($rule['range'])) {
            throw new Exception\InternalServerException($rule['name'] . '缺少枚举范围');
        }

        if (empty($rule['range']) || !is_array($rule['range'])) {
            throw new Exception\InternalServerException($rule['name'] . '枚举规则中的range不能为空');
        }
    }

}
