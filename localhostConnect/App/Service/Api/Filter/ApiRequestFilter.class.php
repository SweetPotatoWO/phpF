<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Filter;

use Service\Api\Filter\IFilter;
use Service\Api\Exception;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Common/function.php';

/**
 * Description of ApiRequestFilter
 *
 * @author Administrator
 */
class ApiRequestFilter implements IFilter {

    protected $timestampName;

    /**
     * 超时时间。
     * @var type 
     */
    private $m_timeout = 180;

    public function __construct($timestampName = 't') {
        $this->timestampName = $timestampName;
    }

    public function check() {
        if (!DI()->config->get('app.apiEnabledTime', false))
            return;
        $allParams = DI()->request->getAll();
        if (empty($allParams)) {
            return;
        }
        $allParams = array_change_key_case($allParams, CASE_LOWER);
        if (!$this->checkTimestamp($allParams)) {
            throw new Exception\BadRequestException('请求超时', 6);
        }
    }

    /**
     * 验证时间戳。
     * @param type $timestamp
     */
    private function checkTimestamp($allParams) {
        $timeout = DI()->config->get('app.apiTimeout', 0);
        if (empty($timeout) && $timeout * 1 <= 0) {
            $timeout = $this->m_timeout;
        }
        $timestamp = isset($allParams[$this->timestampName]) ? $allParams[$this->timestampName] * 1 : 0;
        $timediff = (time() - ($timestamp * 1));
        $result = ($timediff <= $timeout) && ($timediff >= ($timeout * -1));
        return $result;
    }

}
