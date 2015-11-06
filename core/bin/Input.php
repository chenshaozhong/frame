<?php
namespace core\bin;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 21:58
 */
class Input{
    /**
     * @param $array
     * @param null $index
     * @param null $fitter
     * @return mixed|null
     */
    static public function _fetch_from_array(&$array , $index = NULL , $fitter = NULL)
    {

        isset($index) OR $index = array_keys($array);

        is_null($fitter) && $fitter = 'htmlspecialchars';

        if (is_array($index))
        {
            $output = array();
            foreach ($index as $key)
            {
                $output[$key] = self::_fetch_from_array($array, $key);
            }
            return $output;
        }

        if (isset($array[$index]))
        {
            $value = $array[$index];
        }
        elseif (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1) // Does the index contain array notation
        {
            $value = $array;
            for ($i = 0; $i < $count; $i++)
            {
                $key = trim($matches[0][$i], '[]');
                if ($key === '') // Empty notation will return the value as array
                {
                    break;
                }

                if (isset($value[$key]))
                {
                    $value = $value[$key];
                }
                else
                {
                    return NULL;
                }
            }
        }
        else
        {
            return NULL;
        }
        return call_user_func_array($fitter , [$value]);
    }

    /**
     * @param null $index
     * @param null $fitter
     * @return mixed|null
     */
    static public function get($index = NULL, $fitter = NULL)
    {
        return self::_fetch_from_array($_GET , $index , $fitter);
    }

    /**
     * @param null $index
     * @param null $fitter
     * @return mixed|null
     */
    static public function post($index = NULL, $fitter = NULL)
    {
        return self::_fetch_from_array($_POST , $index , $fitter);
    }

    /**
     * @param null $index
     * @param null $fitter
     * @return mixed|null
     */
    static public function request($index = NULL, $fitter = NULL)
    {
        return self::_fetch_from_array(array_merge($_POST , $_GET) , $index , $fitter);
    }

    /**
     * @param null $index
     * @param null $fitter
     * @return mixed|null
     */
    static public function cookie($index = NULL, $fitter = NULL)
    {
        return self::_fetch_from_array($_COOKIE , $index , $fitter);
    }

    /**
     * @param $index
     * @param null $xss_clean
     * @return mixed|null
     */
    static public function server($index, $xss_clean = NULL)
    {
        return self::_fetch_from_array($_SERVER, $index, $xss_clean);
    }

    /**
     * @param null $xss_clean
     * @return mixed|null
     */
    static public function user_agent($xss_clean = NULL)
    {
        return self::_fetch_from_array($_SERVER, 'HTTP_USER_AGENT', $xss_clean);
    }


    /**
     * @return bool
     */
    public function is_ajax()
    {
        return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    }

    /**
     * @return bool
     */
    public function is_cli()
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * @param bool $upper
     * @return string
     */
    public function method($upper = FALSE)
    {
        return ($upper)
            ? strtoupper(self::server('REQUEST_METHOD'))
            : strtolower(self::server('REQUEST_METHOD'));
    }

    /**
     * @param $name
     * @param string $value
     * @param string $expire
     * @param string $domain
     * @param string $path
     * @param string $prefix
     * @param bool $secure
     * @param bool $httponly
     */
    static public function set_cookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE, $httponly = FALSE)
    {
        if (is_array($name))
        {
            foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'httponly', 'name') as $item)
            {
                if (isset($name[$item]))
                {
                    $$item = $name[$item];
                }
            }
        }
        if ( ! is_numeric($expire))
        {
            $expire = time() - 86500;
        }
        else
        {
            $expire = ($expire > 0) ? time() + $expire : 0;
        }
        setcookie($prefix.$name, $value, $expire, $path, $domain, $secure, $httponly);
    }


}