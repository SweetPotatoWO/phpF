<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * ThinkPHP 普通模式定义
 */
return array(
    // 配置文件
    'config' => array(
        THINK_PATH . 'Conf/convention.php', // 系统惯例配置
        CONF_PATH . 'config' . CONF_EXT, // 应用公共配置
    ),
    // 别名定义
    'alias' => array(
        'Think\Log' => CORE_PATH . 'Log' . EXT,
        'Think\Log\Driver\File' => CORE_PATH . 'Log/Driver/File' . EXT,
        'Think\Exception' => CORE_PATH . 'Exception' . EXT,
        'Think\Model' => CORE_PATH . 'Model' . EXT,
        'Think\Db' => CORE_PATH . 'Db' . EXT,
        'Think\Cache' => CORE_PATH . 'Cache' . EXT,
        'Think\Cache\Driver\File' => CORE_PATH . 'Cache/Driver/File' . EXT,
        'Think\Storage' => CORE_PATH . 'Storage' . EXT,
    ),
    // 函数和类文件
    'core' => array(
        THINK_PATH . 'Common/functions.php',
        COMMON_PATH . 'Common/function.php',
        CORE_PATH . 'App' . EXT,
        CORE_PATH . 'Dispatcher' . EXT,
        CORE_PATH . 'Controller' . EXT,
    ),
    // 行为扩展定义
    'tags' => array(
        'view_parse' => array(
            'Behavior\ParseTemplateBehavior', // 模板解析 支持PHP、内置模板引擎和第三方模板引擎
        ),
        'template_filter' => array(
            'Behavior\ContentReplaceBehavior', // 模板输出替换
        ),
    ),
);
