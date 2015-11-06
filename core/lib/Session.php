<?php
namespace core\lib;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/27
 * Time: 23:24
 */
class Session{

    /**
     * @param $key
     */
    static public function get($key)
    {
        return $_SESSION[$key];
    }

    /**
     * @param $key
     * @param $value
     */
    static public function set($key , $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     */
    static public function del($key)
    {
        unset($_SESSION[$key]);
    }


}