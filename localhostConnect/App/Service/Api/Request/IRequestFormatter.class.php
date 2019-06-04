<?php

namespace Service\Api\Request;

/**
 * 格式化接口。
 */
interface IRequestFormatter {

    public function parse($value, $rule);
}
