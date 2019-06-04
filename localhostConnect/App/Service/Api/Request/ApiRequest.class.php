<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Request;

use Service\Api\Exception;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . '../Common/function.php';

/**
 * 参数生成类
 * - 负责根据提供的参数规则，进行参数创建工作，并返回错误信息
 * - 需要与参数规则配合使用
 */
class ApiRequest {

    protected $data = array();
    protected $headers = array();

    /**
     * @param array $data 参数来源，可以为：$_GET/$_POST/$_REQUEST/自定义
     */
    public function __construct($data = null) {
        $this->data = $this->genData($data);
        $this->headers = $this->getAllHeaders();
    }

    /**
     * 生成请求参数
     * 此生成过程便于项目根据不同的需要进行定制化参数的限制，如：
     * 如只允许接受POST数据，或者只接受GET方式的service参数，以及对称加密后的数据包等
     *
     * @param array $data 接口参数包
     *
     * @return array
     */
    protected function genData($data) {
        if (!isset($data) || !is_array($data)) {
            $gets = I('get.');
            $posts = I('post.');
            return array_merge($gets, $posts);
        }
        return $data;
    }

    /**
     * 初始化请求Header头信息
     * @return array|false
     */
    protected function getAllHeaders() {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        //对没有getallheaders函数做处理
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (is_array($value) || substr($name, 0, 5) != 'HTTP_') {
                continue;
            }
            $headerKey = implode('-', array_map('ucwords', explode('_', strtolower(substr($name, 5)))));
            $headers[$headerKey] = $value;
        }
        return $headers;
    }

    /**
     * 获取请求Header参数
     *
     * @param string $key     Header-key值
     * @param mixed  $default 默认值
     *
     * @return string
     */
    public function getHeader($key, $default = null) {
        return isset($this->headers[$key]) ? $this->headers[$key] : $default;
    }

    /**
     * 直接获取接口参数
     *
     * @param string $key     接口参数名字
     * @param mixed  $default 默认值
     *
     * @return Ambigous <unknown, multitype:>
     */
    public function get($key, $default = null) {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * 根据规则获取参数
     * 根据提供的参数规则，进行参数创建工作，并返回错误信息
     *
     * @param $rule array('name' => '', 'type' => '', 'defalt' => ...) 参数规则
     *
     * @return mixed
     */
    public function getByRule($rule) {
        $rs = null;
        if (!isset($rule['name'])) {
            throw new Exception\InternalServerException('参数规则缺少name');
        }
        $rs = RequestVar::format($rule['name'], $rule, $this->data);
        if ($rs === null && (isset($rule['require']) && $rule['require'])) {
            throw new Exception\BadRequestException('缺少必要参数' . $rule['name']);
        }
        return $rs;
    }

    /**
     * 解密加密部分数据。
     */
    public function decryptEncryptPlace() {
        if (!DI()->config->get('app.apiEnabledEncrypt', false))
            return;
        $placekey = DI()->config->get('app.apiEnabledName', 'data');
        if (!empty($this->data[$placekey])) {
            $deskey = DI()->config->get('app.api3DesKey', '');
            $decryptdata = DI()->api3des->decrypt($this->data[$placekey], $deskey);
            foreach ($decryptdata as $key => $value) {
                $this->data[$key] = $value;
            }
              logger_api('Request', "参数：" . json_encode($decryptdata), 'api/request');
            unset($this->data[$placekey]);
        }
    }

    /**
     * 获取全部接口参数
     * @return array
     */
    public function getAll() {
        return $this->data;
    }

}
