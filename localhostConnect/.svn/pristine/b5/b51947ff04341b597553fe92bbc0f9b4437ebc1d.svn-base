<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MobileApi\Controller;

use Think\Controller;
use Service\Api\Common\ApiLoader;
use Service\Api\Common\ApiFactory;
use Service\Api\Exception;
use Service\Api\Config\ApiConfigFile;

define("D_S", DIRECTORY_SEPARATOR);

/**
 * Description of DocController
 *
 * @author Administrator
 */
class DocController extends Controller {

    public function _initialize() {
        //配置
        DI()->config = new ApiConfigFile(APP_PATH . '/MobileApi/Config');
    }

    public function apiList() {
        $apiDirName = 'MobileApi';
        $loader = new ApiLoader(APP_PATH, 'Api');
        DI()->loader = $loader;
        DI()->loader->addDirs($apiDirName);
        $qq = scandir(APP_PATH . $apiDirName . '/Api');
        $files = $this->listDir(APP_PATH . $apiDirName . '/Api');
        $allBaseApiMethods = get_class_methods('\Service\Api\Common\BaseApi');
        $allApiS = array();
        $uri = str_ireplace('apiList', 'checkApiParams', $_SERVER['REQUEST_URI']);
        foreach ($files as $value) {
            $value = realpath($value);
            $subValue = substr($value, strpos($value, D_S . 'Api' . D_S) + 1);
            //支持多层嵌套，不限级
            $arr = explode(D_S, $subValue);
            $subValue = implode('\\', $arr);
            $apiServer = '\\' . $apiDirName . '\\' . str_replace('.class.php', '', $subValue);
            if (!class_exists($apiServer)) {
                continue;
            }
            $method = array_diff(get_class_methods($apiServer), $allBaseApiMethods);
            foreach ($method as $mValue) {
                $rMethod = new \Reflectionmethod($apiServer, $mValue);
                if (!$rMethod->isPublic() || strpos($mValue, '__') === 0 || $rMethod->isStatic()) {
                    continue;
                }
                $title = '//请检测函数注释';
                $desc = '//请使用@desc 注释';
                $docComment = $rMethod->getDocComment();
                if ($docComment !== false) {
                    $docCommentArr = explode("\n", $docComment);
                    $comment = trim($docCommentArr[1]);
                    $title = trim(substr($comment, strpos($comment, '*') + 1));

                    foreach ($docCommentArr as $comment) {
                        $pos = stripos($comment, '@desc');
                        if ($pos !== false) {
                            $desc = substr($comment, $pos + 5);
                        }
                    }
                }
                $service = substr($apiServer, 18) . '.' . ucfirst($mValue);
                $allApiS[$service] = array(
                    'service' => $service,
                    'title' => $title,
                    'desc' => $desc,
                    'url' => $uri . '?service=' . $service,
                );
            }
        }
        ksort($allApiS);
        $this->assign('apilist', $allApiS);
        $this->display('apilist');
    }

    public function checkApiParams() {
        $service = DI()->request->get('service', 'Index.Index');
        $rules = array();
        $returns = array();
        $description = '';
        $descComment = '//请使用@desc 注释';

        $typeMaps = array(
            'string' => '字符串',
            'int' => '整型',
            'float' => '浮点型',
            'boolean' => '布尔型',
            'date' => '日期',
            'array' => '数组',
            'fixed' => '固定值',
            'enum' => '枚举类型',
            'object' => '对象',
        );
        try {
            $api = ApiFactory::generateService(false);
            $rules = $api->getAllApiRules();
        } catch (Exception\BaseException $ex) {
            $service .= ' - ' . $ex->getMessage();
            goto SHOWVIEW;
        }
        list($className, $methodName) = explode('.', $service);
        $className = '\MobileApi\Api\Api' . $className;
        $rMethod = new \ReflectionMethod($className, $methodName);
        $docComment = $rMethod->getDocComment();
        $docCommentArr = explode("\n", $docComment);
        foreach ($docCommentArr as $comment) {
            $comment = trim($comment);
            //标题描述
            if (empty($description) && strpos($comment, '@') === false && strpos($comment, '/') === false) {
                $description = substr($comment, strpos($comment, '*') + 1);
                continue;
            }
            //@desc注释
            $pos = stripos($comment, '@desc');
            if ($pos !== false) {
                $descComment = substr($comment, $pos + 5);
                continue;
            }
            //@return注释
            $pos = stripos($comment, '@return');
            if ($pos === false) {
                continue;
            }
            $returnCommentArr = explode(' ', substr($comment, $pos + 8));
            //将数组中的空值过滤掉，同时将需要展示的值返回
            $returnCommentArr = array_values(array_filter($returnCommentArr));
            if (count($returnCommentArr) < 2) {
                continue;
            }
            if (!isset($returnCommentArr[2])) {
                $returnCommentArr[2] = ''; //可选的字段说明
            } else {
                //兼容处理有空格的注释
                $returnCommentArr[2] = implode(' ', array_slice($returnCommentArr, 2));
            }

            $returns[] = $returnCommentArr;
        }
        SHOWVIEW:
        $result = array();
        foreach ($rules as $key => $rule) {
            $name = $rule['name'];
            if (!isset($rule['type'])) {
                $rule['type'] = 'string';
            }
            $type = isset($typeMaps[$rule['type']]) ? $typeMaps[$rule['type']] : $rule['type'];
            $require = isset($rule['require']) && $rule['require'] ? '<font color="red">必须</font>' : '可选';
            $default = isset($rule['default']) ? $rule['default'] : '';
            if ($default === null) {
                $default = 'NULL';
            } else if (is_array($default)) {
                $default = json_encode($default);
            } else if (!is_string($default)) {
                $default = var_export($default, true);
            }

            $other = '';
            if (isset($rule['min'])) {
                $other .= ' 最小：' . $rule['min'];
            }
            if (isset($rule['max'])) {
                $other .= ' 最大：' . $rule['max'];
            }
            if (isset($rule['range'])) {
                $other .= ' 范围：' . implode('/', $rule['range']);
            }
            $desc = isset($rule['desc']) ? trim($rule['desc']) : '';
            $result[] = array(
                'name' => $name,
                'type' => $type,
                'require' => $require,
                'default' => $default,
                'other' => $other,
                'desc' => $desc,
            );
        }
        $returnsArr = array();
        foreach ($returns as $item) {
            $name = $item[1];
            $type = isset($typeMaps[$item[0]]) ? $typeMaps[$item[0]] : $item[0];
            $detail = $item[2];
            $returnsArr[] = array(
                'name' => $name,
                'type' => $type,
                'detail' => $detail,
            );
        }
        $this->assign('service', $service);
        $this->assign('description', $description);
        $this->assign('descComment', $descComment);
        $this->assign('rules', $result);
        $this->assign('returns', $returnsArr);
        $this->display('apidesc');
    }

    private function listDir($dir) {
        $dir .= substr($dir, -1) == D_S ? '' : D_S;
        $dirInfo = array();
        foreach (glob($dir . '*') as $v) {
            if (is_dir($v)) {
                $dirInfo = array_merge($dirInfo, $this->listDir($v));
            } else {
                $dirInfo[] = $v;
            }
        }
        return $dirInfo;
    }

}
