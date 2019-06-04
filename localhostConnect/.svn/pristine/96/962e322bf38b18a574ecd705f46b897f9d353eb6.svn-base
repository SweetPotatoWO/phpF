<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Org\Util;

use Think\Db;
use Common\Common\Redis;

/**
  +------------------------------------------------------------------------------
 * 基于角色的数据库方式验证类
  +------------------------------------------------------------------------------
 */
// 配置文件增加设置
// USER_AUTH_ON 是否需要认证
// USER_AUTH_TYPE 认证类型
// USER_AUTH_KEY 认证识别号
// REQUIRE_AUTH_MODULE  需要认证模块
// NOT_AUTH_MODULE 无需认证模块
// USER_AUTH_GATEWAY 认证网关
// RBAC_DB_DSN  数据库连接DSN
// RBAC_ROLE_TABLE 角色表名称
// RBAC_USER_TABLE 用户表名称
// RBAC_ACCESS_TABLE 权限表名称
// RBAC_NODE_TABLE 节点表名称

class Rbac {

    // 认证方法
    static public function authenticate($map, $model = '') {
        if (empty($model))
            $model = C('USER_AUTH_MODEL');
        //使用给定的Map进行认证
        return M($model)->where($map)->find();
    }

    //用于检测用户权限的方法,并保存到Session中
    static function saveAccessList($userID = null) {
        if (null === $userID)
            $userID = C('userID');
        // 如果使用普通权限模式，保存当前用户的访问权限列表
        // 对管理员开放所有权限
//         if (C('USER_AUTH_TYPE') != 2 && !$_SESSION[C('ADMIN_AUTH_KEY')])
        //if (C('USER_AUTH_TYPE') != 2)
        $_SESSION['_ACCESS_LIST'] = self::getAccessList($userID);
        return;
    }

    //检查当前操作是否需要认证
    static function checkAccess() {
        //如果项目要求认证，并且当前模块需要认证，则进行权限认证
        if (C('USER_AUTH_ON')) {
            $_module = array();
            $_action = array();
            if ("" != C('REQUIRE_AUTH_MODULE')) {
                //需要认证的模块
                $_module['yes'] = explode(',', strtoupper(C('REQUIRE_AUTH_MODULE')));
            } else {
                //无需认证的模块
                $_module['no'] = explode(',', strtoupper(C('NOT_AUTH_MODULE')));
            }
            //检查当前模块是否需要认证
            if ((!empty($_module['no']) && !in_array(strtoupper(CONTROLLER_NAME), $_module['no'])) || (!empty($_module['yes']) && in_array(strtoupper(CONTROLLER_NAME), $_module['yes']))) {
                if ("" != C('REQUIRE_AUTH_ACTION')) {
                    //需要认证的操作
                    $_action['yes'] = explode(',', strtoupper(C('REQUIRE_AUTH_ACTION')));
                } else {
                    //无需认证的操作
                    $_action['no'] = explode(',', strtoupper(C('NOT_AUTH_ACTION')));
                }
                //检查当前操作是否需要认证
                if ((!empty($_action['no']) && !in_array(strtoupper(ACTION_NAME), $_action['no'])) || (!empty($_action['yes']) && in_array(strtoupper(ACTION_NAME), $_action['yes']))) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return false;
    }

    //权限认证的过滤器方法
    static public function AccessDecision($userID, $appName = ACTION_NAME) {
        //检查是否需要认证
        if (self::checkAccess()) {
            //存在认证识别号，则进行进一步的访问决策
            $accessGuid = md5(strtoupper($appName . CONTROLLER_NAME . ACTION_NAME));
            if (empty($_SESSION[C('ADMIN_AUTH_KEY')])) {
                if (C('USER_AUTH_TYPE') == 2) {
                    //加强验证和即时验证模式 更加安全 后台权限修改可以即时生效
                    //通过数据库进行访问检查
                    $accessList = self::getAccessList($userID);
                } else {
                    // 如果是管理员或者当前操作已经认证过，无需再次认证
                    if ($_SESSION[$accessGuid]) {
                        return true;
                    }
                    //登录验证模式，比较登录后保存的权限访问列表
                    $accessList = $_SESSION['_ACCESS_LIST'];
                }
                //判断是否为组件化模式，如果是，验证其全模块名
                if (!isset($accessList[strtoupper(CONTROLLER_NAME . '/' . $appName)])) {
                    $_SESSION[$accessGuid] = false;
                    return false;
                } else {
                    $_SESSION[$accessGuid] = true;
                }
            } else {
                //管理员无需认证
                return true;
            }
        }
        return true;
    }

    /**
      +----------------------------------------------------------
     * 取得当前认证号的所有权限列表
      +----------------------------------------------------------
     * @param integer $userID 用户ID
      +----------------------------------------------------------
     * @access public
      +----------------------------------------------------------
     */
    static public function getAccessList($userID) {
        $redis = Redis::GetInstance();
        $key = C('USER_AUTH_CACH_KEY') . $userID;
        $access = $redis->get($key);
        if ($access === false || count($access) < 1) {
            // Db方式权限数据
            $db = Db::getInstance(C('RBAC_DB_DSN'));
            $table = array('role' => C('RBAC_ROLE_TABLE'), 'user' => C('RBAC_USER_TABLE'), 'access' => C('RBAC_ACCESS_TABLE'), 'node' => C('RBAC_NODE_TABLE'));
            $sql = "select node.menuID as id,node.menuCode as name from " .
                    $table['role'] . " as role," .
                    $table['user'] . " as user," .
                    $table['access'] . " as access ," .
                    $table['node'] . " as node " .
                    "where user.userID='{$userID}' and user.roleID=role.roleID and access.roleID=role.roleID  and role.status=1 and access.menuID=node.menuID and node.levelType>=1 and node.IfDisplay=1";
            $apps = $db->query($sql);
            $access = array();
            foreach ($apps as $key => $app) {
                if (!empty($app['name'])) {
                    $appId = $app['id'];
                    $appName = strtoupper($app['name']);
                    $access[$appName] = $appId;
                }
            }
            $redis->set($key, $access, 30 * 60);
        } else {
            $redis->set($key, $access, 10 * 60);
        }
        return $access;
    }

}
