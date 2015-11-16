<?php
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 13:24
 */
namespace core\bin;
defined('ENVIRONMENT') OR exit('No direct script access allowed');
class Config{

    protected static  $config = [];

    /**
     * 初始化
     */
    public static function init()
    {
        self::$config = [
            'LOG_ON' => true,
            'LOG_PATH' =>  APP_PATH.'logs'.DIRECTORY_SEPARATOR,
            'TIMEZONE' => 'PRC',
            'AUTO_SESSION'=>false,//打开session

            'SESSION'=>array(
                'sess_expiration'=>60*60*24*365,
            ),

			'DEFAULT_CONTROLLER' => 'Index',
			'DEFAULT_ACTION' => 'index',
            'WECHAT_APPID'=>'',//微信appId
            'WECHAT_SECRET'=>'',//微信secret
            'DB_LINK'=>[
                'default'=>[
                    'DB_TYPE' => 'MysqlPdo',
                    'DB_HOST' => '127.0.0.1',
                    'DB_USER' => 'root',
                    'DB_PWD' => '',
                    'DB_PORT' => 3306,
                    'DB_NAME' => 'myDB',
                    'DB_CHARSET' => 'utf8',
                    'DB_PREFIX' => '',
                    'DB_CACHE' => 'DB_CACHE',
                    'DB_SLAVE' => array(),
                ]
            ],

            'TPL_PATH' => APP_PATH.'view'.DIRECTORY_SEPARATOR,

            'CACHE'=>[
                'file' =>[
                    'CACHE_TYPE' => 'FileCache',
                    'CACHE_PATH' => APP_PATH . 'cache'.DIRECTORY_SEPARATOR,
                    'GROUP' => 'tpl',
                    'HASH_DEEP' => 0,
                ],
                'db' =>[
                    'CACHE_TYPE' => 'FileCache',
                    'CACHE_PATH' => APP_PATH . 'cache'.DIRECTORY_SEPARATOR,
                    'GROUP' => 'db',
                    'HASH_DEEP' => 2,
                ],
                'common' =>[
                    'CACHE_TYPE' => 'FileCache',
                    'CACHE_PATH' => ROOT_PATH.'app'.DIRECTORY_SEPARATOR.'common'. DIRECTORY_SEPARATOR . 'cache'.DIRECTORY_SEPARATOR,
                    'GROUP' => 'runtime',
                    'HASH_DEEP' => 0,
                ],
            ],
        ];

        $files = [
            0=>APP_PATH.'config'.DIRECTORY_SEPARATOR.'config.php',
            1=>ROOT_PATH.'app'.DIRECTORY_SEPARATOR.'common'. DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php'
        ];

        foreach($files as $file)
        {
            if( file_exists($file) )
            {
                $config = require($file);
                if($config)
                {
                    foreach($config as $k=>$v)
                    {
                        if( is_array($v) )
                        {
                            if( !isset(self::$config[$k]) ) self::$config[$k] = [];
                            self::$config[$k] = array_merge((array)self::$config[$k], $config[$k]);
                        }else{
                            self::$config[$k] = $v;
                        }
                    }
                }
            }

        }

    }


    /**
     * @param null $key
     * @return null
     */
    public static function get($key=NULL)
    {
        self::init();
        if( empty($key) ) return self::$config;
        $arr = explode('.', $key);
        switch( count($arr) )
        {
            case 1 :
                if( isset(self::$config[ $arr[0] ]))
                {
                    return self::$config[ $arr[0] ];
                }
                break;
            case 2 :
                if( isset(self::$config[ $arr[0] ][ $arr[1] ]))
                {
                    return self::$config[ $arr[0] ][ $arr[1] ];
                }
                break;
            case 3 :
                if( isset(self::$config[ $arr[0] ][ $arr[1] ][ $arr[2] ]))
                {
                    return self::$config[ $arr[0] ][ $arr[1] ][ $arr[2] ];
                }
                break;
            default: break;
        }
        return NULL;
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public static function set($key , $value)
    {
        self::init();

        $arr = explode('.', $key);
        switch( count($arr) )
        {
            case 1 :
                self::$config[ $arr[0] ] = $value;
                break;
            case 2 :
                self::$config[ $arr[0] ][ $arr[1] ] = $value;
                break;
            case 3 :
                self::$config[ $arr[0] ][ $arr[1] ][ $arr[2] ] = $value;
                break;
            default: return false;
        }
        return true;
    }

}