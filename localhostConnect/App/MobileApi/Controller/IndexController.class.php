<?php

namespace MobileApi\Controller;

use Common\Common\CodeGenerator;
use Service\JavaService\Logic\SiteLogic;
use Think\Controller;
use Service\Api\Common;
use Service\Api\Exception;
use Service\Api\Config;
use Service\System\Logic\SysParameterLogic;
use Service\Api\Crypt\Api3Des;
use MobileApi\Common\ResponseHtml;

/**
 * Description of IndexController
 *
 * @author Administrator
 */
class IndexController extends Controller {


    //跳转的其他页面
    private static $HTML_LIST = array(

    );

    public function _initialize() {
//配置
        DI()->config = new Config\ApiConfigFile(APP_PATH . '/MobileApi/Config');

    }

    public function index() {
//        if(!isApp()){
//            echo '非法请求！';
//            exit;
//        }
        $rs = $this->response();
        $rs->output();
    }



    /**
     * 响应操作
     *
     * 通过工厂方法创建合适的控制器，然后调用指定的方法，最后返回格式化的数据。
     *
     * @return mixed 根据配置的或者手动设置的返回格式，将结果返回
     *  其结果包含以下元素：
      ```
     *  array(
     *      'ret'   => 200,	            //服务器响应状态
     *      'data'  => array(),	        //正常并成功响应后，返回给客户端的数据	
     *      'msg'   => '',		        //错误提示信息
     *  );
      ```
     */
    private function response() {
        $rs = DI()->response;
        $service = DI()->request->get('service', 'Index.Index');
        if (in_array(strtolower($service), self::$HTML_LIST)) {
            $rs = DI()->response = new ResponseHtml();
        }
        try {
            // 接口响应
            $this->changeConfig($service);
            $api = Common\ApiFactory::generateService();
            list($apiClassName, $action) = explode('.', $service);
            $data = call_user_func(array($api, $action));
            $rs->setData($data);
        } catch (Exception\BaseException $ex) {
            // 框架或项目的异常
            $rs->setRet($ex->getCode());
            $rs->setError($ex->getMessage());
            $rs->setJmpUrl($ex->getJmpUrl());
        } catch (Exception $ex) {
            // 不可控的异常
            logger_api($service, strval($ex));
            throw $ex;
        }
        return $rs;
    }

    /**
     * 根据接口名改变配置。
     * @param type $service
     */
    private function changeConfig($service) {
        if (strtolower($service) == 'common.timestamp') {
            DI()->config->set('app.apiEnabledTime', false);
        }
    }





}
