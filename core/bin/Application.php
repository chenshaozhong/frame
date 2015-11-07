<?php
/**
 * 应用start
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 10:51
 */
namespace core\bin;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
class Application{

    /**
     *
     */
    public static function ready()
    {
        date_default_timezone_set(config('TIMEZONE'));

        Config::get('AUTO_SESSION') && session_start();;

        $controller_name = empty(Input::get('c')) ? Config::get('DEFAULT_CONTROLLER') : Input::get('c');
        $action_name = empty(Input::get('m')) ? Config::get('DEFAULT_ACTION') : Input::get('m');

        if( !defined('CONTROLLER_NAME') ) define('CONTROLLER_NAME', $controller_name);
        if( !defined('ACTION_NAME') ) define('ACTION_NAME', $action_name);
        return new self();
    }

    /**
     * @throws \Exception
     */
    public static function go()
    {
        try{
            define('APP_START_TIME', microtime());
            //execute action
            $controller = '\app\\'. APP_NAME .'\controller\\'. CONTROLLER_NAME.'Controller';
            $action = ACTION_NAME;

            if( !class_exists($controller) ) {
                self::show_404();
                throw new \Exception("Controller '{$controller}' not found", 404);
            }
            $obj = new $controller();
            if( !method_exists($obj, $action) ){
                self::show_404();
                throw new \Exception("Action '{$controller}::{$action}()' not found", 404);
            }
            $obj ->$action();
        } catch(\Exception $e){
            throw new \Exception($e->getMessage(), 404);
        }
    }

    /**
     * 显示404页面
     */
    public static function show_404()
    {
        echo file_get_contents(CORE_PATH.'bin'.DIRECTORY_SEPARATOR.'404.php');
        exit(1);
    }

}