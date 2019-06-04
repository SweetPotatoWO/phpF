<?php

/**
 * 请在下面放置任何您需要的应用配置
 */
return array(
    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        'sign' => array('name' => 'sign', 'require' => true, 'desc' => '签名', 'default' => 'sign'),
        'deviceID' => array('name' => 'd', 'require' => true, 'desc' => '设备ID', 'default' => 'abcdefg'),
        'terminal' => array('name' => 'mt', 'type' => 'int', 'require' => true, 'desc' => '终端类型（1：PC 2：安卓 3：IOS 4：微信）', 'default' => 3),
        'timestamp' => array('name' => 't', 'type' => 'int', 'require' => true, 'desc' => '时间戳', 'default' => time()),
        'serviceVersion' => array('name' => 'sv', 'require' => true, 'desc' => '接入的接口服务版本号', 'default' => '2.0.0'),
        'version' => array('name' => 'v', 'require' => true, 'desc' => '终端版本号', 'default' => '2.0.0'),
        'token' => array('name' => 'token', 'require' => false, 'desc' => 'Token')
    ),
    'apiTimeout' => 180, //接口超时时长。
    'api3DesKey' => 'AGAO53D4E5FY27H8I9J0G1I3', //3DES密钥。
    'apiEnabledTime' => false, //是否启用时间戳拦截。
    'apiEnabledSign' => false, //是否启用签名。
    'apiEnabledEncrypt' => false, //启用加密。
    'apiEnabledName' => 'data', //加密部位名称
    'apiRsaPrikeyPath' => dirname(__FILE__) . '/Key/rsa_private_key.pem',
    'apiRsaPubkeyPath' => dirname(__FILE__) . '/Key/rsa_public_key.pem',
    'apiEmailTpl' => dirname(__FILE__) . '/Tpl/email.html',
);

