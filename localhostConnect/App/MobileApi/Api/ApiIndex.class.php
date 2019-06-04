<?php

namespace MobileApi\Api;


use Common\Common\CodeGenerator;
use GraphAware\Neo4j\OGM\Tests\Proxy\Model\User;
use MobileApi\Common\MobileApiBase;
use Service\Connect\Logic\ConnectRelationLogic;
use Service\Integral\Logic\IntegralLogic;
use Service\JavaService\Logic\SiteLogic;
use Service\News\Logic\NewsLogic;
use Service\News\Logic\SendSMSLogic;
use Service\News\Model\SmsSendModel;
use Service\User\Logic\IndexLogic;
use Service\User\Logic\UserFriendLogic;
use Service\User\Logic\UserLogic;
use Service\User\Logic\UserLogLogic;
use Service\User\Model\UserMaillistModel;
use Service\UserCenter\Logic\UserCenterLogic;

/**
 * Description of index
 *
 * @author Administrator
 */
class ApiIndex extends MobileApiBase
{


    private $return  = array("code"=>1,"msg"=>"","info"=>array());

    public function getRules()
    {
        return array(
            'index' => array(),
            "indexRecommend"=>array(
                'isCity' => array("name" => "isCity", "type" => "int", "desc" => "0 不选 1选中 同城"),
                'isIdenty'=> array("name" => "isIdenty", "type" => "int", "desc" => "0 不选 1选中 同行"),
                "isActive"=> array("name" => "isActive", "type" => "int", "desc" => "0 不选 1选中 活跃度"),
                'pageindex' => array('name' => 'pageindex', 'type' => 'int', 'default' => 1, 'desc' => '页码'),
                'pagesize' => array('name' => 'pagesize', 'type' => 'int', 'default' => 15, 'desc' => '每页显示数量')
            ),
        );
    }


    /**
     * 首页推荐列表
     * @desc 首页推荐
     * @return int    code         操作码，0表示成功， 1表示失败
     * @return  array  info         返回数据
     * @return  string msg          提示信息
     */
    public function index() {
        return $this->return;
    }


    /**
     * 测试方法
     * @desc 测试方法
     * @return int code  操作吗
     * @return array info
     * @return string mgs 提示信息
     */
    public function a() {
        $this->return['msg'] = "ddd";
        return $this->return;
    }











































}