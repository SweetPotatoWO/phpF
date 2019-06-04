<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Service\Api\Common;

/**
 * 自动加载器
 *
 * - 按类名映射文件路径自动加载类文件
 * - 可以自定义加载指定文件
 *
 */
class ApiLoader {

    /**
     * @var array $dirs 指定需要加载的目录
     */
    protected $dirs = array();

    /**
     * @var string $basePath 根目录
     */
    protected $basePath = '';

    public function __construct($basePath, $dirs = array()) {
        $this->setBasePath($basePath);
        if (!empty($dirs)) {
            $this->addDirs($dirs);
        }
        spl_autoload_register(array($this, 'load'));
    }

    /**
     * 添加需要加载的目录
     * @param string $dirs 待需要加载的目录，绝对路径
     * @return NULL
     */
    public function addDirs($dirs) {
        if (!is_array($dirs)) {
            $dirs = array($dirs);
        }
        $this->dirs = array_merge($this->dirs, $dirs);
    }

    /**
     * 设置根目录
     * @param string $path 根目录
     * @return NULL
     */
    public function setBasePath($path) {
        $this->basePath = $path;
    }

    /**
     * 手工加载指定的文件
     * 可以是相对路径，也可以是绝对路径
     * @param string $filePath 文件路径
     */
    public function loadFile($filePath) {
        require_once (substr($filePath, 0, 1) != '/' && substr($filePath, 1, 1) != ':') ? $this->basePath . DIRECTORY_SEPARATOR . $filePath : $filePath;
    }

    /** ------------------ 内部实现 ------------------ * */

    /**
     * 自动加载
     * 
     * 这里，我们之所以在未找到类时没有抛出异常是为了开发人员自动加载或者其他扩展类库有机会进行处理
     *
     * @param string $className 等待加载的类名
     */
    public function load($className) {
        if (class_exists($className, false) || interface_exists($className, false)) {
            return;
        }
        if ($this->loadClass(PHALAPI_ROOT, $className)) {
            return;
        }
        foreach ($this->dirs as $dir) {
            if ($this->loadClass($this->basePath . DIRECTORY_SEPARATOR . $dir, $className)) {
                return;
            }
        }
    }

    protected function loadClass($path, $className) {
        $toRequireFile = $path . DIRECTORY_SEPARATOR
                . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.class.php';
        if (file_exists($toRequireFile)) {
            require_once $toRequireFile;
            return true;
        }
        return false;
    }

}
