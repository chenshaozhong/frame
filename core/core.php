<?php
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 10:38
 */
/**
 * 框架核心
 */
if (version_compare(PHP_VERSION, '5.4.0','<'))
{
    header("Content-Type: text/html; charset=UTF-8");
    echo 'PHP环境不能低于5.4.0';
    exit;
}

switch (ENVIRONMENT)
{
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;
    case 'production':
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.4', '>='))
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        }
        else
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

use core\bin\Config;
use core\bin\Application;
use core\bin\Input;
use core\bin\Logger;

/**
 * @param null $key
 * @param null $value
 */
function config($key = NULL, $value = NULL)
{
    return func_num_args() <= 1 ? Config::get($key) :  Config::set($key, $value);
}

/**
 * @param $action
 * @param $method
 * @param array $param
 */
function url($action , $method = 'index' , $param = [])
{
    $url = 'http';
    $uri = '';
    if (isset($_SERVER['HTTPS']) &&  $_SERVER["HTTPS"] == "on")
    {
        $url .= "s";
    }
    $url .= "://";

    if ($_SERVER["SERVER_PORT"] <> "80")
    {
        $url .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
    }else{
        $url .= $_SERVER["SERVER_NAME"];
    }

    if(array_key_exists('PHP_SELF' , $_SERVER))
    {
        $uri = $_SERVER['PHP_SELF'];
    }

    $param['c'] = $action;
    $param['m'] = $method;

    return $url.$uri.'?'.http_build_query($param);
}



/**
 * 对象调用函数
 * @param  string $class 模块名/类名
 * @param  string $layer 模块层
 * @return object
 */
function load($class, $layer = 'model')
{
    static $models = array();
    $param = explode('/', $class, 2);
    $paramCount = count($param);
    switch ($paramCount)
    {
        case 1:
            $app = strtolower(APP_NAME);
            $module = $param[0];
            $class = "\\app\\{$app}\\{$layer}\\{$module}".ucfirst($layer);
            break;
        case 2:
            $app = strtolower(APP_NAME);
            $dir = strtolower($param[0]);
            $module = $param[1];
            $class = "\\app\\{$app}\\{$layer}\\{$dir}\\{$module}".ucfirst($layer);
            break;
    }

    if(isset($models[$class]))
    {
        return $models[$class];
    }
    if(!class_exists($class))
    {
        throw new \Exception("Class '{$class}' not found'", 500);
    }
    $obj = new $class();
    $models[$class] = $obj;
    return $obj;
}

/**
 * @param $func
 * @param string $layer
 */
function loadFunc($func = 'func' , $layer = APP_NAME)
{
    $file = ROOT_PATH . 'app' . DIRECTORY_SEPARATOR . $layer . DIRECTORY_SEPARATOR . 'func' . DIRECTORY_SEPARATOR . $func . '.php';
    if( file_exists($file) ) {
        return require($file);
    }
    return false;
}

/**
 * 自动注册类
 */
spl_autoload_register(function($class){
    static $fileList = array();
    $prefixes =['core' => CORE_PATH, 'app' => ROOT_PATH, '*'=>ROOT_PATH];
    $class = ltrim($class, '\\');
    if (false !== ($pos = strrpos($class, '\\')) )
    {
        $namespace = substr($class, 0, $pos);
        $className = substr($class, $pos + 1);
        foreach ($prefixes as $prefix => $baseDir)
        {
            if ( '*'!==$prefix && 0!==strpos($namespace, $prefix) ) continue;
            $fileDIR = $baseDir.str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR;
            if( !isset($fileList[$fileDIR]) )
            {
                $fileList[$fileDIR] = [];
                foreach(glob($fileDIR.'*.php') as $file)
                {
                    $fileList[$fileDIR][] = $file;
                }
            }
            $fileBase = $baseDir.str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR.$className;
            foreach($fileList[$fileDIR] as $file)
            {
                if( false!==stripos($file, $fileBase) )
                {
                    require $file;
                    return true;
                }
            }
        }
    }
    return false;
});

Application::go();