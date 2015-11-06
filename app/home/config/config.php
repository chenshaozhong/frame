<?php
defined('ENVIRONMENT') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Charon
 * Date: 2015/10/25
 * Time: 23:04
 */
return [



    'CACHE'=>[
        'default' =>[
            'CACHE_TYPE' => 'FileCache',
            'CACHE_PATH' => APP_PATH . 'cache'.DIRECTORY_SEPARATOR,
            'GROUP' => 'runtime',
            'HASH_DEEP' => 0,
        ],
    ],

    'WECHAT_APPID'=>'wx9467b53e5422b8dd',//微信appId
    'WECHAT_SECRET'=>'d9e676c8edf8f9cc01d63c49784f6f73',//微信secret
    'TOKEN'=>'kissaoli',//微信secret
    'EncodingAesKey'=>'1222222',//微信secret
    'SafeMode'=>1,//模式（1:明文模式,2：安全模式）


];