<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TypeArr {

    /**
     * 还款方式
     * @var type 
     */
    static $payway = array('1' => '等额本息', '2' => '先息后本', '3' => '到期还本付息', '4' => '按天还款', '5' => '秒还');

    /**
     * 投标方式
     * @var type 1 PC 2 微信 3 IOS 4 安卓 5 自动投标
     */
    static $tenderway = array('1' => 'PC投资', '2' => '微信投资', '3' => 'IOS', '4' => '安卓', '5' => '自动投标');

    /**
     * 银行
     * @var type 
     */
    static $bank = array(
        'CMB' => '招商银行', 'ABC' => '中国农业银行', 'CMBC' => '中国民生银行', 'PSBC' => '邮储银行', 'BOC' => '中国银行',
        'COMM' => '交通银行', 'BJB' => '北京银行', 'ICBC' => '中国工商银行', 'SPDB' => '浦发银行', 'GDB' => '广发银行',
        'BEA' => '东亚银行', 'CIB' => '兴业银行', 'CITIC' => '中信银行', 'SHB' => '上海银行', 'CEB' => '光大银行');

}
