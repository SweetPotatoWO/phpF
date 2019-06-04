<?php

namespace Service\Api\Filter;

use Service\Api\Filter\IFilter;
use Service\Api\Exception;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Common/function.php';

/**
 * Description of SignFilter
 *
 * @author Administrator
 */
class ApiSignFilter implements IFilter {

    protected $signName;

    public function __construct($signName = 's') {
        $this->signName = $signName;
    }

    public function check() {
        if (!DI()->config->get('app.apiEnabledSign', false))
            return;
        $allParams = DI()->request->getAll();
        if (empty($allParams)) {
            return;
        }
        $allParams = array_change_key_case($allParams, CASE_LOWER);
        $sign = isset($allParams[$this->signName]) ? $allParams[$this->signName] : '';
        unset($allParams[$this->signName]);
        unset($allParams['service']);
        $expectSign = getSignStr($allParams);
        if (!$this->checkSign($sign, $expectSign)) {
            logger_api('签名错误', $this->getSignStr1($allParams));
            throw new Exception\BadRequestException('签名错误' , 7);
        }
    }

    private function getSignStr1($data = array()) {
        if (empty($data) || !is_array($data))
            return "";
        ksort($data);
        $signStr = '';
        foreach ($data as $key => $val) {
            $signStr .= $val;
        }
        return $signStr;
    }

    /**
     * 验证签名。
     * @param type $sign
     * @param type $expectSign
     */
    private function checkSign($sign, $expectSign) {
        $rsapubkey = DI()->config->get('app.apiRsaPubkeyPath', '');
        if (!empty($rsapubkey)) {
            $sign = DI()->apirsa->decrypt($sign, $rsapubkey);
        }
        return $sign === $expectSign;
    }

}
