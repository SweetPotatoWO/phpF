<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Common;

use Service\Api\Exception;
use Service\Api\Common\ApiDI;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'function.php';

/**
 * 创建控制器类 工厂方法
 *
 * 将创建与使用分离，简化客户调用，负责控制器复杂的创建过程
 *
  ```
 *      //根据请求(?service=XXX.XXX)生成对应的接口服务，并进行初始化
 *      $api = ApiFactory::generateService();
  ```
 */
class ApiFactory {

    /**
     * 创建服务器
     * 根据客户端提供的接口服务名称和需要调用的方法进行创建工作，如果创建失败，则抛出相应的自定义异常
     *
     * 创建过程主要如下：
     * - 1、 是否缺少控制器名称和需要调用的方法
     * - 2、 控制器文件是否存在，并且控制器是否存在
     * - 3、 方法是否可调用
     * - 4、 控制器是否初始化成功
     *
     * @param boolen $isInitialize 是否在创建后进行初始化
     * @param string $_REQUEST['service'] 接口服务名称，格式：XXX.XXX
     * @return PhalApi_Api 自定义的控制器
     *
     * @uses PhalApi_Api::init()
     * @throws PhalApi_Exception_BadRequest 非法请求下返回400
     */
    static function generateService($isInitialize = true) {
        $service = DI()->request->get('service', 'Index.Index');

        $serviceArr = explode('.', $service);

        if (count($serviceArr) < 2) {
            throw new Exception\BaseException('非法服务：' . $service, 10);
        }

        list ($apiClassName, $action) = $serviceArr;
        $apiClassName = '\MobileApi\Api\Api' . ucfirst($apiClassName);

        if (!class_exists($apiClassName)) {
            throw new Exception\BaseException('接口服务' . $service . '不存在', 11);
        }
        $api = new $apiClassName();
        if (!is_subclass_of($api, '\Service\Api\Common\BaseApi')) {
            throw new Exception\InternalServerException($apiClassName . '不是 BaseApi的子类');
        }

        if (!method_exists($api, $action) || !is_callable(array($api, $action))) {
            throw new Exception\BaseException('接口服务' . $service . '不存在', 11);
        }
        if ($isInitialize) {
            $api->init();
        }
        return $api;
    }

}
