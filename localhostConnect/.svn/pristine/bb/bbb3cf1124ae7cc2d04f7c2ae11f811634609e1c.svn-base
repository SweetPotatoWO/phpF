<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Common;

use Service\Api\Exception;
use Service\Api\Filter\IFilter;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'function.php';

/**
 * BaseApi 接口服务基类
 *
 * - 实现身份验证、按参数规则解析生成接口参数等操作
 * - 提供给开发人员自宝义的接口服务具体类继承
 *
 * <br>通常地，可以这样继承：<br>
 *
  ```
 *  class ApiDemo extends BaseApi {
 *      
 *      public function getRules() {
 *          return array(
 *              // ...
 *          );
 *      }
 *
 *      public function doSth() {
 *          $rs = array();
 *
 *          // ...
 *
 *          return $rs;
 *      }
 *  }
  ```
 *
 */
class BaseApi {

    private $commonRules = array();
    private $apiRules = array();

    /**
     * 设置规则解析后的接口参数
     * @param string $name 接口参数名字
     * @param mixed $value 接口参数解析后的值
     */
    public function __set($name, $value) {
        $this->$name = $value;
    }

    /**
     * 获取规则解析后的接口参数
     * @param string $name 接口参数名字
     * @throws InternalServerException 获取未设置的接口参数时，返回500
     * @return mixed
     */
    public function __get($name) {
        if (!isset($this->$name) || empty($name)) {
            throw new Exception\InternalServerException('BaseApi::' . $name . ' 未定义');
        }
        return $this->$name;
    }

    /**
     * 初始化
     *
     * 主要完成的初始化工作有：
     * - 1、[必须]过滤器调用生成公共接口参数并进行签名认证
     * - 2、[必须]按参数规则解析生成接口参数
     * - 3、[可选]用户身份验证
     * 
     * @uses BaseApi::createMemberValue()
     * @uses BaseApi::filterCheck()
     * @uses BaseApi::userCheck()
     * @return null
     */
    public function init() {
        $this->filterCheck();
        $this->createMemberValue();
        $this->initUserInfo();
    }

    /**
     * 按参数规则解析生成接口参数
     *
     * 根据配置的参数规则，解析过滤，并将接口参数存放于类成员变量
     * 
     * @uses BaseApi::getApiRules()
     */
    protected function createMemberValue() {
        DI()->request->decryptEncryptPlace();
        logger_api('Request', "参数：" . json_encode($this->apiRules), 'api/request');
        foreach ($this->apiRules as $key => $rule) {
            $this->$key = DI()->request->getByRule($rule);
             logger_api('Request', "参数：" . json_encode($this->$key), 'api/request');
        }
    }

    /**
     * 设置所有规则。
     */
    private function setAllRules() {
        $this->commonRules = $this->getCommonRules();
        $this->apiRules = $this->getApiRules();
        $pubkeys = array_intersect_key($commonRules, $apiRules);
        foreach ($pubkeys as $key => $value) {
            unset($commonRules[$key]);
        }
        foreach ($this->commonRules as $key => $rule) {
            $this->$key = DI()->request->getByRule($rule);
        }
    }

    /**
     * 取接口参数规则
     *
     * 主要包括有：
     * - 1、[固定]系统级的service参数
     * - 2、应用级统一接口参数规则，在app.apiCommonRules中配置
     * - 3、接口级通常参数规则，在子类的*中配置
     * - 4、接口级当前操作参数规则
     *
     * <b>当规则有冲突时，以后面为准。另外，被请求的函数名和配置的下标都转成小写再进行匹配。</b>
     *
     * @uses BaseApi::getRules()
     * @return array
     */
    public function getApiRules() {
        $rules = array();
        $allRules = $this->getRules();
        if (!is_array($allRules)) {
            $allRules = array();
        }
        $allRules = array_change_key_case($allRules, CASE_LOWER);
        $service = DI()->request->get('service', 'Index.Index');
        list($apiClassName, $action) = explode('.', $service);
        $action = strtolower($action);

        if (isset($allRules[$action]) && is_array($allRules[$action])) {
            $rules = $allRules[$action];
        }
        if (isset($allRules['*'])) {
            $rules = array_merge($allRules['*'], $rules);
        }
         logger_api('Request', "参数：" .$action, 'api/request');
        return $rules;
    }

    /**
     * 获取所有接口所有参数规则
     * @return type
     */
    public function getAllApiRules() {
        $rules = $this->getApiRules();
        $apiCommonRules = $this->getCommonRules();
        if (!empty($apiCommonRules) && is_array($apiCommonRules)) {
            $rules = array_merge($apiCommonRules, $rules);
        }
        return $rules;
    }

    /**
     * 获取公共规则。
     * @return type
     */
    public function getCommonRules() {
        $apiCommonRules = DI()->config->get('app.apiCommonRules', array());
        return $apiCommonRules;
    }

    /**
     * 获取参数设置的规则
     *
     * 可由开发人员根据需要重载
     * 
     * @return array
     */
    public function getRules() {
        return array();
    }

    /**
     * 过滤器调用
     *
     * 可由开发人员根据需要重载，以实现项目拦截处理，需要：
     * - 1、实现IFilter::check()接口
     * - 2、注册的过滤器到DI()->filter
     *
     * <br>以下是一个简单的示例：<br>
      ```
     * 	class MyFilter implements IFilter {
     * 
     * 		public function check() {
     * 			//TODO
     * 		}
     * 	}
     * 
     * 
     *  //在初始化文件 init.php 中注册过滤器
     *  DI()->filter = 'MyFilter';
      ```
     * 
     * @see IFilter::check()
     * @throws BadRequestException 当验证失败时，请抛出此异常，以返回400
     */
    protected function filterCheck() {
        $this->setAllRules();
        $timefilte = DI()->get('timefilte', '\Service\Api\Filter\ApiRequestFilter');
        if (isset($timefilte) && ($timefilte instanceof IFilter)) {
            $timefilte->check();
        }
        $filter = DI()->get('filter', '\Service\Api\Filter\ApiSignFilter');
        if (isset($filter) && ($filter instanceof IFilter)) {
            $filter->check();
        }
    }

    /**
     * 验证用户身份。
     * @param type $isExit 身份验证不通过时是否抛出异常。
     */
    protected function userCheck($isExit = true) {
        
    }

    /**
     * 初始化用户信息。
     */
    protected function initUserInfo() {
        
    }

}
