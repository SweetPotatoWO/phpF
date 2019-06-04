<?php

namespace Service\Api\Common;

use Service\Api\Exception;

/**
 * 依赖注入类
 *
 *  Dependency Injection 依赖注入容器
 *  
 * - 调用的方式有：set/get函数、魔法方法setX/getX、类变量$fdi->X、数组$fdi['X]
 * - 初始化的途径：直接赋值、类名、匿名函数
 *
 * <br>使用示例：<br>
  ```
 *       $di = new ApiDI();
 *      
 *       // 用的方式有：set/get函数  魔法方法setX/getX、类属性$di->X、数组$di['X']
 *       $di->key = 'value';
 *       $di['key'] = 'value';
 *       $di->set('key', 'value');
 *       $di->setKey('value');
 *      
 *       echo $di->key;
 *       echo $di['key'];
 *       echo $di->get('key');
 *       echo $di->getKey();
 *      
 *       // 初始化的途径：直接赋值、类名(会回调onInitialize函数)、匿名函数
 *       $di->simpleKey = array('value');
 *       $di->classKey = 'ApiDI';
 *       $di->closureKey = function () {
 *            return 'sth heavy ...';
 *       };
  ```
 *      
 * @property ApiRequest        $request    请求
 * @property ResponseJson      $response   结果响应
 * @property ApiCrypt          $crypt      加密
 * @property ApiConfig         $config     配置
 * @property ApiLogger         $logger     日记
 * @property ApiLoader         $loader     自动加载
 * @property ApiRsa            $apirsa     Rsa加密
 * @property IFilter           $filter     签名过滤器
 * @property IFilter           $timefilter 时间戳过滤器
 * @property Api3Des           $api3des    3DES加密
 */
class ApiDI implements \ArrayAccess {

    /**
     * @var ApiDI $instance 单例
     */
    protected static $instance = NULL;

    /**
     * @var array $hitTimes 服务命中的次数
     */
    protected $hitTimes = array();

    /**
     * @var array 注册的服务池
     */
    protected $data = array();

    public function __construct() {
        
    }

    /**
     * 获取DI单体实例
     *
     * - 1、将进行service级的构造与初始化
     * - 2、也可以通过new创建，但不能实现service的共享
     */
    public static function one() {
        if (self::$instance == null) {
            self::$instance = new ApiDI();
            self::$instance->onConstruct();
        }
        return self::$instance;
    }

    /**
     * service级的构造函数
     *
     * - 1、可实现一些自定义业务的操作，如内置默认service
     * - 2、首次创建时将会调用
     */
    public function onConstruct() {
        $this->request = 'Service\Api\Request\ApiRequest';
        $this->response = 'Service\Api\Response\ResponseJson';
        $this->api3des = 'Service\Api\Crypt\Api3Des';
        $this->apirsa = 'Service\Api\Crypt\ApiRsa';
    }

    public function onInitialize() {
        
    }

    /**
     * 统一setter
     *
     * - 1、设置保存service的构造原型，延时创建
     *
     * @param string $key service注册名称，要求唯一，区分大小写
     * @parms mixed $value service的值，可以是具体的值或实例、类名、匿名函数、数组配置
     */
    public function set($key, $value) {
        $this->resetHit($key);
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * 统一getter
     *
     * - 1、获取指定service的值，并根据其原型分不同情况创建
     * - 2、首次创建时，如果service级的构造函数可调用，则调用
     * - 3、每次获取时，如果非共享且service级的初始化函数可调用，则调用
     *
     * @param string $key service注册名称，要求唯一，区分大小写
     * @param mixed $default service不存在时的默认值
     * @param boolean $isShare 是否获取共享service
     * @return mixed 没有此服务时返回NULL
     */
    public function get($key, $default = null) {
        if (!isset($this->data[$key])) {
            $this->data[$key] = $default;
        }
        $this->recordHitTimes($key);
        if ($this->isFirstHit($key)) {
            $this->data[$key] = $this->initService($this->data[$key]);
        }
        return $this->data[$key];
    }

    /** ------------------ 魔法方法 ------------------ * */
    public function __call($name, $arguments) {
        if (substr($name, 0, 3) == 'set') {
            $key = lcfirst(substr($name, 3));
            return $this->set($key, isset($arguments[0]) ? $arguments[0] : null);
        } else if (substr($name, 0, 3) == 'get') {
            $key = lcfirst(substr($name, 3));
            return $this->get($key, isset($arguments[0]) ? $arguments[0] : null);
        } else {
            
        }
        throw new Exception\InternalServerException('调用了未定义的方法ApiDI::' . $name . '()');
    }

    public function __set($name, $value) {
        $this->set($name, $value);
    }

    public function __get($name) {
        return $this->get($name, null);
    }

    /** ------------------ ArrayAccess（数组式访问）接口 ------------------ * */
    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    public function offsetGet($offset) {
        return $this->get($offset, null);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    /** ------------------ 内部方法 ------------------ * */
    protected function initService($config) {
        $rs = null;
        if ($config instanceOf Closure) {
            $rs = $config();
        } elseif (is_string($config) && class_exists($config)) {
            $rs = new $config();
            if (is_callable(array($rs, 'onInitialize'))) {
                call_user_func(array($rs, 'onInitialize'));
            }
        } else {
            $rs = $config;
        }
        return $rs;
    }

    protected function resetHit($key) {
        $this->hitTimes[$key] = 0;
    }

    protected function isFirstHit($key) {
        return $this->hitTimes[$key] == 1;
    }

    protected function recordHitTimes($key) {
        if (!isset($this->hitTimes[$key])) {
            $this->hitTimes[$key] = 0;
        }
        $this->hitTimes[$key] ++;
    }

}
